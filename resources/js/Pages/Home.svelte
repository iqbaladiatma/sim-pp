<script>
  import { page } from '@inertiajs/svelte';
  import AppLayout from '../Layouts/AppLayout.svelte';
  
  export let title = 'Dashboard Overview';
  const user = $page.props.auth.user;
</script>

<AppLayout>
    
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Santri</p>
                    <p class="text-2xl font-bold text-gray-800">1,240</p>
                </div>
                <div class="rounded-full bg-blue-100 p-3 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Tagihan Belum Lunas</p>
                    <p class="text-2xl font-bold text-red-600">Rp 45jt</p>
                </div>
                <div class="rounded-full bg-red-100 p-3 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Hafalan Hari Ini</p>
                    <p class="text-2xl font-bold text-green-600">85 Santri</p>
                </div>
                <div class="rounded-full bg-green-100 p-3 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
            </div>
        </div>
        
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 lg:col-span-2">
            <h3 class="font-bold text-gray-800">Aktivitas Terkini</h3>
            <p class="text-sm text-gray-500 mb-4">Log aktivitas penggunaan sistem</p>
            <div class="h-64 bg-gray-50 rounded flex items-center justify-center text-gray-400">
                [Area Grafik Placeholder]
            </div>
        </div>
        
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800">Pengumuman</h3>
            <ul class="mt-4 space-y-4">
                <li class="border-b pb-2">
                    <span class="text-xs font-bold text-blue-600">INFO</span>
                    <p class="text-sm text-gray-600">Rapat wali santri tanggal 25 Februari.</p>
                </li>
                <li class="border-b pb-2">
                    <span class="text-xs font-bold text-red-600">PENTING</span>
                    <p class="text-sm text-gray-600">Backup data bulanan dijadwalkan besok.</p>
                </li>
            </ul>
        </div>
    </div>

</AppLayout>