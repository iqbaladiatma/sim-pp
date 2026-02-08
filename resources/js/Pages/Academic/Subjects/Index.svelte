<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Edit2, 
    Trash2,
    BookOpen,
    X,
    Save,
    Search
  } from 'lucide-svelte';

  export let subjects;

  let isModalOpen = false;
  let editingSubject = null;

  const form = useForm({
    name: '',
    code: '',
    description: '',
  });

  function openModal(subject = null) {
    editingSubject = subject;
    if (subject) {
      $form.name = subject.name;
      $form.code = subject.code || '';
      $form.description = subject.description || '';
    } else {
      $form.reset();
    }
    isModalOpen = true;
  }

  function submit() {
    if (editingSubject) {
      $form.put(`/subjects/${editingSubject.id}`, {
        onSuccess: () => closeModal(),
      });
    } else {
      $form.post('/subjects', {
        onSuccess: () => closeModal(),
      });
    }
  }

  function closeModal() {
    isModalOpen = false;
    editingSubject = null;
    $form.reset();
  }

  function deleteSubject(id) {
    if (confirm('Hapus mata pelajaran ini?')) {
      router.delete(`/subjects/${id}`);
    }
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Mata Pelajaran</h1>
        <p class="text-gray-500 text-sm">Kelola daftar kurikulum dan mata pelajaran pondok</p>
      </div>
      <button 
        on:click={() => openModal()}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Mapel
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {#each subjects as subject}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 group transition-all hover:shadow-xl hover:shadow-gray-200/50">
          <div class="flex items-start justify-between mb-4">
            <div class="h-12 w-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
              <BookOpen class="h-6 w-6" />
            </div>
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button on:click={() => openModal(subject)} class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition">
                <Edit2 class="h-4 w-4" />
              </button>
              <button on:click={() => deleteSubject(subject.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                <Trash2 class="h-4 w-4" />
              </button>
            </div>
          </div>
          
          <h3 class="text-lg font-bold text-gray-900">{subject.name}</h3>
          <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-3">{subject.code || 'Tanpa Kode'}</p>
          <p class="text-sm text-gray-500 line-clamp-2">{subject.description || 'Tidak ada deskripsi.'}</p>
        </div>
      {:else}
        <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
           <p class="text-gray-400">Belum ada data mata pelajaran.</p>
        </div>
      {/each}
    </div>
  </div>

  <!-- Modal -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-900">{editingSubject ? 'Edit Mapel' : 'Tambah Mapel Baru'}</h2>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <X class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-6">
          <div class="space-y-1.5">
            <label for="name" class="text-sm font-semibold text-gray-700">Nama Mata Pelajaran</label>
            <input 
              id="name"
              type="text" 
              bind:value={$form.name}
              placeholder="Contoh: Bahasa Arab"
              required
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all"
            />
          </div>

          <div class="space-y-1.5">
            <label for="code" class="text-sm font-semibold text-gray-700">Kode Mapel (Opsional)</label>
            <input 
              id="code"
              type="text" 
              bind:value={$form.code}
              placeholder="Contoh: ARB-01"
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all"
            />
          </div>

          <div class="space-y-1.5">
            <label for="description" class="text-sm font-semibold text-gray-700">Deskripsi</label>
            <textarea 
              id="description"
              bind:value={$form.description}
              rows="3"
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all resize-none"
            ></textarea>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            {editingSubject ? 'Update Mapel' : 'Simpan Mapel'}
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
