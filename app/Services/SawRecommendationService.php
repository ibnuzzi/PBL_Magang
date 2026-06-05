<?php

namespace App\Services;

use App\Models\LowonganMagang;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class SawRecommendationService
{
    /**
     * Extract text from PDF file using pdftotext utility.
     */
    public function extractTextFromPdf(string $absolutePath): ?string
    {
        if (!file_exists($absolutePath)) {
            return null;
        }

        // pdftotext is available in system path on Windows (Laragon environment)
        $command = "pdftotext " . escapeshellarg($absolutePath) . " -";
        $text = shell_exec($command);

        return $text !== null ? trim($text) : '';
    }

    /**
     * Calculate recommendations for a student using SAW method.
     */
    public function calculateRecommendations(User $user, array $weights): array
    {
        // 1. Get all open and published vacancies
        $vacancies = LowonganMagang::query()
            ->with(['mitra'])
            ->where('is_published', true)
            ->where('is_full', false)
            ->get();

        if ($vacancies->isEmpty()) {
            return [];
        }

        // Normalize weights to sum up to 1.0
        $totalWeight = array_sum($weights) ?: 100;
        $w1 = ($weights['cv'] ?? 40) / $totalWeight;
        $w2 = ($weights['portfolio'] ?? 40) / $totalWeight;
        $w3 = ($weights['ipk'] ?? 20) / $totalWeight;

        $studentSkills = ($user->skills ?? '') . ' ' . ($user->cv_text ?? '');
        $studentExp = ($user->pengalaman ?? '') . ' ' . ($user->portfolio_text ?? '');
        $studentIpk = (float) ($user->ipk ?? 0.0);

        $matrix = [];
        $maxC1 = 1.0;
        $maxC2 = 1.0;
        $maxC3 = 1.0;

        // 2. Evaluate raw scores (x_ij)
        foreach ($vacancies as $vacancy) {
            $vacancyContent = ($vacancy->judul ?? '') . ' ' . ($vacancy->deskripsi ?? '');

            // C1: CV/Skills Match Score
            $c1 = $this->calculateKeywordScore($studentSkills, $vacancyContent);

            // C2: Portfolio/Exp Match Score
            $c2 = $this->calculateKeywordScore($studentExp, $vacancyContent);

            // AI (Gemini) enhancement if API key is provided
            if (env('GEMINI_API_KEY')) {
                try {
                    $response = Http::timeout(8)->post(
                        'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . env('GEMINI_API_KEY'),
                        [
                            'contents' => [
                                [
                                    'parts' => [
                                        [
                                            'text' => "Evaluate the matching score between a student and an internship vacancy.
                                            Student skills/CV: \"{$studentSkills}\"
                                            Student experience/Portfolio: \"{$studentExp}\"
                                            Vacancy Title: \"{$vacancy->judul}\"
                                            Vacancy Description: \"{$vacancy->deskripsi}\"
                                            
                                            Return ONLY a JSON object with format:
                                            {
                                              \"cv_score\": <integer between 1 and 100>,
                                              \"portfolio_score\": <integer between 1 and 100>
                                            }"
                                        ]
                                    ]
                                ]
                            ],
                            'generationConfig' => [
                                'responseMimeType' => 'application/json'
                            ]
                        ]
                    );

                    if ($response->successful()) {
                        $json = $response->json();
                        $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
                        $data = json_decode($text, true);
                        if (isset($data['cv_score']) && isset($data['portfolio_score'])) {
                            $c1 = (float) $data['cv_score'];
                            $c2 = (float) $data['portfolio_score'];
                        }
                    }
                } catch (\Exception $e) {
                    // Fallback to local keyword engine on connection error
                }
            }

            // C3: IPK Match Score
            $requiredIpk = (float) ($vacancy->syarat_ipk ?? 0.0);
            if ($requiredIpk <= 0.0) {
                $c3 = 100.0;
            } else {
                $c3 = $studentIpk >= $requiredIpk ? 100.0 : ($studentIpk / $requiredIpk) * 100.0;
            }

            // Keep track of maximum values for normalization
            $maxC1 = max($maxC1, $c1);
            $maxC2 = max($maxC2, $c2);
            $maxC3 = max($maxC3, $c3);

            $matrix[$vacancy->id] = [
                'vacancy' => $vacancy,
                'raw_c1' => $c1,
                'raw_c2' => $c2,
                'raw_c3' => $c3,
            ];
        }

        // 3. Normalization (r_ij) and final SAW preference score (V_i) calculation
        $results = [];
        foreach ($matrix as $id => $item) {
            $r1 = $item['raw_c1'] / $maxC1;
            $r2 = $item['raw_c2'] / $maxC2;
            $r3 = $item['raw_c3'] / $maxC3;

            $sawScore = ($w1 * $r1) + ($w2 * $r2) + ($w3 * $r3);

            $results[] = [
                'vacancy' => $item['vacancy'],
                'saw_score' => $sawScore * 100.0, // convert to percentage
                'score_cv' => $item['raw_c1'],
                'score_portfolio' => $item['raw_c2'],
                'score_ipk' => $item['raw_c3'],
            ];
        }

        // 4. Rank candidates in descending order
        usort($results, fn($a, $b) => $b['saw_score'] <=> $a['saw_score']);

        return $results;
    }

    /**
     * Compute keyword based matching score.
     */
    private function calculateKeywordScore(string $studentText, string $vacancyText): float
    {
        $studentText = strtolower($studentText);
        $vacancyText = strtolower($vacancyText);

        if (empty($studentText) || empty($vacancyText)) {
            return 10.0; // base score for empty inputs
        }

        $techKeywords = [
            'laravel', 'php', 'javascript', 'vue', 'react', 'angular', 'node', 'express',
            'html', 'css', 'bootstrap', 'tailwind', 'typescript', 'next.js', 'nuxt.js',
            'codeigniter', 'symfony', 'jquery', 'android', 'ios', 'flutter', 'react native',
            'kotlin', 'swift', 'dart', 'mysql', 'postgresql', 'mongodb', 'sql', 'sqlite',
            'redis', 'oracle', 'database', 'ui/ux', 'figma', 'xd', 'sketch', 'design',
            'photoshop', 'illustrator', 'qa', 'testing', 'selenium', 'jest', 'cypress',
            'docker', 'kubernetes', 'aws', 'gcp', 'azure', 'linux', 'devops', 'git',
            'github', 'gitlab', 'ci/cd', 'python', 'data', 'analyst', 'scientist',
            'machine learning', 'tensorflow', 'pytorch', 'pandas', 'numpy', 'scrum', 'agile'
        ];

        $requiredSkills = [];
        foreach ($techKeywords as $keyword) {
            if (str_contains($vacancyText, $keyword)) {
                $requiredSkills[] = $keyword;
            }
        }

        if (empty($requiredSkills)) {
            // Fallback to Jaccard word similarity
            $jaccard = $this->calculateTextSimilarity($studentText, $vacancyText);
            return min(100.0, max(30.0, $jaccard * 200.0));
        }

        $matchedSkillsCount = 0;
        foreach ($requiredSkills as $skill) {
            if (str_contains($studentText, $skill)) {
                $matchedSkillsCount++;
            }
        }

        return ($matchedSkillsCount / count($requiredSkills)) * 100.0;
    }

    /**
     * Calculate basic text Jaccard similarity.
     */
    private function calculateTextSimilarity(string $sourceText, string $targetText): float
    {
        $stopWords = [
            'dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'dengan', 'adalah', 'yaitu', 'pada', 'atau',
            'ini', 'itu', 'saya', 'kami', 'kita', 'anda', 'mereka', 'dia', 'adapun', 'dalam', 'sebagai',
            'the', 'and', 'to', 'in', 'of', 'for', 'with', 'is', 'a', 'an', 'at', 'by', 'from', 'on'
        ];

        preg_match_all('/\b\w+\b/', $sourceText, $sourceMatches);
        preg_match_all('/\b\w+\b/', $targetText, $targetMatches);

        $sourceTokens = array_diff($sourceMatches[0] ?? [], $stopWords);
        $targetTokens = array_diff($targetMatches[0] ?? [], $stopWords);

        if (empty($sourceTokens) || empty($targetTokens)) {
            return 0.0;
        }

        $intersection = array_intersect($sourceTokens, $targetTokens);
        $union = array_unique(array_merge($sourceTokens, $targetTokens));

        return count($intersection) / count($union);
    }
}
