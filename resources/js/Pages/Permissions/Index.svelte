<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Check, 
    X as Close,
    Clock,
    User,
    Calendar,
    Save,
    ClipboardCheck,
    AlertCircle
  } from 'lucide-svelte';

  export let permissions;
  export let students;

  let isModalOpen = false;

  const form = useForm({
    student_id: '',
    type: 'pulang',
    reason: '',
    start_date: new Date().toISOString().split('T')[0],
    end_date: '',
  });

  function openModal() {
    $form.reset();
    isModalOpen = true;
  }

  function submit() {
    $form.post('/permissions', {
      onSuccess: () => closeModal(),
    });
  }

  function closeModal() {
    isModalOpen = false;
    $form.reset();
  }

  function updateStatus(id, status) {
    router.put(`/permissions/${id}`, { status }, { 
      preserveScroll: true 
    });
  }

  const getStatusClass = (status) => {
    switch(status) {
      case 'approved': return 'bg-green-50 text-green-600 border-green-100';
      case 'rejected': return 'bg-red-50 text-red-600 border-red-100';
      case 'on_leave': return 'bg-amber-50 text-amber-600 border-amber-100';
      case 'returned': return 'bg-blue-50 text-blue-600 border-blue-100';
      default: return 'bg-gray-50 text-gray-500 border-gray-100';
    }
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Perizinan Santri</h1>
        <p class="text-gray-500 text-sm">Kelola izin pulang, sakit, dan izin khusus santri</p>
      </div>
      <button 
        on:click={openModal}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Buat Izin Baru
      </button>
    </div>

    <!-- Permissions Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-widest">
              <th class="px-6 py-4">Santri</th>
              <th class="px-6 py-4">Jenis & Alasan</th>
              <th class="px-6 py-4">Waktu Izin</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            {#each permissions.data as perm}
              <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                  <p class="text-sm font-bold text-gray-900">{perm.student.name}</p>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <span class="text-xs font-bold capitalize text-gray-700">{perm.type.replace('_', ' ')}</span>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-1">{perm.reason}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-xs text-gray-600">
                    <p class="font-bold">{new Date(perm.start_date).toLocaleDateString('id-ID')}</p>
                    <p class="text-gray-400">sd {perm.end_date ? new Date(perm.end_date).toLocaleDateString('id-ID') : 'Selesai'}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase border {getStatusClass(perm.status)}">
                    {perm.status}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1">
                    {#if perm.status === 'pending'}
                      <button on:click={() => updateStatus(perm.id, 'approved')} class="p-2 text-green-600 hover:bg-green-50 rounded-xl transition" title="Approve">
                        <Check class="h-4 w-4" />
                      </button>
                      <button on:click={() => updateStatus(perm.id, 'rejected')} class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition" title="Reject">
                        <Close class="h-4 w-4" />
                      </button>
                    {:else if perm.status === 'approved'}
                      <button on:click={() => updateStatus(perm.id, 'on_leave')} class="p-2 text-amber-600 hover:bg-amber-50 rounded-xl transition text-xs font-bold px-3">
                        Berangkat
                      </button>
                    {:else if perm.status === 'on_leave'}
                      <button on:click={() => updateStatus(perm.id, 'returned')} class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition text-xs font-bold px-3">
                        Kembali
                      </button>
                    {/if}
                  </div>
                </td>
              </tr>
            {:else}
              <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data perizinan.</td>
              </tr>
            {/each}
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <ClipboardCheck class="h-6 w-6 text-blue-600" />
            Permohonan Izin Baru
          </h2>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <Close class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-6">
          <div class="space-y-1.5">
            <label for="student_id" class="text-sm font-semibold text-gray-700">Pilih Santri</label>
            <select bind:value={$form.student_id} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all appearance-none">
              <option value="">Cari Santri...</option>
              {#each students as student}
                <option value={student.id}>{student.name}</option>
              {/each}
            </select>
          </div>

          <div class="space-y-1.5">
            <label for="type" class="text-sm font-semibold text-gray-700">Tipe Izin</label>
            <div class="grid grid-cols-3 gap-3">
               {#each ['pulang', 'sakit', 'izin_khusus'] as t}
                <button 
                  type="button" 
                  on:click={() => $form.type = t}
                  class="py-2 text-xs font-bold rounded-xl border transition-all { $form.type === t ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-100' : 'bg-white text-gray-500 border-gray-100' }"
                >
                  {t.replace('_', ' ').toUpperCase()}
                </button>
               {/each}
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label for="start" class="text-sm font-semibold text-gray-700">Tgl Mulai</label>
              <input type="date" bind:value={$form.start_date} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all" />
            </div>
            <div class="space-y-1.5">
              <label for="end" class="text-sm font-semibold text-gray-700">Tgl Estimasi Kembali</label>
              <input type="date" bind:value={$form.end_date} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all" />
            </div>
          </div>

          <div class="space-y-1.5">
            <label for="reason" class="text-sm font-semibold text-gray-700">Alasan / Kepentingan</label>
            <textarea bind:value={$form.reason} rows="2" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all resize-none"></textarea>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            Ajukan Perizinan
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
