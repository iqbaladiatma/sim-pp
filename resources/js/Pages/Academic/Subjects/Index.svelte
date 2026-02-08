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
        BookOpen,
        Code as CodeIcon,
    } from "lucide-svelte";

    export let subjects = { data: [], links: [] };
    export let filters = { search: "" };

    let search = filters.search;
    let isModalOpen = false;
    let isEditing = false;
    let editingId = null;

    const form = useForm({
        name: "",
        code: "",
        description: "",
    });

    function openCreateModal() {
        isEditing = false;
        editingId = null;
        form.reset();
        isModalOpen = true;
    }

    function openEditModal(subject) {
        isEditing = true;
        editingId = subject.id;
        form.name = subject.name;
        form.code = subject.code;
        form.description = subject.description || "";
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
        form.reset();
    }

    function submit() {
        if (isEditing) {
            form.put(route("subjects.update", editingId), {
                onSuccess: () => closeModal(),
            });
        } else {
            form.post(route("subjects.store"), {
                onSuccess: () => closeModal(),
            });
        }
    }

    function deleteSubject(id) {
        if (
            confirm(
                "Apakah Anda yakin ingin menghapus data mata pelajaran ini?",
            )
        ) {
            router.delete(route("subjects.destroy", id));
        }
    }

    function handleSearch() {
        router.get(
            route("subjects.index"),
            { search },
            {
                preserveState: true,
                replace: true,
            },
        );
    }
</script>

<svelte:head>
    <title>Mata Pelajaran</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Mata Pelajaran
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Daftar mata pelajaran yang diajarkan
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
            >
                <Plus class="h-4 w-4" />
                Tambah Mapel
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
                    placeholder="Cari nama atau kode mapel..."
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
                            <th class="px-6 py-4 font-bold">Kode</th>
                            <th class="px-6 py-4 font-bold"
                                >Nama Mata Pelajaran</th
                            >
                            <th class="px-6 py-4 font-bold">Deskripsi</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each subjects.data as subject (subject.id)}
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td
                                    class="px-6 py-4 font-mono text-xs font-bold text-blue-600 bg-blue-50/30"
                                >
                                    {subject.code}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center"
                                        >
                                            <BookOpen class="h-4 w-4" />
                                        </div>
                                        {subject.name}
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 text-gray-600 text-xs max-w-sm truncate"
                                >
                                    {subject.description || "-"}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <button
                                            on:click={() =>
                                                openEditModal(subject)}
                                            class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-blue-600 transition-colors"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </button>
                                        <button
                                            on:click={() =>
                                                deleteSubject(subject.id)}
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
                                    colspan="4"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    Belum ada data mata pelajaran.
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
                    Menampilkan {subjects.from || 0} - {subjects.to || 0} dari {subjects.total ||
                        0} data
                </span>
                <div class="flex gap-2">
                    {#each subjects.links as link}
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
                class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200"
            >
                <div
                    class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50"
                >
                    <h3 class="text-lg font-bold text-gray-900">
                        {isEditing
                            ? "Edit Mata Pelajaran"
                            : "Tambah Mata Pelajaran"}
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
                            >Kode Mapel</label
                        >
                        <input
                            type="text"
                            bind:value={$form.code}
                            placeholder="MAT01"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 font-mono uppercase"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama Mata Pelajaran</label
                        >
                        <input
                            type="text"
                            bind:value={$form.name}
                            placeholder="Matematika"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Deskripsi (Opsional)</label
                        >
                        <textarea
                            bind:value={$form.description}
                            rows="2"
                            placeholder="Keterangan singkat..."
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
                            disabled={$form.processing}
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
