<?php
namespace App\Filament\Resources\PenilaianResource\Pages;
use App\Filament\Resources\PenilaianResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListPenilaian extends ListRecords
{
    protected static string $resource = PenilaianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportExcel')
                ->label('Ekspor Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $handle = fopen('php://output', 'w');
                        
                        // Add BOM for Microsoft Excel compatibility
                        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
                        
                        fputcsv($handle, [
                            'No',
                            'NIM',
                            'Nama Mahasiswa',
                            'Program Studi',
                            'Mitra Perusahaan',
                            'Nilai Industri',
                            'Nilai Dosen Pembimbing',
                            'Nilai Penguji',
                            'Nilai Akhir',
                            'Grade',
                            'Predikat',
                            'Tahun Akademik'
                        ], ';');

                        $penilaians = \App\Models\Penilaian::with([
                            'pelaksanaan.pendaftaran.mahasiswa.programStudi',
                            'pelaksanaan.pendaftaran.mitra',
                            'parameter'
                        ])->get();

                        foreach ($penilaians as $index => $item) {
                            $mahasiswa = $item->pelaksanaan?->pendaftaran?->mahasiswa;
                            fputcsv($handle, [
                                $index + 1,
                                $mahasiswa?->nim ?? '',
                                $mahasiswa?->name ?? '',
                                $mahasiswa?->programStudi?->nama ?? '',
                                $item->pelaksanaan?->pendaftaran?->mitra?->nama ?? '',
                                $item->nilai_industri,
                                $item->nilai_dosen,
                                $item->nilai_penguji,
                                $item->nilai_akhir,
                                $item->grade,
                                $item->predikat,
                                $item->parameter?->tahun_akademik ?? ''
                            ], ';');
                        }

                        fclose($handle);
                    }, 'nilai_magang_' . now()->format('Y-m-d') . '.csv', [
                        'Content-Type' => 'text/csv; charset=utf-8',
                    ]);
                }),
            CreateAction::make(),
        ];
    }
}
