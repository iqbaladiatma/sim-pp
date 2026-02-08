<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Calendar, 
    Users, 
    CheckCircle2, 
    XCircle, 
    Clock, 
    UserSlice,
    Filter,
    Save,
    AlertCircle
  } from 'lucide-svelte';

  export let classrooms;
  export let students;
  export let filters;

  const form = useForm({
    date: filters.date,
    classroom_id: filters.classroom_id || '',
    attendance: []
  });

  // Watch for student prop changes to re-fill form.attendance
  $: if (students && students.length > 0) {
    form.attendance = students.map(s => ({
      student_id: s.id,
      name: s.name,
      status: s.attendances[0]?.status || 'h'
    }));
  }

  function handleFilter() {
    router.get('/attendance', { 
      date: $form.date, 
      classroom_id: $form.classroom_id 
    }, { 
      preserveState: true,
      replace: true 
    });
  }

  function submit() {
    $form.post('/attendance');
  }

  function setAllStatus(status) {
    $form.attendance = $form.attendance.map(a => ({ ...a, status }));
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Presensi Harian</h1>
        <p class="text-gray-500 text-sm">Input data kehadiran santri per kelas secara kolektif</p>
      </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
      <div class="flex flex-col md:flex-row gap-4 items-end">
        <div class="space-y-1.5 flex-1">
          <label for="date" class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Tanggal</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
               <Calendar class="h-4 w-4" />
            </div>
            <input 
              type="date" 
              bind:value={$form.date} 
              on:change={handleFilter}
              class="block w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all" 
            />
          </div>
        </div>

        <div class="space-y-1.5 flex-1">
          <label for="classroom" class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Pilih Kelas</label>
          <div class="relative">
             <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <Filter class="h-4 w-4" />
             </div>
             <select 
              bind:value={$form.classroom_id} 
              on:change={handleFilter}
              class="block w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer"
             >
              <option value="">-- Pilih Kelas --</option>
              {#each classrooms as classroom}
                <option value={classroom.id}>{classroom.name}</option>
              {/each}
             </select>
          </div>
        </div>
      </div>
    </div>

    {#if students.length > 0}
      <!-- Bulk Actions & Table -->
      <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/20">
          <p class="text-sm font-bold text-gray-700">Daftar Santri ({students.length})</p>
          <div class="flex items-center gap-2">
            <span class="text-xs font-medium text-gray-400 mr-2">Setel semua:</span>
            <button on:click={() => setAllStatus('h')} class="px-3 py-1.5 bg-green-50 text-green-600 rounded-xl text-[10px] font-bold border border-green-100 uppercase tracking-wider hover:bg-green-600 hover:text-white transition">Hadir</button>
            <button on:click={() => setAllStatus('a')} class="px-3 py-1.5 bg-red-50 text-red-600 rounded-xl text-[10px] font-bold border border-red-100 uppercase tracking-wider hover:bg-red-600 hover:text-white transition">Alpha</button>
          </div>
        </div>

        <form on:submit|preventDefault={submit}>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                  <th class="px-8 py-4 w-12 text-center">No</th>
                  <th class="px-6 py-4">Nama Santri</th>
                  <th class="px-6 py-4 text-center">Status Kehadiran</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                {#each $form.attendance as entry, i}
                  <tr class="hover:bg-gray-50/30 transition-colors">
                    <td class="px-8 py-4 text-center text-xs font-bold text-gray-300">{i + 1}</td>
                    <td class="px-6 py-4">
                      <p class="text-sm font-bold text-gray-900">{entry.name}</p>
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex items-center justify-center gap-3">
                        <label class="flex flex-col items-center gap-1 cursor-pointer">
                          <input type="radio" bind:group={entry.status} value="h" class="hidden peer" />
                          <div class="h-9 w-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-300 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition-all">H</div>
                          <span class="text-[9px] font-bold text-gray-400 peer-checked:text-green-600 uppercase">Hadir</span>
                        </label>
                        <label class="flex flex-col items-center gap-1 cursor-pointer">
                          <input type="radio" bind:group={entry.status} value="s" class="hidden peer" />
                          <div class="h-9 w-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-300 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 transition-all">S</div>
                          <span class="text-[9px] font-bold text-gray-400 peer-checked:text-blue-600 uppercase">Sakit</span>
                        </label>
                        <label class="flex flex-col items-center gap-1 cursor-pointer">
                          <input type="radio" bind:group={entry.status} value="i" class="hidden peer" />
                          <div class="h-9 w-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-300 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all">I</div>
                          <span class="text-[9px] font-bold text-gray-400 peer-checked:text-amber-500 uppercase">Izin</span>
                        </label>
                        <label class="flex flex-col items-center gap-1 cursor-pointer">
                          <input type="radio" bind:group={entry.status} value="a" class="hidden peer" />
                          <div class="h-9 w-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-300 peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-red-600 transition-all">A</div>
                          <span class="text-[9px] font-bold text-gray-400 peer-checked:text-red-600 uppercase">Alpha</span>
                        </label>
                      </div>
                    </td>
                  </tr>
                {/each}
              </tbody>
            </table>
          </div>

          <div class="p-8 bg-gray-50/50 flex justify-end">
            <button 
              type="submit" 
              disabled={$form.processing}
              class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-10 rounded-2xl shadow-xl shadow-blue-200 transition-all flex items-center gap-2 disabled:opacity-70"
            >
              <Save class="h-5 w-5" />
              Simpan Presensi Kelas
            </button>
          </div>
        </form>
      </div>
    {:else if $form.classroom_id}
      <div class="py-20 text-center bg-white rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex flex-col items-center gap-3">
          <AlertCircle class="h-10 w-10 text-gray-200" />
          <p class="text-gray-400">Tidak ada santri di kelas ini.</p>
        </div>
      </div>
    {:else}
      <div class="py-20 text-center bg-white rounded-3xl border border-gray-100 shadow-sm border-dashed">
        <div class="flex flex-col items-center gap-3">
          <Users class="h-12 w-12 text-gray-100" />
          <p class="text-gray-400">Silakan pilih kelas terlebih dahulu untuk memulai presensi.</p>
        </div>
      </div>
    {/if}
  </div>
</AppLayout>
