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
        GraduationCap,
    } from "lucide-svelte";

    // ... imports ...

    export let classrooms = { data: [], links: [] };
    export let teachers = [];
    export let filters = { search: "" };

    let search = filters.search;
    let isModalOpen = false;
    let isEditing = false;
    let form = {
        id: null,
        name: "",
        level: "",
        academic_year: "",
        homeroom_teacher_id: "",
        capacity: 40,
    };

    function openCreateModal() {
        isEditing = false;
        form = {
            id: null,
            name: "",
            level: "",
            academic_year: "",
            homeroom_teacher_id: "",
            capacity: 40,
        };
        isModalOpen = true;
    }

    function openEditModal(classroom) {
        isEditing = true;
        form = {
            id: classroom.id,
            name: classroom.name,
            level: classroom.level,
            academic_year: classroom.academic_year,
            homeroom_teacher_id: classroom.homeroom_teacher_id,
            capacity: classroom.capacity,
        };
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
    }

    function submit() {
        if (isEditing) {
            router.put(route("classrooms.update", form.id), form, {
                onSuccess: () => closeModal(),
            });
        } else {
            router.post(route("classrooms.store"), form, {
                onSuccess: () => closeModal(),
            });
        }
    }

    function deleteClassroom(id) {
        if (confirm("Apakah Anda yakin ingin menghapus kelas ini?")) {
            router.delete(route("classrooms.destroy", id));
        }
    }

    function handleSearch() {
        router.get(
            route("classrooms.index"),
            { search },
            {
                preserveState: true,
                replace: true,
            },
        );
    }
</script>

<svelte:head>
    <title>Manajemen Kelas</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Manajemen Kelas
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data rombongan belajar dan wali kelas
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
            >
                <Plus class="h-4 w-4" />
                Tambah Kelas
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
                    placeholder="Cari nama kelas..."
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
                            <th class="px-6 py-4 font-bold">Nama Kelas</th>
                            <th class="px-6 py-4 font-bold">Tingkat</th>
                            <th class="px-6 py-4 font-bold">Tahun Ajaran</th>
                            <th class="px-6 py-4 font-bold">Wali Kelas</th>
                            <th class="px-6 py-4 font-bold text-center"
                                >Kapasitas</th
                            >
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each classrooms.data as classroom (classroom.id)}
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"
                                        >
                                            <GraduationCap class="h-4 w-4" />
                                        </div>
                                        {classroom.name}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span
                                        class="px-2 py-1 rounded-md bg-gray-100 text-xs font-medium text-gray-600"
                                    >
                                        {classroom.level || "-"}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600"
                                    >{classroom.academic_year || "-"}</td
                                >
                                <td class="px-6 py-4 text-gray-600">
                                    {#if classroom.homeroom_teacher}
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="h-6 w-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold"
                                            >
                                                {classroom.homeroom_teacher.name.charAt(
                                                    0,
                                                )}
                                            </div>
                                            <span class="text-xs font-medium"
                                                >{classroom.homeroom_teacher
                                                    .name}</span
                                            >
                                        </div>
                                    {:else}
                                        <span
                                            class="text-gray-400 italic text-xs"
                                            >Belum ditentukan</span
                                        >
                                    {/if}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold"
                                    >
                                        {classroom.capacity}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <button
                                            on:click={() =>
                                                openEditModal(classroom)}
                                            class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-blue-600 transition-colors"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </button>
                                        <button
                                            on:click={() =>
                                                deleteClassroom(classroom.id)}
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
                                    Belum ada data kelas yang ditambahkan.
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
                    Menampilkan {classrooms.from || 0} - {classrooms.to || 0} dari
                    {classrooms.total || 0} data
                </span>
                <div class="flex gap-2">
                    {#each classrooms.links as link}
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
                        {isEditing ? "Edit Data Kelas" : "Tambah Kelas Baru"}
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
                            >Nama Kelas</label
                        >
                        <input
                            type="text"
                            bind:value={form.name}
                            placeholder="Contoh: X IPA 1"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Tingkat</label
                            >
                            <select
                                bind:value={form.level}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="">Pilih Tingkat</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="MA">MA</option>
                                <option value="SMK">SMK</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Tahun Ajaran</label
                            >
                            <input
                                type="text"
                                bind:value={form.academic_year}
                                placeholder="2025/2026"
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Wali Kelas</label
                        >
                        <select
                            bind:value={form.homeroom_teacher_id}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">-- Pilih Wali Kelas --</option>
                            {#each teachers as teacher}
                                <option value={teacher.id}
                                    >{teacher.name}</option
                                >
                            {/each}
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Kapasitas Maksimal</label
                        >
                        <input
                            type="number"
                            bind:value={form.capacity}
                            min="1"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        />
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
