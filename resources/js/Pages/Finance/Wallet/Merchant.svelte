<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Search, 
    ShoppingCart, 
    User, 
    Wallet, 
    AlertCircle, 
    CheckCircle2, 
    X, 
    Printer,
    CreditCard,
    ArrowRightLeft,
    ChevronRight,
    History
  } from 'lucide-svelte';

  export let students;
  export let recentTransactions;
  export let filters;

  let searchTerm = filters.search || '';
  let selectedStudent = null;
  let isProcessing = false;

  const form = useForm({
    student_id: '',
    amount: '',
    description: '',
  });

  function handleSearch() {
    router.get('/merchant', { search: searchTerm }, { 
      preserveState: true,
      replace: true,
      onSuccess: () => {
         // Focus first student if found
      }
    });
  }

  function selectStudent(student) {
    selectedStudent = student;
    $form.student_id = student.id;
    searchTerm = '';
    // Clear student list by resetting router search if needed, but for now just local
  }

  function resetForm() {
    selectedStudent = null;
    $form.reset();
  }

  function submit() {
    isProcessing = true;
    $form.post('/merchant/purchase', {
      onSuccess: () => {
        isProcessing = false;
        resetForm();
      },
      onError: () => {
        isProcessing = false;
      }
    });
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  function formatDateTime(date) {
    return new Date(date).toLocaleString('id-ID', { hour: '2-digit', minute: '2-digit' });
  }
</script>

<AppLayout>
  <div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Kantin & POS</h1>
        <p class="text-gray-500 font-medium">Pembayaran non-tunai menggunakan kartu santri</p>
      </div>
      <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100">
         <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
         <span class="text-[10px] font-black uppercase tracking-widest">Sistem Online</span>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- POS Terminal -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-8 space-y-8 min-h-[500px] flex flex-col justify-center">
          
          {#if !selectedStudent}
            <div class="max-w-md mx-auto w-full space-y-8 text-center animate-in fade-in slide-in-from-bottom-4 duration-500">
              <div class="h-24 w-24 bg-blue-50 text-blue-600 rounded-[32px] flex items-center justify-center mx-auto shadow-xl shadow-blue-50">
                <CreditCard class="h-10 w-10" />
              </div>
              <div class="space-y-2">
                <h2 class="text-2xl font-black text-gray-900 leading-tight">Mulai Transaksi</h2>
                <p class="text-gray-400 text-sm font-medium">Cari nama santri atau scan kartu untuk memulai</p>
              </div>

              <div class="relative group">
                <div class="absolute inset-y-0 left-6 flex items-center">
                  <Search class="h-5 w-5 text-gray-300 group-focus-within:text-blue-500 transition-colors" />
                </div>
                <input 
                  type="text" 
                  bind:value={searchTerm}
                  on:input={handleSearch}
                  placeholder="Nama / NIS / No. Kartu..."
                  class="w-full pl-16 pr-6 py-5 bg-gray-50 border-none rounded-3xl outline-none ring-2 ring-transparent focus:ring-blue-500/10 focus:bg-white transition-all text-sm font-bold shadow-sm"
                />
              </div>

              {#if students && students.length > 0}
                <div class="space-y-3 pt-4">
                  {#each students as s}
                    <button 
                      on:click={() => selectStudent(s)}
                      class="w-full flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:border-blue-500 hover:shadow-lg hover:shadow-blue-50 transition-all group"
                    >
                      <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                          <User class="h-5 w-5" />
                        </div>
                        <div class="text-left">
                          <p class="text-sm font-black text-gray-900">{s.name}</p>
                          <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{s.nis}</p>
                        </div>
                      </div>
                      <ChevronRight class="h-4 w-4 text-gray-300 group-hover:text-blue-600" />
                    </button>
                  {/each}
                </div>
              {/if}
            </div>
          {:else}
            <!-- Transaction Form -->
            <div class="max-w-md mx-auto w-full space-y-8 animate-in zoom-in duration-300">
               <div class="flex items-start justify-between">
                  <div class="flex items-center gap-4">
                     <div class="h-16 w-16 rounded-[24px] bg-blue-600 text-white flex items-center justify-center shadow-xl shadow-blue-100">
                        <User class="h-8 w-8" />
                     </div>
                     <div>
                        <p class="text-xl font-black text-gray-900 leading-tight">{selectedStudent.name}</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{selectedStudent.nis}</p>
                     </div>
                  </div>
                  <button on:click={resetForm} class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition">
                     <X class="h-5 w-5" />
                  </button>
               </div>

               <div class="bg-gray-50 rounded-[32px] p-6 border border-gray-100 flex items-center justify-between">
                  <div>
                     <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Saldo Tersedia</p>
                     <p class="text-2xl font-black text-blue-600 leading-none">{formatCurrency(selectedStudent.wallet.balance)}</p>
                  </div>
                  <Wallet class="h-10 w-10 text-blue-100" />
               </div>

               <form on:submit|preventDefault={submit} class="space-y-6">
                  <div class="space-y-3">
                     <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nominal Pembelian (Rp)</label>
                     <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center px-6 pointer-events-none">
                           <span class="text-lg font-black text-gray-300">Rp</span>
                        </div>
                        <input 
                           type="number" 
                           bind:value={$form.amount}
                           required
                           placeholder="0"
                           class="w-full pl-16 pr-6 py-6 bg-white border-2 border-gray-100 rounded-[32px] outline-none focus:border-blue-600 transition-all text-3xl font-black placeholder:text-gray-100"
                        />
                     </div>
                     {#if $form.amount > selectedStudent.wallet.balance}
                        <p class="text-[10px] font-bold text-red-500 flex items-center gap-1 ml-1 uppercase">
                           <AlertCircle class="h-3 w-3" /> Saldo tidak mencukupi
                        </p>
                     {/if}
                  </div>

                  <div class="space-y-2">
                     <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Keterangan (Opsional)</label>
                     <input 
                        type="text" 
                        bind:value={$form.description}
                        placeholder="e.g., Makan Siang, Alat Tulis"
                        class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl outline-none text-sm font-bold"
                     />
                  </div>

                  <button 
                     type="submit" 
                     disabled={isProcessing || !$form.amount || $form.amount > selectedStudent.wallet.balance}
                     class="w-full bg-blue-600 hover:bg-black text-white font-black py-6 rounded-[32px] shadow-2xl shadow-blue-200 transition-all flex items-center justify-center gap-3 transform active:scale-95 disabled:opacity-50 disabled:scale-100"
                  >
                     {#if isProcessing}
                        <div class="h-5 w-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                     {:else}
                        <ShoppingCart class="h-5 w-5" />
                     {/if}
                     BAYAR SEKARANG
                  </button>
               </form>
            </div>
          {/if}
        </div>
      </div>

      <!-- Side Stats & History -->
      <div class="space-y-6">
        <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-8">
           <div class="flex items-center gap-3 mb-6">
              <div class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                 <History class="h-5 w-5" />
              </div>
              <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight">Riwayat Hari Ini</h3>
           </div>

           <div class="space-y-4">
              {#each recentTransactions as trx}
                 <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                       <div class="h-10 w-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center text-[10px] font-black uppercase">
                          {trx.wallet.student.name.substring(0, 2)}
                       </div>
                       <div>
                          <p class="text-xs font-black text-gray-900 leading-tight">{trx.wallet.student.name}</p>
                          <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{formatDateTime(trx.created_at)}</p>
                       </div>
                    </div>
                    <p class="text-xs font-black text-red-600">-{formatCurrency(trx.amount)}</p>
                 </div>
              {:else}
                 <div class="py-12 text-center">
                    <p class="text-xs text-gray-300 font-bold uppercase italic tracking-widest">Belum ada transaksi</p>
                 </div>
              {/each}
           </div>

           <div class="mt-8 pt-6 border-t border-gray-50">
              <div class="flex items-center justify-between text-gray-400">
                 <p class="text-[10px] font-black uppercase tracking-widest">Total Penjualan</p>
                 <p class="text-sm font-black text-gray-900">
                    {formatCurrency(recentTransactions.reduce((acc, t) => acc + parseFloat(t.amount), 0))}
                 </p>
              </div>
           </div>
        </div>

        <div class="bg-black rounded-[40px] p-8 text-white space-y-4">
           <ShoppingCart class="h-8 w-8 text-blue-400" />
           <p class="text-lg font-black leading-tight italic">"Digitalisasi Ekosistem Ekonomi Pesantren"</p>
           <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest leading-relaxed">System v1.2 Enabled with secure e-money authentication</p>
        </div>
      </div>
    </div>
  </div>
</AppLayout>
