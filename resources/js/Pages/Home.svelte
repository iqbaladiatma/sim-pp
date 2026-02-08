<script>
    import AppLayout from "@/Layouts/AppLayout.svelte";
    import {
        Users,
        Wallet,
        ShieldAlert,
        BookOpen,
        TrendingUp,
        ArrowUpRight,
        ArrowDownRight,
        Clock,
        UserCircle2,
        CheckCircle2,
        CalendarCheck,
        Briefcase,
        Plus,
        ShoppingCart,
        ArrowRight,
        CreditCard,
    } from "lucide-svelte";

    export let stats = {};
    export let recent = { transactions: [], violations: [] };

    // Helper for currency formatting
    function formatCurrency(amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(amount || 0);
    }

    // Helper for date formatting
    function formatDate(date) {
        if (!date) return "-";
        return new Date(date).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
        });
    }

    // Safe accessors
    $: totalStudents = stats?.students?.total || 0;
    $: maleStudents = stats?.students?.male || 0;
    $: femaleStudents = stats?.students?.female || 0;

    $: collectability = stats?.finance?.collectability || 0;
    $: totalOutstanding = stats?.finance?.total_outstanding || 0;

    $: presentToday = stats?.attendance?.present || 0;

    $: tahfidzToday = stats?.academic?.tahfidz_today || 0;
    $: totalTeachers = stats?.hr?.total_teachers || 0;
    $: teacherPresent = stats?.hr?.teacher_present || 0;
    $: totalBalance = stats?.finance?.total_balance || 0;

    $: transactions = recent?.transactions || [];
    $: violations = recent?.violations || [];
</script>

<svelte:head>
    <title>Dashboard</title>
</svelte:head>

