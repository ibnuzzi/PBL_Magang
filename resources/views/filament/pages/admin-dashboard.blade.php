<x-filament-panels::page>
    @php
        $stats           = $this->getStats();
        $recentUsers     = $this->getRecentUsers();
        $activeParameter = $this->getActiveParameter();
        $today           = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
        $totalBobot      = $this->bobotIndustri + $this->bobotDosen + $this->bobotPenguji;

        $statCards = [
            [
                'label'   => 'Total User Aktif',
                'value'   => $stats['totalUserAktif'],
                'change'  => '+14 bulan ini',
                'icon'    => 'heroicon-o-users',
                'color'   => 'blue',
                'up'      => true,
            ],
            [
                'label'   => 'Mahasiswa Aktif',
                'value'   => $stats['mahasiswaAktif'],
                'change'  => '+8 minggu ini',
                'icon'    => 'heroicon-o-academic-cap',
                'color'   => 'cyan',
                'up'      => true,
            ],
            [
                'label'   => 'Mitra Terdaftar',
                'value'   => $stats['mitraTerdaftar'],
                'change'  => '+3 bulan ini',
                'icon'    => 'heroicon-o-building-office',
                'color'   => 'amber',
                'up'      => true,
            ],
            [
                'label'   => 'Pendaftaran Baru',
                'value'   => $stats['pendaftaranBaru'],
                'change'  => $stats['pendaftaranBaru'] . ' menunggu',
                'icon'    => 'heroicon-o-document-text',
                'color'   => 'violet',
                'up'      => true,
            ],
            [
                'label'   => 'Logbook Hari Ini',
                'value'   => $stats['logbookHariIni'],
                'change'  => 'Normal',
                'icon'    => 'heroicon-o-book-open',
                'color'   => 'green',
                'up'      => true,
            ],
            [
                'label'   => 'Nilai Belum Proses',
                'value'   => $stats['nilaiBelumProses'],
                'change'  => $stats['nilaiBelumProses'] > 0 ? '⚠ Perlu tindakan' : 'Semua selesai',
                'icon'    => 'heroicon-o-exclamation-circle',
                'color'   => $stats['nilaiBelumProses'] > 0 ? 'red' : 'green',
                'up'      => false,
            ],
        ];
    @endphp

    {{-- ══════════════════════════════════════ STYLES ══════════════════════════════════════ --}}
    <style>
        /* Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        .adm-wrap      { font-family: 'Plus Jakarta Sans', sans-serif; color: #1e293b; }

        /* ── Page Header ── */
        .adm-header    { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
        .adm-title     { font-size:1.75rem; font-weight:800; color:#1e293b; margin:0; line-height:1.2; }
        .dark .adm-title { color: #ffffff; }
        .adm-subtitle  { font-size:0.8125rem; color:#64748b; margin-top:0.25rem; }
        .dark .adm-subtitle { color: #94a3b8; }
        .adm-badges    { display:flex; align-items:center; gap:0.625rem; }
        .badge-online  { display:inline-flex; align-items:center; gap:0.375rem; padding:0.375rem 0.875rem; border-radius:9999px; font-size:0.75rem; font-weight:600; background:#dcfce7; color:#16a34a; }
        .badge-online span{ width:0.5rem;height:0.5rem;border-radius:50%;background:#16a34a;animation:pulse 2s infinite; }
        .btn-refresh   { display:inline-flex; align-items:center; gap:0.375rem; padding:0.375rem 0.875rem; border-radius:0.5rem; font-size:0.75rem; font-weight:600; background:#003B7A; color:#fff; border:none; cursor:pointer; transition:background 0.2s; }
        .btn-refresh:hover{ background:#002d5e; }
        @keyframes pulse{ 0%,100%{opacity:1}50%{opacity:.4} }

        /* ── Stat Cards ── */
        .stat-grid     { display:grid; grid-template-columns:repeat(6,1fr); gap:0.875rem; margin-bottom:1.25rem; }
        @media(max-width:1200px){ .stat-grid{grid-template-columns:repeat(3,1fr);} }
        @media(max-width:700px) { .stat-grid{grid-template-columns:repeat(2,1fr);} }

        .stat-card     { background:#fff; border-radius:0.875rem; padding:1.1rem 1.1rem 0.875rem; box-shadow:0 1px 4px rgba(0,0,0,.06),0 0 0 1px rgba(0,0,0,.04); position:relative; overflow:hidden; transition:box-shadow .2s,transform .2s; }
        .stat-card:hover{ box-shadow:0 6px 20px rgba(0,0,0,.1); transform:translateY(-2px); }
        .stat-icon-wrap{ width:2.5rem;height:2.5rem;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem; }
        .stat-icon-wrap svg{ width:1.25rem;height:1.25rem; }
        .stat-value    { font-size:2rem;font-weight:800;line-height:1;margin:0.25rem 0; }
        .stat-label    { font-size:0.7rem;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.04em; }
        .stat-change   { display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;font-weight:600;margin-top:0.5rem; }
        .stat-change.up  { color:#16a34a; }
        .stat-change.warn{ color:#dc2626; }
        .stat-change.neutral{ color:#64748b; }

        /* color palettes */
        .ic-blue  { background:#dbeafe; color:#1d4ed8; }
        .ic-cyan  { background:#cffafe; color:#0891b2; }
        .ic-amber { background:#fef3c7; color:#d97706; }
        .ic-violet{ background:#ede9fe; color:#7c3aed; }
        .ic-green { background:#dcfce7; color:#16a34a; }
        .ic-red   { background:#fee2e2; color:#dc2626; }
        .val-blue { color:#1d4ed8; }
        .val-cyan { color:#0891b2; }
        .val-amber{ color:#d97706; }
        .val-violet{color:#7c3aed; }
        .val-green{ color:#16a34a; }
        .val-red  { color:#dc2626; }

        /* ── Alert Banner ── */
        .alert-banner  { background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%); border:1.5px solid #fcd34d; border-radius:0.875rem; padding:1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; }
        .alert-icon    { width:2.5rem;height:2.5rem;background:#f59e0b;border-radius:0.625rem;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
        .alert-icon svg{ width:1.25rem;height:1.25rem;color:#fff; }
        .alert-title   { font-size:0.875rem;font-weight:700;color:#92400e; }
        .alert-desc    { font-size:0.75rem;color:#b45309;margin-top:0.2rem;line-height:1.5; }
        .btn-tinjau    { flex-shrink:0;padding:0.5rem 1.1rem;background:#f59e0b;color:#fff;border:none;border-radius:0.5rem;font-size:0.75rem;font-weight:700;cursor:pointer;transition:background .2s;white-space:nowrap; }
        .btn-tinjau:hover{ background:#d97706; }

        /* ── Two-column grid ── */
        .two-col       { display:grid; grid-template-columns:340px 1fr; gap:1.25rem; }
        @media(max-width:900px){ .two-col{grid-template-columns:1fr;} }

        /* ── Parameter Penilaian Card ── */
        .param-card    { background:#fff; border-radius:0.875rem; box-shadow:0 1px 4px rgba(0,0,0,.06),0 0 0 1px rgba(0,0,0,.04); overflow:hidden; display:flex; flex-direction:column; }
        .param-head    { display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.25rem 0.75rem; border-bottom:1px solid #f1f5f9; }
        .param-badge   { display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.6rem;background:#fef3c7;color:#d97706;border-radius:9999px;font-size:0.7rem;font-weight:700; }
        .param-total   { font-size:0.75rem;font-weight:700;color:#64748b; }
        .param-title   { font-size:1rem;font-weight:700;color:#1e293b;margin:0; }
        .param-sub     { font-size:0.7rem;color:#94a3b8;margin-top:0.15rem; }
        .param-body    { padding:1rem 1.25rem;flex:1; }
        .param-item    { margin-bottom:1.1rem; }
        .param-item-label{ display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem; }
        .param-item-name { font-size:0.75rem;font-weight:600;color:#1e293b; }
        .param-item-src  { font-size:0.65rem;color:#94a3b8;margin-top:0.1rem; }
        .param-val-input { display:flex;align-items:center;gap:0.375rem; }
        .param-val-input input[type=number]{ width:3.5rem;text-align:center;border:1.5px solid #e2e8f0;border-radius:0.375rem;font-size:0.8rem;font-weight:700;color:#1e293b;padding:0.2rem 0.35rem;outline:none; }
        .param-val-input input[type=number]:focus{ border-color:#003B7A; }
        .param-pct    { font-size:0.75rem;color:#64748b; }
        .param-slider { width:100%;height:0.375rem;border-radius:9999px;appearance:none;outline:none;background:#e2e8f0;cursor:pointer;margin-top:0.35rem; }
        .param-slider::-webkit-slider-thumb{ appearance:none;width:14px;height:14px;border-radius:50%;background:#003B7A;cursor:pointer;transition:background .2s; }
        .param-slider.sl-blue::-webkit-slider-thumb{ background:#1d4ed8; }
        .param-slider.sl-green::-webkit-slider-thumb{ background:#16a34a; }
        .param-slider.sl-purple::-webkit-slider-thumb{ background:#7c3aed; }

        .param-total-bar-wrap{ padding:0 1.25rem 0.75rem; }
        .param-total-row  { display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem; }
        .param-total-label{ font-size:0.75rem;font-weight:600;color:#475569; }
        .param-total-val  { font-size:0.875rem;font-weight:800; }
        .param-total-val.ok { color:#16a34a; }
        .param-total-val.err{ color:#dc2626; }
        .param-total-bar  { height:0.5rem;border-radius:9999px;background:#e2e8f0;overflow:hidden; }
        .param-total-fill { height:100%;border-radius:9999px;transition:width .3s;background:linear-gradient(90deg,#003B7A,#f59e0b); }

        .param-foot    { padding:0.875rem 1.25rem;border-top:1px solid #f1f5f9; }
        .btn-simpan    { width:100%;padding:0.625rem;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;border:none;border-radius:0.6rem;font-size:0.8125rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.4rem;transition:opacity .2s; font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-simpan:hover{ opacity:.9; }
        .btn-simpan:disabled{ opacity:.6;cursor:not-allowed; }
        .param-lastupd { font-size:0.65rem;color:#94a3b8;text-align:center;margin-top:0.5rem; }

        /* ── Manajemen User Card ── */
        .user-card     { background:#fff; border-radius:0.875rem; box-shadow:0 1px 4px rgba(0,0,0,.06),0 0 0 1px rgba(0,0,0,.04); display:flex;flex-direction:column;overflow:hidden; }
        .user-head     { display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.25rem;border-bottom:1px solid #f1f5f9; }
        .user-head-left .user-title{ font-size:1rem;font-weight:700;color:#1e293b;margin:0; }
        .user-head-left .user-count{ font-size:0.72rem;color:#94a3b8;margin-top:0.15rem; }
        .user-head-actions{ display:flex;gap:0.5rem; }
        .btn-add      { padding:0.45rem 0.875rem;background:#003B7A;color:#fff;border:none;border-radius:0.5rem;font-size:0.75rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:0.3rem;font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-add:hover{ background:#002d5e; }
        .btn-seeall   { padding:0.45rem 0.875rem;background:#f8fafc;color:#475569;border:1.5px solid #e2e8f0;border-radius:0.5rem;font-size:0.75rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-seeall:hover{ border-color:#003B7A;color:#003B7A; }

        .user-table    { width:100%;border-collapse:collapse; }
        .user-table th { padding:0.625rem 1.25rem;text-align:left;font-size:0.65rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;background:#f8fafc;border-bottom:1px solid #f1f5f9; }
        .user-table td { padding:0.75rem 1.25rem;border-bottom:1px solid #f8fafc;font-size:0.8125rem; }
        .user-table tr:last-child td{ border-bottom:none; }
        .user-table tr:hover td{ background:#f8fafc; }

        .user-avatar   { width:2.25rem;height:2.25rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:800;flex-shrink:0; }
        .user-name-col { display:flex;align-items:center;gap:0.75rem; }
        .user-name     { font-weight:600;color:#1e293b;font-size:0.8125rem; }
        .user-email    { font-size:0.7rem;color:#94a3b8; }

        .role-badge    { display:inline-flex;align-items:center;padding:0.2rem 0.625rem;border-radius:9999px;font-size:0.65rem;font-weight:700; }
        .role-mahasiswa{ background:#dbeafe;color:#1d4ed8; }
        .role-dosen    { background:#dcfce7;color:#16a34a; }
        .role-koordinator{ background:#ede9fe;color:#7c3aed; }
        .role-admin    { background:#fee2e2;color:#dc2626; }
        .role-kps,
        .role-kajur,
        .role-wadir1   { background:#fef3c7;color:#d97706; }

        /* Toggle switch */
        .toggle-wrap   { position:relative;display:inline-block;width:2.5rem;height:1.375rem; }
        .toggle-wrap input{ opacity:0;width:0;height:0; }
        .toggle-slider { position:absolute;cursor:pointer;inset:0;background:#e2e8f0;border-radius:9999px;transition:.3s; }
        .toggle-slider:before{ content:'';position:absolute;height:1rem;width:1rem;left:0.2rem;bottom:0.2rem;background:#fff;border-radius:50%;transition:.3s;box-shadow:0 1px 3px rgba(0,0,0,.2); }
        input:checked + .toggle-slider{ background:#003B7A; }
        input:checked + .toggle-slider:before{ transform:translateX(1.125rem); }

        .btn-edit  { padding:0.25rem 0.625rem;background:#f1f5f9;color:#475569;border:none;border-radius:0.375rem;font-size:0.7rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-edit:hover{ background:#e2e8f0; }
        .btn-deact { padding:0.25rem 0.625rem;background:#fee2e2;color:#dc2626;border:none;border-radius:0.375rem;font-size:0.7rem;font-weight:600;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-deact:hover{ background:#fecaca; }

        /* Toast */
        .adm-toast     { position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:0.5rem; }
        .toast-item    { padding:0.75rem 1.25rem;border-radius:0.625rem;font-size:0.8125rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.12);display:flex;align-items:center;gap:0.5rem;animation:slideIn .3s ease; }
        .toast-success { background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0; }
        .toast-error   { background:#fee2e2;color:#dc2626;border:1px solid #fecaca; }
        @keyframes slideIn{ from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1} }

        /* Avatar colors cycle */
        .av-0{ background:#dbeafe;color:#1d4ed8; }
        .av-1{ background:#dcfce7;color:#16a34a; }
        .av-2{ background:#ede9fe;color:#7c3aed; }
        .av-3{ background:#fef3c7;color:#d97706; }
        .av-4{ background:#fee2e2;color:#dc2626; }

        /* ── Dark Mode Adaptations ── */
        .dark .stat-card {
            background: #18181b !important;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.05), 0 4px 6px -1px rgba(0,0,0,0.2) !important;
        }
        .dark .stat-label {
            color: #a1a1aa !important;
        }
        
        .dark .badge-online {
            background: #14532d !important;
            color: #4ade80 !important;
        }

        .dark .alert-banner {
            background: linear-gradient(135deg, #271c0c 0%, #1c1206 100%) !important;
            border-color: #78350f !important;
        }
        .dark .alert-title {
            color: #fcd34d !important;
        }
        .dark .alert-desc {
            color: #f59e0b !important;
        }

        .dark .param-card, .dark .user-card {
            background: #18181b !important;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.05), 0 4px 6px -1px rgba(0,0,0,0.2) !important;
            color: #f8fafc !important;
        }
        .dark .param-head, .dark .param-foot, .dark .user-head {
            border-bottom-color: #27272a !important;
            border-top-color: #27272a !important;
        }
        .dark .param-title, .dark .user-title {
            color: #f8fafc !important;
        }
        .dark .param-sub, .dark .param-lastupd {
            color: #71717a !important;
        }
        .dark .param-item-name {
            color: #e4e4e7 !important;
        }
        .dark .param-item-src {
            color: #71717a !important;
        }
        .dark .param-pct {
            color: #a1a1aa !important;
        }
        .dark .param-val-input input[type=number] {
            background: #27272a !important;
            border-color: #3f3f46 !important;
            color: #f8fafc !important;
        }
        .dark .param-val-input input[type=number]:focus {
            border-color: #f59e0b !important;
        }
        .dark .param-slider {
            background: #27272a !important;
        }
        .dark .param-total-label {
            color: #a1a1aa !important;
        }
        .dark .param-total-bar {
            background: #27272a !important;
        }

        .dark .user-head-left .user-count {
            color: #71717a !important;
        }
        .dark .btn-seeall {
            background: #27272a !important;
            border-color: #3f3f46 !important;
            color: #e4e4e7 !important;
        }
        .dark .btn-seeall:hover {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }

        .dark .user-table th {
            background: #27272a !important;
            border-bottom-color: #3f3f46 !important;
            color: #a1a1aa !important;
        }
        .dark .user-table td {
            border-bottom-color: #27272a !important;
            color: #e4e4e7 !important;
        }
        .dark .user-table tr:hover td {
            background: #27272a !important;
        }
        .dark .user-name {
            color: #f8fafc !important;
        }
        .dark .user-email {
            color: #71717a !important;
        }
        .dark .btn-edit {
            background: #27272a !important;
            color: #e4e4e7 !important;
        }
        .dark .btn-edit:hover {
            background: #3f3f46 !important;
        }
        .dark .toggle-slider {
            background: #3f3f46 !important;
        }
        .dark input:checked + .toggle-slider {
            background: #f59e0b !important;
        }
    </style>

    <div class="adm-wrap" x-data="{
        get totalBobot() { return (parseInt(this.$wire.bobotIndustri) || 0) + (parseInt(this.$wire.bobotDosen) || 0) + (parseInt(this.$wire.bobotPenguji) || 0); },
        toasts: [],
        addToast(type, msg) {
            const id = Date.now();
            this.toasts.push({id, type, msg});
            setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 3000);
        }
    }">

        {{-- ── Page Header ── --}}
        <div class="adm-header">
            <div>
                <h1 class="adm-title">Dashboard Admin</h1>
                <p class="adm-subtitle">Sistem Informasi Magang JTI Polinema — {{ $today }}</p>
            </div>
            <div class="adm-badges">
                <span class="badge-online"><span></span>Sistem Online</span>
                <button class="btn-refresh" onclick="window.location.reload()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:0.875rem;height:0.875rem"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H5.498a.75.75 0 00-.75.75v3.498a.75.75 0 001.5 0v-1.439l.41.41a7 7 0 0011.638-3.07.75.75 0 00-1.449-.394zm1.23-3.723a.75.75 0 00.219-.53V3.198a.75.75 0 00-1.5 0v1.438l-.41-.408A7 7 0 003.28 7.325a.75.75 0 101.448.396A5.501 5.501 0 0113.476 4l.314.31h-2.433a.75.75 0 000 1.5h3.498a.75.75 0 00.53-.219l.157-.157z" clip-rule="evenodd"/></svg>
                    Refresh
                </button>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="stat-grid">
            @php
                $colors = ['blue','cyan','amber','violet','green','red'];
                $icons = [
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>',
                    '<path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.172-.311a54.614 54.614 0 014.653-2.52.75.75 0 00-.65-1.352 56.129 56.129 0 00-4.78 2.589 1.858 1.858 0 00-.859 1.228 49.803 49.803 0 00-4.634-1.527.75.75 0 01-.231-1.337A60.653 60.653 0 0111.7 2.805z"/><path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-8.105 4.342.75.75 0 01-.832 0 47.877 47.877 0 00-8.104-4.342.75.75 0 01-.461-.71c.035-1.442.121-2.87.255-4.286A48.4 48.4 0 016 13.18v1.27a1.5 1.5 0 00-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.661a6.729 6.729 0 00.551-1.608 1.5 1.5 0 00.14-2.67v-.645a48.549 48.549 0 013.44 1.668 2.25 2.25 0 002.12 0z"/>',
                    '<path fill-rule="evenodd" d="M4.5 2.25a.75.75 0 000 1.5v16.5h-.75a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5h-.75V3.75a.75.75 0 000-1.5h-15zM9 6a.75.75 0 000 1.5h1.5a.75.75 0 000-1.5H9zm-.75 3.75A.75.75 0 019 9h1.5a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM9 12a.75.75 0 000 1.5h1.5a.75.75 0 000-1.5H9zm3.75-5.25A.75.75 0 0113.5 6H15a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM13.5 9a.75.75 0 000 1.5H15A.75.75 0 0015 9h-1.5zm-.75 3.75a.75.75 0 01.75-.75H15a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM9 19.5v-2.25a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v2.25a.75.75 0 01-.75.75h-4.5A.75.75 0 019 19.5z" clip-rule="evenodd"/>',
                    '<path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75-6.75a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd"/><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"/>',
                    '<path d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z"/>',
                    '<path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>',
                ];
            @endphp
            @foreach ($statCards as $i => $card)
            <div class="stat-card">
                <div class="stat-icon-wrap ic-{{ $card['color'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        {!! $icons[$i] !!}
                    </svg>
                </div>
                <div class="stat-value val-{{ $card['color'] }}">{{ number_format($card['value']) }}</div>
                <div class="stat-label">{{ $card['label'] }}</div>
                <div class="stat-change {{ $card['up'] ? 'up' : ($card['color'] === 'red' ? 'warn' : 'neutral') }}">
                    @if($card['up'])
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" style="width:.7rem;height:.7rem"><path fill-rule="evenodd" d="M8 2a.75.75 0 01.75.75v8.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.22V2.75A.75.75 0 018 2z" clip-rule="evenodd" transform="rotate(180,8,8)"/></svg>
                    @endif
                    {{ $card['change'] }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Alert Banner ── --}}
        @if($stats['nilaiBelumProses'] > 0)
        <div class="alert-banner">
            <div style="display:flex;align-items:center;gap:1rem;">
                <div class="alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                </div>
                <div>
                    <div class="alert-title">{{ $stats['nilaiBelumProses'] }} Nilai Belum Diproses — Perhatian Diperlukan</div>
                    <div class="alert-desc">Terdapat mahasiswa magang yang telah selesai masa magang namun belum mendapatkan nilai akhir dari dosen/penguji. Segera koordinasikan.</div>
                </div>
            </div>
            <button class="btn-tinjau">Tinjau Sekarang</button>
        </div>
        @endif

        {{-- ── Two-column: Parameter Penilaian + Manajemen User ── --}}
        <div class="two-col">

            {{-- ── Parameter Penilaian ── --}}
            <div class="param-card">
                <div class="param-head">
                    <div>
                        <span class="param-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" style="width:.7rem;height:.7rem"><path fill-rule="evenodd" d="M8 1.75a.75.75 0 01.692.462l1.41 3.393 3.664.293a.75.75 0 01.428 1.317l-2.791 2.39.853 3.575a.75.75 0 01-1.12.814L8 11.989l-3.135 1.995a.75.75 0 01-1.12-.814l.852-3.574-2.79-2.39a.75.75 0 01.427-1.318l3.663-.293L7.308 2.21A.75.75 0 018 1.75z" clip-rule="evenodd"/></svg>
                            Fitur Kunci
                        </span>
                        <p class="param-title" style="margin-top:0.4rem">Parameter Penilaian</p>
                        <p class="param-sub">Bobot komponen nilai akhir magang</p>
                    </div>
                    <div>
                        <span class="param-total">Total:<br><strong x-text="totalBobot + '%'" :class="totalBobot === 100 ? 'val-green' : 'val-red'"></strong></span>
                    </div>
                </div>

                <div class="param-body">
                    {{-- Nilai Industri --}}
                    <div class="param-item">
                        <div class="param-item-label">
                            <div>
                                <div class="param-item-name">Nilai Industri</div>
                                <div class="param-item-src">Dari supervisor perusahaan</div>
                            </div>
                            <div class="param-val-input">
                                <input type="number" wire:model.live="bobotIndustri" min="0" max="100" @change="$wire.bobotIndustri = Math.min(100, Math.max(0, parseInt($event.target.value)||0))">
                                <span class="param-pct">%</span>
                            </div>
                        </div>
                        <input type="range" class="param-slider sl-blue" min="0" max="100" wire:model.live="bobotIndustri"
                            :style="'background: linear-gradient(to right, #1d4ed8 ' + $wire.bobotIndustri + '%, #e2e8f0 ' + $wire.bobotIndustri + '%)'">
                    </div>

                    {{-- Nilai Dosen --}}
                    <div class="param-item">
                        <div class="param-item-label">
                            <div>
                                <div class="param-item-name">Nilai Dosen</div>
                                <div class="param-item-src">Dari dosen pembimbing</div>
                            </div>
                            <div class="param-val-input">
                                <input type="number" wire:model.live="bobotDosen" min="0" max="100" @change="$wire.bobotDosen = Math.min(100, Math.max(0, parseInt($event.target.value)||0))">
                                <span class="param-pct">%</span>
                            </div>
                        </div>
                        <input type="range" class="param-slider sl-green" min="0" max="100" wire:model.live="bobotDosen"
                            :style="'background: linear-gradient(to right, #16a34a ' + $wire.bobotDosen + '%, #e2e8f0 ' + $wire.bobotDosen + '%)'">
                    </div>

                    {{-- Nilai Penguji --}}
                    <div class="param-item">
                        <div class="param-item-label">
                            <div>
                                <div class="param-item-name">Nilai Penguji</div>
                                <div class="param-item-src">Dari dosen penguji sidang</div>
                            </div>
                            <div class="param-val-input">
                                <input type="number" wire:model.live="bobotPenguji" min="0" max="100" @change="$wire.bobotPenguji = Math.min(100, Math.max(0, parseInt($event.target.value)||0))">
                                <span class="param-pct">%</span>
                            </div>
                        </div>
                        <input type="range" class="param-slider sl-purple" min="0" max="100" wire:model.live="bobotPenguji"
                            :style="'background: linear-gradient(to right, #7c3aed ' + $wire.bobotPenguji + '%, #e2e8f0 ' + $wire.bobotPenguji + '%)'">
                    </div>
                </div>

                {{-- Total bobot bar --}}
                <div class="param-total-bar-wrap">
                    <div class="param-total-row">
                        <span class="param-total-label">Total Bobot</span>
                        <span class="param-total-val" :class="totalBobot === 100 ? 'ok' : 'err'" x-text="totalBobot + '%'"></span>
                    </div>
                    <div class="param-total-bar">
                        <div class="param-total-fill" :style="'width:' + Math.min(totalBobot,100) + '%'"></div>
                    </div>
                </div>

                <div class="param-foot">
                    <button class="btn-simpan"
                        wire:click="simpanParameter"
                        :disabled="totalBobot !== 100"
                        wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem"><path d="M3 3.5A1.5 1.5 0 014.5 2h6.879a1.5 1.5 0 011.06.44l4.122 4.12A1.5 1.5 0 0117 7.622V16.5a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 013 16.5v-13zm10.5-.937V5.5a1 1 0 001 1h2.937L13.5 2.563z"/></svg>
                        <span wire:loading.remove wire:target="simpanParameter">Simpan Perubahan</span>
                        <span wire:loading wire:target="simpanParameter">Menyimpan...</span>
                    </button>
                    @if($activeParameter)
                    <p class="param-lastupd">Terakhir diubah: {{ $activeParameter->updated_at->format('j M Y') }} oleh Admin</p>
                    @else
                    <p class="param-lastupd">Belum ada parameter aktif</p>
                    @endif
                </div>
            </div>

            {{-- ── Manajemen User ── --}}
            <div class="user-card">
                <div class="user-head">
                    <div class="user-head-left">
                        <p class="user-title">Manajemen User</p>
                        <p class="user-count">{{ \App\Models\User::count() }} user terdaftar — tampil 5 terbaru</p>
                    </div>
                    <div class="user-head-actions">
                        <a href="#" class="btn-add" style="text-decoration:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:.875rem;height:.875rem"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/></svg>
                            + Tambah User
                        </a>
                        <a href="#" class="btn-seeall" style="text-decoration:none;">
                            Lihat Semua →
                        </a>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Nama / Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $i => $user)
                            @php
                                $initials = collect(explode(' ', $user->name))->take(2)->map(fn($w)=>strtoupper($w[0]))->implode('');
                                $roleClass = 'role-' . $user->role;
                                $roleLabel = match($user->role) {
                                    'mahasiswa'   => 'Mahasiswa',
                                    'dosen'       => 'Dosen',
                                    'koordinator' => 'Koordinator',
                                    'admin'       => 'Admin',
                                    'kps'         => 'KPS',
                                    'kajur'       => 'Kajur',
                                    'wadir1'      => 'Wadir 1',
                                    default       => ucfirst($user->role),
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="user-name-col">
                                        <div class="user-avatar av-{{ $i % 5 }}">{{ $initials }}</div>
                                        <div>
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                                </td>
                                <td>
                                    <label class="toggle-wrap">
                                        <input type="checkbox" {{ $user->is_active ? 'checked' : '' }} wire:click="toggleUserStatus({{ $user->id }})">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    <div style="display:flex;gap:0.375rem;">
                                        <button class="btn-edit">Edit</button>
                                        <button class="btn-deact">{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end two-col --}}

        {{-- ── Toast Notifications ── --}}
        <div class="adm-toast">
            <template x-for="t in toasts" :key="t.id">
                <div class="toast-item" :class="t.type === 'success' ? 'toast-success' : 'toast-error'">
                    <template x-if="t.type === 'success'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem;flex-shrink:0"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    </template>
                    <template x-if="t.type === 'error'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1rem;height:1rem;flex-shrink:0"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                    </template>
                    <span x-text="t.msg"></span>
                </div>
            </template>
        </div>

    </div>{{-- end adm-wrap --}}

    {{-- Listen to Livewire notify events --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (data) => {
                // Use Alpine's dispatch via the component
                const el = document.querySelector('[x-data]');
                if (el && el._x_dataStack) {
                    const ctx = el._x_dataStack[0];
                    if (ctx && ctx.addToast) {
                        ctx.addToast(data[0].type, data[0].message);
                    }
                }
            });
        });
    </script>
</x-filament-panels::page>
