<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'pelaksanaan_id',
        'parameter_id',
        'penguji_id',
        'nilai_industri',
        'nilai_dosen',
        'nilai_penguji',
        'nilai_akhir',
        'grade',
        'predikat',
        'sudah_dikonversi',
    ];

    protected function casts(): array
    {
        return [
            'nilai_industri' => 'decimal:2',
            'nilai_dosen' => 'decimal:2',
            'nilai_penguji' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
            'sudah_dikonversi' => 'boolean',
        ];
    }

    // ─── Relationships ───────────────────────────────────────────────

    public function pelaksanaan(): BelongsTo
    {
        return $this->belongsTo(PelaksanaanMagang::class, 'pelaksanaan_id');
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(ParameterPenilaian::class, 'parameter_id');
    }

    public function penguji(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penguji_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class, 'penilaian_id');
    }

    protected static function booted()
    {
        static::saving(function ($penilaian) {
            $parameter = $penilaian->parameter ?? ParameterPenilaian::where('is_active', true)->first();
            if ($parameter) {
                if (!$penilaian->parameter_id) {
                    $penilaian->parameter_id = $parameter->id;
                }
                
                $bobotIndustri = (float) $parameter->bobot_industri;
                $bobotDosen = (float) $parameter->bobot_dosen;
                $bobotPenguji = (float) $parameter->bobot_penguji;

                $nilaiIndustri = (float) $penilaian->nilai_industri;
                $nilaiDosen = (float) $penilaian->nilai_dosen;
                $nilaiPenguji = (float) $penilaian->nilai_penguji;

                $penilaian->nilai_akhir = ($nilaiIndustri * $bobotIndustri +
                                           $nilaiDosen * $bobotDosen +
                                           $nilaiPenguji * $bobotPenguji) / 100;

                $gradeMap = $parameter->konversi_grade;
                if (!empty($gradeMap)) {
                    arsort($gradeMap);
                    $grade = 'E';
                    foreach ($gradeMap as $g => $minVal) {
                        if ($penilaian->nilai_akhir >= (float) $minVal) {
                            $grade = $g;
                            break;
                        }
                    }
                    $penilaian->grade = $grade;
                }

                $penilaian->predikat = match ($penilaian->grade) {
                    'A' => 'Dengan Pujian (Sangat Memuaskan)',
                    'AB' => 'Sangat Baik',
                    'B' => 'Baik',
                    'BC' => 'Cukup Baik',
                    'C' => 'Cukup',
                    'D' => 'Kurang',
                    'E' => 'Gagal',
                    default => 'Tidak Diketahui',
                };
            }
        });
    }
}
