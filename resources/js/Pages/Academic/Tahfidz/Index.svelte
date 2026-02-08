<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Trash2,
    BookOpen,
    X,
    Save,
    Calendar,
    Star,
    Award
  } from 'lucide-svelte';

  export let progress;
  export let students;

  let isModalOpen = false;

  const form = useForm({
    student_id: '',
    surah_name: '',
    start_verse: '',
    end_verse: '',
    type: 'ziyadah',
    quality: 'A',
    recorded_at: new Date().toISOString().split('T')[0],
    note: '',
  });

  function openModal() {
    $form.reset();
    isModalOpen = true;
  }

  function submit() {
    $form.post('/tahfidz', {
      onSuccess: () => closeModal(),
    });
  }

  function closeModal() {
    isModalOpen = false;
    $form.reset();
  }

  function deleteProgress(id) {
    if (confirm('Hapus catatan tahfidz ini?')) {
      router.delete(`/tahfidz/${id}`);
    }
  }

  const getQualityColor = (q) => {
    switch(q) {
      case 'A': return 'bg-green-50 text-green-600 border-green-100';
      case 'B': return 'bg-blue-50 text-blue-600 border-blue-100';
      case 'C': return 'bg-amber-50 text-amber-600 border-amber-100';
      default: return 'bg-red-50 text-red-600 border-red-100';
    }
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Setoran Tahfidz</h1>
        <p class="text-gray-500 text-sm">Pantau progres hapalan Al-Qur'an santri harian</p>
      </div>
      <button 
        on:click={openModal}
        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-emerald-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Setoran
      </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-widest">
              <th class="px-6 py-4">Santri</th>
              <th class="px-6 py-4">Surah & Ayat</th>
              <th class="px-6 py-4">Jenis</th>
              <th class="px-6 py-4 text-center">Kualitas</th>
              <th class="px-6 py-4 text-right">Tanggal</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            {#each progress.data as item}
              <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                   <p class="text-sm font-bold text-gray-900">{item.student.name}</p>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <BookOpen class="h-4 w-4 text-emerald-600" />
                    <div>
                      <p class="text-sm font-bold text-gray-800">{item.surah_name}</p>
                      <p class="text-[10px] text-gray-400 font-bold">Ayat {item.start_verse || '?'}-{item.end_verse || '?'}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="text-xs font-bold uppercase tracking-wider {item.type === 'ziyadah' ? 'text-blue-600' : 'text-purple-600'}">
                    {item.type}
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-bold border {getQualityColor(item.quality)}">
                    Mumtaz ({item.quality})
                  </span>
                </td>
                <td class="px-6 py-4 text-right text-xs text-gray-500 font-medium">
                  {new Date(item.recorded_at).toLocaleDateString('id-ID')}
                </td>
                <td class="px-6 py-4 text-right">
                  <button on:click={() => deleteProgress(item.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition opacity-0 group-hover:opacity-100">
                    <Trash2 class="h-4 w-4" />
                  </button>
                </td>
              </tr>
            {:else}
               <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Belum ada progres setoran yang tercatat.</td>
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
            <Award class="h-6 w-6 text-emerald-600" />
            Catat Setoran Baru
          </h2>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <X class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-6">
          <div class="space-y-1.5">
            <label for="student_id" class="text-sm font-semibold text-gray-700">Pilih Santri</label>
            <select bind:value={$form.student_id} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all appearance-none" required>
              <option value="">Cari Santri...</option>
              {#each students as student}
                <option value={student.id}>{student.name}</option>
              {/each}
            </select>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5 col-span-2">
              <label for="surah" class="text-sm font-semibold text-gray-700">Nama Surah</label>
              <input type="text" bind:value={$form.surah_name} placeholder="Contoh: Al-Baqarah" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all" />
            </div>
            <div class="space-y-1.5">
              <label for="start" class="text-sm font-semibold text-gray-700">Ayat Mulai</label>
              <input type="number" bind:value={$form.start_verse} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all" />
            </div>
            <div class="space-y-1.5">
              <label for="end" class="text-sm font-semibold text-gray-700">Ayat Selesai</label>
              <input type="number" bind:value={$form.end_verse} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700">Jenis Setoran</label>
              <div class="flex bg-gray-50 p-1 rounded-2xl border border-gray-200">
                <button type="button" on:click={() => $form.type = 'ziyadah'} class="flex-1 py-2 text-xs font-bold rounded-xl transition-all {$form.type === 'ziyadah' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400'}">ZIYADAH</button>
                <button type="button" on:click={() => $form.type = 'murajaah'} class="flex-1 py-2 text-xs font-bold rounded-xl transition-all {$form.type === 'murajaah' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-400'}">MURAJAAH</button>
              </div>
            </div>
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700">Kualitas</label>
              <select bind:value={$form.quality} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none transition-all appearance-none">
                <option value="A">Mumtaz (A)</option>
                <option value="B">Jayyid Jiddan (B)</option>
                <option value="C">Jayyid (C)</option>
                <option value="D">Maqbul (D)</option>
              </select>
            </div>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-emerald-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            Simpan Setoran
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