<AppLayout>
    <div class="space-y-8">
        <!-- Welcome Header -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4"
        >
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                    Ringkasan Operasional
                </h1>
                <p class="text-gray-500 mt-1">
                    Selamat datang kembali! Berikut kondisi terkini pondok
                    pesantren Anda.
                </p>
            </div>
            <div class="hidden md:flex flex-col items-end">
                <p
                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"
                >
                    Waktu Server
                </p>
                <p
                    class="text-sm font-bold text-gray-900 flex items-center gap-2"
                >
                    <Clock class="h-4 w-4 text-blue-600" />
                    {new Date().toLocaleDateString("id-ID", {
                        weekday: "long",
                        day: "numeric",
                        month: "long",
                        year: "numeric",
                    })}
                </p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a
                href="/merchant"
                class="group bg-blue-600 p-5 rounded-[32px] shadow-xl shadow-blue-100 flex items-center justify-between hover:bg-black transition-all"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 bg-white/20 rounded-2xl flex items-center justify-center text-white"
                    >
                        <ShoppingCart class="h-6 w-6" />
                    </div>
                    <span
                        class="text-xs font-black text-white uppercase tracking-widest leading-tight"
                    >
                        Kantin<br /><span class="opacity-60 text-[9px]"
                            >E-Money POS</span
                        >
                    </span>
                </div>
                <ArrowRight
                    class="h-4 w-4 text-white opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"
                />
            </a>

            <a
                href="/students/create"
                class="group bg-white p-5 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between hover:border-blue-500 transition-all"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 bg-gray-50 text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors rounded-2xl flex items-center justify-center"
                    >
                        <Plus class="h-6 w-6" />
                    </div>
                    <span
                        class="text-xs font-black text-gray-500 group-hover:text-gray-900 transition-colors uppercase tracking-widest leading-tight"
                    >
                        Registrasi<br /><span class="opacity-60 text-[9px]"
                            >Santri Baru</span
                        >
                    </span>
                </div>
            </a>

            <a
                href="/student-bills"
                class="group bg-white p-5 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between hover:border-blue-500 transition-all"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 bg-gray-50 text-gray-400 group-hover:bg-amber-50 group-hover:text-amber-600 transition-colors rounded-2xl flex items-center justify-center"
                    >
                        <Wallet class="h-6 w-6" />
                    </div>
                    <span
                        class="text-xs font-black text-gray-500 group-hover:text-gray-900 transition-colors uppercase tracking-widest leading-tight"
                    >
                        Tagihan<br /><span class="opacity-60 text-[9px]"
                            >Input Manual</span
                        >
                    </span>
                </div>
            </a>

            <a
                href="/reports"
                class="group bg-white p-5 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between hover:border-blue-500 transition-all"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 bg-gray-50 text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors rounded-2xl flex items-center justify-center"
                    >
                        <TrendingUp class="h-6 w-6" />
                    </div>
                    <span
                        class="text-xs font-black text-gray-500 group-hover:text-gray-900 transition-colors uppercase tracking-widest leading-tight"
                    >
                        Laporan<br /><span class="opacity-60 text-[9px]"
                            >Executive View</span
                        >
                    </span>
                </div>
            </a>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Students -->
            <div
                class="bg-white p-7 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all"
            >
                <div class="flex items-center justify-between relative z-10">
                    <div
                        class="h-14 w-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center"
                    >
                        <Users class="h-7 w-7" />
                    </div>
                    <span
                        class="flex items-center gap-1 text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg"
                        >AKTIF</span
                    >
                </div>
                <div class="mt-8 relative z-10">
                    <p class="text-4xl font-black text-gray-900">
                        {totalStudents}
                    </p>
                    <p class="text-sm font-medium text-gray-400 mt-1">
                        Total Santri Terdaftar
                    </p>
                </div>
                <div
                    class="mt-4 flex items-center gap-4 text-xs font-bold relative z-10"
                >
                    <span class="text-blue-600">L: {maleStudents}</span>
                    <span class="text-pink-600">P: {femaleStudents}</span>
                </div>
            </div>

            <!-- Finance Collectability -->
            <div
                class="bg-white p-7 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all"
            >
                <div class="flex items-center justify-between relative z-10">
                    <div
                        class="h-14 w-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center"
                    >
                        <Wallet class="h-7 w-7" />
                    </div>
                    <span
                        class="flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-wider"
                        >Kolektibilitas</span
                    >
                </div>
                <div class="mt-8 relative z-10">
                    <p class="text-4xl font-black text-gray-900">
                        {collectability}%
                    </p>
                    <p class="text-sm font-medium text-gray-400 mt-1">
                        Total Dana Tertagih
                    </p>
                </div>
                <div
                    class="mt-4 w-full bg-gray-100 h-1.5 rounded-full overflow-hidden relative z-10"
                >
                    <div
                        class="bg-emerald-600 h-full transition-all duration-1000"
                        style="width: {collectability}%"
                    ></div>
                </div>
            </div>

            <!-- Outstanding Bills -->
            <div
                class="bg-white p-7 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all"
            >
                <div class="flex items-center justify-between relative z-10">
                    <div
                        class="h-14 w-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center"
                    >
                        <TrendingUp class="h-7 w-7" />
                    </div>
                </div>
                <div class="mt-8 relative z-10">
                    <p class="text-4xl font-black text-gray-900 leading-tight">
                        {totalOutstanding > 1000000
                            ? (totalOutstanding / 1000000).toFixed(1) + "jt"
                            : formatCurrency(totalOutstanding)}
                    </p>
                    <p class="text-sm font-medium text-gray-400 mt-1">
                        Piutang Belum Terbayar
                    </p>
                </div>
                <div
                    class="mt-4 flex items-center gap-1 text-red-600 text-[10px] font-bold uppercase relative z-10"
                >
                    <ArrowUpRight class="h-3 w-3" />
                    Butuh Perhatian
                </div>
            </div>

            <!-- Attendance Today -->
            <div
                class="bg-white p-7 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all"
            >
                <div class="flex items-center justify-between relative z-10">
                    <div
                        class="h-14 w-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center"
                    >
                        <CalendarCheck class="h-7 w-7" />
                    </div>
                    <span
                        class="flex items-center gap-1 text-[10px] font-black text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg uppercase tracking-wider"
                        >Realtime</span
                    >
                </div>
                <div class="mt-8 relative z-10">
                    <p class="text-4xl font-black text-gray-900">
                        {presentToday}
                    </p>
                    <p class="text-sm font-medium text-gray-400 mt-1">
                        Santri Hadir Hari Ini
                    </p>
                </div>
                <div
                    class="mt-4 flex items-center gap-1 text-emerald-600 text-[10px] font-bold uppercase relative z-10"
                >
                    <CheckCircle2 class="h-3 w-3" />
                    Dipantau Sistem
                </div>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between"
            >
                <div>
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1"
                    >
                        Setoran Tahfidz (Hari Ini)
                    </p>
                    <p class="text-2xl font-black text-gray-900 leading-none">
                        {tahfidzToday}
                    </p>
                </div>
                <div
                    class="h-12 w-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center"
                >
                    <BookOpen class="h-6 w-6" />
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between"
            >
                <div>
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1"
                    >
                        Presensi Ustadz (Hadir)
                    </p>
                    <p class="text-2xl font-black text-gray-900 leading-none">
                        {teacherPresent} / {totalTeachers}
                    </p>
                </div>
                <div
                    class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center"
                >
                    <Briefcase class="h-6 w-6" />
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between"
            >
                <div>
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1"
                    >
                        Saldo Tabungan (Total)
                    </p>
                    <p class="text-xl font-black text-gray-900 leading-none">
                        {formatCurrency(totalBalance)}
                    </p>
                </div>
                <div
                    class="h-12 w-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center"
                >
                    <CreditCard class="h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Finance History -->
            <div
                class="lg:col-span-2 bg-white rounded-[40px] border border-gray-100 shadow-sm p-8"
            >
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-black text-gray-900">
                            Transaksi Terakhir
                        </h3>
                        <p class="text-sm text-gray-400">
                            Arus kas masuk terbaru dari santri
                        </p>
                    </div>
                    <a
                        href="/transactions"
                        class="text-blue-600 text-xs font-bold hover:underline"
                        >LIHAT SEMUA</a
                    >
                </div>
                <div class="space-y-5">
                    {#each transactions as trx}
                        <div
                            class="flex items-center justify-between p-5 bg-gray-50/50 rounded-3xl border border-transparent hover:border-blue-100 hover:bg-blue-50/20 transition-all group"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-12 w-12 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 transition-colors group-hover:bg-blue-600 group-hover:text-white"
                                >
                                    <Wallet class="h-6 w-6" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">
                                        {trx.bill?.student?.name || "Santri"}
                                    </p>
                                    <p
                                        class="text-[10px] text-gray-400 font-bold uppercase tracking-widest"
                                    >
                                        {trx.bill?.category?.name || "Tagihan"}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-emerald-600">
                                    +{formatCurrency(trx.amount)}
                                </p>
                                <p class="text-[10px] font-bold text-gray-400">
                                    {formatDate(trx.payment_date)}
                                </p>
                            </div>
                        </div>
                    {:else}
                        <div
                            class="py-10 text-center text-gray-400 text-sm italic"
                        >
                            Belum ada transaksi terbaru.
                        </div>
                    {/each}
                </div>
            </div>

            <!-- Disciplinary Alerts -->
            <div
                class="bg-gray-900 rounded-[40px] shadow-2xl p-8 text-white h-fit"
            >
                <div class="flex items-center gap-3 mb-8">
                    <div
                        class="h-10 w-10 rounded-xl bg-red-500 flex items-center justify-center"
                    >
                        <ShieldAlert class="h-6 w-6 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black tracking-tight">
                            Pelanggaran Baru
                        </h3>
                        <p
                            class="text-[10px] text-gray-400 font-bold uppercase tracking-wider"
                        >
                            Laporan Kedisiplinan
                        </p>
                    </div>
                </div>
                <div class="space-y-4">
                    {#each violations as v}
                        <div
                            class="p-5 bg-white/5 rounded-3xl border border-white/10 hover:bg-white/10 transition-all"
                        >
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-bold">
                                    {v.student?.name || "Santri"}
                                </p>
                                <span
                                    class="text-[10px] font-black text-red-400"
                                    >-{v.points_deduction} Pts</span
                                >
                            </div>
                            <p class="text-xs text-gray-400 line-clamp-2">
                                {v.description}
                            </p>
                            <div
                                class="mt-3 flex items-center justify-between uppercase tracking-widest"
                            >
                                <span
                                    class="text-[9px] font-black text-gray-500"
                                    >{v.type}</span
                                >
                                <span
                                    class="text-[9px] font-black text-gray-500"
                                    >{formatDate(v.incident_date)}</span
                                >
                            </div>
                        </div>
                    {:else}
                        <div
                            class="py-10 text-center text-gray-500 text-xs italic"
                        >
                            Kondisi pondok kondusif.
                        </div>
                    {/each}
                </div>
                <a
                    href="/violations"
                    class="mt-8 block w-full py-4 bg-white/5 hover:bg-white/10 text-center rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all"
                >
                    Kelola Disiplin
                </a>
            </div>
        </div>
    </div>
</AppLayout>
