<script>
    import { Link, router, useForm } from "@inertiajs/svelte";
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        DollarSign,
        Search,
        Filter,
        Plus,
        FileText,
        CheckCircle2,
        AlertCircle,
        Calendar,
    } from "lucide-svelte";

    export let payrolls = [];
    export let filters = { month: "", year: "" };
    export let teachers_count = 0;
    export let total_salary = 0;

    let selectedMonth = filters.month || new Date().getMonth() + 1;
    let selectedYear = filters.year || new Date().getFullYear();
    let isModalOpen = false;
    let selectedPayroll = null;

    const form = useForm({
        id: null,
        base_salary: 0,
        allowances: 0,
        deductions: 0,
        status: "pending",
        payment_date: "",
        notes: "",
    });

    const generateForm = useForm({
        month: selectedMonth,
        year: selectedYear,
    });

    function handleFilter() {
        router.get(
            route("payrolls.index"),
            {
                month: selectedMonth,
                year: selectedYear,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
        }).format(amount);
    }

    function openEditModal(payroll) {
        selectedPayroll = payroll;
        form.id = payroll.id;
        form.base_salary = payroll.base_salary;
        form.allowances = payroll.allowances;
        form.deductions = payroll.deductions;
        form.status = payroll.status;
        form.payment_date =
            payroll.payment_date || new Date().toISOString().split("T")[0];
        form.notes = payroll.notes || "";
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
        selectedPayroll = null;
        form.reset();
    }

    function submitUpdate() {
        if (!selectedPayroll) return;

        form.put(route("payrolls.update", selectedPayroll.id), {
            onSuccess: () => closeModal(),
        });
    }

    function generatePayroll() {
        generateForm.month = selectedMonth;
        generateForm.year = selectedYear;

        if (
            confirm(
                `Generate data gaji untuk periode ${selectedMonth}/${selectedYear}?`,
            )
        ) {
            generateForm.post(route("payrolls.generate"), {
                onSuccess: () => {
                    // Success handled by layout flash
                },
            });
        }
    }
</script>

<svelte:head>
    <title>Payroll & Penggajian</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Penggajian Guru & Staf
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola gaji bulanan dan slip pembayaran
                </p>
            </div>

            <button
                on:click={generatePayroll}
                disabled={generateForm.processing}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl font-medium transition-colors shadow-lg shadow-gray-200"
            >
                <Plus class="h-4 w-4" />
                Generate Periode Ini
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between"
            >
                <div>
                    <p
                        class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"
                    >
                        Total Gaji (Est)
                    </p>
                    <p class="text-xl font-black text-gray-900">
                        {formatCurrency(total_salary)}
                    </p>
                </div>
                <div
                    class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center"
                >
                    <DollarSign class="h-5 w-5" />
                </div>
            </div>
            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between"
            >
                <div>
                    <p
                        class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"
                    >
                        Total Penerima
                    </p>
                    <p class="text-xl font-black text-gray-900">
                        {payrolls.length} / {teachers_count}
                    </p>
                </div>
                <div
                    class="h-10 w-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center"
                >
                    <CheckCircle2 class="h-5 w-5" />
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div
            class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 items-center"
        >
            <div class="flex items-center gap-2">
                <Calendar class="h-5 w-5 text-gray-400" />
                <select
                    bind:value={selectedMonth}
                    on:change={handleFilter}
                    class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
                    {#each Array.from({ length: 12 }, (_, i) => i + 1) as m}
                        <option value={m}>Bulan {m}</option>
                    {/each}
                </select>
                <select
                    bind:value={selectedYear}
                    on:change={handleFilter}
                    class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>
        </div>

        <!-- Payroll List -->
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100"
                    >
                        <tr>
                            <th class="px-6 py-4 font-bold">Nama Guru</th>
                            <th class="px-6 py-4 font-bold text-right"
                                >Gaji Pokok</th
                            >
                            <th class="px-6 py-4 font-bold text-right"
                                >Tunjangan</th
                            >
                            <th class="px-6 py-4 font-bold text-right"
                                >Potongan</th
                            >
                            <th class="px-6 py-4 font-bold text-right"
                                >Total Terima</th
                            >
                            <th class="px-6 py-4 font-bold text-center"
                                >Status</th
                            >
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each payrolls as payroll (payroll.id)}
                            <tr
                                class="hover:bg-gray-50/50 transition-colors group"
                            >
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {payroll.teacher.user.name}
                                    <div class="text-xs text-gray-500">
                                        {payroll.teacher.position}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600"
                                    >{formatCurrency(payroll.base_salary)}</td
                                >
                                <td
                                    class="px-6 py-4 text-right text-emerald-600"
                                    >+{formatCurrency(payroll.allowances)}</td
                                >
                                <td class="px-6 py-4 text-right text-red-600"
                                    >-{formatCurrency(payroll.deductions)}</td
                                >
                                <td
                                    class="px-6 py-4 text-right font-bold text-gray-900"
                                    >{formatCurrency(payroll.total_salary)}</td
                                >
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wider {payroll.status ===
                                        'paid'
                                            ? 'bg-emerald-100 text-emerald-700'
                                            : 'bg-yellow-100 text-yellow-700'}"
                                    >
                                        {payroll.status}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        on:click={() => openEditModal(payroll)}
                                        class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase tracking-wider hover:underline"
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        {:else}
                            <tr>
                                <td
                                    colspan="7"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    Belum ada data gaji untuk periode ini.
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    {#if isModalOpen}
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
        >
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200"
            >
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50"
                >
                    <h3 class="text-lg font-bold text-gray-900">
                        Edit Rincian Gaji
                    </h3>
                    <button
                        on:click={closeModal}
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <AlertCircle class="h-5 w-5" />
                    </button>
                </div>

                <form
                    on:submit|preventDefault={submitUpdate}
                    class="p-6 space-y-4"
                >
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-3">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Gaji Pokok</label
                            >
                            <input
                                type="number"
                                bind:value={$form.base_salary}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Tunjangan (+)</label
                            >
                            <input
                                type="number"
                                bind:value={$form.allowances}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-emerald-600 font-bold"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Potongan (-)</label
                            >
                            <input
                                type="number"
                                bind:value={$form.deductions}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-red-600 font-bold"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Total</label
                            >
                            <input
                                type="text"
                                value={formatCurrency(
                                    $form.base_salary +
                                        $form.allowances -
                                        $form.deductions,
                                )}
                                disabled
                                class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 font-bold"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Status Pembayaran</label
                            >
                            <select
                                bind:value={$form.status}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="pending"
                                    >Pending (Belum Dibayar)</option
                                >
                                <option value="paid"
                                    >Paid (Sudah Dibayar)</option
                                >
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Tanggal Bayar</label
                            >
                            <input
                                type="date"
                                bind:value={$form.payment_date}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Catatan Tambahan</label
                        >
                        <textarea
                            bind:value={$form.notes}
                            rows="2"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        ></textarea>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button
                            type="button"
                            on:click={closeModal}
                            class="px-5 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 font-medium"
                            >Batal</button
                        >
                        <button
                            type="submit"
                            disabled={$form.processing}
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-lg shadow-blue-200"
                            >Simpan Perubahan</button
                        >
                    </div>
                </form>
            </div>
        </div>
    {/if}
</AppLayout>
