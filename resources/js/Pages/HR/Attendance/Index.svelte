<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Calendar, 
    Users, 
    Save, 
    UserCheck,
    Clock,
    AlertCircle,
    Check
  } from 'lucide-svelte';

  export let teachers;
  export let filters;

  const form = useForm({
    date: filters.date,
    attendance: []
  });

  $: if (teachers && teachers.length > 0) {
    form.attendance = teachers.map(t => ({
      teacher_id: t.id,
      name: t.user.name,
      position: t.position,
      status: t.attendances[0]?.status || 'hadir'
    }));
  }

  function handleDateChange() {
    router.get('/teacher-attendance', { date: $form.date }, { preserveState: true });
  }

  function submit() {
    $form.post('/teacher-attendance');
  }

  function setAllHadir() {
     $form.attendance = $form.attendance.map(a => ({ ...a, status: 'hadir' }));
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Presensi Kepegawaian</h1>
        <p class="text-gray-500 text-sm">Input kehadiran harian ustadz dan ustadzah</p>
      </div>
      <div class="flex items-center gap-3">
         <div class="relative">
            <Calendar class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
            <input 
              type="date" 
              bind:value={$form.date} 
              on:change={handleDateChange}
              class="pl-10 pr-4 py-2.5 bg-white border border-gray-100 rounded-2xl text-sm font-bold outline-none focus:ring-4 focus:ring-blue-500/10 transition-all shadow-sm"
            />
         </div>
      </div>
    </div>

    <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
       <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
          <div class="flex items-center gap-2">
             <span class="text-sm font-black text-gray-900 uppercase tracking-tight">Daftar Pengajar ({teachers.length})</span>
          </div>
          <div class="flex items-center gap-3">
             <button on:click={setAllHadir} class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100 uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all">
                Semua Hadir
             </button>
             <button 
                on:click={submit}
                disabled={$form.processing}
                class="bg-blue-600 hover:bg-black text-white px-6 py-2 rounded-xl shadow-lg shadow-blue-100 transition-all font-black text-[10px] uppercase tracking-widest flex items-center gap-2"
             >
                <Save class="h-4 w-4" />
                Simpan Presensi
             </button>
          </div>
       </div>

       <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
             <thead>
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50">
                   <th class="px-8 py-5">Nama Ustadz/ah</th>
                   <th class="px-8 py-5">Jabatan</th>
                   <th class="px-8 py-5 text-center">Status</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                {#each $form.attendance as att}
                   <tr class="hover:bg-gray-50/30 transition-colors group">
                      <td class="px-8 py-5 text-sm font-bold text-gray-900">{att.name}</td>
                      <td class="px-8 py-5 text-xs text-gray-400 font-bold uppercase">{att.position}</td>
                      <td class="px-8 py-5">
                         <div class="flex items-center justify-center gap-2">
                            {#each ['hadir', 'sakit', 'izin', 'alpha'] as status}
                               <button 
                                  type="button"
                                  on:click={() => att.status = status}
                                  class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {att.status === status ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-50 text-gray-300'}"
                               >
                                  {status}
                               </button>
                            {/each}
                         </div>
                      </td>
                   </tr>
                {:else}
                   <tr>
                      <td colspan="3" class="px-8 py-20 text-center text-gray-400 italic">Data pengajar tidak tersedia.</td>
                   </tr>
                {/each}
             </tbody>
          </table>
       </div>
    </div>
  </div>
</AppLayout>
