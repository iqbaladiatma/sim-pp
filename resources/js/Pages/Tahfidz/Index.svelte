<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    BookOpen, 
    User, 
    CheckCircle, 
    AlertCircle, 
    RotateCcw,
    X,
    Save,
    Calendar,
    Search
  } from 'lucide-svelte';

  export let recent_memorizations;
  export let students;

  let isModalOpen = false;

  const form = useForm({
    student_id: '',
    surah_name: '',
    juz_number: '',
    type: 'setoran_baru',
    status: 'lancar',
    notes: '',
  });

  function openModal() {
    $form.reset();
    isModalOpen = true;
  }

  function submit() {
    $form.post('/tahfidz', {
      onSuccess: () => {
        isModalOpen = false;
        $form.reset();
      }
    });
  }

  function getStatusIcon(status) {
    if (status === 'lancar') return { icon: CheckCircle, color: 'text-emerald-500', bg: 'bg-emerald-50' };
    if (status === 'kurang_lancar') return { icon: AlertCircle, color: 'text-amber-500', bg: 'bg-amber-50' };
    return { icon: RotateCcw, color: 'text-red-500', bg: 'bg-red-50' };
  }

  function formatDate(date) {
    return new Date(date).toLocaleString('id-ID', { 
       day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' 
    });
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Monitor Tahfidz Santri</h1>
        <p class="text-gray-500 text-sm">Pencatatan hafalan Al-Quran harian dan murojaah</p>
      </div>
      <button 
        on:click={openModal}
        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-emerald-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Input Setoran Baru
      </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
       <!-- Recent Feed -->
       <div class="lg:col-span-2 space-y-4">
          <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-8">
             <h2 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-2">
                <BookOpen class="h-5 w-5 text-emerald-600" />
                Aktivitas Setoran Terkini
             </h2>

             <div class="space-y-6">
                {#each recent_memorizations.data as memo}
                   <div class="relative pl-8 border-l-2 border-gray-50 pb-6 last:pb-0">
                      <div class="absolute -left-[9px] top-0 h-4 w-4 rounded-full border-2 border-white {getStatusIcon(memo.status).bg} {getStatusIcon(memo.status).color} shadow-sm flex items-center justify-center">
                         <svelte:component this={getStatusIcon(memo.status).icon} class="h-2 w-2" />
                      </div>
                      
                      <div class="flex items-start justify-between">
                         <div>
                            <p class="text-sm font-black text-gray-900">{memo.student.name}</p>
                            <div class="flex items-center gap-2 mt-1">
                               <span class="px-2 py-0.5 bg-gray-100 text-[9px] font-black uppercase tracking-widest text-gray-400 rounded-md">
                                  {memo.type.replace('_', ' ')}
                               </span>
                               <span class="text-[10px] text-gray-400 font-bold">{formatDate(memo.recorded_at)}</span>
                            </div>
                         </div>
                         <div class="text-right">
                            <p class="text-sm font-black text-emerald-600">{memo.surah_name}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Juz {memo.juz_number || '-'}</p>
                         </div>
                      </div>
                      
                      {#if memo.notes}
                        <div class="mt-3 p-3 bg-gray-50/50 rounded-xl text-xs text-gray-500 italic">
                           "{memo.notes}"
                        </div>
                      {/if}
                   </div>
                {:else}
                   <p class="text-center py-10 text-gray-400 italic">Belum ada setoran masuk.</p>
                {/each}
             </div>
          </div>
       </div>

       <!-- Stats / Search (Placeholder) -->
       <div class="space-y-6">
          <div class="bg-blue-600 rounded-[32px] p-6 text-white shadow-xl shadow-blue-200">
             <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Target Hari Ini</p>
             <p class="text-2xl font-black mt-2">25 Santri Setoran</p>
             <div class="mt-4 h-2 bg-blue-400/30 rounded-full overflow-hidden">
                <div class="h-full bg-white w-2/3"></div>
             </div>
          </div>

          <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm p-6">
             <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Penanggung Jawab</h3>
             <div class="mt-4 flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 font-bold">U</div>
                <div>
                   <p class="text-sm font-bold text-gray-900">Ustadz Penguji</p>
                   <p class="text-[10px] text-gray-400 font-bold uppercase">Sesi Pagi</p>
                </div>
             </div>
          </div>
       </div>
    </div>
  </div>

  <!-- Input Modal -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-lg rounded-[40px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-black text-gray-900 uppercase">Input Setoran Tahfidz</h2>
            <p class="text-[10px] text-gray-400 font-black tracking-widest mt-1">PENCATATAN REAL-TIME</p>
          </div>
          <button on:click={() => isModalOpen = false} class="p-2 text-gray-400 hover:bg-gray-50 rounded-2xl transition">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-5">
          <div class="space-y-2">
            <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Pilih Santri</label>
            <select bind:value={$form.student_id} required class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold appearance-none">
               <option value="">Cari Santri...</option>
               {#each students as student}
                  <option value={student.id}>{student.name} ({student.nis})</option>
               {/each}
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
             <div class="space-y-2">
                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Nama Surah</label>
                <input type="text" bind:value={$form.surah_name} placeholder="Contoh: Al-Baqarah" required class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
             </div>
             <div class="space-y-2">
                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Juz</label>
                <input type="number" bind:value={$form.juz_number} placeholder="1-30" class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
             </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
             <div class="space-y-2">
                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Jenis</label>
                <select bind:value={$form.type} class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold appearance-none">
                   <option value="setoran_baru">Setoran Baru</option>
                   <option value="murojaah">Murojaah</option>
                </select>
             </div>
             <div class="space-y-2">
                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Status</label>
                <select bind:value={$form.status} class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold appearance-none">
                   <option value="lancar">Lancar</option>
                   <option value="kurang_lancar">Kurang Lancar</option>
                   <option value="ulang">Ulang</option>
                </select>
             </div>
          </div>

          <div class="space-y-2">
             <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Catatan / Koreksi</label>
             <textarea bind:value={$form.notes} placeholder="Hati-hati pada ayat..." rows="2" class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold resize-none"></textarea>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-3xl shadow-xl shadow-emerald-200 transition-all flex items-center justify-center gap-2 transform active:scale-95"
          >
            <Save class="h-5 w-5" />
            SIMPAN SETORAN
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
