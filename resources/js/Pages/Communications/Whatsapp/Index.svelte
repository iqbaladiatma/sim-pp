<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { router } from '@inertiajs/svelte';
  import { 
    MessageSquare, 
    Send, 
    RotateCcw, 
    Clock, 
    ShieldCheck, 
    ShieldAlert,
    User,
    Phone
  } from 'lucide-svelte';

  export let logs;

  function resend(id) {
    router.post(`/whatsapp-logs/${id}/resend`);
  }

  function formatDateTime(date) {
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
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Riwayat WhatsApp</h1>
        <p class="text-gray-500 text-sm">Log pengiriman notifikasi otomatis ke wali santri dan staf</p>
      </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
       <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Pesan Terkirim</p>
          <div class="flex items-end justify-between">
             <p class="text-3xl font-black text-gray-900 leading-none">{logs.total}</p>
             <ShieldCheck class="h-8 w-8 text-emerald-100" />
          </div>
       </div>
    </div>

    <!-- Logs List -->
    <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
       <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
             <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                   <th class="px-8 py-5">Tujuan (No. WA)</th>
                   <th class="px-8 py-5">Isi Pesan</th>
                   <th class="px-8 py-5">Waktu</th>
                   <th class="px-8 py-5 text-center">Status</th>
                   <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                {#each logs.data as log}
                   <tr class="hover:bg-gray-50/30 transition-colors group">
                      <td class="px-8 py-5">
                         <div class="flex items-center gap-2">
                            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                               <Phone class="h-4 w-4" />
                            </div>
                            <span class="text-sm font-bold text-gray-900">{log.phone_number}</span>
                         </div>
                      </td>
                      <td class="px-8 py-5">
                         <p class="text-xs text-gray-500 font-bold max-w-md line-clamp-2 italic">"{log.message}"</p>
                      </td>
                      <td class="px-8 py-5 text-[10px] font-bold text-gray-400 uppercase">
                         {formatDateTime(log.sent_at)}
                      </td>
                      <td class="px-8 py-5 text-center">
                         {#if log.status === 'sent'}
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase rounded-lg border border-emerald-100">Sent</span>
                         {:else if log.status === 'failed'}
                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[9px] font-black uppercase rounded-lg border border-red-100">Failed</span>
                         {:else}
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[9px] font-black uppercase rounded-lg border border-amber-100">Pending</span>
                         {/if}
                      </td>
                      <td class="px-8 py-5 text-right">
                         <button 
                            on:click={() => resend(log.id)}
                            class="p-2 text-gray-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition opacity-0 group-hover:opacity-100"
                            title="Resend Message"
                         >
                            <RotateCcw class="h-4 w-4" />
                         </button>
                      </td>
                   </tr>
                {:else}
                   <tr>
                      <td colspan="5" class="px-8 py-20 text-center text-gray-400 italic font-bold uppercase tracking-widest text-xs">Belum ada riwayat pengiriman pesan.</td>
                   </tr>
                {/each}
             </tbody>
          </table>
       </div>
    </div>
  </div>
</AppLayout>
