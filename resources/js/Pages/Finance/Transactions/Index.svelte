<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { 
    Clock, 
    User, 
    CreditCard, 
    CheckCircle2, 
    ArrowUpRight,
    Search
  } from 'lucide-svelte';

  export let transactions;

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  function formatDate(date) {
    return new Date(date).toLocaleString('id-ID', { 
      day: 'numeric', 
      month: 'long', 
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h1>
        <p class="text-gray-500 text-sm">Log seluruh pembayaran masuk dari santri/wali</p>
      </div>
    </div>

    <!-- Stats Summary (Optional Mock) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
       <!-- We can add summary cards here in future -->
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-widest">
              <th class="px-6 py-4">Waktu & Ref</th>
              <th class="px-6 py-4">Santri</th>
              <th class="px-6 py-4">Kategori Tagihan</th>
              <th class="px-6 py-4">Metode</th>
              <th class="px-6 py-4 text-right">Nominal</th>
              <th class="px-6 py-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            {#each transactions.data as trx}
              <tr class="hover:bg-gray-50/30 transition-colors">
                <td class="px-6 py-4">
                  <p class="text-xs font-bold text-gray-900">{formatDate(trx.payment_date)}</p>
                  <p class="text-[10px] text-gray-400 font-mono mt-0.5 uppercase tracking-tighter">TRX#{trx.id.toString().padStart(6, '0')}</p>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <User class="h-3.5 w-3.5 text-gray-400" />
                    <p class="text-sm font-bold text-gray-700">{trx.bill?.student?.name || 'Unknown'}</p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-500">{trx.bill?.category?.name || '-'}</p>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2">
                    <CreditCard class="h-3.5 w-3.5 text-gray-400" />
                    <span class="text-xs font-bold capitalize text-gray-600 font-mono">{trx.payment_method.replace('_', ' ')}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <p class="text-sm font-black text-gray-900">{formatCurrency(trx.amount)}</p>
                </td>
                <td class="px-6 py-4 text-center">
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-600">
                    <CheckCircle2 class="h-3 w-3" />
                    {trx.status}
                  </span>
                </td>
              </tr>
            {:else}
              <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Belum ada transaksi tercatat.</td>
              </tr>
            {/each}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</AppLayout>
