<script>
  import AppLayout from '../../../Layouts/AppLayout.svelte';
  import { useForm, router } from '@inertiajs/svelte';
  import { 
    Banknote, 
    Filter, 
    Plus, 
    CheckCircle, 
    Clock, 
    Printer, 
    Search,
    ChevronRight,
    X,
    Save,
    CreditCard
  } from 'lucide-svelte';

  export let payrolls;
  export let teachers;
  export let filters;

  let isModalOpen = false;

  const form = useForm({
    teacher_id: '',
    month: filters.month,
    year: filters.year,
    base_salary: 0,
    allowances: 0,
    deductions: 0,
    notes: '',
  });

  function openModal() {
    $form.reset();
    $form.month = filters.month;
    $form.year = filters.year;
    isModalOpen = true;
  }

  function handleTeacherChange(e) {
    const teacherId = e.target.value;
    const teacher = teachers.find(t => t.id == teacherId);
    if (teacher) {
      $form.base_salary = teacher.salary;
    }
  }

  function submit() {
    $form.post('/payrolls', {
      onSuccess: () => isModalOpen = false
    });
  }

  function markAsPaid(id) {
    if (confirm('Tandai gaji ini sebagai sudah terbayar?')) {
       router.patch(`/payrolls/${id}/status`);
    }
  }

  function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
  }

  const months = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
  ];

  function handleFilter() {
    router.get('/payrolls', { month: filters.month, year: filters.year }, { preserveState: true });
  }
</script>

