<script>
    import { Link, router } from "@inertiajs/svelte";
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        Search,
        Plus,
        Pencil,
        Trash2,
        X,
        Save,
        Wallet,
    } from "lucide-svelte";

    export let categories = { data: [], links: [] };
    export let filters = { search: "" };

    let search = filters.search;
    let isModalOpen = false;
    let isEditing = false;
    let form = {
        id: null,
        name: "",
        default_amount: 0,
        type: "monthly",
        description: "",
    };

    function openCreateModal() {
        isEditing = false;
        form = {
            id: null,
            name: "",
            default_amount: 0,
            type: "monthly",
            description: "",
        };
        isModalOpen = true;
    }

    function openEditModal(category) {
        isEditing = true;
        form = {
            id: category.id,
            name: category.name,
            default_amount: category.default_amount,
            type: category.type,
            description: category.description,
        };
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
    }

    function submit() {
        if (isEditing) {
            router.put(route("bill-categories.update", form.id), form, {
                onSuccess: () => closeModal(),
            });
        } else {
            router.post(route("bill-categories.store"), form, {
                onSuccess: () => closeModal(),
            });
        }
    }

    function deleteCategory(id) {
        if (
            confirm("Apakah Anda yakin ingin menghapus kategori tagihan ini?")
        ) {
            router.delete(route("bill-categories.destroy", id));
        }
    }

    function handleSearch() {
        router.get(
            route("bill-categories.index"),
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
</script>

<svelte:head>
    <title>Kategori Tagihan</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Kategori Tagihan
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola jenis-jenis tagihan santri (SPP, Daftar Ulang, dll)
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
            >
                <Plus class="h-4 w-4" />
                Tambah Kategori
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
                    placeholder="Cari nama kategori..."
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
                            <th class="px-6 py-4 font-bold">Nama Kategori</th>
                            <th class="px-6 py-4 font-bold">Tipe Pembayaran</th>
                            <th class="px-6 py-4 font-bold">Nominal Standar</th>
                            <th class="px-6 py-4 font-bold">Keterangan</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each categories.data as category (category.id)}
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"
                                        >
                                            <Wallet class="h-4 w-4" />
                                        </div>
                                        {category.name}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span
                                        class="px-2 py-1 rounded-md text-xs font-medium {category.type ===
                                        'monthly'
                                            ? 'bg-purple-50 text-purple-700'
                                            : 'bg-orange-50 text-orange-700'}"
                                    >
                                        {category.type === "monthly"
                                            ? "Bulanan"
                                            : "Sekali Bayar"}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {formatCurrency(category.default_amount)}
                                </td>
                                <td
                                    class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate"
                                >
                                    {category.description || "-"}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <button
                                            on:click={() =>
                                                openEditModal(category)}
                                            class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-blue-600 transition-colors"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </button>
                                        <button
                                            on:click={() =>
                                                deleteCategory(category.id)}
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
                                    colspan="5"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    Belum ada data kategori tagihan.
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
                    Menampilkan {categories.from || 0} - {categories.to || 0} dari
                    {categories.total || 0} data
                </span>
                <div class="flex gap-2">
                    {#each categories.links as link}
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
                            ? "Edit Kategori Tagihan"
                            : "Tambah Kategori Baru"}
                    </h3>
                    <button
                        on:click={closeModal}
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <form on:submit|preventDefault={submit} class="p-6 space-y-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama Kategori</label
                        >
                        <input
                            type="text"
                            bind:value={form.name}
                            placeholder="Contoh: SPP Bulanan"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Tipe Pembayaran</label
                        >
                        <select
                            bind:value={form.type}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="monthly">Bulanan (Berulang)</option>
                            <option value="one_time"
                                >Sekali Bayar (Insidental)</option
                            >
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nominal Standar (Rp)</label
                        >
                        <input
                            type="number"
                            bind:value={form.default_amount}
                            min="0"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                        <p class="text-xs text-gray-400 mt-1">
                            Nominal ini akan menjadi default saat membuat
                            tagihan massal.
                        </p>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Keterangan (Opsional)</label
                        >
                        <textarea
                            bind:value={form.description}
                            rows="3"
                            placeholder="Tambahkan catatan..."
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        ></textarea>
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
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-lg shadow-blue-200 transition-colors flex items-center gap-2"
                        >
                            <Save class="h-4 w-4" />
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    {/if}
</AppLayout>
