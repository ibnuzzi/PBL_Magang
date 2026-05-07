<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KoordinatorController extends Controller
{
    private function getQueue()
    {
        if (!session()->has('magang_queue')) {
            session(['magang_queue' => [
                ['id' => 1, 'initial' => 'AF', 'bg' => 'bg-blue-100 text-blue-600', 'name' => 'Ahmad F...', 'nim' => '2241760001', 'type' => 'Wajib', 'typeBg' => 'bg-amber-100 text-amber-700', 'company' => 'PT. Telkom I...', 'date' => '16 Jun 2024', 'days' => '3 hari lalu', 'daysColor' => 'text-amber-600', 'docs' => ['ok','ok','ok','no'], 'status' => 'menunggu'],
                ['id' => 2, 'initial' => 'SR', 'bg' => 'bg-emerald-100 text-emerald-600', 'name' => 'Siti Rah...', 'nim' => '2241760015', 'type' => 'Pilihan', 'typeBg' => 'bg-blue-100 text-blue-700', 'company' => 'PT. Bank M...', 'date' => '16 Jun 2024', 'days' => '3 hari lalu', 'daysColor' => 'text-amber-600', 'docs' => ['ok','no','no','no'], 'status' => 'menunggu'],
                ['id' => 3, 'initial' => 'DP', 'bg' => 'bg-amber-100 text-amber-600', 'name' => 'Dian Pr...', 'nim' => '2241760022', 'type' => 'Wajib', 'typeBg' => 'bg-amber-100 text-amber-700', 'company' => 'CV. Kreasi ...', 'date' => '17 Jun 2024', 'days' => '2 hari lalu', 'daysColor' => 'text-slate-500', 'docs' => ['ok','ok','ok','ok'], 'status' => 'disetujui'],
                ['id' => 4, 'initial' => 'RH', 'bg' => 'bg-purple-100 text-purple-600', 'name' => 'Rizky Hi...', 'nim' => '2241760034', 'type' => 'Mandiri', 'typeBg' => 'bg-emerald-100 text-emerald-700', 'company' => 'PT. Astra Int...', 'date' => '17 Jun 2024', 'days' => '2 hari lalu', 'daysColor' => 'text-slate-500', 'docs' => ['ok','no','ok','ok'], 'status' => 'ditolak'],
                ['id' => 5, 'initial' => 'NA', 'bg' => 'bg-pink-100 text-pink-600', 'name' => 'Nur Aini ...', 'nim' => '2241760045', 'type' => 'Pilihan', 'typeBg' => 'bg-blue-100 text-blue-700', 'company' => 'PT. Inovasi...', 'date' => '18 Jun 2024', 'days' => 'Hari ini', 'daysColor' => 'text-emerald-600', 'docs' => ['ok','ok','ok','ok'], 'status' => 'menunggu'],
            ]]);
        }
        return session('magang_queue');
    }

    public function index()
    {
        $queue = $this->getQueue();

        // Calculate Stats
        $totalPendaftar = count($queue) + 142; // arbitrary large number for dummy
        $menunggu = collect($queue)->where('status', 'menunggu')->count();
        $disetujui = collect($queue)->where('status', 'disetujui')->count() + 88;
        $ditolak = collect($queue)->where('status', 'ditolak')->count() + 13;
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

        // Chart Data
        $chartData = [
            collect($queue)->where('type', 'Wajib')->count() + 78,
            collect($queue)->where('type', 'Pilihan')->count() + 43,
            collect($queue)->where('type', 'Mandiri')->count() + 21,
        ];

        return view('koordinator.index', compact('queue', 'stats', 'chartData'));
    }

    public function create()
    {
        // Not used, using modal
    }

    public function store(Request $request)
    {
        // For dummy, we'll just redirect
        return redirect()->route('koordinator.index')->with('success', 'Data pendaftaran baru ditambahkan!');
    }

    public function show($id)
    {
        $queue = collect($this->getQueue());
        $item = $queue->firstWhere('id', $id);

        if (!$item) abort(404);

        return view('koordinator.show', compact('item'));
    }

    public function edit($id)
    {
        // Handled by Alpine.js modal in index
    }

    public function update(Request $request, $id)
    {
        $queue = $this->getQueue();
        
        $request->validate([
            'company' => 'required|string',
            'type' => 'required|string',
        ]);

        foreach ($queue as &$item) {
            if ($item['id'] == $id) {
                $item['company'] = $request->company;
                $item['type'] = $request->type;
                if ($request->type == 'Wajib') $item['typeBg'] = 'bg-amber-100 text-amber-700';
                if ($request->type == 'Pilihan') $item['typeBg'] = 'bg-blue-100 text-blue-700';
                if ($request->type == 'Mandiri') $item['typeBg'] = 'bg-emerald-100 text-emerald-700';
                break;
            }
        }

        session(['magang_queue' => $queue]);
        return redirect()->route('koordinator.index')->with('success', 'Data pendaftaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $queue = $this->getQueue();
        $queue = array_filter($queue, function ($item) use ($id) {
            return $item['id'] != $id;
        });

        session(['magang_queue' => array_values($queue)]);
        return redirect()->route('koordinator.index')->with('success', 'Data pendaftaran berhasil dihapus dari antrian!');
    }

    public function updateStatus(Request $request, $id)
    {
        $queue = $this->getQueue();
        
        foreach ($queue as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = $request->status; // 'disetujui' or 'ditolak'
                break;
            }
        }

        session(['magang_queue' => $queue]);
        return redirect()->route('koordinator.index')->with('success', 'Status pendaftaran berhasil diperbarui!');
    }
}
