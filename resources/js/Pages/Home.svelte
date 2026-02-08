<script>
  import AppLayout from '../Layouts/AppLayout.svelte';
  import { 
    Users, 
    Wallet, 
    ShieldAlert, 
    BookOpen, 
    TrendingUp, 
    ArrowUpRight, 
    ArrowDownRight,
    Clock,
    UserCircle2,
    CheckCircle2,
    CalendarCheck,
    Briefcase
  } from 'lucide-svelte';

  export let stats;
  export let recent;
  export let classrooms;

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  function formatDate(date) {
    return new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
  }
</script>

<AppLayout>
  <div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Ringkasan Operasional</h1>
        <p class="text-gray-500 mt-1">Selamat datang kembali! Berikut kondisi terkini pondok pesantren Anda.</p>
      </div>
      <div class="hidden md:flex flex-col items-end">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Waktu Server</p>
        <p class="text-sm font-bold text-gray-900 flex items-center gap-2">
          <Clock class="h-4 w-4 text-blue-600" />
          {new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}
        </p>
      </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Students -->
      <div class="bg-white p-7 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all">
        <div class="flex items-center justify-between relative z-10">
          <div class="h-14 w-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
            <Users class="h-7 w-7" />
          </div>
          <span class="flex items-center gap-1 text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
            AKTIF
          </span>
        </div>
        <div class="mt-8 relative z-10">
          <p class="text-4xl font-black text-gray-900">{stats?.students?.total || 0}</p>
          <p class="text-sm font-medium text-gray-400 mt-1">Total Santri Terdaftar</p>
        </div>
        <div class="mt-4 flex items-center gap-4 text-xs font-bold relative z-10">
           <span class="text-blue-600">L: {stats?.students?.male || 0}</span>
           <span class="text-pink-600">P: {stats?.students?.female || 0}</span>
        </div>
      </div>

      <!-- Finance Collectability -->
      <div class="bg-white p-7 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all">
        <div class="flex items-center justify-between relative z-10">
          <div class="h-14 w-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
            <Wallet class="h-7 w-7" />
          </div>
          <span class="flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-wider">
            Kolektibilitas
          </span>
        </div>
        <div class="mt-8 relative z-10">
          <p class="text-4xl font-black text-gray-900">{stats?.finance?.collectability || 0}%</p>
          <p class="text-sm font-medium text-gray-400 mt-1">Total Dana Tertagih</p>
        </div>
        <div class="mt-4 w-full bg-gray-100 h-1.5 rounded-full overflow-hidden relative z-10">
          <div class="bg-emerald-600 h-full transition-all duration-1000" style="width: {stats?.finance?.collectability || 0}%"></div>
        </div>
      </div>

      <!-- Outstanding Bills -->
      <div class="bg-white p-7 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all">
        <div class="flex items-center justify-between relative z-10">
          <div class="h-14 w-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
            <TrendingUp class="h-7 w-7" />
          </div>
        </div>
        <div class="mt-8 relative z-10">
          <p class="text-4xl font-black text-gray-900 leading-tight">
            {(stats?.finance?.total_outstanding || 0) > 1000000 ? ((stats?.finance?.total_outstanding || 0) / 1000000).toFixed(1) + 'jt' : formatCurrency(stats?.finance?.total_outstanding || 0)}
          </p>
          <p class="text-sm font-medium text-gray-400 mt-1">Piutang Belum Terbayar</p>
        </div>
        <div class="mt-4 flex items-center gap-1 text-red-600 text-[10px] font-bold uppercase relative z-10">
          <ArrowUpRight class="h-3 w-3" />
          Butuh Perhatian
        </div>
      </div>

      <!-- Attendance Today -->
      <div class="bg-white p-7 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all">
        <div class="flex items-center justify-between relative z-10">
          <div class="h-14 w-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <CalendarCheck class="h-7 w-7" />
          </div>
          <span class="flex items-center gap-1 text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg uppercase tracking-wider">
            Realtime
          </span>
        </div>
        <div class="mt-8 relative z-10">
          <p class="text-4xl font-black text-gray-900">{stats?.attendance?.present || 0}</p>
          <p class="text-sm font-medium text-gray-400 mt-1">Santri Hadir Hari Ini</p>
        </div>
        <div class="mt-4 flex items-center gap-1 text-emerald-600 text-[10px] font-bold uppercase relative z-10">
          <CheckCircle2 class="h-3 w-3" />
          Dipantau Sistem
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Finance History -->
      <div class="lg:col-span-2 bg-white rounded-[40px] border border-gray-100 shadow-sm p-8">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h3 class="text-xl font-black text-gray-900">Transaksi Terakhir</h3>
            <p class="text-sm text-gray-400">Arus kas masuk terbaru dari santri</p>
          </div>
          <a href="/transactions" class="text-blue-600 text-xs font-bold hover:underline">LIHAT SEMUA</a>
        </div>
        <div class="space-y-5">
          {#each recent?.transactions || [] as trx}
            <div class="flex items-center justify-between p-5 bg-gray-50/50 rounded-3xl border border-transparent hover:border-blue-100 hover:bg-blue-50/20 transition-all group">
              <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 transition-colors group-hover:bg-blue-600 group-hover:text-white">
                  <Wallet class="h-6 w-6" />
                </div>
                <div>
                  <p class="text-sm font-bold text-gray-900">{trx.bill?.student?.name || 'Santri'}</p>
                  <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{trx.bill?.category?.name || 'Tagihan'}</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-black text-emerald-600">+{formatCurrency(trx.amount)}</p>
                <p class="text-[10px] font-bold text-gray-400">{formatDate(trx.payment_date)}</p>
              </div>
            </div>
          {:else}
            <div class="py-10 text-center text-gray-400 text-sm italic">Belum ada transaksi hari ini.</div>
          {/each}
        </div>
      </div>

      <!-- Disciplinary Alerts -->
      <div class="bg-gray-900 rounded-[40px] shadow-2xl p-8 text-white">
        <div class="flex items-center gap-3 mb-8">
          <div class="h-10 w-10 rounded-xl bg-red-500 flex items-center justify-center">
            <ShieldAlert class="h-6 w-6 text-white" />
          </div>
          <div>
            <h3 class="text-lg font-black tracking-tight">Pelanggaran Baru</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Laporan Kedisiplinan</p>
          </div>
        </div>
        <div class="space-y-4">
          {#each recent?.violations || [] as v}
            <div class="p-5 bg-white/5 rounded-3xl border border-white/10 hover:bg-white/10 transition-all">
              <div class="flex items-center justify-between mb-2">
                <p class="text-sm font-bold">{v.student?.name || 'Santri'}</p>
                <span class="text-[10px] font-black text-red-400">-{v.points_deduction} Pts</span>
              </div>
              <p class="text-xs text-gray-400 line-clamp-2">{v.description}</p>
              <div class="mt-3 flex items-center justify-between uppercase tracking-widest">
                <span class="text-[9px] font-black text-gray-500">{v.type}</span>
                <span class="text-[9px] font-black text-gray-500">{formatDate(v.incident_date)}</span>
              </div>
            </div>
          {:else}
            <div class="py-10 text-center text-gray-500 text-xs italic">Kondisi pondok kondusif.</div>
          {/each}
        </div>
        <a href="/violations" class="mt-8 block w-full py-4 bg-white/5 hover:bg-white/10 text-center rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all">
          Kelola Disiplin
        </a>
      </div>
    </div>
  </div>
</AppLayout>