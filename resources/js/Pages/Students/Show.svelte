<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { Link } from '@inertiajs/svelte';
  import { 
    ChevronLeft, 
    Edit2, 
    User, 
    Home, 
    Book, 
    Phone, 
    MapPin, 
    Calendar,
    Award,
    ShieldAlert,
    Clock,
    Printer
  } from 'lucide-svelte';

  export let student;

  const getStatusColor = (status) => {
    switch(status) {
      case 'active': return 'bg-green-50 text-green-600 border-green-100';
      case 'alumni': return 'bg-blue-50 text-blue-600 border-blue-100';
      case 'transferred': return 'bg-amber-50 text-amber-600 border-amber-100';
      case 'dropped_out': return 'bg-red-50 text-red-600 border-red-100';
      default: return 'bg-gray-50 text-gray-600 border-gray-100';
    }
  };
</script>

<AppLayout>
  <div class="max-w-5xl mx-auto space-y-6">
    <!-- Header/Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div class="flex items-center gap-4">
        <Link 
          href="/students" 
          class="h-10 w-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:shadow-sm transition"
        >
          <ChevronLeft class="h-5 w-5" />
        </Link>
        <div>
          <div class="flex items-center gap-2 mb-1">
            <h1 class="text-3xl font-extrabold text-gray-900">{student.name}</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {getStatusColor(student.status)} uppercase tracking-wider">
              {student.status}
            </span>
          </div>
          <p class="text-gray-500 font-medium">NIS: <span class="text-gray-900 font-bold">{student.nis}</span> • NISN: <span class="text-gray-900 font-bold">{student.nisn || '-'}</span></p>
        </div>
      </div>
      
      <div class="flex gap-3">
        <button class="flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-bold py-2.5 px-5 rounded-2xl border border-gray-200 transition-all">
          <Printer class="h-5 w-5" />
          Cetak Kartu
        </button>
        <Link 
          href="/students/{student.id}/edit" 
          class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
        >
          <Edit2 class="h-5 w-5" />
          Edit Profil
        </Link>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Left Column: Photo & Quick Info -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Photo Card -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-8 flex flex-col items-center">
          <div class="h-48 w-48 rounded-3xl bg-gray-50 border-4 border-white shadow-xl flex items-center justify-center text-gray-200 mb-6 group relative overflow-hidden">
            {#if student.photo}
              <img src={student.photo} alt={student.name} class="h-full w-full object-cover" />
            {:else}
              <User class="h-20 w-20" />
            {/if}
          </div>
          <div class="text-center">
            <p class="text-lg font-bold text-gray-900">{student.gender === 'L' ? 'Santriwan' : 'Santriwati'}</p>
            <p class="text-sm text-gray-500">{student.classroom?.name || 'Belum Masuk Kelas'}</p>
          </div>
        </div>

        <!-- Displine Stats -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
              <ShieldAlert class="h-5 w-5 text-amber-500" />
              Poin Kedisiplinan
            </h3>
            <span class="text-2xl font-black text-blue-600">{student.discipline_points}</span>
          </div>
          <div class="w-full bg-gray-100 h-2.5 rounded-full overflow-hidden">
            <div 
              class="bg-blue-600 h-full transition-all duration-500" 
              style="width: {student.discipline_points}%"
            ></div>
          </div>
          <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider text-center">Batas minimum: 0 poin</p>
        </div>

        <!-- Academic Placement -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-gray-50 bg-gray-50/30 flex items-center gap-2">
            <Award class="h-4 w-4 text-purple-600" />
            <span class="text-xs font-bold text-gray-800 uppercase tracking-widest">Penempatan</span>
          </div>
          <div class="p-6 space-y-4">
            <div class="flex items-start gap-4">
              <div class="h-8 w-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                <Book class="h-4 w-4" />
              </div>
              <div>
                <p class="text-xs text-gray-400 font-medium">Kelas Akademik</p>
                <p class="text-sm font-bold text-gray-900">{student.classroom?.name || '-'}</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <div class="h-8 w-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 shrink-0">
                <Home class="h-4 w-4" />
              </div>
              <div>
                <p class="text-xs text-gray-400 font-medium">Asrama / Kamar</p>
                <p class="text-sm font-bold text-gray-900">
                  {student.dormitory_room ? `${student.dormitory_room.building_name} - ${student.dormitory_room.room_number}` : 'Non-Asrama'}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Full Details -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Biodata Section -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
              <User class="h-5 w-5 text-blue-600" />
              Biodata Lengkap
            </h2>
          </div>
          <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Tempat, Tanggal Lahir</p>
              <p class="text-sm font-semibold text-gray-900">{student.birth_place || '-'}, {student.birth_date ? new Date(student.birth_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-'}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Tanggal Masuk Pondok</p>
              <p class="text-sm font-semibold text-gray-900">{student.entry_date ? new Date(student.entry_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-'}</p>
            </div>
            <div class="md:col-span-2">
              <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1 flex items-center gap-1">
                <MapPin class="h-3 w-3" />
                Alamat Rumah
              </p>
              <p class="text-sm font-semibold text-gray-900 leading-relaxed">{student.address || '-'}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1 flex items-center gap-1">
                <Phone class="h-3 w-3" />
                No. Telepon / WA
              </p>
              <p class="text-sm font-semibold text-gray-900">{student.phone_number || '-'}</p>
            </div>
          </div>
        </div>

        <!-- Activity/History Tabs (Placeholder UI) -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="border-b border-gray-100 flex overflow-x-auto">
            <button class="px-6 py-4 text-sm font-bold text-blue-600 border-b-2 border-blue-600 whitespace-nowrap">Riwayat Akademik</button>
            <button class="px-6 py-4 text-sm font-bold text-gray-400 hover:text-gray-600 whitespace-nowrap transition">Pelanggaran</button>
            <button class="px-6 py-4 text-sm font-bold text-gray-400 hover:text-gray-600 whitespace-nowrap transition">Perizinan</button>
            <button class="px-6 py-4 text-sm font-bold text-gray-400 hover:text-gray-600 whitespace-nowrap transition">Pembayaran</button>
          </div>
          <div class="p-12 text-center">
            <div class="flex flex-col items-center gap-3">
              <Clock class="h-10 w-10 text-gray-100" />
              <p class="text-gray-400 text-sm">Belum ada riwayat aktivitas yang tercatat.</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</AppLayout>
