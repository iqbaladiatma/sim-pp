<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Edit2, 
    Trash2, 
    X, 
    Save, 
    User, 
    BadgeCheck, 
    Briefcase,
    Mail,
    Phone,
    Banknote
  } from 'lucide-svelte';

  export let teachers;

  let isModalOpen = false;
  let editingTeacher = null;

  const form = useForm({
    name: '',
    email: '',
    nip: '',
    position: '',
    specialization: '',
    salary: '',
    join_date: '',
    status: 'active',
  });

  function openModal(teacher = null) {
    editingTeacher = teacher;
    if (teacher) {
      $form.name = teacher.user.name;
      $form.email = teacher.user.email;
      $form.nip = teacher.nip || '';
      $form.position = teacher.position;
      $form.specialization = teacher.specialization || '';
      $form.salary = teacher.salary;
      $form.join_date = teacher.join_date || '';
      $form.status = teacher.status;
    } else {
      $form.reset();
    }
    isModalOpen = true;
  }

  function submit() {
    if (editingTeacher) {
      $form.put(`/teachers/${editingTeacher.id}`, {
        onSuccess: () => closeModal(),
      });
    } else {
      $form.post('/teachers', {
        onSuccess: () => closeModal(),
      });
    }
  }

  function closeModal() {
    isModalOpen = false;
    editingTeacher = null;
    $form.reset();
  }

  function deleteTeacher(id) {
    if (confirm('Hapus data guru ini? User akun ustadz juga akan dihapus.')) {
      router.delete(`/teachers/${id}`);
    }
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Data Pengajar & Guru</h1>
        <p class="text-gray-500 text-sm">Manajemen sumber daya manusia dan tenaga pendidik pesantren</p>
      </div>
      <button 
        on:click={() => openModal()}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Pengajar
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {#each teachers as teacher}
        <div class="group bg-white rounded-[32px] border border-gray-100 shadow-sm p-6 transition-all hover:shadow-xl hover:shadow-gray-200/50 relative overflow-hidden">
          <div class="flex items-start justify-between relative z-10">
            <div class="flex items-center gap-4">
              <div class="h-14 w-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xl">
                {teacher.user.name.charAt(0)}
              </div>
              <div>
                <h3 class="font-bold text-gray-900 leading-tight">{teacher.user.name}</h3>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mt-0.5">{teacher.position}</p>
              </div>
            </div>
            
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button on:click={() => openModal(teacher)} class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                <Edit2 class="h-4 w-4" />
              </button>
              <button on:click={() => deleteTeacher(teacher.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                <Trash2 class="h-4 w-4" />
              </button>
            </div>
          </div>

          <div class="mt-6 space-y-3 relative z-10">
            <div class="flex items-center gap-3 text-xs text-gray-500">
               <Mail class="h-3.5 w-3.5 text-gray-400" />
               <span>{teacher.user.email}</span>
            </div>
            {#if teacher.nip}
              <div class="flex items-center gap-3 text-xs text-gray-500">
                <BadgeCheck class="h-3.5 w-3.5 text-gray-400" />
                <span>NIP: {teacher.nip}</span>
              </div>
            {/if}
            <div class="flex items-center gap-3 text-xs text-gray-500">
               <Banknote class="h-3.5 w-3.5 text-gray-400" />
               <span class="font-bold text-gray-700">{formatCurrency(teacher.salary)}</span>
               <span class="text-[10px] bg-gray-100 px-1.5 py-0.5 rounded uppercase font-bold text-gray-400">Gaji Pokok</span>
            </div>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-50 flex items-center justify-between relative z-10">
             <span class="text-[10px] font-black uppercase tracking-widest {teacher.status === 'active' ? 'text-emerald-500' : 'text-gray-400'}">
                ● {teacher.status}
             </span>
             <span class="text-[10px] text-gray-400 font-bold uppercase">Mulai: {teacher.join_date || '-'}</span>
          </div>
        </div>
      {:else}
        <div class="col-span-full py-20 text-center bg-white rounded-[40px] border border-dashed border-gray-200">
           <User class="h-12 w-12 text-gray-200 mx-auto mb-4" />
           <p class="text-gray-400">Belum ada data pengajar terdaftar.</p>
        </div>
      {/each}
    </div>
  </div>

  <!-- Modal -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-lg rounded-[40px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-black text-gray-900">{editingTeacher ? 'Edit Data Pengajar' : 'Tambah Pengajar Baru'}</h2>
            <p class="text-xs text-gray-400 mt-1 uppercase font-bold tracking-widest">Informasi SDM Pesantren</p>
          </div>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-2xl transition">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-5">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5 col-span-2">
              <label for="name" class="text-xs font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
              <input 
                id="name"
                type="text" 
                bind:value={$form.name}
                placeholder="Masukkan nama lengkap"
                required
                class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold"
              />
            </div>

            <div class="space-y-1.5">
              <label for="email" class="text-xs font-black text-gray-400 uppercase tracking-widest">Email (Login)</label>
              <input 
                id="email"
                type="email" 
                bind:value={$form.email}
                placeholder="email@simpp.com"
                required
                disabled={!!editingTeacher}
                class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold disabled:opacity-50"
              />
            </div>

            <div class="space-y-1.5">
              <label for="nip" class="text-xs font-black text-gray-400 uppercase tracking-widest">NIP / ID</label>
              <input 
                id="nip"
                type="text" 
                bind:value={$form.nip}
                placeholder="1980xxxx"
                class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold"
              />
            </div>

            <div class="space-y-1.5">
              <label for="position" class="text-xs font-black text-gray-400 uppercase tracking-widest">Jabatan</label>
              <input 
                id="position"
                type="text" 
                bind:value={$form.position}
                placeholder="Ustadz Tahfidz / Guru B. Arab"
                required
                class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold"
              />
            </div>

            <div class="space-y-1.5">
              <label for="salary" class="text-xs font-black text-gray-400 uppercase tracking-widest">Gaji Pokok</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-black text-xs">Rp</span>
                <input 
                  id="salary"
                  type="number" 
                  bind:value={$form.salary}
                  placeholder="3000000"
                  required
                  class="block w-full pl-12 pr-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold"
                />
              </div>
            </div>

            <div class="space-y-1.5">
              <label for="join_date" class="text-xs font-black text-gray-400 uppercase tracking-widest">Tanggal Bergabung</label>
              <input 
                id="join_date"
                type="date" 
                bind:value={$form.join_date}
                class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold"
              />
            </div>

            <div class="space-y-1.5">
               <label for="status" class="text-xs font-black text-gray-400 uppercase tracking-widest">Status</label>
               <select bind:value={$form.status} class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold appearance-none">
                  <option value="active">Aktif</option>
                  <option value="inactive">Non-Aktif</option>
                  <option value="resigned">Mengundurkan Diri</option>
               </select>
            </div>
          </div>

          <div class="pt-4">
            <button 
              type="submit" 
              disabled={$form.processing}
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-3xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2 transform active:scale-95"
            >
              <Save class="h-5 w-5" />
              {editingTeacher ? 'SIMPAN PERUBAHAN' : 'TAMBAHKAN GURU'}
            </button>
          </div>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
