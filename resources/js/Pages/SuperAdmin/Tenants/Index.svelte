<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router, Link } from '@inertiajs/svelte';
  import { 
    Plus, 
    Search, 
    Edit2, 
    Trash2, 
    ExternalLink, 
    ShieldCheck, 
    AlertTriangle, 
    Building2,
    Users,
    X,
    Save
  } from 'lucide-svelte';

  export let tenants;
  export let filters;

  let isModalOpen = false;
  let editingTenant = null;

  const form = useForm({
    name: '',
    slug: '',
    email: '',
    subscription_plan: 'basic',
    subscription_status: 'trial',
    max_students: 100,
  });

  function openModal(tenant = null) {
    editingTenant = tenant;
    if (tenant) {
      $form.name = tenant.name;
      $form.slug = tenant.slug;
      $form.email = tenant.email || '';
      $form.subscription_plan = tenant.subscription_plan;
      $form.subscription_status = tenant.subscription_status;
      $form.max_students = tenant.max_students;
    } else {
      $form.reset();
    }
    isModalOpen = true;
  }

  function submit() {
    if (editingTenant) {
      $form.put(`/super-admin/tenants/${editingTenant.id}`, {
        onSuccess: () => closeModal(),
      });
    } else {
      $form.post('/super-admin/tenants', {
        onSuccess: () => closeModal(),
      });
    }
  }

  function closeModal() {
    isModalOpen = false;
    editingTenant = null;
    $form.reset();
  }

  function deleteTenant(id) {
    if (confirm('Apakah Anda yakin ingin menghapus tenant ini? Semua data terkait akan ikut terhapus!')) {
      router.delete(`/super-admin/tenants/${id}`);
    }
  }

  let searchTerm = filters.search || '';
  function handleSearch() {
    router.get('/super-admin/tenants', { search: searchTerm }, { preserveState: true });
  }

  function getPlanColor(plan) {
    const colors = {
      basic: 'bg-blue-50 text-blue-600',
      pro: 'bg-purple-50 text-purple-600',
      enterprise: 'bg-amber-50 text-amber-600'
    };
    return colors[plan] || 'bg-gray-50 text-gray-600';
  }

  function getStatusBadge(status) {
    const badges = {
      active: 'bg-emerald-50 text-emerald-600',
      trial: 'bg-blue-50 text-blue-600',
      inactive: 'bg-gray-100 text-gray-500',
      expired: 'bg-red-50 text-red-600'
    };
    return badges[status] || 'bg-gray-50 text-gray-600';
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Tenant</h1>
        <p class="text-gray-500 text-sm">Kelola instance pondok pesantren dan paket langganan mereka</p>
      </div>
      <button 
        on:click={() => openModal()}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Pondok Baru
      </button>
    </div>

    <!-- Stats summary for Super Admin -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
       <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Pondok</p>
          <div class="flex items-end justify-between mt-2">
             <p class="text-3xl font-black text-gray-900">{tenants.total}</p>
             <Building2 class="h-8 w-8 text-blue-100 mb-1" />
          </div>
       </div>
       <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest text-opacity-80">Aktif & Berbayar</p>
          <div class="flex items-end justify-between mt-2">
             <p class="text-3xl font-black text-gray-900">{tenants.data.filter(t => t.subscription_status === 'active').length}</p>
             <ShieldCheck class="h-8 w-8 text-emerald-100 mb-1" />
          </div>
       </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm p-4 flex flex-col md:flex-row gap-4 items-center">
       <div class="relative flex-1">
          <Search class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
          <input 
            type="text" 
            placeholder="Cari nama pondok atau slug..." 
            bind:value={searchTerm}
            on:input={handleSearch}
            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-transparent rounded-2xl outline-none focus:bg-white focus:border-blue-500 transition-all text-sm font-bold"
          />
       </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
              <th class="px-8 py-5">Informasi Pondok</th>
              <th class="px-8 py-5">Subdomain</th>
              <th class="px-8 py-5 text-center">Plan</th>
              <th class="px-8 py-5 text-center">Status</th>
              <th class="px-8 py-5 text-right">Limit Santri</th>
              <th class="px-8 py-5 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            {#each tenants.data as tenant}
              <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-8 py-5">
                   <div class="flex items-center gap-4">
                      <div class="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 font-bold">
                         {tenant.name.charAt(0)}
                      </div>
                      <div>
                         <p class="font-bold text-gray-900 leading-tight">{tenant.name}</p>
                         <p class="text-xs text-gray-400 font-bold mt-0.5">{tenant.email || 'Email tidak tersedia'}</p>
                      </div>
                   </div>
                </td>
                <td class="px-8 py-5">
                   <div class="flex items-center gap-2">
                      <span class="text-sm font-bold text-gray-600 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">{tenant.slug}</span>
                      <a href={`http://${tenant.slug}.simpp.test`} target="_blank" class="p-1 px-1.5 text-blue-600 bg-blue-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider">
                         Visit <ExternalLink class="h-3 w-3" />
                      </a>
                   </div>
                </td>
                <td class="px-8 py-5 text-center">
                   <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {getPlanColor(tenant.subscription_plan)}">
                      {tenant.subscription_plan}
                   </span>
                </td>
                <td class="px-8 py-5 text-center">
                   <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {getStatusBadge(tenant.subscription_status)}">
                      ● {tenant.subscription_status}
                   </span>
                </td>
                <td class="px-8 py-5 text-right">
                   <div class="flex items-center justify-end gap-2 text-sm font-bold text-gray-600">
                      <Users class="h-3.5 w-3.5 text-gray-400" />
                      <span>{tenant.max_students}</span>
                   </div>
                </td>
                <td class="px-8 py-5">
                  <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button 
                      on:click={() => openModal(tenant)}
                      class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition"
                    >
                      <Edit2 class="h-4 w-4" />
                    </button>
                    <button 
                      on:click={() => deleteTenant(tenant.id)}
                      class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition"
                    >
                      <Trash2 class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            {:else}
              <tr>
                <td colspan="6" class="px-8 py-20 text-center text-gray-400 italic">Data tenant tidak ditemukan.</td>
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
      <div class="bg-white w-full max-w-lg rounded-[40px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-black text-gray-900 uppercase">{editingTenant ? 'Edit Pondok' : 'Registrasi Pondok Baru'}</h2>
            <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-[0.2em]">Setup Instansi Saas</p>
          </div>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-2xl transition">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-5">
           <div class="space-y-1.5">
              <label for="name" class="text-xs font-black text-gray-400 uppercase tracking-widest">Nama Pondok Pesantren</label>
              <input id="name" type="text" bind:value={$form.name} required placeholder="e.g., Pondok Al-Ikhlas" class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold" />
           </div>

           <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1.5">
                 <label for="slug" class="text-xs font-black text-gray-400 uppercase tracking-widest">Slug Subdomain</label>
                 <div class="relative">
                    <input id="slug" type="text" bind:value={$form.slug} required placeholder="alikhlas" class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold" />
                 </div>
              </div>
              <div class="space-y-1.5">
                 <label for="email" class="text-xs font-black text-gray-400 uppercase tracking-widest">Email Admin Pondok</label>
                 <input id="email" type="email" bind:value={$form.email} placeholder="admin@domain.com" class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white transition-all text-sm font-bold" />
              </div>
           </div>

           <div class="grid grid-cols-2 gap-4">
              <div class="space-y-1.5">
                 <label for="plan" class="text-xs font-black text-gray-400 uppercase tracking-widest">Paket Layanan</label>
                 <select id="plan" bind:value={$form.subscription_plan} class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold appearance-none">
                    <option value="basic">Basic</option>
                    <option value="pro">Pro</option>
                    <option value="enterprise">Enterprise</option>
                 </select>
              </div>
              <div class="space-y-1.5">
                 <label for="max_students" class="text-xs font-black text-gray-400 uppercase tracking-widest">Limit Santri</label>
                 <input id="max_students" type="number" bind:value={$form.max_students} class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
              </div>
           </div>

           {#if editingTenant}
           <div class="space-y-1.5">
              <label for="status" class="text-xs font-black text-gray-400 uppercase tracking-widest">Status Berlangganan</label>
              <select id="status" bind:value={$form.subscription_status} class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold appearance-none">
                 <option value="active">Aktif</option>
                 <option value="trial">Trial</option>
                 <option value="inactive">Non-Aktif</option>
                 <option value="expired">Expired</option>
              </select>
           </div>
           {/if}

           <div class="pt-4">
            <button 
              type="submit" 
              disabled={$form.processing}
              class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-3xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2 transform active:scale-95"
            >
              <Save class="h-5 w-5" />
              {editingTenant ? 'UPDATE INSTANSI' : 'DAFTARKAN PONDOK'}
            </button>
          </div>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
