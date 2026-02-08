<script>
    import { Link, router, useForm } from "@inertiajs/svelte";
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        Search,
        Plus,
        Pencil,
        Trash2,
        X,
        Save,
        TrendingDown,
        FileText,
        Calendar,
    } from "lucide-svelte";

    export let expenses = { data: [], links: [] };
    export let filters = { search: "" };

    let search = filters.search;
    let isModalOpen = false;
    let isEditing = false;
    let editingId = null;

    const form = useForm({
        category: "",
        description: "",
        amount: 0,
        expense_date: new Date().toISOString().split("T")[0],
        receipt_number: "",
        attachment: null,
    });

    function openCreateModal() {
        isEditing = false;
        editingId = null;
        form.reset();
        form.expense_date = new Date().toISOString().split("T")[0];
        isModalOpen = true;
    }

    function openEditModal(expense) {
        isEditing = true;
        editingId = expense.id;
        form.category = expense.category;
        form.description = expense.description || "";
        form.amount = expense.amount;
        form.expense_date = expense.expense_date;
        form.receipt_number = expense.receipt_number || "";
        form.attachment = null; // Don't prepopulate file
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
        form.reset();
    }

    function submit() {
        if (isEditing) {
            form.post(route("expenses.update", editingId), {
                forceFormData: true,
                onSuccess: () => closeModal(),
                _method: "PUT", // Inertia workaround for file upload in PUT
            });
        } else {
            form.post(route("expenses.store"), {
                forceFormData: true,
                onSuccess: () => closeModal(),
            });
        }
    }

    function deleteExpense(id) {
        if (
            confirm("Apakah Anda yakin ingin menghapus data pengeluaran ini?")
        ) {
            router.delete(route("expenses.destroy", id));
        }
    }

    function handleSearch() {
        router.get(
            route("expenses.index"),
            { search },
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
            minimumFractionDigits: 0,
        }).format(amount);
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    }
</script>

<svelte:head>
    <title>Kas Keluar & Operasional</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Kas Keluar Pondok
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Catat dan pantau pengeluaran operasional pesantren
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
            >
                <Plus class="h-4 w-4" />
                Catat Pengeluaran
            </button>
        </div>

        <!-- Filters & Search -->
        <div
            class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-4"
        >
            <div class="relative flex-1">
                <Search
                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                />
                <input
                    type="text"
                    bind:value={search}
                    on:input={handleSearch}
                    placeholder="Cari kategori, deskripsi, atau no. bon..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                />
            </div>
        </div>

        <!-- Data Table -->
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100"
                    >
                        <tr>
                            <th class="px-6 py-4 font-bold">Tanggal</th>
                            <th class="px-6 py-4 font-bold">Kategori</th>
                            <th class="px-6 py-4 font-bold">Deskripsi</th>
                            <th class="px-6 py-4 font-bold">Nominal</th>
                            <th class="px-6 py-4 font-bold">Bukti</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each expenses.data as expense (expense.id)}
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td
                                    class="px-6 py-4 text-gray-600 whitespace-nowrap"
                                >
                                    <div class="flex items-center gap-2">
                                        <Calendar
                                            class="h-3.5 w-3.5 text-gray-400"
                                        />
                                        {formatDate(expense.expense_date)}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="h-6 w-6 rounded bg-orange-50 text-orange-600 flex items-center justify-center"
                                        >
                                            <TrendingDown class="h-3.5 w-3.5" />
                                        </div>
                                        {expense.category}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 text-gray-600 max-w-xs truncate"
                                >
                                    {expense.description || "-"}
                                    {#if expense.receipt_number}
                                        <span
                                            class="block text-xs text-gray-400 mt-0.5"
                                            >Ref: {expense.receipt_number}</span
                                        >
                                    {/if}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {formatCurrency(expense.amount)}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {#if expense.attachment}
                                        <a
                                            href={`/storage/${expense.attachment}`}
                                            target="_blank"
                                            class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline"
                                        >
                                            <FileText class="h-3 w-3" />
                                            Lihat
                                        </a>
                                    {:else}
                                        <span
                                            class="text-xs text-gray-400 italic"
                                            >Tidak ada</span
                                        >
                                    {/if}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <button
                                            on:click={() =>
                                                openEditModal(expense)}
                                            class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-blue-600 transition-colors"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </button>
                                        <button
                                            on:click={() =>
                                                deleteExpense(expense.id)}
                                            class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-red-600 transition-colors"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        {:else}
                            <tr>
                                <td
                                    colspan="6"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    Belum ada data pengeluaran yang dicatat.
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-4 border-t border-gray-100 flex items-center justify-between"
            >
                <span class="text-xs text-gray-500">
                    Menampilkan {expenses.from || 0} - {expenses.to || 0} dari {expenses.total ||
                        0} data
                </span>
                <div class="flex gap-2">
                    {#each expenses.links as link}
                        {#if link.url}
                            <Link
                                href={link.url}
                                class="px-3 py-1 text-xs rounded-lg {link.active
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-50 text-gray-600 hover:bg-gray-100'}"
                            >
                                {@html link.label}
                            </Link>
                        {:else}
                            <span
                                class="px-3 py-1 text-xs rounded-lg text-gray-300"
                            >
                                {@html link.label}
                            </span>
                        {/if}
                    {/each}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
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
                        {isEditing
                            ? "Edit Pengeluaran"
                            : "Catat Pengeluaran Baru"}
                    </h3>
                    <button
                        on:click={closeModal}
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <form on:submit|preventDefault={submit} class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Tanggal</label
                            >
                            <input
                                type="date"
                                bind:value={$form.expense_date}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Kategori</label
                            >
                            <input
                                type="text"
                                bind:value={$form.category}
                                placeholder="e.g. Listrik, Dapur"
                                list="expense_categories"
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                            <datalist id="expense_categories">
                                <option value="Operasional Dapur"></option>
                                <option value="Listrik & Air"></option>
                                <option value="Gaji Pegawai"></option>
                                <option value="Perlengkapan Kebersihan"
                                ></option>
                                <option value="Pemeliharaan Gedung"></option>
                            </datalist>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nominal (Rp)</label
                        >
                        <input
                            type="number"
                            bind:value={$form.amount}
                            min="0"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 font-bold text-lg"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Deskripsi</label
                        >
                        <textarea
                            bind:value={$form.description}
                            rows="2"
                            placeholder="Keterangan detail pengeluaran..."
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >No. Bon / Resi</label
                            >
                            <input
                                type="text"
                                bind:value={$form.receipt_number}
                                placeholder="Optional"
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Bukti Foto / Dokumen</label
                            >
                            <input
                                type="file"
                                on:change={(e) =>
                                    ($form.attachment = e.target.files[0])}
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            />
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button
                            type="button"
                            on:click={closeModal}
                            class="px-5 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 font-medium transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            disabled={$form.processing}
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-lg shadow-blue-200 transition-colors flex items-center gap-2 disabled:opacity-50"
                        >
                            <Save class="h-4 w-4" />
                            {isEditing ? "Perbarui Data" : "Simpan Pengeluaran"}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    {/if}
</AppLayout>
