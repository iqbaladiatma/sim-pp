<script>
  import AppLayout from '../../Layouts/AppLayout.svelte';
  import { router, Link } from '@inertiajs/svelte';
  import { 
    Plus, 
    Search, 
    Filter, 
    MoreHorizontal, 
    Eye, 
    Edit2, 
    Trash2,
    Users,
    GraduationCap,
    Clock
  } from 'lucide-svelte';

  export let students;
  export let classrooms;
  export let filters;

  let search = filters.search || '';
  let classroom_id = filters.classroom_id || '';

  function handleFilter() {
    router.get('/students', { search, classroom_id }, { 
      preserveState: true,
      replace: true 
    });
  }

  function deleteStudent(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data santri ini?')) {
      router.delete(`/students/${id}`);
    }
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Database Santri</h1>
        <p class="text-gray-500 text-sm">Kelola informasi biodata, kelas, dan asrama seluruh santri</p>
      </div>
      <Link 
        href="/students/create" 
        class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Santri Baru
      </Link>
    </div>

    <!-- Stats Cards (Optional/Mock) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
          <Users class="h-6 w-6" />
        </div>
        <div>
          <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Santri</p>
          <p class="text-2xl font-bold text-gray-900">{students.total}</p>
        </div>
      </div>
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="h-12 w-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600">
          <GraduationCap class="h-6 w-6" />
        </div>
        <div>
          <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Aktif</p>
          <p class="text-2xl font-bold text-gray-900">{students.total}</p> <!-- Temporarily same -->
        </div>
      </div>
      <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="h-12 w-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600">
          <Clock class="h-6 w-6" />
        </div>
        <div>
          <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tingkat Kelas</p>
          <p class="text-2xl font-bold text-gray-900">{classrooms.length}</p>
        </div>
      </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
      <div class="flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1">
          <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <Search class="h-5 w-5 text-gray-400" />
          </div>
          <input 
            type="text" 
            bind:value={search}
            on:input={handleFilter}
            placeholder="Cari nama atau NIS santri..."
            class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
          />
        </div>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
          <div class="relative flex-1 md:w-48">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
              <Filter class="h-4 w-4 text-gray-400" />
            </div>
            <select 
              bind:value={classroom_id}
              on:change={handleFilter}
              class="block w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 appearance-none transition-all outline-none cursor-pointer"
            >
              <option value="">Semua Kelas</option>
              {#each classrooms as classroom}
                <option value={classroom.id}>{classroom.name}</option>
              {/each}
            </select>
          </div>
          
          <button 
            on:click={() => { search = ''; classroom_id = ''; handleFilter(); }}
            class="p-3 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-2xl transition"
            title="Reset Filter"
          >
            <Clock class="h-5 w-5 rotate-180" />
          </button>
        </div>
      </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-widest">
              <th class="px-6 py-4">Nama & NIS</th>
              <th class="px-6 py-4">Gender</th>
              <th class="px-6 py-4">Kelas</th>
              <th class="px-6 py-4">Asrama</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            {#each students.data as student}
              <tr class="hover:bg-blue-50/30 transition-colors group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-gray-500 group-hover:bg-white group-hover:shadow-sm transition-all">
                      {#if student.photo}
                        <img src={student.photo} alt={student.name} class="h-full w-full object-cover rounded-xl" />
                      {:else}
                        {student.name.charAt(0)}
                      {/if}
                    </div>
                    <div>
                      <p class="text-sm font-bold text-gray-900">{student.name}</p>
                      <p class="text-xs text-gray-400">{student.nis}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold {student.gender === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'}">
                    {student.gender === 'L' ? 'Laki-laki' : 'Perempuan'}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  {student.classroom?.name || 'Belum diatur'}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  {student.dormitory_room ? `${student.dormitory_room.building_name} - ${student.dormitory_room.room_number}` : 'Luar Pondok'}
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {student.status === 'active' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500'}">
                    <span class="h-1.5 w-1.5 rounded-full {student.status === 'active' ? 'bg-green-600' : 'bg-gray-500'}"></span>
                    {student.status}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <Link href="/students/{student.id}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition" title="Lihat Detail">
                      <Eye class="h-4 w-4" />
                    </Link>
                    <Link href="/students/{student.id}/edit" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition" title="Edit Data">
                      <Edit2 class="h-4 w-4" />
                    </Link>
                    <button on:click={() => deleteStudent(student.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition" title="Hapus Data">
                      <Trash2 class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            {:else}
              <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <Users class="h-12 w-12 text-gray-200" />
                    <p class="text-gray-400 text-sm">Tidak ada data santri ditemukan.</p>
                  </div>
                </td>
              </tr>
            {/each}
          </tbody>
        </table>
      </div>

      <!-- Pagination (Simple Placeholder) -->
      {#if students.links.length > 3}
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between">
          <p class="text-xs text-gray-500 italic">Showing {students.from} to {students.to} of {students.total} students</p>
          <div class="flex items-center gap-2">
            {#each students.links as link}
              <Link 
                href={link.url} 
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {link.active ? 'bg-blue-600 text-white shadow-md shadow-blue-100' : 'bg-white text-gray-500 border border-gray-200 hover:bg-gray-50'} {!link.url ? 'opacity-50 cursor-not-allowed hidden' : ''}"
                dangerouslySetInnerHTML={{ __html: link.label }}
              />
            {/each}
          </div>
        </div>
      {/if}
    </div>
  </div>
</AppLayout>
