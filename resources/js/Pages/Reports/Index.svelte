<script>
    import AppLayout from "../../Layouts/AppLayout.svelte";
    import {
        BarChart3,
        PieChart,
        TrendingUp,
        ArrowUpRight,
        Calendar,
        Download,
        Table as TableIcon,
    } from "lucide-svelte";

    export let incomeByMonth = [];
    export let expenseByMonth = [];
    export let outstandingByType = [];

    function formatCurrency(amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(amount);
    }

    const monthNames = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Agu",
        "Sep",
        "Okt",
        "Nov",
        "Des",
    ];

    // Prepare data safely
    let months = [];
    let maxVal = 1000000;

    $: {
        months = Array.from({ length: 12 }, (_, i) => ({
            name: monthNames[i],
            income:
                Number(
                    incomeByMonth?.find((m) => parseInt(m.month) === i + 1)
                        ?.total,
                ) || 0,
            expense:
                Number(
                    expenseByMonth?.find((m) => parseInt(m.month) === i + 1)
                        ?.total,
                ) || 0,
        }));

        const computedMax = Math.max(
            ...months.map((m) => Math.max(m.income, m.expense)),
        );
        maxVal = computedMax > 0 ? computedMax : 1000000;
    }
</script>

<svelte:head>
    <title>Laporan & Analitik</title>
</svelte:head>

