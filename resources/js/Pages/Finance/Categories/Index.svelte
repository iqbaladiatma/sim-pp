<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Edit2, 
    Trash2,
    Wallet,
    X,
    Save,
    Tag
  } from 'lucide-svelte';

  export let categories;

  let isModalOpen = false;
  let editingCategory = null;

  const form = useForm({
    name: '',
    default_amount: '',
    type: 'monthly',
    description: '',
  });

  function openModal(category = null) {
    editingCategory = category;
    if (category) {
      $form.name = category.name;
      $form.default_amount = category.default_amount;
      $form.type = category.type;
      $form.description = category.description || '';
    } else {
      $form.reset();
    }
    isModalOpen = true;
  }

  function submit() {
    if (editingCategory) {
      $form.put(`/bill-categories/${editingCategory.id}`, {
        onSuccess: () => closeModal(),
      });
    } else {
      $form.post('/bill-categories', {
        onSuccess: () => closeModal(),
      });
    }
  }

  function closeModal() {
    isModalOpen = false;
    editingCategory = null;
    $form.reset();
  }

  function deleteCategory(id) {
    if (confirm('Hapus kategori tagihan ini?')) {
      router.delete(`/bill-categories/${id}`);
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
        <h1 class="text-2xl font-bold text-gray-900">Kategori Tagihan</h1>
        <p class="text-gray-500 text-sm">Definisikan jenis-jenis biaya dan tagihan santri</p>
      </div>
      <button 
        on:click={() => openModal()}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Tambah Kategori
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {#each categories as category}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 group transition-all hover:shadow-xl hover:shadow-gray-200/50">
          <div class="flex items-start justify-between mb-4">
            <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
              <Tag class="h-6 w-6" />
            </div>
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <button on:click={() => openModal(category)} class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition">
                <Edit2 class="h-4 w-4" />
              </button>
              <button on:click={() => deleteCategory(category.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                <Trash2 class="h-4 w-4" />
              </button>
            </div>
          </div>
          
          <h3 class="text-lg font-bold text-gray-900">{category.name}</h3>
          <p class="text-sm font-black text-blue-600 mt-1 mb-3">{formatCurrency(category.default_amount)}</p>
          
          <div class="flex items-center gap-2 mb-4">
            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {category.type === 'monthly' ? 'bg-purple-50 text-purple-600' : 'bg-green-50 text-green-600'}">
              {category.type === 'monthly' ? 'Bulanan' : 'Sekali Bayar'}
            </span>
          </div>

          <p class="text-xs text-gray-500 line-clamp-2">{category.description || 'Tidak ada deskripsi.'}</p>
        </div>
      {:else}
        <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
           <p class="text-gray-400">Belum ada data kategori tagihan.</p>
        </div>
      {/each}
    </div>
  </div>

  <!-- Modal -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-900">{editingCategory ? 'Edit Kategori' : 'Tambah Kategori Baru'}</h2>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <X class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-6">
          <div class="space-y-1.5">
            <label for="name" class="text-sm font-semibold text-gray-700">Nama Kategori</label>
            <input 
              id="name"
              type="text" 
              bind:value={$form.name}
              placeholder="Contoh: SPP Bulanan"
              required
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all"
            />
          </div>

          <div class="space-y-1.5">
            <label for="amount" class="text-sm font-semibold text-gray-700">Nominal Default</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
              <input 
                id="amount"
                type="number" 
                bind:value={$form.default_amount}
                placeholder="500000"
                required
                class="block w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all"
              />
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700">Siklus Tagihan</label>
            <div class="flex bg-gray-50 p-1 rounded-2xl border border-gray-200">
              <button 
                type="button" 
                on:click={() => $form.type = 'monthly'}
                class="flex-1 py-2 text-xs font-bold rounded-xl transition-all {$form.type === 'monthly' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400'}"
              >Bulanan</button>
              <button 
                type="button" 
                on:click={() => $form.type = 'one_time'}
                class="flex-1 py-2 text-xs font-bold rounded-xl transition-all {$form.type === 'one_time' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400'}"
              >Sekali Bayar</button>
            </div>
          </div>

          <div class="space-y-1.5">
            <label for="description" class="text-sm font-semibold text-gray-700">Deskripsi (Opsional)</label>
            <textarea 
              id="description"
              bind:value={$form.description}
              rows="2"
              class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all resize-none"
            ></textarea>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            {editingCategory ? 'Update Kategori' : 'Simpan Kategori'}
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
