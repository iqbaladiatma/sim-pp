<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { Link } from '@inertiajs/svelte';
  import { 
    ChevronLeft, 
    Edit2, 
    User, 
    Home, 
    Book, 
    Phone, 
    MapPin, 
    Calendar,
    Award,
    ShieldAlert,
    Clock,
    Printer
  } from 'lucide-svelte';

  export let student;
  export let grades = [];
  export let violations = [];
  export let permissions = [];
  export let bills = [];
  export let walletTransactions = [];

  let activeTab = 'academic';

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  };

  const formatDate = (date) => {
    return date ? new Date(date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
  };
</script>

<AppLayout>
  <div class="max-w-6xl mx-auto space-y-6">
    <!-- Header/Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div class="flex items-center gap-4">
        <Link 
          href="/students" 
          class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:shadow-sm transition"
        >
          <ChevronLeft class="h-5 w-5" />
        </Link>
        <div>
          <div class="flex items-center gap-2 mb-1">
            <h1 class="text-3xl font-extrabold text-gray-900">{student.name}</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {getStatusColor(student.status)} uppercase tracking-wider">
              {student.status}
            </span>
          </div>
          <p class="text-gray-500 font-medium font-mono text-xs uppercase tracking-widest">NIS: <span class="text-gray-900 font-bold">{student.nis}</span> • NISN: <span class="text-gray-900 font-bold">{student.nisn || '-'}</span></p>
        </div>
      </div>
      
      <div class="flex gap-3">
        <button class="flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-bold py-2.5 px-6 rounded-2xl border border-gray-200 transition-all text-xs uppercase tracking-widest">
          <Printer class="h-4 w-4 text-blue-600" />
          Cetak Kartu
        </button>
        <Link 
          href="/students/{student.id}/edit" 
          class="flex items-center gap-2 bg-blue-600 hover:bg-black text-white font-bold py-2.5 px-6 rounded-2xl shadow-xl shadow-blue-100 transition-all transform active:scale-95 text-xs uppercase tracking-widest"
        >
          <Edit2 class="h-4 w-4" />
          Edit Profil
        </Link>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      
      <!-- Left Column: Photo & Quick Info -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Photo Card -->
        <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden p-10 flex flex-col items-center">
          <div class="h-40 w-40 rounded-[32px] bg-gray-50 border-4 border-white shadow-2xl flex items-center justify-center text-gray-200 mb-8 overflow-hidden">
            {#if student.photo}
              <img src={student.photo} alt={student.name} class="h-full w-full object-cover" />
            {:else}
              <User class="h-16 w-16" />
            {/if}
          </div>
          <div class="text-center space-y-2">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em]">{student.gender === 'L' ? 'Santriwan' : 'Santriwati'}</p>
            <p class="text-sm font-black text-gray-900">{student.classroom?.name || 'BELUM TERDAFTAR'}</p>
          </div>
        </div>

        <!-- Displine Stats -->
        <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm p-6 space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
              <ShieldAlert class="h-4 w-4 text-amber-500" />
              Kedisiplinan
            </h3>
            <span class="text-xl font-black text-blue-600">{student.discipline_points}</span>
          </div>
          <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
            <div 
              class="bg-blue-600 h-full transition-all duration-500" 
              style="width: {student.discipline_points}%"
            ></div>
          </div>
        </div>

        <!-- Academic Placement -->
        <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-8 space-y-6">
            <div class="flex items-start gap-4">
              <div class="h-10 w-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                <Book class="h-5 w-5" />
              </div>
              <div>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Akademik</p>
                <p class="text-sm font-bold text-gray-900">{student.classroom?.name || '-'}</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                <Home class="h-5 w-5" />
              </div>
              <div>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Asrama</p>
                <p class="text-sm font-bold text-gray-900">
                  {student.dormitory_room ? `Km. ${student.dormitory_room.room_number}` : 'Luar Pondok'}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Full Details & Tabs -->
      <div class="lg:col-span-3 space-y-6">
        <!-- Tabs Header -->
        <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
          <div class="flex items-center px-4 overflow-x-auto no-scrollbar">
            {#each [
              { id: 'biodata', label: 'Profil' },
              { id: 'academic', label: 'Akademik' },
              { id: 'conduct', label: 'Karakter' },
              { id: 'finance', label: 'Keuangan' }
            ] as tab}
              <button 
                on:click={() => activeTab = tab.id}
                class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] transition-all border-b-2 {activeTab === tab.id ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600'}"
              >
                {tab.label}
              </button>
            {/each}
          </div>

          <div class="p-10">
            {#if activeTab === 'biodata'}
              <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-12 animate-in fade-in duration-300">
                <div>
                  <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2 flex items-center gap-2"><MapPin class="h-3 w-3" /> TTL</p>
                  <p class="text-sm font-bold text-gray-900 underline decoration-blue-100 decoration-4 underline-offset-4">{student.birth_place || '-'}, {formatDate(student.birth_date)}</p>
                </div>
                <div>
                  <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2 flex items-center gap-2"><Calendar class="h-3 w-3" /> Registrasi</p>
                  <p class="text-sm font-bold text-gray-900">{formatDate(student.entry_date)}</p>
                </div>
                <div class="md:col-span-2 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                  <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-3 flex items-center gap-2"><Home class="h-3 w-3" /> Alamat Lengkap</p>
                  <p class="text-sm font-bold text-gray-900 leading-relaxed italic">"{student.address || '-'}"</p>
                </div>
                <div>
                  <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-2 flex items-center gap-2"><Phone class="h-3 w-3" /> Kontak</p>
                  <p class="text-sm font-black text-blue-600">{student.phone_number || '-'}</p>
                </div>
              </div>

            {:else if activeTab === 'academic'}
              <div class="space-y-6 animate-in slide-in-from-right-4 duration-300">
                <h4 class="text-sm font-black text-gray-900 uppercase">Riwayat Nilai Terakhir</h4>
                <div class="space-y-3">
                  {#each grades as g}
                    <div class="p-4 bg-gray-50 rounded-2xl flex items-center justify-between border border-transparent hover:border-blue-100 hover:bg-white transition-all group">
                      <div class="flex items-center gap-4">
                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-white text-blue-600 font-black text-xs shadow-sm">
                          {g.score}
                        </div>
                        <div>
                          <p class="text-sm font-bold text-gray-900">{g.subject?.name}</p>
                          <p class="text-[10px] text-gray-400 font-bold uppercase">{g.exam?.name || 'Tugas'}</p>
                        </div>
                      </div>
                      <span class="text-[10px] font-black text-gray-300 group-hover:text-blue-200 transition-colors uppercase tracking-widest">{formatDate(g.created_at)}</span>
                    </div>
                  {:else}
                    <div class="py-10 text-center text-gray-300 italic text-sm">Belum ada data nilai.</div>
                  {/each}
                </div>
              </div>

            {:else if activeTab === 'conduct'}
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-in slide-in-from-right-4 duration-300">
                <div class="space-y-4">
                   <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Daftar Pelanggaran</h4>
                   {#each violations as v}
                      <div class="p-4 bg-red-50/30 border border-red-100 rounded-2xl flex items-start gap-4">
                         <ShieldAlert class="h-5 w-5 text-red-400 shrink-0 mt-0.5" />
                         <div>
                            <p class="text-xs font-black text-red-600 uppercase">{v.violation_type}</p>
                            <p class="text-[10px] text-gray-500 font-medium mt-1 leading-relaxed">{v.description}</p>
                            <p class="text-[9px] font-black text-red-400 mt-2 uppercase tracking-tight">-{v.points_deducted} Poin • {formatDate(v.violation_date)}</p>
                         </div>
                      </div>
                   {:else}
                      <div class="py-10 text-center bg-gray-50 rounded-2xl">
                         <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada record buruk</p>
                      </div>
                   {/each}
                </div>
                <div class="space-y-4">
                   <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Riwayat Perizinan</h4>
                   {#each permissions as p}
                      <div class="p-4 bg-blue-50/30 border border-blue-100 rounded-2xl flex items-center justify-between">
                         <div class="flex items-center gap-3">
                            <Clock class="h-4 w-4 text-blue-400" />
                            <div>
                               <p class="text-xs font-black text-blue-900 uppercase">{p.type}</p>
                               <p class="text-[9px] text-blue-400 font-bold uppercase">{formatDate(p.start_date)} s/d {formatDate(p.end_date)}</p>
                            </div>
                         </div>
                         <span class="text-[9px] font-black px-2 py-0.5 rounded-lg {p.status === 'approved' ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-white'} uppercase">{p.status}</span>
                      </div>
                   {:else}
                      <p class="text-xs text-center text-gray-300 italic pt-10">Belum ada riwayat izin.</p>
                   {/each}
                </div>
              </div>

            {:else if activeTab === 'finance'}
              <div class="space-y-8 animate-in slide-in-from-right-4 duration-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                   <div class="p-6 bg-blue-600 rounded-[32px] text-white space-y-2 shadow-xl shadow-blue-100">
                      <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest leading-none">Tagihan Tertunda</p>
                      <p class="text-2xl font-black">
                         {formatCurrency(bills.reduce((acc, b) => acc + (b.amount - b.paid_amount), 0))}
                      </p>
                   </div>
                   <div class="p-6 bg-gray-900 rounded-[32px] text-white space-y-2">
                      <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Saldo Dompet</p>
                      <p class="text-2xl font-black text-emerald-400">
                         {formatCurrency(student.wallet?.balance || 0)}
                      </p>
                   </div>
                </div>

                <div class="space-y-4">
                   <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Transaksi E-Money Terakhir</h4>
                   <div class="space-y-2">
                      {#each walletTransactions as t}
                         <div class="px-6 py-4 bg-gray-50 rounded-2xl flex items-center justify-between group hover:bg-white border border-transparent hover:border-gray-100 transition-all">
                            <div class="flex items-center gap-4">
                               <div class="h-8 w-8 rounded-lg flex items-center justify-center {t.type === 'topup' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'}">
                                  <ArrowUpRight class="h-4 w-4" />
                               </div>
                               <div>
                                  <p class="text-xs font-black text-gray-900 uppercase">{t.merchant_name || 'Top Up'}</p>
                                  <p class="text-[9px] text-gray-400 font-bold uppercase">{formatDate(t.created_at)}</p>
                               </div>
                            </div>
                            <p class="text-xs font-black {t.type === 'topup' ? 'text-emerald-600' : 'text-red-500'}">
                               {t.type === 'topup' ? '+' : '-'}{formatCurrency(t.amount)}
                            </p>
                         </div>
                      {:else}
                         <p class="text-xs text-center text-gray-300 italic py-10">Belum ada aktivitas dompet.</p>
                      {/each}
                   </div>
                </div>
              </div>
            {/if}
          </div>
        </div>
      </div>
    </div>
  </div>
</AppLayout>
