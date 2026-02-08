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
        ShieldCheck,
        Phone,
        Mail,
    } from "lucide-svelte";

    export let teachers = { data: [], links: [] };
    export let filters = { search: "" };

    let search = filters.search;
    let isModalOpen = false;
    let isEditing = false;
    let form = {
        id: null,
        name: "",
        email: "",
        phone_number: "",
        role: "ustadz",
        password: "",
    };

    function openCreateModal() {
        isEditing = false;
        form = {
            id: null,
            name: "",
            email: "",
            phone_number: "",
            role: "ustadz",
            password: "",
        };
        isModalOpen = true;
    }

    function openEditModal(teacher) {
        isEditing = true;
        form = {
            id: teacher.id,
            name: teacher.name,
            email: teacher.email,
            phone_number: teacher.phone_number,
            role: teacher.role,
            password: "",
        };
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
    }

    function submit() {
        if (isEditing) {
            router.put(route("teachers.update", form.id), form, {
                onSuccess: () => closeModal(),
            });
        } else {
            router.post(route("teachers.store"), form, {
                onSuccess: () => closeModal(),
            });
        }
    }

    function deleteTeacher(id) {
        if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
            router.delete(route("teachers.destroy", id));
        }
    }

    function handleSearch() {
        router.get(
            route("teachers.index"),
            { search },
            {
                preserveState: true,
                replace: true,
            },
        );
    }
</script>

<svelte:head>
    <title>Direktori Ustadz & Pegawai</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Direktori SDM
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data ustadz, pengurus asrama, dan staf administrasi
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
            >
                <Plus class="h-4 w-4" />
                Tambah Pegawai
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
                    placeholder="Cari nama atau email..."
                    class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                />
            </div>
        </div>

        <!-- Grid Layout for Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {#each teachers.data as teacher (teacher.id)}
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col gap-4"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            {#if teacher.profile_photo_url}
                                <img
                                    src={teacher.profile_photo_url}
                                    alt={teacher.name}
                                    class="h-12 w-12 rounded-full object-cover border border-gray-100"
                                />
                            {:else}
                                <div
                                    class="h-12 w-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold border border-blue-100"
                                >
                                    {teacher.name.charAt(0)}
                                </div>
                            {/if}
                            <div>
                                <h3
                                    class="font-bold text-gray-900 line-clamp-1"
                                >
                                    {teacher.name}
                                </h3>
                                <p class="text-xs text-gray-500">
                                    {teacher.email}
                                </p>
                            </div>
                        </div>
                        <span
                            class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-lg {teacher.role ===
                            'admin_pondok'
                                ? 'bg-purple-50 text-purple-700'
                                : 'bg-emerald-50 text-emerald-700'}"
                        >
                            {teacher.role === "admin_pondok"
                                ? "Admin"
                                : "Ustadz"}
                        </span>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-gray-50">
                        <div
                            class="flex items-center gap-2 text-sm text-gray-600"
                        >
                            <Phone class="h-3.5 w-3.5 text-gray-400" />
                            {teacher.phone_number || "-"}
                        </div>
                        <div
                            class="flex items-center gap-2 text-sm text-gray-600"
                        >
                            <ShieldCheck class="h-3.5 w-3.5 text-gray-400" />
                            <span class="capitalize"
                                >{teacher.role.replace("_", " ")}</span
                            >
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-auto pt-4">
                        <button
                            on:click={() => openEditModal(teacher)}
                            class="flex-1 py-2 text-xs font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors flex items-center justify-center gap-2"
                        >
                            <Pencil class="h-3.5 w-3.5" />
                            Edit
                        </button>
                        <button
                            on:click={() => deleteTeacher(teacher.id)}
                            class="flex-1 py-2 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors flex items-center justify-center gap-2"
                        >
                            <Trash2 class="h-3.5 w-3.5" />
                            Hapus
                        </button>
                    </div>
                </div>
            {:else}
                <div
                    class="col-span-full py-12 text-center text-gray-500 italic"
                >
                    Belum ada data pegawai yang ditemukan.
                </div>
            {/each}
        </div>

        <!-- Pagination -->
        {#if teachers.data.length > 0}
            <div class="flex justify-center mt-8">
                <div class="flex gap-2">
                    {#each teachers.links as link}
                        {#if link.url}
                            <Link
                                href={link.url}
                                class="px-3 py-1 text-xs rounded-lg {link.active
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'}"
                            >
                                {@html link.label}
                            </Link>
                        {:else}
                            <span
                                class="px-3 py-1 text-xs rounded-lg text-gray-300 bg-white border border-gray-100"
                            >
                                {@html link.label}
                            </span>
                        {/if}
                    {/each}
                </div>
            </div>
        {/if}
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
                            ? "Edit Data Pegawai"
                            : "Tambah Pegawai Baru"}
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
                            >Nama Lengkap</label
                        >
                        <input
                            type="text"
                            bind:value={form.name}
                            placeholder="Nama Lengkap"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Alamat Email</label
                        >
                        <input
                            type="email"
                            bind:value={form.email}
                            placeholder="email@pesantren.com"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nomor WhatsApp</label
                        >
                        <input
                            type="text"
                            bind:value={form.phone_number}
                            placeholder="0812..."
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Peran / Jabatan</label
                        >
                        <select
                            bind:value={form.role}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="ustadz">Ustadz / Pengajar</option>
                            <option value="admin_pondok">Admin Pondok</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                        >
                            {isEditing
                                ? "Password Baru (Opsional)"
                                : "Password"}
                        </label>
                        <input
                            type="password"
                            bind:value={form.password}
                            placeholder={isEditing
                                ? "Kosongkan jika tidak ingin mengubah"
                                : "Minimal 8 karakter"}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            {...!isEditing ? { required: true } : {}}
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
