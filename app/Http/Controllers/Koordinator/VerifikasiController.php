<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    private function getQueue()
    {
        if (!session()->has('magang_queue')) {
            session(['magang_queue' => [
                ['id' => 1, 'initial' => 'AF', 'bg' => 'bg-blue-100 text-blue-600', 'name' => 'Ahmad F...', 'nim' => '2241760001', 'type' => 'Wajib', 'typeBg' => 'bg-amber-100 text-amber-700', 'company' => 'PT. Telkom I...', 'date' => '16 Jun 2024', 'days' => '3 hari lalu', 'daysColor' => 'text-amber-600', 'docs' => ['ok','ok','ok','no'], 'status' => 'pending'],
                ['id' => 2, 'initial' => 'SR', 'bg' => 'bg-emerald-100 text-emerald-600', 'name' => 'Siti Rah...', 'nim' => '2241760015', 'type' => 'Pilihan', 'typeBg' => 'bg-blue-100 text-blue-700', 'company' => 'PT. Bank M...', 'date' => '16 Jun 2024', 'days' => '3 hari lalu', 'daysColor' => 'text-amber-600', 'docs' => ['ok','no','no','no'], 'status' => 'pending'],
                ['id' => 3, 'initial' => 'DP', 'bg' => 'bg-amber-100 text-amber-600', 'name' => 'Dian Pr...', 'nim' => '2241760022', 'type' => 'Wajib', 'typeBg' => 'bg-amber-100 text-amber-700', 'company' => 'CV. Kreasi ...', 'date' => '17 Jun 2024', 'days' => '2 hari lalu', 'daysColor' => 'text-slate-500', 'docs' => ['ok','ok','ok','ok'], 'status' => 'approved'],
                ['id' => 4, 'initial' => 'RH', 'bg' => 'bg-purple-100 text-purple-600', 'name' => 'Rizky Hi...', 'nim' => '2241760034', 'type' => 'Mandiri', 'typeBg' => 'bg-emerald-100 text-emerald-700', 'company' => 'PT. Astra Int...', 'date' => '17 Jun 2024', 'days' => '2 hari lalu', 'daysColor' => 'text-slate-500', 'docs' => ['ok','no','ok','ok'], 'status' => 'rejected'],
                ['id' => 5, 'initial' => 'NA', 'bg' => 'bg-pink-100 text-pink-600', 'name' => 'Nur Aini ...', 'nim' => '2241760045', 'type' => 'Pilihan', 'typeBg' => 'bg-blue-100 text-blue-700', 'company' => 'PT. Inovasi...', 'date' => '18 Jun 2024', 'days' => 'Hari ini', 'daysColor' => 'text-emerald-600', 'docs' => ['ok','ok','ok','ok'], 'status' => 'pending'],
            ]]);
        }
        return session('magang_queue');
    }

    public function index()
    {
        $queue = collect($this->getQueue())->where('status', 'pending')->values()->all();
        
        // Since we are not changing UI, we pass stats and chartData so it doesn't break koordinator.index
        $totalPendaftar = count($this->getQueue()) + 142;
        $menunggu = collect($this->getQueue())->where('status', 'pending')->count();
        $disetujui = collect($this->getQueue())->where('status', 'approved')->count() + 88;
        $ditolak = collect($this->getQueue())->where('status', 'rejected')->count() + 13;
        $sedangMagang = 62;
        $selesaiMagang = 36;

        $stats = [
            ['title' => 'Total Pendaftar', 'value' => $totalPendaftar, 'icon' => 'users', 'border' => 'border-blue-500', 'iconBg' => 'bg-blue-50', 'iconColor' => 'text-blue-500', 'trend' => '+12 bulan ini', 'trendColor' => 'text-emerald-500'],
            ['title' => 'Menunggu Verif.', 'value' => $menunggu, 'icon' => 'clock', 'border' => 'border-yellow-500', 'iconBg' => 'bg-yellow-50', 'iconColor' => 'text-yellow-500', 'trend' => '+3 dari kemarin', 'trendColor' => 'text-yellow-600'],
            ['title' => 'Disetujui', 'value' => $disetujui, 'icon' => 'check-square', 'border' => 'border-emerald-500', 'iconBg' => 'bg-emerald-50', 'iconColor' => 'text-emerald-500', 'trend' => '+6 minggu ini', 'trendColor' => 'text-emerald-500'],
            ['title' => 'Ditolak', 'value' => $ditolak, 'icon' => 'x-circle', 'border' => 'border-rose-500', 'iconBg' => 'bg-rose-50', 'iconColor' => 'text-rose-500', 'trend' => '↓ -2 dari bulan lalu', 'trendColor' => 'text-rose-500'],
            ['title' => 'Sedang Magang', 'value' => $sedangMagang, 'icon' => 'briefcase', 'border' => 'border-blue-500', 'iconBg' => 'bg-blue-50', 'iconColor' => 'text-blue-500', 'trend' => '+5 aktif baru', 'trendColor' => 'text-blue-600'],
            ['title' => 'Selesai Magang', 'value' => $selesaiMagang, 'icon' => 'check-circle', 'border' => 'border-emerald-500', 'iconBg' => 'bg-emerald-50', 'iconColor' => 'text-emerald-500', 'trend' => '+4 bulan ini', 'trendColor' => 'text-emerald-500'],
        ];

        $chartData = [
            collect($this->getQueue())->where('type', 'Wajib')->count() + 78,
            collect($this->getQueue())->where('type', 'Pilihan')->count() + 43,
            collect($this->getQueue())->where('type', 'Mandiri')->count() + 21,
        ];

        return view('koordinator.index', compact('queue', 'stats', 'chartData'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(string $id)
    {
        $queue = collect($this->getQueue());
        $item = $queue->firstWhere('id', $id);

        if (!$item) abort(404);

        return view('koordinator.show', compact('item'));
    }

    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
        $queue = $this->getQueue();
        
        foreach ($queue as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = $request->status; // 'approved' or 'rejected'
                break;
            }
        }

        session(['magang_queue' => $queue]);
        return redirect()->route('koordinator.verifikasi.index')->with('success', 'Status pendaftaran berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
    }
}
