<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router, Link } from '@inertiajs/svelte';
  import { 
    Search,
    Wallet,
    TrendingUp,
    ChevronRight,
    ArrowUpCircle,
    ArrowDownCircle,
    User,
    CreditCard,
    X,
    Save
  } from 'lucide-svelte';

  export let wallets;

  let searchQuery = '';
  let isTopupModalOpen = false;
  let selectedWallet = null;

  const topupForm = useForm({
    amount: '',
    payment_method: 'cash',
    note: '',
  });

  function openTopupModal(wallet) {
    selectedWallet = wallet;
    isTopupModalOpen = true;
  }

  function submitTopup() {
    $topupForm.post(`/wallets/${selectedWallet.id}/topup`, {
      onSuccess: () => closeTopupModal(),
    });
  }

  function closeTopupModal() {
    isTopupModalOpen = false;
    selectedWallet = null;
    $topupForm.reset();
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  $: filteredWallets = wallets.filter(w => 
    w.student.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    w.student.nis.toLowerCase().includes(searchQuery.toLowerCase())
  );
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Tabungan Santri (E-Wallet)</h1>
        <p class="text-gray-500 text-sm">Monitor saldo dan transaksi non-tunai santri di lingkungan pesantren</p>
      </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
       <div class="bg-blue-600 rounded-[32px] p-6 text-white shadow-xl shadow-blue-200 overflow-hidden relative group">
          <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform">
             <Wallet class="h-32 w-32" />
          </div>
          <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80">Total Saldo Tersimpan</p>
          <p class="text-3xl font-black mt-2">{formatCurrency(wallets.reduce((acc, w) => acc + parseFloat(w.balance), 0))}</p>
       </div>
       <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total Akun Aktif</p>
          <p class="text-3xl font-black text-gray-900 mt-2">{wallets.length}</p>
       </div>
    </div>

    <!-- Search & List -->
    <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-8">
       <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
          <h2 class="text-lg font-bold text-gray-900">Daftar Akun Tabungan</h2>
          <div class="relative w-full md:w-96">
             <Search class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
             <input 
                type="text" 
                bind:value={searchQuery}
                placeholder="Cari nama santri atau NIS..."
                class="w-full pl-12 pr-6 py-3 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all text-sm font-bold"
             />
          </div>
       </div>

       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {#each filteredWallets as wallet}
            <div class="p-6 bg-gray-50/50 rounded-3xl border border-transparent hover:border-blue-100 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
               <div class="flex items-center justify-between mb-4">
                  <div class="flex items-center gap-3">
                     <div class="h-10 w-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 font-bold group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        {wallet.student.name.charAt(0)}
                     </div>
                     <div>
                        <p class="text-sm font-bold text-gray-900 leading-tight">{wallet.student.name}</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{wallet.student.nis}</p>
                     </div>
                  </div>
                  <Link href={`/wallets/${wallet.id}`} class="p-2 text-gray-300 hover:text-blue-600 transition">
                     <ChevronRight class="h-5 w-5" />
                  </Link>
               </div>

               <div class="mt-4 p-4 bg-white rounded-2xl border border-gray-100 group-hover:border-blue-50">
                  <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Saldo Saat Ini</p>
                  <p class="text-xl font-black text-gray-900 mt-1">{formatCurrency(wallet.balance)}</p>
               </div>

               <div class="mt-6 flex items-center gap-2">
                  <button 
                    on:click={() => openTopupModal(wallet)}
                    class="flex-1 bg-white hover:bg-emerald-50 text-emerald-600 border border-emerald-100 font-black py-2.5 rounded-xl text-[10px] uppercase tracking-wider transition-all flex items-center justify-center gap-2"
                  >
                    <ArrowUpCircle class="h-3 w-3" />
                    Top Up
                  </button>
                  <Link 
                    href={`/wallets/${wallet.id}`}
                    class="flex-1 bg-white hover:bg-blue-50 text-blue-600 border border-blue-100 font-black py-2.5 rounded-xl text-[10px] uppercase tracking-wider transition-all flex items-center justify-center gap-2 text-center"
                  >
                    Histori
                  </Link>
               </div>
            </div>
          {:else}
            <div class="col-span-full py-20 text-center">
               <p class="text-gray-400 italic">Data tidak ditemukan.</p>
            </div>
          {/each}
       </div>
    </div>
  </div>

  <!-- Top Up Modal -->
  {#if isTopupModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-md rounded-[40px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-black text-gray-900 uppercase">Top Up Saldo</h2>
            <p class="text-[10px] text-gray-400 font-black tracking-widest mt-1">SANTRI: {selectedWallet?.student.name}</p>
          </div>
          <button on:click={closeTopupModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-2xl transition">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submitTopup} class="p-8 space-y-6">
          <div class="space-y-2">
            <label for="amount" class="text-xs font-black text-gray-400 uppercase tracking-widest">Nominal Top Up</label>
            <div class="relative">
               <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-black text-sm">Rp</span>
               <input 
                  id="amount"
                  type="number" 
                  bind:value={$topupForm.amount}
                  placeholder="50000"
                  required
                  class="block w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 focus:bg-white transition-all text-lg font-black"
               />
            </div>
          </div>

          <div class="space-y-2">
             <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Metode Pembayaran</label>
             <select bind:value={$topupForm.payment_method} class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold appearance-none">
                <option value="cash">Tunai / Cash (Admin)</option>
                <option value="transfer">Transfer Bank</option>
             </select>
          </div>

          <div class="space-y-2">
             <label for="note" class="text-xs font-black text-gray-400 uppercase tracking-widest">Catatan (Opsional)</label>
             <input type="text" bind:value={$topupForm.note} class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
          </div>

          <button 
            type="submit" 
            disabled={$topupForm.processing}
            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-3xl shadow-xl shadow-emerald-200 transition-all flex items-center justify-center gap-2 transform active:scale-95"
          >
            <Save class="h-5 w-5" />
            PROSES TOP UP
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
