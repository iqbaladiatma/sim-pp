<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Plus, 
    Trash2,
    Wallet,
    X,
    Save,
    Calendar,
    Users,
    Filter,
    Search,
    CreditCard
  } from 'lucide-svelte';

  export let bills;
  export let students;
  export let categories;
  export let classrooms;
  export let filters;

  let isModalOpen = false;
  let isPaymentModalOpen = false;
  let selectedBill = null;

  const form = useForm({
    category_id: '',
    type: 'individual',
    student_id: '',
    classroom_id: '',
    amount: '',
    due_date: '',
    period_month: new Date().toISOString().substring(0, 7),
  });

  const paymentForm = useForm({
    student_bill_id: '',
    amount: '',
    payment_method: 'cash',
    payment_date: new Date().toISOString().split('T')[0],
    note: '',
  });

  function openModal() {
    $form.reset();
    isModalOpen = true;
  }

  function openPaymentModal(bill) {
    selectedBill = bill;
    $paymentForm.student_bill_id = bill.id;
    $paymentForm.amount = bill.amount - bill.paid_amount;
    isPaymentModalOpen = true;
  }

  function submit() {
    $form.post('/student-bills', {
      onSuccess: () => closeModal(),
    });
  }

  function submitPayment() {
    $paymentForm.post('/transactions', {
      onSuccess: () => closePaymentModal(),
    });
  }

  function closeModal() {
    isModalOpen = false;
    $form.reset();
  }

  function closePaymentModal() {
    isPaymentModalOpen = false;
    $paymentForm.reset();
    selectedBill = null;
  }

  function deleteBill(id) {
    if (confirm('Hapus tagihan ini?')) {
      router.delete(`/student-bills/${id}`);
    }
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  function handleFilter(status) {
    router.get('/student-bills', { status }, { preserveState: true, replace: true });
  }

  $: if ($form.category_id) {
    const cat = categories.find(c => c.id == $form.category_id);
    if (cat) $form.amount = cat.default_amount;
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Tagihan Santri</h1>
        <p class="text-gray-500 text-sm">Kelola tagihan biaya pendidikan dan asrama santri</p>
      </div>
      <button 
        on:click={openModal}
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95"
      >
        <Plus class="h-5 w-5" />
        Buat Tagihan Baru
      </button>
    </div>

    <!-- Stats/Filters -->
    <div class="flex flex-wrap gap-3">
      <button on:click={() => handleFilter('')} class="px-5 py-2.5 rounded-2xl text-xs font-bold transition-all {filters.status === '' || !filters.status ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50'}">Semua</button>
      <button on:click={() => handleFilter('unpaid')} class="px-5 py-2.5 rounded-2xl text-xs font-bold transition-all {filters.status === 'unpaid' ? 'bg-red-600 text-white shadow-lg shadow-red-100' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50'}">Belum Bayar</button>
      <button on:click={() => handleFilter('partially_paid')} class="px-5 py-2.5 rounded-2xl text-xs font-bold transition-all {filters.status === 'partially_paid' ? 'bg-amber-500 text-white shadow-lg shadow-amber-100' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50'}">Kurang Bayar</button>
      <button on:click={() => handleFilter('paid')} class="px-5 py-2.5 rounded-2xl text-xs font-bold transition-all {filters.status === 'paid' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50'}">Lunas</button>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-widest">
              <th class="px-6 py-4">Santri</th>
              <th class="px-6 py-4">Jenis Tagihan</th>
              <th class="px-6 py-4">Periode</th>
              <th class="px-6 py-4">Total</th>
              <th class="px-6 py-4">Sisa</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            {#each bills.data as bill}
              <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-4">
                  <p class="text-sm font-bold text-gray-900">{bill.student.name}</p>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-600">{bill.category.name}</p>
                </td>
                <td class="px-6 py-4">
                  <p class="text-xs font-bold text-gray-400 uppercase">{bill.period_month || '-'}</p>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm font-bold text-gray-900">{formatCurrency(bill.amount)}</p>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm font-bold text-red-600">{formatCurrency(bill.amount - bill.paid_amount)}</p>
                </td>
                <td class="px-6 py-4">
                  <span class="inline-flex px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase border {bill.status === 'paid' ? 'bg-green-50 text-green-600 border-green-100' : bill.status === 'partially_paid' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-red-50 text-red-600 border-red-100'}">
                    {bill.status.replace('_', ' ')}
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    {#if bill.status !== 'paid'}
                      <button on:click={() => openPaymentModal(bill)} class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition" title="Bayar">
                        <CreditCard class="h-4 w-4" />
                      </button>
                    {/if}
                    <button on:click={() => deleteBill(bill.id)} class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition" title="Hapus">
                      <Trash2 class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            {:else}
              <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada data tagihan.</td>
              </tr>
            {/each}
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal: Buat Tagihan -->
  {#if isModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <Wallet class="h-6 w-6 text-blue-600" />
            Terbitkan Tagihan Baru
          </h2>
          <button on:click={closeModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <X class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-6">
          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5 col-span-2">
              <label for="category" class="text-sm font-semibold text-gray-700">Kategori Tagihan</label>
              <select bind:value={$form.category_id} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none appearance-none" required>
                <option value="">Pilih Kategori...</option>
                {#each categories as cat}
                  <option value={cat.id}>{cat.name}</option>
                {/each}
              </select>
            </div>

            <div class="space-y-1.5 col-span-2">
              <label class="text-sm font-semibold text-gray-700">Metode Distribusi</label>
              <div class="flex bg-gray-50 p-1 rounded-2xl border border-gray-200">
                <button type="button" on:click={() => $form.type = 'individual'} class="flex-1 py-2 text-xs font-bold rounded-xl transition-all {$form.type === 'individual' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400'}">Per Santri</button>
                <button type="button" on:click={() => $form.type = 'classroom'} class="flex-1 py-2 text-xs font-bold rounded-xl transition-all {$form.type === 'classroom' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400'}">Kolektif Kelas</button>
              </div>
            </div>

            {#if $form.type === 'individual'}
              <div class="space-y-1.5 col-span-2">
                <label for="student" class="text-sm font-semibold text-gray-700">Pilih Santri</label>
                <select bind:value={$form.student_id} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none appearance-none" required>
                  <option value="">Cari Santri...</option>
                  {#each students as student}
                    <option value={student.id}>{student.name}</option>
                  {/each}
                </select>
              </div>
            {:else}
              <div class="space-y-1.5 col-span-2">
                <label for="classroom" class="text-sm font-semibold text-gray-700">Pilih Kelas</label>
                <select bind:value={$form.classroom_id} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none appearance-none" required>
                  <option value="">Pilih Kelas...</option>
                  {#each classrooms as cr}
                    <option value={cr.id}>{cr.name}</option>
                  {/each}
                </select>
              </div>
            {/if}

            <div class="space-y-1.5">
              <label for="amount" class="text-sm font-semibold text-gray-700">Nominal</label>
              <input type="number" bind:value={$form.amount} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none" required />
            </div>

            <div class="space-y-1.5">
              <label for="month" class="text-sm font-semibold text-gray-700">Periode (Bulan)</label>
              <input type="month" bind:value={$form.period_month} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none" />
            </div>
          </div>

          <button 
            type="submit" 
            disabled={$form.processing}
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            Terbitkan Tagihan
          </button>
        </form>
      </div>
    </div>
  {/if}

  <!-- Modal: Bayar Tagihan -->
  {#if isPaymentModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-900">Input Pembayaran</h2>
          <button on:click={closePaymentModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-xl transition">
            <X class="h-5 w-5" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submitPayment} class="p-8 space-y-6">
          <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 flex items-center justify-between">
            <div>
              <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest">Sisa Tagihan</p>
              <p class="text-lg font-black text-blue-900">{formatCurrency(selectedBill?.amount - selectedBill?.paid_amount)}</p>
            </div>
            <Wallet class="h-8 w-8 text-blue-200" />
          </div>

          <div class="space-y-1.5">
            <label for="pay_amount" class="text-sm font-semibold text-gray-700">Jumlah Dibayar</label>
            <input type="number" bind:value={$paymentForm.amount} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none" required />
          </div>

          <div class="space-y-1.5">
            <label class="text-sm font-semibold text-gray-700">Metode Pembayaran</label>
            <select bind:value={$paymentForm.payment_method} class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm outline-none appearance-none">
              <option value="cash">Tunai / Cash</option>
              <option value="transfer">Transfer Bank</option>
            </select>
          </div>

          <button 
            type="submit" 
            disabled={$paymentForm.processing}
            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-green-200 transition-all flex items-center justify-center gap-2"
          >
            <Save class="h-5 w-5" />
            Catat Pembayaran
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
