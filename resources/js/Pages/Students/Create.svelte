<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { useForm, Link } from '@inertiajs/svelte';
  import { 
    ChevronLeft, 
    Save, 
    User, 
    Home, 
    Book, 
    Phone, 
    MapPin, 
    Calendar,
    Camera
  } from 'lucide-svelte';

  export let classrooms;
  export let dormitoryRooms;

  const form = useForm({
    nis: '',
    nisn: '',
    name: '',
    gender: 'L',
    birth_place: '',
    birth_date: '',
    address: '',
    phone_number: '',
    classroom_id: '',
    dormitory_room_id: '',
    entry_date: new Date().toISOString().split('T')[0],
    status: 'active',
  });

  function submit() {
    $form.post('/students');
  }
</script>

<AppLayout>
  <div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Title -->
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-4">
        <Link 
          href="/students" 
          class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:shadow-sm transition"
        >
          <ChevronLeft class="h-5 w-5" />
        </Link>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Tambah Santri</h1>
          <p class="text-gray-500 text-sm">Input data individu santri baru ke sistem</p>
        </div>
      </div>
    </div>

    <form on:submit|preventDefault={submit} class="space-y-6">
      <!-- Section 1: Basic Bio -->
      <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex items-center gap-3">
          <User class="h-5 w-5 text-blue-600" />
          <h2 class="font-bold text-gray-800">Informasi Dasar</h2>
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-1.5">
            <label for="name" class="text-sm font-semibold text-gray-700 ml-1">Nama Lengkap</label>
            <input 
              id="name"
              type="text" 
              bind:value={$form.name}
              placeholder="Masukkan nama sesuai ijazah"
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
            />
            {#if $form.errors.name}<p class="text-red-500 text-xs mt-1 ml-1">{$form.errors.name}</p>{/if}
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label for="nis" class="text-sm font-semibold text-gray-700 ml-1">NIS</label>
              <input 
                id="nis"
                type="text" 
                bind:value={$form.nis}
                placeholder="2026XXX"
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
              />
              {#if $form.errors.nis}<p class="text-red-500 text-xs mt-1 ml-1">{$form.errors.nis}</p>{/if}
            </div>
            <div class="space-y-1.5">
              <label for="nisn" class="text-sm font-semibold text-gray-700 ml-1">NISN</label>
              <input 
                id="nisn"
                type="text" 
                bind:value={$form.nisn}
                placeholder="No. Nasional"
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
              />
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700 ml-1">Jenis Kelamin</label>
            <div class="grid grid-cols-2 gap-4">
              <label class="flex items-center justify-center gap-2 p-3 rounded-2xl border border-gray-200 bg-gray-50 cursor-pointer transition-all has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 has-[:checked]:shadow-lg has-[:checked]:shadow-blue-100">
                <input type="radio" value="L" bind:group={$form.gender} class="hidden" />
                <span class="text-sm font-bold">Laki-laki</span>
              </label>
              <label class="flex items-center justify-center gap-2 p-3 rounded-2xl border border-gray-200 bg-gray-50 cursor-pointer transition-all has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 has-[:checked]:shadow-lg has-[:checked]:shadow-blue-100">
                <input type="radio" value="P" bind:group={$form.gender} class="hidden" />
                <span class="text-sm font-bold">Perempuan</span>
              </label>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label for="birth_place" class="text-sm font-semibold text-gray-700 ml-1">Tempat Lahir</label>
              <input 
                id="birth_place"
                type="text" 
                bind:value={$form.birth_place}
                placeholder="Contoh: Jakarta"
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
              />
            </div>
            <div class="space-y-1.5">
              <label for="birth_date" class="text-sm font-semibold text-gray-700 ml-1">Tanggal Lahir</label>
              <input 
                id="birth_date"
                type="date" 
                bind:value={$form.birth_date}
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Section 2: Contact & Status -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Academic Info -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex items-center gap-3">
            <Book class="h-5 w-5 text-purple-600" />
            <h2 class="font-bold text-gray-800">Akademik & Asrama</h2>
          </div>
          <div class="p-8 space-y-6">
            <div class="space-y-1.5">
              <label for="classroom_id" class="text-sm font-semibold text-gray-700 ml-1">Kelas</label>
              <select 
                id="classroom_id"
                bind:value={$form.classroom_id}
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 appearance-none transition-all outline-none cursor-pointer"
              >
                <option value="">Pilih Kelas</option>
                {#each classrooms as classroom}
                  <option value={classroom.id}>{classroom.name}</option>
                {/each}
              </select>
            </div>

            <div class="space-y-1.5">
              <label for="dormitory_room_id" class="text-sm font-semibold text-gray-700 ml-1">Asrama / Kamar</label>
              <select 
                id="dormitory_room_id"
                bind:value={$form.dormitory_room_id}
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 appearance-none transition-all outline-none cursor-pointer"
              >
                <option value="">Luar Pondok (Non-Asrama)</option>
                {#each dormitoryRooms as room}
                  <option value={room.id}>{room.building_name} - {room.room_number}</option>
                {/each}
              </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label for="entry_date" class="text-sm font-semibold text-gray-700 ml-1">Tgl Masuk</label>
                <input 
                  id="entry_date"
                  type="date" 
                  bind:value={$form.entry_date}
                  class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                />
              </div>
              <div class="space-y-1.5">
                <label for="status" class="text-sm font-semibold text-gray-700 ml-1">Status</label>
                <select 
                  id="status"
                  bind:value={$form.status}
                  class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 appearance-none transition-all outline-none cursor-pointer"
                >
                  <option value="active">Aktif</option>
                  <option value="alumni">Alumni</option>
                  <option value="transferred">Pindah</option>
                  <option value="dropped_out">Dikeluarkan</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
          <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex items-center gap-3">
            <Phone class="h-5 w-5 text-green-600" />
            <h2 class="font-bold text-gray-800">Kontak & Alamat</h2>
          </div>
          <div class="p-8 space-y-6 flex-1">
            <div class="space-y-1.5">
              <label for="phone_number" class="text-sm font-semibold text-gray-700 ml-1">No. WhatsApp Santri/Wali</label>
              <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-green-500 transition-colors">
                  <span class="text-sm font-bold text-gray-400">+62</span>
                </div>
                <input 
                  id="phone_number"
                  type="text" 
                  bind:value={$form.phone_number}
                  placeholder="812xxx"
                  class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-green-500/10 focus:border-green-500 transition-all outline-none"
                />
              </div>
            </div>

            <div class="space-y-1.5 flex-1">
              <label for="address" class="text-sm font-semibold text-gray-700 ml-1">Alamat Lengkap</label>
              <textarea 
                id="address"
                bind:value={$form.address}
                rows="5"
                placeholder="Nama jalan, perumahan, RT/RW, Kecamatan, Kota"
                class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none resize-none"
              ></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Footer -->
      <div class="flex items-center justify-end gap-4 pb-12">
        <Link 
          href="/students" 
          class="bg-white hover:bg-gray-50 text-gray-700 font-bold py-3.5 px-8 rounded-2xl border border-gray-200 transition-all"
        >
          Batal
        </Link>
        <button 
          type="submit" 
          disabled={$form.processing}
          class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-10 rounded-2xl shadow-xl shadow-blue-200 transition-all transform active:scale-[0.98] flex items-center gap-2 disabled:opacity-70"
        >
          {#if $form.processing}
            <div class="h-5 w-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            Menyimpan...
          {:else}
            <Save class="h-5 w-5" />
            Simpan Data Santri
          {/if}
        </button>
      </div>
    </form>
  </div>
</AppLayout>

<style>
  :global(input[type="date"]::-webkit-calendar-picker-indicator) {
    cursor: pointer;
    filter: invert(0.5);
  }
</style>
