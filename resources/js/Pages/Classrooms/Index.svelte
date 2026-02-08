<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Edit2, 
    Trash2,
    Users,
    GraduationCap,
    School,
    X,
    Save
  } from 'lucide-svelte';

  export let classrooms;
  export let teachers;

  let isModalOpen = false;
  let editingClassroom = null;

  const form = useForm({
    name: '',
    level: '',
    academic_year: '2025/2026',
    homeroom_teacher_id: '',
    capacity: 40,
  });

  function openModal(classroom = null) {
    editingClassroom = classroom;
    if (classroom) {
      $form.name = classroom.name;
      $form.level = classroom.level || '';
      $form.academic_year = classroom.academic_year || '2025/2026';
      $form.homeroom_teacher_id = classroom.homeroom_teacher_id || '';
      $form.capacity = classroom.capacity;
    } else {
      $form.reset();
    }
    isModalOpen = true;
  }

  function submit() {
    if (editingClassroom) {
      $form.put(`/classrooms/${editingClassroom.id}`, {
        onSuccess: () => closeModal(),
      });
    } else {
      $form.post('/classrooms', {
        onSuccess: () => closeModal(),
      });
    }
  }

  function closeModal() {
    isModalOpen = false;
    editingClassroom = null;
    $form.reset();
  }

  function deleteClassroom(id) {
    if (confirm('Hapus kelas ini? Semua data relasi santri akan menjadi null.')) {
      router.delete(`/classrooms/${id}`);
    }
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Kelas</h1>
        <p class="text-gray-500 text-sm">Atur daftar kelas akademik dan wali kelas</p>
      </div>
      <button 
        on:click={() => openModal()}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Kelas
      </button>
    </div>

    <!-- Class Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {#each classrooms as classroom}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all group p-6">
          <div class="flex items-start justify-between mb-6">
            <div class="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
              <School class="h-6 w-6" />
            </div>
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button on:click={() => openModal(classroom)} class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition">
                <Edit2 class="h-4 w-4" />
              </button>
              <button on:click={() => deleteClassroom(classroom.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                <Trash2 class="h-4 w-4" />
              </button>
            </div>
          </div>
          
          <div class="space-y-4">
            <div>
              <h3 class="text-xl font-black text-gray-900">{classroom.name}</h3>
              <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">{classroom.level || 'Umum'} • {classroom.academic_year}</p>
            </div>

            <div class="pt-4 border-t border-gray-50 space-y-3">
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-400 font-medium">Wali Kelas</span>
                <span class="text-gray-900 font-bold">{classroom.homeroom_teacher?.name || 'Belum ditunjuk'}</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-400 font-medium">Kapasitas</span>
                <span class="text-gray-900 font-bold">{classroom.capacity} Santri</span>
              </div>
            </div>
          </div>
        </div>
      {:else}
        <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
           <p class="text-gray-400">Belum ada data kelas.</p>
        </div>
      {/each}
    </div>
  </div>

  <!-- Modal -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-900">{editingClassroom ? 'Edit Kelas' : 'Tambah Kelas Baru'}</h2>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <X class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-6">
          <div class="space-y-1.5">
            <label for="name" class="text-sm font-semibold text-gray-700">Nama Kelas</label>
            <input 
              id="name"
              type="text" 
              bind:value={$form.name}
              placeholder="Contoh: 10 IPA 1"
              required
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
            />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label for="level" class="text-sm font-semibold text-gray-700">Tingkat</label>
              <input 
                id="level"
                type="text" 
                bind:value={$form.level}
                placeholder="SMP/SMA"
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
              />
            </div>
            <div class="space-y-1.5">
              <label for="capacity" class="text-sm font-semibold text-gray-700">Kapasitas</label>
              <input 
                id="capacity"
                type="number" 
                bind:value={$form.capacity}
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
              />
            </div>
          </div>

          <div class="space-y-1.5">
            <label for="homeroom_teacher_id" class="text-sm font-semibold text-gray-700">Wali Kelas</label>
            <select 
              id="homeroom_teacher_id"
              bind:value={$form.homeroom_teacher_id}
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all appearance-none"
            >
              <option value="">Pilih Pengajar</option>
              {#each teachers as teacher}
                <option value={teacher.id}>{teacher.name}</option>
              {/each}
            </select>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            {editingClassroom ? 'Update Data' : 'Simpan Kelas'}
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