<AppLayout>
    <div class="space-y-8 pb-12">
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4"
        >
            <div>
                <h1
                    class="text-3xl font-black text-gray-900 tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500"
                >
                    Analitik & Laporan
                </h1>
                <p class="text-gray-500 font-medium">
                    Laporan komprehensif kesehatan finansial dan operasional
                    pondok
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="flex items-center gap-2 px-5 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-500 hover:bg-gray-50 transition-all"
                >
                    <Calendar class="h-4 w-4" />
                    Tahun {new Date().getFullYear()}
                </button>
                <button
                    class="flex items-center gap-2 px-5 py-3 bg-black text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:opacity-80 transition-all shadow-xl shadow-gray-200"
                >
                    <Download class="h-4 w-4" />
                    Export PDF
                </button>
            </div>
        </div>

        <!-- Monthly Cash Flow Chart -->
        <div
            class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-6 md:p-10 space-y-10 overflow-x-auto"
        >
            <div
                class="flex flex-col md:flex-row md:items-center justify-between gap-6"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shrink-0"
                    >
                        <BarChart3 class="h-6 w-6" />
                    </div>
                    <div>
                        <h3
                            class="text-lg font-black text-gray-900 uppercase tracking-tight"
                        >
                            Arus Kas Bulanan
                        </h3>
                        <p class="text-xs text-gray-400 font-bold uppercase">
                            Perbandingan Pemasukan vs Pengeluaran
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-blue-600"></div>
                        <span
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest"
                            >Pemasukan</span
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-red-400"></div>
                        <span
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest"
                            >Pengeluaran</span
                        >
                    </div>
                </div>
            </div>

            <div
                class="min-w-[600px] h-[300px] flex items-end justify-between gap-2 md:gap-4 pt-8"
            >
                {#each months as month}
                    <div
                        class="flex-1 flex flex-col justify-end items-center group relative gap-1 h-full"
                    >
                        <div
                            class="w-full flex justify-center gap-1 items-end h-full"
                        >
                            <div
                                class="w-1/2 bg-blue-600 rounded-t-sm md:rounded-t-lg transition-all duration-700 hover:opacity-80 relative group/bar"
                                style="height: {(month.income / maxVal) * 100}%"
                            >
                                <div
                                    class="absolute -top-10 left-1/2 -translate-x-1/2 bg-black text-white text-[9px] font-black px-2 py-1 rounded-md opacity-0 group-hover/bar:opacity-100 transition-opacity whitespace-nowrap z-20 pointer-events-none"
                                >
                                    {formatCurrency(month.income)}
                                </div>
                            </div>
                            <div
                                class="w-1/2 bg-red-400 rounded-t-sm md:rounded-t-lg transition-all duration-700 hover:opacity-80 relative group/bar"
                                style="height: {(month.expense / maxVal) *
                                    100}%"
                            >
                                <div
                                    class="absolute -top-10 left-1/2 -translate-x-1/2 bg-black text-white text-[9px] font-black px-2 py-1 rounded-md opacity-0 group-hover/bar:opacity-100 transition-opacity whitespace-nowrap z-20 pointer-events-none"
                                >
                                    {formatCurrency(month.expense)}
                                </div>
                            </div>
                        </div>
                        <span
                            class="text-[9px] font-black text-gray-400 uppercase mt-4"
                            >{month.name}</span
                        >
                    </div>
                {/each}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Piutang Card -->
            <div
                class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 space-y-8"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center"
                    >
                        <PieChart class="h-6 w-6" />
                    </div>
                    <div>
                        <h3
                            class="text-lg font-black text-gray-900 uppercase tracking-tight"
                        >
                            Saldo Piutang
                        </h3>
                        <p
                            class="text-xs text-gray-400 font-bold uppercase tracking-widest"
                        >
                            Tagihan Belum Tertagih
                        </p>
                    </div>
                </div>

                <div class="space-y-6">
                    {#if outstandingByType && outstandingByType.length > 0}
                        {#each outstandingByType as out}
                            <div class="space-y-2">
                                <div
                                    class="flex items-center justify-between text-xs font-black uppercase"
                                >
                                    <span class="text-gray-400 tracking-widest"
                                        >{out.category?.name || "Lainnya"}</span
                                    >
                                    <span class="text-gray-900"
                                        >{formatCurrency(out.total)}</span
                                    >
                                </div>
                                <div
                                    class="w-full bg-gray-50 h-3 rounded-full overflow-hidden border border-gray-100"
                                >
                                    <div
                                        class="bg-amber-500 h-full rounded-full"
                                        style="width: 70%"
                                    ></div>
                                </div>
                            </div>
                        {/each}
                    {:else}
                        <div
                            class="py-12 text-center bg-gray-50 rounded-2xl border border-gray-100 border-dashed"
                        >
                            <p class="text-gray-400 italic text-sm font-medium">
                                Tidak ada data piutang saat ini.
                            </p>
                        </div>
                    {/if}
                </div>
            </div>

            <!-- Quick Insights -->
            <div class="grid grid-cols-1 gap-6">
                <div
                    class="bg-emerald-600 rounded-[40px] p-10 text-white relative overflow-hidden group"
                >
                    <div
                        class="absolute top-0 right-0 p-8 opacity-10 group-hover:scale-110 transition-transform"
                    >
                        <TrendingUp class="h-32 w-32" />
                    </div>
                    <div class="relative z-10 space-y-6">
                        <div>
                            <p
                                class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-200"
                            >
                                Kesehatan Finansial
                            </p>
                            <h2 class="text-4xl font-black mt-2">
                                SANGAT BAIK
                            </h2>
                        </div>
                        <p
                            class="text-xs font-bold text-emerald-100 max-w-xs leading-relaxed opacity-80 uppercase tracking-widest"
                        >
                            Pemasukan bulan ini terjaga dengan baik.
                        </p>
                        <button
                            class="px-6 py-3 bg-white/20 backdrop-blur-md rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-white/30 transition-all flex items-center gap-2"
                        >
                            Review Strategi <ArrowUpRight class="h-3 w-3" />
                        </button>
                    </div>
                </div>

                <div
                    class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 flex items-center justify-between group hover:border-blue-500 transition-colors"
                >
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center"
                            >
                                <TableIcon class="h-5 w-5" />
                            </div>
                            <h3
                                class="text-sm font-black text-gray-900 uppercase"
                            >
                                Export Excel
                            </h3>
                        </div>
                        <p
                            class="text-xs text-gray-400 font-bold uppercase tracking-widest leading-none"
                        >
                            Dapatkan rekap data mentah (CSV/XLS)
                        </p>
                    </div>
                    <button
                        class="p-4 bg-gray-50 text-gray-400 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-all shadow-xl shadow-gray-100"
                    >
                        <Download class="h-6 w-6" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</AppLayout>
