<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Trash2,
    ShieldAlert,
    User,
    X,
    Save,
    Calendar,
    AlertCircle
  } from 'lucide-svelte';

  export let violations;
  export let students;

  let isModalOpen = false;

  const form = useForm({
    student_id: '',
    type: 'Ringan',
    description: '',
    points_deduction: 5,
    incident_date: new Date().toISOString().split('T')[0],
    action_taken: '',
  });

  function openModal() {
    $form.reset();
    isModalOpen = true;
  }

  function submit() {
    $form.post('/violations', {
      onSuccess: () => closeModal(),
    });
  }

  function closeModal() {
    isModalOpen = false;
    $form.reset();
  }

  function deleteViolation(id) {
    if (confirm('Hapus catatan ini? Poin santri akan dikembalikan.')) {
      router.delete(`/violations/${id}`);
    }
  }

  const getSeverityClass = (points) => {
    if (points >= 50) return 'bg-red-50 text-red-600 border-red-100';
    if (points >= 20) return 'bg-amber-50 text-amber-600 border-amber-100';
    return 'bg-blue-50 text-blue-600 border-blue-100';
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Kedisiplinan & Pelanggaran</h1>
        <p class="text-gray-500 text-sm">Catat tindakan indisipliner dan pantau poin santri</p>
      </div>
      <button 
        on:click={openModal}
        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-red-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Catat Pelanggaran
      </button>
    </div>

    <!-- Violations Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-widest">
              <th class="px-6 py-4">Santri</th>
              <th class="px-6 py-4">Jenis & Tanggal</th>
              <th class="px-6 py-4">Deskripsi</th>
              <th class="px-6 py-4 text-center">Poin</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            {#each violations.data as violation}
              <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-lg bg-gray-100 flex items-center justify-center font-bold text-gray-400">
                      {violation.student.name.charAt(0)}
                    </div>
                    <div>
                      <p class="text-sm font-bold text-gray-900">{violation.student.name}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div>
                    <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase border {getSeverityClass(violation.points_deduction)}">
                      {violation.type}
                    </span>
                    <p class="text-xs text-gray-400 mt-1">{new Date(violation.incident_date).toLocaleDateString('id-ID')}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-600 line-clamp-1" title={violation.description}>{violation.description}</p>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="text-sm font-black text-red-600">-{violation.points_deduction}</span>
                </td>
                <td class="px-6 py-4 text-right">
                  <button on:click={() => deleteViolation(violation.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition opacity-0 group-hover:opacity-100">
                    <Trash2 class="h-4 w-4" />
                  </button>
                </td>
              </tr>
            {:else}
              <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Belum ada catatan pelanggaran.</td>
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
            <AlertCircle class="h-6 w-6 text-red-600" />
            Catat Pelanggaran Baru
          </h2>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <X class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-6">
          <div class="space-y-1.5">
            <label for="student_id" class="text-sm font-semibold text-gray-700">Pilih Santri</label>
            <select 
              id="student_id"
              bind:value={$form.student_id}
              required
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:ring-4 focus:ring-blue-500/10 outline-none transition-all appearance-none"
            >
              <option value="">Cari Santri...</option>
              {#each students as student}
                <option value={student.id}>{student.name}</option>
              {/each}
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label for="type" class="text-sm font-semibold text-gray-700">Kategori</label>
              <select bind:value={$form.type} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all appearance-none">
                <option>Ringan</option>
                <option>Sedang</option>
                <option>Berat</option>
                <option>Sangat Berat</option>
              </select>
            </div>
            <div class="space-y-1.5">
              <label for="points" class="text-sm font-semibold text-gray-700">Potongan Poin</label>
              <input type="number" bind:value={$form.points_deduction} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all" />
            </div>
          </div>

          <div class="space-y-1.5">
            <label for="date" class="text-sm font-semibold text-gray-700">Tanggal Kejadian</label>
            <input type="date" bind:value={$form.incident_date} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all" />
          </div>

          <div class="space-y-1.5">
            <label for="desc" class="text-sm font-semibold text-gray-700">Deskripsi Kejadian</label>
            <textarea bind:value={$form.description} rows="3" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all resize-none"></textarea>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-red-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            Simpan Pelanggaran
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