<AppLayout>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Penggajian Ustadz</h1>
        <p class="text-gray-500 text-sm">Manajemen payroll dan slip gaji ustadz & staf</p>
      </div>
      <button 
        on:click={openModal}
        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-2xl shadow-lg shadow-emerald-100 transition-all transform active:scale-95"
      >
        <Banknote class="h-5 w-5" />
        Proses Gaji Baru
      </button>
    </div>

    <!-- Stats & Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
       <div class="md:col-span-3 bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm flex items-center justify-between">
          <div class="flex items-center gap-6">
             <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Total Payroll Bulan Ini</p>
                <p class="text-2xl font-black text-gray-900 leading-none">
                   {formatCurrency(payrolls.reduce((acc, p) => acc + parseFloat(p.total_salary), 0))}
                </p>
             </div>
             <div class="h-10 w-px bg-gray-100"></div>
             <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Terbayar</p>
                <p class="text-2xl font-black text-emerald-600 leading-none">
                   {payrolls.filter(p => p.status === 'paid').length} / {payrolls.length}
                </p>
             </div>
          </div>
          <div class="flex items-center gap-3">
             <select bind:value={filters.month} on:change={handleFilter} class="px-4 py-2 bg-gray-50 border-none rounded-xl text-xs font-bold outline-none ring-1 ring-gray-100 focus:ring-blue-500 transition-all">
                {#each months as month, i}
                   <option value={i + 1}>{month}</option>
                {/each}
             </select>
             <select bind:value={filters.year} on:change={handleFilter} class="px-4 py-2 bg-gray-50 border-none rounded-xl text-xs font-bold outline-none ring-1 ring-gray-100 focus:ring-blue-500 transition-all">
                <option value={2026}>2026</option>
                <option value={2025}>2025</option>
             </select>
          </div>
       </div>
    </div>

    <!-- Payroll Table -->
    <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden animate-in fade-in duration-500">
       <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
             <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                   <th class="px-8 py-5">Nama Ustadz/ah</th>
                   <th class="px-8 py-5 text-right">Gaji Pokok</th>
                   <th class="px-8 py-5 text-right">Tunjangan</th>
                   <th class="px-8 py-5 text-right">Potongan</th>
                   <th class="px-8 py-5 text-right">Total Akhir</th>
                   <th class="px-8 py-5 text-center">Status</th>
                   <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-gray-50">
                {#each payrolls as payroll}
                   <tr class="hover:bg-gray-50/30 transition-colors group">
                      <td class="px-8 py-5">
                         <p class="text-sm font-bold text-gray-900 leading-tight">{payroll.teacher.user.name}</p>
                         <p class="text-[10px] text-gray-400 font-bold uppercase mt-0.5">{payroll.teacher.nip || 'No NIP'}</p>
                      </td>
                      <td class="px-8 py-5 text-right text-xs font-bold text-gray-600">{formatCurrency(payroll.base_salary)}</td>
                      <td class="px-8 py-5 text-right text-xs font-bold text-emerald-600">+{formatCurrency(payroll.allowances)}</td>
                      <td class="px-8 py-5 text-right text-xs font-bold text-red-600">-{formatCurrency(payroll.deductions)}</td>
                      <td class="px-8 py-5 text-right font-black text-gray-900">{formatCurrency(payroll.total_salary)}</td>
                      <td class="px-8 py-5 text-center">
                         {#if payroll.status === 'paid'}
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase rounded-lg border border-emerald-100">Paid</span>
                         {:else}
                            <span class="px-3 py-1 bg-amber-50 text-amber-600 text-[9px] font-black uppercase rounded-lg border border-amber-100">Pending</span>
                         {/if}
                      </td>
                      <td class="px-8 py-5 text-right">
                         <div class="flex items-center justify-end gap-2">
                            {#if payroll.status === 'pending'}
                               <button on:click={() => markAsPaid(payroll.id)} class="p-2 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Mark as Paid">
                                  <CheckCircle class="h-4 w-4" />
                               </button>
                            {/if}
                            <button class="p-2 bg-gray-50 text-gray-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Print Slip">
                               <Printer class="h-4 w-4" />
                            </button>
                         </div>
                      </td>
                   </tr>
                {:else}
                   <tr>
                      <td colspan="7" class="px-8 py-20 text-center text-gray-400 italic font-bold uppercase tracking-widest text-xs">Belum ada data payroll untuk periode ini.</td>
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
            <h2 class="text-xl font-black text-gray-900 uppercase">Input Payroll</h2>
            <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-widest">Kesejahteraan Pegawai</p>
          </div>
          <button on:click={() => isModalOpen = false} class="p-2 text-gray-400 hover:bg-gray-50 rounded-2xl transition">
            <X class="h-6 w-6" />
          </button>
        </div>
        
        <form on:submit|preventDefault={submit} class="p-8 space-y-5">
           <div class="space-y-2">
              <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pilih Ustadz/ah</label>
              <select bind:value={$form.teacher_id} on:change={handleTeacherChange} required class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold appearance-none">
                 <option value="">Cari Pengajar...</option>
                 {#each teachers as teacher}
                    <option value={teacher.id}>{teacher.user.name} ({teacher.position})</option>
                 {/each}
              </select>
           </div>

           <div class="grid grid-cols-2 gap-4">
              <div class="space-y-2">
                 <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Gaji Pokok</label>
                 <input type="number" bind:value={$form.base_salary} required class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
              </div>
              <div class="space-y-2">
                 <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tunjangan</label>
                 <input type="number" bind:value={$form.allowances} class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
              </div>
           </div>

           <div class="grid grid-cols-2 gap-4">
              <div class="space-y-2">
                 <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Potongan</label>
                 <input type="number" bind:value={$form.deductions} class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
              </div>
              <div class="space-y-2">
                 <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest leading-none block mb-2">Total Terima</label>
                 <div class="px-5 py-3.5 bg-blue-50 rounded-2xl font-black text-blue-600 text-sm">
                   {formatCurrency($form.base_salary + $form.allowances - $form.deductions)}
                 </div>
              </div>
           </div>

           <div class="space-y-2">
              <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan / Keterangan</label>
              <input type="text" bind:value={$form.notes} placeholder="Bonus lembur, potong kas, dll" class="block w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl outline-none text-sm font-bold" />
           </div>

           <button 
             type="submit" 
             disabled={$form.processing}
             class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-3xl shadow-xl shadow-emerald-100 transition-all flex items-center justify-center gap-2 transform active:scale-95"
           >
             <Save class="h-5 w-5" />
             PROSES & CETAK PAYROLL
           </button>
        </form>
      </div>
    </div>
  {/if}
</AppLayout>
