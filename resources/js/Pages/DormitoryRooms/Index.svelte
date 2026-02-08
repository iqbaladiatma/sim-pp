<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Edit2, 
    Trash2,
    Home,
    User,
    X,
    Save,
    Map
  } from 'lucide-svelte';

  export let rooms;
  export let supervisors;

  let isModalOpen = false;
  let editingRoom = null;

  const form = useForm({
    building_name: '',
    room_number: '',
    floor: 1,
    capacity: 10,
    gender: 'L',
    supervisor_id: '',
  });

  function openModal(room = null) {
    editingRoom = room;
    if (room) {
      $form.building_name = room.building_name;
      $form.room_number = room.room_number;
      $form.floor = room.floor;
      $form.capacity = room.capacity;
      $form.gender = room.gender;
      $form.supervisor_id = room.supervisor_id || '';
    } else {
      $form.reset();
    }
    isModalOpen = true;
  }

  function submit() {
    if (editingRoom) {
      $form.put(`/dormitory-rooms/${editingRoom.id}`, {
        onSuccess: () => closeModal(),
      });
    } else {
      $form.post('/dormitory-rooms', {
        onSuccess: () => closeModal(),
      });
    }
  }

  function closeModal() {
    isModalOpen = false;
    editingRoom = null;
    $form.reset();
  }

  function deleteRoom(id) {
    if (confirm('Hapus kamar asrama ini?')) {
      router.delete(`/dormitory-rooms/${id}`);
    }
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Asrama</h1>
        <p class="text-gray-500 text-sm">Kelola gedung asrama, kapasitas kamar, dan pembimbing</p>
      </div>
      <button 
        on:click={() => openModal()}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Kamar
      </button>
    </div>

    <!-- Room Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {#each rooms as room}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-gray-200/50 transition-all group p-6">
          <div class="flex items-start justify-between mb-6">
            <div class="h-12 w-12 rounded-2xl {room.gender === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'} flex items-center justify-center">
              <Home class="h-6 w-6" />
            </div>
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button on:click={() => openModal(room)} class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition">
                <Edit2 class="h-4 w-4" />
              </button>
              <button on:click={() => deleteRoom(room.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                <Trash2 class="h-4 w-4" />
              </button>
            </div>
          </div>
          
          <div class="space-y-4">
            <div>
              <h3 class="text-xl font-black text-gray-900">{room.building_name}</h3>
              <p class="text-xs font-bold {room.gender === 'L' ? 'text-blue-600' : 'text-pink-600'} uppercase tracking-widest">Kamar {room.room_number} • Lt. {room.floor}</p>
            </div>

            <div class="pt-4 border-t border-gray-50 space-y-3">
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-400 font-medium">Pembimbing</span>
                <span class="text-gray-900 font-bold">{room.supervisor?.name || 'Belum ditunjuk'}</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="text-gray-400 font-medium">Kapasitas</span>
                <span class="text-gray-900 font-bold">{room.capacity} Santri</span>
              </div>
            </div>
          </div>
        </div>
      {:else}
        <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
           <p class="text-gray-400">Belum ada data kamar asrama.</p>
        </div>
      {/each}
    </div>
  </div>

  <!-- Modal -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-900">{editingRoom ? 'Edit Kamar' : 'Tambah Kamar Baru'}</h2>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <X class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-6">
          <div class="space-y-1.5">
            <label for="building_name" class="text-sm font-semibold text-gray-700">Nama Gedung</label>
            <input 
              id="building_name"
              type="text" 
              bind:value={$form.building_name}
              placeholder="Contoh: Gedung Abu Bakar"
              required
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
            />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label for="room_number" class="text-sm font-semibold text-gray-700">Nomor Kamar</label>
              <input 
                id="room_number"
                type="text" 
                bind:value={$form.room_number}
                placeholder="A.01"
                required
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
              />
            </div>
            <div class="space-y-1.5">
              <label for="floor" class="text-sm font-semibold text-gray-700">Lantai</label>
              <input 
                id="floor"
                type="number" 
                bind:value={$form.floor}
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label for="capacity" class="text-sm font-semibold text-gray-700">Kapasitas</label>
              <input 
                id="capacity"
                type="number" 
                bind:value={$form.capacity}
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
              />
            </div>
            <div class="space-y-1.5">
              <label class="text-sm font-semibold text-gray-700">Peruntukan</label>
              <div class="flex bg-gray-50 p-1 rounded-2xl border border-gray-200">
                <button 
                  type="button" 
                  on:click={() => $form.gender = 'L'}
                  class="flex-1 py-2 text-xs font-bold rounded-xl transition-all {$form.gender === 'L' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400'}"
                >PA</button>
                <button 
                  type="button" 
                  on:click={() => $form.gender = 'P'}
                  class="flex-1 py-2 text-xs font-bold rounded-xl transition-all {$form.gender === 'P' ? 'bg-pink-600 text-white shadow-md' : 'text-gray-400'}"
                >PI</button>
              </div>
            </div>
          </div>

          <div class="space-y-1.5">
            <label for="supervisor_id" class="text-sm font-semibold text-gray-700">Pembimbing Asrama</label>
            <select 
              id="supervisor_id"
              bind:value={$form.supervisor_id}
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all appearance-none"
            >
              <option value="">Pilih Pembimbing</option>
              {#each supervisors as supervisor}
                <option value={supervisor.id}>{supervisor.name}</option>
              {/each}
            </select>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            {editingRoom ? 'Update Data' : 'Simpan Kamar'}
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
