<script>
    import { Link } from "@inertiajs/svelte";
    import {
        BookOpen,
        ShieldCheck,
        CreditCard,
        Users,
        ArrowRight,
        School,
    } from "lucide-svelte";

    export let canLogin;
</script>

<svelte:head>
    <title>Selamat Datang di SIM-PP</title>
</svelte:head>

<div
    class="min-h-screen bg-white font-sans text-gray-900 selection:bg-blue-100 selection:text-blue-900"
>
    <!-- Nav -->
    <nav class="flex items-center justify-between px-10 h-24 max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <div
                class="h-10 w-10 bg-blue-600 rounded-xl flex items-center justify-center text-white"
            >
                <BookOpen class="h-6 w-6" />
            </div>
            <span class="text-2xl font-black tracking-tighter">SIM-PP</span>
        </div>
        <div class="flex items-center gap-8">
            <a
                href="#features"
                class="text-sm font-bold text-gray-500 hover:text-black transition-colors"
                >Fitur</a
            >
            <a
                href="#pricing"
                class="text-sm font-bold text-gray-500 hover:text-black transition-colors"
                >Harga</a
            >
            {#if canLogin}
                <Link
                    href="/login"
                    class="px-8 py-3 bg-gray-900 text-white rounded-2xl text-sm font-bold shadow-xl shadow-gray-200 hover:bg-blue-600 transition-all active:scale-95"
                    >Masuk Platform</Link
                >
            {/if}
        </div>
    </nav>

    <!-- Hero -->
    <section
        class="max-w-7xl mx-auto px-10 py-24 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center"
    >
        <div class="space-y-10">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full text-xs font-black uppercase tracking-widest"
            >
                <div
                    class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"
                ></div>
                Manajemen Pesantren 4.0
            </div>
            <h1 class="text-7xl font-black leading-[1.05] tracking-tighter">
                Kelola Pesantren Jadi <span class="text-blue-600"
                    >Lebih Modern.</span
                >
            </h1>
            <p
                class="text-xl text-gray-400 font-medium leading-relaxed max-w-lg"
            >
                Satu platform terintegrasi untuk Kesantrian, Akademik, Keuangan
                (E-Money), hingga HR & Monitoring Wali Santri.
            </p>
            <div class="flex gap-4">
                <Link
                    href="/login"
                    class="px-10 py-5 bg-blue-600 text-white rounded-[24px] font-black text-xs uppercase tracking-widest shadow-2xl shadow-blue-200 hover:bg-black transition-all flex items-center gap-3"
                >
                    Mulai Sekarang
                    <ArrowRight class="h-5 w-5" />
                </Link>
                <button
                    class="px-10 py-5 bg-white border border-gray-100 rounded-[24px] font-black text-xs uppercase tracking-widest text-gray-500 hover:bg-gray-50 transition-all"
                    >Lihat Demo</button
                >
            </div>
            <div class="pt-10 flex items-center gap-6">
                <div class="flex -space-x-3">
                    {#each [1, 2, 3, 4] as i}
                        <div
                            class="h-12 w-12 rounded-full border-4 border-white bg-gray-100 flex items-center justify-center font-bold text-xs text-gray-400"
                        >
                            P{i}
                        </div>
                    {/each}
                </div>
                <p class="text-sm font-bold text-gray-500">
                    Dipercaya oleh <span class="text-black"
                        >50+ Pondok Pesantren</span
                    > di Indonesia
                </p>
            </div>
        </div>
        <div class="relative">
            <div
                class="aspect-square bg-blue-50 rounded-[80px] rotate-3 absolute inset-0"
            ></div>
            <div
                class="relative aspect-square bg-gray-900 rounded-[80px] p-12 overflow-hidden shadow-3xl"
            >
                <div
                    class="h-full w-full border-2 border-white/10 rounded-[40px] p-6 space-y-6"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-4 w-4 rounded-full bg-red-400"></div>
                            <div
                                class="h-4 w-4 rounded-full bg-amber-400"
                            ></div>
                            <div
                                class="h-4 w-4 rounded-full bg-emerald-400"
                            ></div>
                        </div>
                        <div
                            class="px-3 py-1 bg-white/10 rounded-lg text-[10px] font-black text-white/40 tracking-widest uppercase"
                        >
                            Admin Dasbor
                        </div>
                    </div>
                    <div class="space-y-4 pt-10">
                        <div class="h-6 w-1/2 bg-white/20 rounded-lg"></div>
                        <div
                            class="h-24 w-full bg-white/5 rounded-2xl border border-white/5"
                        ></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div
                                class="h-20 bg-blue-500/20 rounded-2xl border border-blue-500/20"
                            ></div>
                            <div
                                class="h-20 bg-emerald-500/20 rounded-2xl border border-emerald-500/20"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="bg-gray-50 py-32">
        <div class="max-w-7xl mx-auto px-10">
            <div class="text-center space-y-4 mb-20">
                <p
                    class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600"
                >
                    Ekosistem Terintegrasi
                </p>
                <h2 class="text-5xl font-black tracking-tight leading-tight">
                    Solusi Menyeluruh untuk <br />Manajemen Pendidikan Islam
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    class="bg-white p-10 rounded-[48px] shadow-sm hover:shadow-xl transition-all group"
                >
                    <div
                        class="h-16 w-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mb-10 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-500"
                    >
                        <Users class="h-8 w-8" />
                    </div>
                    <h3 class="text-xl font-black mb-4">Kesantrian</h3>
                    <p class="text-gray-400 font-medium leading-relaxed">
                        Kelola data santri, absensi harian, poin kedisiplinan
                        hingga perizinan santri dalam satu dashboard.
                    </p>
                </div>
                <div
                    class="bg-white p-10 rounded-[48px] shadow-sm hover:shadow-xl transition-all group"
                >
                    <div
                        class="h-16 w-16 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mb-10 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-500"
                    >
                        <CreditCard class="h-8 w-8" />
                    </div>
                    <h3 class="text-xl font-black mb-4">E-Wallet</h3>
                    <p class="text-gray-400 font-medium leading-relaxed">
                        Ekosistem cashless dengan kartu santri digital.
                        Transaksi kantin, koperasi, dan monitoring saku
                        real-time.
                    </p>
                </div>
                <div
                    class="bg-white p-10 rounded-[48px] shadow-sm hover:shadow-xl transition-all group"
                >
                    <div
                        class="h-16 w-16 bg-purple-50 text-purple-600 rounded-3xl flex items-center justify-center mb-10 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-500"
                    >
                        <School class="h-8 w-8" />
                    </div>
                    <h3 class="text-xl font-black mb-4">Akademik</h3>
                    <p class="text-gray-400 font-medium leading-relaxed">
                        Manajemen kelas, jadwal pelajaran, input nilai
                        (E-Raport), hingga monitoring setoran hafalan (Tahfidz).
                    </p>
                </div>
                <div
                    class="bg-white p-10 rounded-[48px] shadow-sm hover:shadow-xl transition-all group"
                >
                    <div
                        class="h-16 w-16 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center mb-10 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-500"
                    >
                        <ShieldCheck class="h-8 w-8" />
                    </div>
                    <h3 class="text-xl font-black mb-4">MoniWali</h3>
                    <p class="text-gray-400 font-medium leading-relaxed">
                        Portal khusus wali santri untuk memantau perkembangan
                        anak secara transparan dari mana saja.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-20 border-t border-gray-100 text-center">
        <p class="text-sm font-bold text-gray-300 uppercase tracking-widest">
            &copy; 2026 SIM-PP. All Rights Reserved.
        </p>
    </footer>
</div>
