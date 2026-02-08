<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { 
    Users, 
    ShieldAlert, 
    Wallet, 
    BookOpen, 
    Calendar, 
    TrendingUp, 
    ArrowUpRight,
    MessageSquare,
    Clock,
    MapPin,
    ArrowRight
  } from 'lucide-svelte';

  export let student;
  export let attendance;
  export let bills;
  export let grades;
  export let reports;

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  const getStatusColor = (status) => {
    switch(status) {
      case 'h': return 'text-emerald-500 bg-emerald-50';
      case 's': return 'text-blue-500 bg-blue-50';
      case 'i': return 'text-amber-500 bg-amber-50';
      case 'a': return 'text-red-500 bg-red-50';
      default: return 'text-gray-400 bg-gray-50';
    }
  };

  const getStatusLabel = (status) => {
    const labels = { 'h': 'Hadir', 's': 'Sakit', 'i': 'Izin', 'a': 'Alpha' };
    return labels[status] || 'Unknown';
  };
</script>

<AppLayout>
  <div class="space-y-8 pb-12">
    <!-- Student Quick Profile Header -->
    <div class="bg-white rounded-[40px] p-10 border border-gray-100 shadow-xl shadow-gray-200/50 flex flex-col md:flex-row items-center gap-10">
       <div class="h-40 w-40 rounded-[32px] bg-gray-50 border-4 border-white shadow-2xl overflow-hidden shrink-0">
          {#if student.photo}
             <img src={student.photo} alt={student.name} class="h-full w-full object-cover" />
          {:else}
             <div class="h-full w-full flex items-center justify-center text-gray-200">
                <Users class="h-16 w-16" />
             </div>
          {/if}
       </div>
       <div class="flex-1 text-center md:text-left space-y-4">
          <div>
             <h1 class="text-4xl font-black text-gray-900 tracking-tight">{student.name}</h1>
             <p class="text-gray-500 font-bold uppercase tracking-[0.2em] text-[10px] mt-2">NIS: {student.nis} • KELAS: {student.classroom?.name || '-'}</p>
          </div>
          <div class="flex flex-wrap justify-center md:justify-start gap-4 pt-2">
             <div class="px-5 py-2.5 bg-blue-50 rounded-2xl flex items-center gap-3">
                <BookOpen class="h-4 w-4 text-blue-600" />
                <span class="text-xs font-black text-blue-600 uppercase tracking-widest">Akademik: Aktif</span>
             </div>
             <div class="px-5 py-2.5 bg-emerald-50 rounded-2xl flex items-center gap-3">
                <Wallet class="h-4 w-4 text-emerald-600" />
                <span class="text-xs font-black text-emerald-600 uppercase tracking-widest">SPP: Lunas</span>
             </div>
          </div>
       </div>
       <div class="hidden lg:block shrink-0">
          <div class="p-8 bg-blue-600 rounded-[32px] text-white space-y-1 shadow-2xl shadow-blue-200">
             <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60">Poin Kedisiplinan</p>
             <h2 class="text-5xl font-black">{student.discipline_points}</h2>
          </div>
       </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
       <!-- Left Column: Attendance & Academic -->
       <div class="lg:col-span-2 space-y-8">
          <!-- Live Attendance Monitor -->
          <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 space-y-8">
             <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                   <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                      <Calendar class="h-6 w-6" />
                   </div>
                   <div>
                      <h3 class="text-lg font-black text-gray-900 uppercase">Monitor Kehadiran</h3>
                      <p class="text-xs text-gray-400 font-bold uppercase tracking-widest leading-none">Record 10 hari terakhir</p>
                   </div>
                </div>
                <button class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Lihat Seluruhnya</button>
             </div>

             <div class="flex items-center justify-between gap-4 pt-4">
                {#each attendance as att}
                   <div class="flex-1 flex flex-col items-center gap-3 group">
                      <div class="h-14 w-full rounded-2xl border border-gray-50 {getStatusColor(att.status)} flex items-center justify-center transition-transform group-hover:scale-105">
                         <span class="text-lg font-black uppercase">{att.status}</span>
                      </div>
                      <div class="text-center">
                         <p class="text-[9px] font-black text-gray-400 uppercase tracking-tight">{new Date(att.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })}</p>
                         <p class="text-[8px] font-bold text-gray-300 uppercase">{new Date(att.date).toLocaleDateString('id-ID', { weekday: 'short' })}</p>
                      </div>
                   </div>
                {:else}
                   <div class="w-full py-10 text-center text-gray-300 italic text-sm">Belum ada record absensi.</div>
                {/each}
             </div>
          </div>

          <!-- Academic Reports (Grades) -->
          <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 space-y-8">
             <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                   <div class="h-12 w-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center">
                      <TrendingUp class="h-6 w-6" />
                   </div>
                   <div>
                      <h3 class="text-lg font-black text-gray-900 uppercase">Grafik Akademik</h3>
                      <p class="text-xs text-gray-400 font-bold uppercase tracking-widest leading-none">Pencapaian Nilai Terakhir</p>
                   </div>
                </div>
             </div>

             <div class="space-y-4">
                {#each grades as grade}
                   <div class="flex items-center justify-between p-6 bg-gray-50 rounded-3xl group hover:bg-white border border-transparent hover:border-gray-100 transition-all">
                      <div class="flex items-center gap-4">
                         <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center shadow-sm font-black text-blue-600 text-sm">
                            {grade.score}
                         </div>
                         <div>
                            <p class="text-sm font-black text-gray-900 uppercase leading-none">{grade.subject?.name}</p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">{grade.exam?.name || 'Tugas Formatif'}</p>
                         </div>
                      </div>
                      <div class="text-right">
                         <div class="flex items-center gap-1.5 text-emerald-500">
                            <ArrowUpRight class="h-3 w-3" />
                            <span class="text-[10px] font-black uppercase">Above Avg</span>
                         </div>
                      </div>
                   </div>
                {:else}
                   <div class="py-10 text-center text-gray-300 italic">Belum ada nilai yang dipublikasikan.</div>
                {/each}
             </div>
          </div>
       </div>

       <!-- Right Column: Finance & Wallet -->
       <div class="lg:col-span-1 space-y-8">
          <!-- E-Wallet Card -->
          <div class="bg-gray-900 rounded-[40px] p-10 text-white space-y-8 relative overflow-hidden group">
             <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition-transform">
                <Wallet class="h-64 w-64" />
             </div>
             <div class="relative z-10 space-y-10">
                <div class="flex items-center justify-between">
                   <span class="text-[10px] font-black uppercase tracking-[0.3em] opacity-40">Uang Saku Digital</span>
                   <Users class="h-5 w-5 opacity-40" />
                </div>
                <div>
                   <p class="text-4xl font-black tracking-tight">{formatCurrency(student.wallet?.balance || 0)}</p>
                   <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mt-4">KARTU SANTRI AKTIF</p>
                </div>
                <div class="flex gap-4">
                   <button class="flex-1 py-4 bg-white/10 backdrop-blur-md rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition-all">Riwayat Jajan</button>
                   <button class="flex-1 py-4 bg-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-900/40 hover:bg-blue-500 transition-all">Isi Saldo</button>
                </div>
             </div>
          </div>

          <!-- Pending Bills -->
          <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 space-y-8">
             <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tagihan Mendatang</h3>
             
             <div class="space-y-6">
                {#each bills as bill}
                   <div class="flex items-start justify-between group">
                      <div class="flex items-start gap-4">
                         <div class="h-12 w-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shrink-0">
                            <Clock class="h-6 w-6" />
                         </div>
                         <div>
                            <p class="text-sm font-black text-gray-900 uppercase leading-none">{bill.category?.name}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Jatuh Tempo: {new Date(bill.due_date).toLocaleDateString()}</p>
                            <p class="text-xs font-black text-amber-600 mt-2">{formatCurrency(bill.amount - bill.paid_amount)}</p>
                         </div>
                      </div>
                      <button class="h-10 w-10 flex items-center justify-center bg-gray-50 text-gray-300 rounded-full group-hover:bg-blue-600 group-hover:text-white transition-all">
                         <ArrowRight class="h-5 w-5" />
                      </button>
                   </div>
                {:else}
                   <div class="py-4 text-center">
                      <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                         <CheckCircle2 class="h-3 w-3" />
                         Semua Tagihan Lunas
                      </div>
                   </div>
                {/each}
             </div>
          </div>

          <!-- WhatsApp Connect -->
          <div class="p-8 bg-emerald-600 rounded-[40px] text-white space-y-4">
             <div class="flex items-center gap-4">
                <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center">
                   <MessageSquare class="h-5 w-5" />
                </div>
                <h4 class="text-sm font-black uppercase tracking-widest leading-none">Layanan Wali</h4>
             </div>
             <p class="text-xs font-bold text-emerald-100 leading-relaxed opacity-80 uppercase tracking-wider">Aktifkan notifikasi WhatsApp untuk update nilai dan kehadiran secara real-time.</p>
             <button class="w-full py-4 bg-white text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all">Hubungkan WhatsApp</button>
          </div>
       </div>
    </div>
  </div>
</AppLayout>
