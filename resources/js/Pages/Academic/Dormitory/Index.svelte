<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Home, 
    Plus, 
    Users, 
    UserPlus,
    X, 
    Save, 
    Trash2, 
    Edit2,
    Building,
    MapPin,
    ArrowRightLeft,
    CheckCircle2,
    AlertTriangle
  } from 'lucide-svelte';

  export let rooms;
  export let supervisors;
  export let unassignedStudents;

  let isModalOpen = false;
  let isAssignModalOpen = false;
  let editingRoom = null;
  let selectedRoomForAssignment = null;

  const form = useForm({
    building_name: '',
    room_number: '',
    floor: 1,
    capacity: 10,
    gender: 'L',
    supervisor_id: '',
  });

  const assignForm = useForm({
    student_id: '',
    dormitory_room_id: '',
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

  function openAssignModal(room) {
    selectedRoomForAssignment = room;
    $assignForm.dormitory_room_id = room.id;
    $assignForm.student_id = '';
    isAssignModalOpen = true;
  }

  function submit() {
    if (editingRoom) {
      $form.put(`/dormitories/${editingRoom.id}`, {
        onSuccess: () => closeModal(),
      });
    } else {
      $form.post('/dormitories', {
        onSuccess: () => closeModal(),
      });
    }
  }

  function submitAssignment() {
    $assignForm.post('/dormitories/assign', {
      onSuccess: () => {
        isAssignModalOpen = false;
        $assignForm.reset();
      }
    });
  }

  function closeModal() {
    isModalOpen = false;
    editingRoom = null;
    $form.reset();
  }

  function deleteRoom(id) {
    if (confirm('Hapus kamar ini?')) {
      router.delete(`/dormitories/${id}`);
    }
  }

  function removeStudent(studentId) {
    if (confirm('Keluarkan santri dari kamar ini?')) {
      router.post('/dormitories/assign', {
        student_id: studentId,
        dormitory_room_id: null
      });
    }
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen Asrama</h1>
        <p class="text-gray-500 text-sm">Kelola gedung, kamar, dan penempatan asrama santri</p>
      </div>
      <button 
        on:click={() => openModal()}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-black text-white font-black py-2.5 px-6 rounded-2xl shadow-xl shadow-blue-100 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Kamar
      </button>
    </div>

    <!-- Stats summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
       <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Total Gedung</p>
          <p class="text-2xl font-black text-gray-900 leading-none">
             {[...new Set(rooms.map(r => r.building_name))].length}
          </p>
       </div>
       <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Santri Belum Masuk Asrama</p>
          <p class="text-2xl font-black text-amber-500 leading-none">{unassignedStudents.length}</p>
       </div>
    </div>

    <!-- Room Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
       {#each rooms as room}
          <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all flex flex-col">
             <div class="p-8 space-y-6 flex-1">
                <div class="flex items-start justify-between">
                   <div class="flex items-center gap-4">
                      <div class="h-14 w-14 rounded-2xl {room.gender === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'} flex items-center justify-center shadow-sm">
                         <Home class="h-7 w-7" />
                      </div>
                      <div>
                         <p class="text-lg font-black text-gray-900 leading-tight">{room.building_name}</p>
                         <p class="text-sm font-bold text-gray-400 uppercase tracking-tighter">Kamar {room.room_number}</p>
                      </div>
                   </div>
                   <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button on:click={() => openModal(room)} class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                         <Edit2 class="h-4 w-4" />
                      </button>
                      <button on:click={() => deleteRoom(room.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                         <Trash2 class="h-4 w-4" />
                      </button>
                   </div>
                </div>

                <div class="space-y-4">
                   <div class="flex items-center justify-between text-xs font-bold">
                      <span class="text-gray-400 uppercase tracking-widest">Kapasitas</span>
                      <span class="text-gray-900">{room.students_count} / {room.capacity} Santri</span>
                   </div>
                   <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                      <div 
                         class="h-full transition-all duration-500 {room.students_count >= room.capacity ? 'bg-red-500' : 'bg-emerald-500'}" 
                         style="width: {(room.students_count / room.capacity) * 100}%"
                      ></div>
                   </div>
                </div>

                <div class="flex items-center gap-2 pt-2 text-xs font-bold text-gray-500">
                   <MapPin class="h-3.5 w-3.5" />
                   Lantai {room.floor}
                   <span class="mx-1">•</span>
                   <Users class="h-3.5 w-3.5" />
                   {room.supervisor?.name || 'Belum ada pembina'}
                </div>

                <div class="space-y-2 pt-2">
                   {#each room.students.slice(0, 3) as student}
                      <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl group/item">
                         <span class="text-xs font-bold text-gray-700">{student.name}</span>
                         <button on:click={() => removeStudent(student.id)} class="text-gray-300 hover:text-red-500 opacity-0 group-hover/item:opacity-100 transition-opacity">
                            <X class="h-3 w-3" />
                         </button>
                      </div>
                   {/each}
                   {#if room.students_count > 3}
                      <p class="text-[10px] text-center font-black text-gray-400 uppercase tracking-widest">+{room.students_count - 3} Santri Lainnya</p>
                   {/if}
                </div>
             </div>

             <div class="p-4 bg-gray-50 flex items-center gap-2">
                <button 
                   on:click={() => openAssignModal(room)}
                   disabled={room.students_count >= room.capacity}
                   class="w-full py-3 bg-white hover:bg-gray-100 text-gray-900 font-black text-[10px] uppercase tracking-widest rounded-2xl border border-gray-200 transition-all flex items-center justify-center gap-2 disabled:opacity-50"
                >
                   <UserPlus class="h-4 w-4" />
                   Tambah Santri
                </button>
             </div>
          </div>
       {:else}
          <div class="col-span-full py-20 text-center opacity-30 italic font-bold">
             Belum ada data kamar asrama.
          </div>
       {/each}
    </div>
  </div>

  <!-- Modal: Room CRUD -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-lg rounded-[40px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
          <div>
             <h2 class="text-2xl font-black text-gray-900 uppercase">{editingRoom ? 'Edit Kamar' : 'Kamar Baru'}</h2>
             <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Konfigurasi Ruang Asrama</p>
          </div>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-2xl transition">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-5">
           <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1.5 col-span-2">
                <label for="building" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Gedung</label>
                <input type="text" bind:value={$form.building_name} placeholder="e.g., Asrama Al-Ghazali" class="block w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/10 focus:bg-white transition-all" required />
              </div>
              <div class="space-y-1.5">
                <label for="number" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">No. Kamar</label>
                <input type="text" bind:value={$form.room_number} placeholder="101" class="block w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/10 focus:bg-white transition-all" required />
              </div>
              <div class="space-y-1.5">
                <label for="floor" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Lantai</label>
                <input type="number" bind:value={$form.floor} class="block w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/10 focus:bg-white transition-all" required />
              </div>
              <div class="space-y-1.5">
                <label for="capacity" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kapasitas</label>
                <input type="number" bind:value={$form.capacity} class="block w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/10 focus:bg-white transition-all" required />
              </div>
              <div class="space-y-1.5">
                <label for="gender" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Khusus</label>
                <select bind:value={$form.gender} class="block w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/10 focus:bg-white transition-all appearance-none cursor-pointer">
                   <option value="L">Laki-laki</option>
                   <option value="P">Perempuan</option>
                </select>
              </div>
              <div class="space-y-1.5 col-span-2">
                <label for="supervisor" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pembina Asrama</label>
                <select bind:value={$form.supervisor_id} class="block w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/10 focus:bg-white transition-all appearance-none cursor-pointer">
                   <option value="">Pilih Pembina...</option>
                   {#each supervisors as s}
                      <option value={s.id}>{s.name}</option>
                   {/each}
                </select>
              </div>
           </div>

           <button 
             type="submit" 
             disabled={$form.processing}
             class="w-full bg-blue-600 hover:bg-black text-white font-black py-4 rounded-3xl shadow-xl shadow-blue-100 transition-all flex items-center justify-center gap-2 transform active:scale-95"
           >
             <Save class="h-5 w-5" />
             {editingRoom ? 'PERBARUI DATA' : 'SIMPAN KAMAR'}
           </button>
        </form>
      </div>
    </div>
  {/if}

  <!-- Modal: Assign Student -->
  {#if isAssignModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-md rounded-[40px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
          <div>
             <h2 class="text-2xl font-black text-gray-900 uppercase">Input Santri</h2>
             <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                {selectedRoomForAssignment?.building_name} - {selectedRoomForAssignment?.room_number}
             </p>
          </div>
          <button on:click={() => isAssignModalOpen = false} class="p-2 text-gray-400 hover:bg-gray-50 rounded-2xl transition">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submitAssignment} class="p-8 space-y-6">
           <div class="space-y-2">
              <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Cari Nama Santri</label>
              <select bind:value={$assignForm.student_id} required class="block w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/10 focus:bg-white transition-all appearance-none cursor-pointer">
                 <option value="">Cari Santri...</option>
                 {#each unassignedStudents as student}
                    <option value={student.id}>{student.name} ({student.gender})</option>
                 {/each}
              </select>
              {#if unassignedStudents.length === 0}
                 <p class="text-[10px] text-amber-500 font-bold italic">Semua santri sudah memiliki kamar.</p>
              {/if}
           </div>

           <button 
             type="submit" 
             disabled={$assignForm.processing || !$assignForm.student_id}
             class="w-full bg-emerald-600 hover:bg-black text-white font-black py-4 rounded-3xl shadow-xl shadow-emerald-100 transition-all flex items-center justify-center gap-2 transform active:scale-95"
           >
             <CheckCircle2 class="h-5 w-5" />
             KONFIRMASI PENEMPATAN
           </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
