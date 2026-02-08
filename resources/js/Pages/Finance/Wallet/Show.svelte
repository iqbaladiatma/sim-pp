<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router, Link } from '@inertiajs/svelte';
  import { 
    ArrowLeft,
    Wallet,
    ArrowUpCircle,
    ArrowDownCircle,
    User,
    CreditCard,
    X,
    Save,
    Clock,
    ShoppingCart,
    MoreVertical
  } from 'lucide-svelte';

  export let wallet;

  let isPurchaseModalOpen = false;

  const purchaseForm = useForm({
    amount: '',
    merchant_name: 'Kantin Pondok',
    description: '',
  });

  function openPurchaseModal() {
    isPurchaseModalOpen = true;
  }

  function submitPurchase() {
    $purchaseForm.post(`/wallets/${wallet.id}/purchase`, {
      onSuccess: () => closePurchaseModal(),
    });
  }

  function closePurchaseModal() {
    isPurchaseModalOpen = false;
    $purchaseForm.reset();
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  function formatDate(date) {
    return new Date(date).toLocaleString('id-ID', { 
      day: 'numeric', 
      month: 'short', 
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center gap-4">
      <Link href="/wallets" class="p-2 text-gray-400 hover:text-gray-900 bg-white border border-gray-100 rounded-xl transition">
        <ArrowLeft class="h-5 w-5" />
      </Link>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Detail Tabungan Santri</h1>
        <p class="text-gray-500 text-sm">Riwayat transaksi dan manajemen pengeluaran santri</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Info Card -->
      <div class="space-y-6">
         <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-8 text-center">
            <div class="h-20 w-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-3xl mx-auto mb-6">
               {wallet.student.name.charAt(0)}
            </div>
            <h2 class="text-xl font-black text-gray-900 leading-tight">{wallet.student.name}</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">{wallet.student.nis}</p>
            
            <div class="mt-10 p-6 bg-gray-50 rounded-[32px] border border-gray-100">
               <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Saldo Saat Ini</p>
               <p class="text-3xl font-black text-gray-900 mt-2">{formatCurrency(wallet.balance)}</p>
            </div>

            <button 
               on:click={openPurchaseModal}
               class="mt-6 w-full bg-gray-900 hover:bg-black text-white font-black py-4 rounded-3xl shadow-xl shadow-gray-200 transition-all flex items-center justify-center gap-2 transform active:scale-95"
            >
               <ShoppingCart class="h-5 w-5" />
               CATAT PEMBELANJAAN
            </button>
         </div>

         <div class="bg-amber-50 rounded-[32px] p-6 border border-amber-100">
            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Catatan Sistem</p>
            <p class="text-xs text-amber-700 mt-2 leading-relaxed">
               Gunakan fitur <b>Catat Pembelanjaan</b> untuk mengurangi saldo santri saat bertransaksi di kantin atau koperasi pondok.
            </p>
         </div>
      </div>

      <!-- Transaction History -->
      <div class="lg:col-span-2 bg-white rounded-[40px] border border-gray-100 shadow-sm p-8">
         <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-black text-gray-900">Riwayat Transaksi Terakhir</h3>
            <Clock class="h-5 w-5 text-gray-300" />
         </div>

         <div class="space-y-4">
            {#each wallet.transactions as trx}
               <div class="flex items-center justify-between p-5 bg-white border border-gray-100 rounded-3xl hover:bg-gray-50/50 transition-all">
                  <div class="flex items-center gap-4">
                     <div class="h-11 w-11 rounded-2xl flex items-center justify-center {trx.type === 'topup' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'}">
                        {#if trx.type === 'topup'}
                           <ArrowUpCircle class="h-6 w-6" />
                        {:else}
                           <ArrowDownCircle class="h-6 w-6" />
                        {/if}
                     </div>
                     <div>
                        <p class="text-sm font-bold text-gray-900">{trx.type === 'topup' ? 'Top Up Saldo' : (trx.merchant_name || 'Pembelanjaan')}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{formatDate(trx.created_at)}</p>
                     </div>
                  </div>
                  <div class="text-right">
                     <p class="text-sm font-black {trx.type === 'topup' ? 'text-emerald-600' : 'text-red-600'}">
                        {trx.type === 'topup' ? '+' : '-'}{formatCurrency(trx.amount)}
                     </p>
                     <p class="text-[10px] text-gray-400 font-bold">Saldo: {formatCurrency(trx.balance_after)}</p>
                  </div>
               </div>
            {:else}
               <div class="py-20 text-center text-gray-400 italic">Belum ada riwayat transaksi.</div>
            {/each}
         </div>
      </div>
    </div>
  </div>

  <!-- Purchase Modal -->
  {#if isPurchaseModalOpen}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm animate-in fade-in duration-200">
      <div class="bg-white w-full max-w-md rounded-[40px] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-black text-gray-900 uppercase">Input Pembelanjaan</h2>
            <p class="text-[10px] text-gray-400 font-black tracking-widest mt-1">PENGURANGAN SALDO OTOMATIS</p>
          </div>
          <button on:click={closePurchaseModal} class="p-2 text-gray-400 hover:bg-gray-50 rounded-2xl transition">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submitPurchase} class="p-8 space-y-6">
          <div class="space-y-2">
            <label for="p_amount" class="text-xs font-black text-gray-400 uppercase tracking-widest">Jumlah Belanja</label>
            <div class="relative">
               <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-black text-sm">Rp</span>
               <input 
                  id="p_amount"
                  type="number" 
                  bind:value={$purchaseForm.amount}
                  placeholder="25000"
                  required
                  class="block w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 focus:bg-white transition-all text-lg font-black"
               />
            </div>
          </div>

          <div class="space-y-2">
             <label for="merchant" class="text-xs font-black text-gray-400 uppercase tracking-widest">Lokasi Belanja</label>
             <input type="text" id="merchant" bind:value={$purchaseForm.merchant_name} placeholder="Kantin / Koperasi" class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" required />
          </div>

          <div class="space-y-2">
             <label for="p_note" class="text-xs font-black text-gray-400 uppercase tracking-widest">Keterangan</label>
             <input type="text" id="p_note" bind:value={$purchaseForm.description} placeholder="Beli jajan, alat tulis, dll" class="block w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
          </div>

          <button 
            type="submit" 
            disabled={$purchaseForm.processing}
            class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-4 rounded-3xl shadow-xl shadow-red-200 transition-all flex items-center justify-center gap-2 transform active:scale-95"
          >
            <Save class="h-5 w-5" />
            CATAT TRANSAKSI
          </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
