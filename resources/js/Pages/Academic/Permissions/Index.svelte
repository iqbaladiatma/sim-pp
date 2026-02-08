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
        FileText,
        CheckCircle,
        XCircle,
    } from "lucide-svelte";

    export let permissions = { data: [], links: [] };
    export let students = [];
    export let filters = { status: "" };

    let isModalOpen = false;
    let isEditing = false;
    let editingId = null;

    const form = useForm({
        student_id: "",
        type: "sakit",
        reason: "",
        start_date: new Date().toISOString().split("T")[0],
        end_date: "",
        status: "pending", // For internal reference, usually set by admin
    });

    const statusForm = useForm({
        status: "approved",
        note: "",
    });

    let isStatusModalOpen = false;
    let selectedPermission = null;

    function openCreateModal() {
        isEditing = false;
        editingId = null;
        form.reset();
        form.start_date = new Date().toISOString().split("T")[0];
        isModalOpen = true;
    }

    function openStatusModal(permission) {
        selectedPermission = permission;
        statusForm.status =
            permission.status === "pending" ? "approved" : permission.status;
        statusForm.note = permission.note || "";
        isStatusModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
        isStatusModalOpen = false;
        form.reset();
    }

    function submit() {
        if (isEditing) {
            // Not implemented for simplicity, usually permissions are just created or status updated
        } else {
            form.post(route("permissions.store"), {
                onSuccess: () => closeModal(),
            });
        }
    }

    function submitStatus() {
        statusForm.put(route("permissions.update", selectedPermission.id), {
            onSuccess: () => closeModal(),
        });
    }

    function deletePermission(id) {
        if (confirm("Hapus data perizinan ini?")) {
            router.delete(route("permissions.destroy", id));
        }
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
        });
    }

    function getStatusBadge(status) {
        switch (status) {
            case "approved":
                return "bg-green-100 text-green-800";
            case "rejected":
                return "bg-red-100 text-red-800";
            case "pending":
                return "bg-yellow-100 text-yellow-800";
            case "returned":
                return "bg-blue-100 text-blue-800";
            default:
                return "bg-gray-100 text-gray-800";
        }
    }

    function getStatusLabel(status) {
        switch (status) {
            case "approved":
                return "Disetujui";
            case "rejected":
                return "Ditolak";
            case "pending":
                return "Menunggu";
            case "returned":
                return "Kembali";
            default:
                return status;
        }
    }
</script>

<svelte:head>
    <title>Perizinan Santri</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Perizinan Santri
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola izin sakit, pulang, dan keperluan khusus
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
            >
                <Plus class="h-4 w-4" />
                Buat Izin Baru
            </button>
        </div>

        <!-- Filters (Simple Tabs) -->
        <div class="flex gap-2 overflow-x-auto pb-2">
            <button
                on:click={() => router.visit(route("permissions.index"))}
                class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {filters.status ===
                ''
                    ? 'bg-blue-50 text-blue-700'
                    : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-100'}"
            >
                Semua
            </button>
            <button
                on:click={() =>
                    router.visit(
                        route("permissions.index", { status: "pending" }),
                    )}
                class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {filters.status ===
                'pending'
                    ? 'bg-yellow-50 text-yellow-700'
                    : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-100'}"
            >
                Menunggu
            </button>
            <button
                on:click={() =>
                    router.visit(
                        route("permissions.index", { status: "approved" }),
                    )}
                class="px-4 py-2 rounded-xl text-sm font-medium transition-colors {filters.status ===
                'approved'
                    ? 'bg-green-50 text-green-700'
                    : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-100'}"
            >
                Disetujui
            </button>
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
                            <th class="px-6 py-4 font-bold">Santri</th>
                            <th class="px-6 py-4 font-bold">Jenis Izin</th>
                            <th class="px-6 py-4 font-bold">Tanggal</th>
                            <th class="px-6 py-4 font-bold">Alasan</th>
                            <th class="px-6 py-4 font-bold">Status</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each permissions.data as permission (permission.id)}
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {permission.student.name}
                                </td>
                                <td
                                    class="px-6 py-4 text-gray-600 uppercase text-xs font-bold tracking-wider"
                                >
                                    {permission.type.replace("_", " ")}
                                </td>
                                <td
                                    class="px-6 py-4 text-gray-600 whitespace-nowrap"
                                >
                                    {formatDate(permission.start_date)}
                                    {#if permission.end_date}
                                        - {formatDate(permission.end_date)}
                                    {/if}
                                </td>
                                <td
                                    class="px-6 py-4 text-gray-600 max-w-xs truncate"
                                >
                                    {permission.reason}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-bold {getStatusBadge(
                                            permission.status,
                                        )}"
                                    >
                                        {getStatusLabel(permission.status)}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <button
                                            on:click={() =>
                                                openStatusModal(permission)}
                                            class="p-2 hover:bg-gray-100 rounded-lg text-blue-600 hover:text-blue-700 transition-colors"
                                            title="Update Status"
                                        >
                                            <CheckCircle class="h-4 w-4" />
                                        </button>
                                        <button
                                            on:click={() =>
                                                deletePermission(permission.id)}
                                            class="p-2 hover:bg-gray-100 rounded-lg text-gray-400 hover:text-red-600 transition-colors"
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
                                    Belum ada data perizinan.
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
                    Menampilkan {permissions.from || 0} - {permissions.to || 0} dari
                    {permissions.total || 0} data
                </span>
                <div class="flex gap-2">
                    {#each permissions.links as link}
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

    <!-- Create Modal -->
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
                        Buat Izin Baru
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
                            >Nama Santri</label
                        >
                        <select
                            bind:value={$form.student_id}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                            <option value="">-- Pilih Santri --</option>
                            {#each students as student}
                                <option value={student.id}
                                    >{student.name}</option
                                >
                            {/each}
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Jenis Izin</label
                        >
                        <select
                            bind:value={$form.type}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="sakit">Sakit</option>
                            <option value="pulang">Pulang / Sambangan</option>
                            <option value="izin_khusus"
                                >Izin Khusus / Lainnya</option
                            >
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Dari Tanggal</label
                            >
                            <input
                                type="date"
                                bind:value={$form.start_date}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Sampai (Opsional)</label
                            >
                            <input
                                type="date"
                                bind:value={$form.end_date}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Alasan</label
                        >
                        <textarea
                            bind:value={$form.reason}
                            rows="2"
                            placeholder="Jelaskan alasan izin..."
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
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
                            >Simpan Permohonan</button
                        >
                    </div>
                </form>
            </div>
        </div>
    {/if}

    <!-- Status Update Modal -->
    {#if isStatusModalOpen}
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
        >
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in duration-200"
            >
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">
                        Update Status Izin
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {selectedPermission?.student?.name}
                    </p>
                </div>

                <form
                    on:submit|preventDefault={submitStatus}
                    class="p-6 space-y-4"
                >
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Status</label
                        >
                        <select
                            bind:value={$statusForm.status}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="pending">Menunggu</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                            <option value="returned">Sudah Kembali</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Catatan (Opsional)</label
                        >
                        <textarea
                            bind:value={$statusForm.note}
                            rows="2"
                            placeholder="Catatan persetujuan/penolakan..."
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        ></textarea>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button
                            type="button"
                            on:click={closeModal}
                            class="px-5 py-2 rounded-xl text-gray-600 hover:bg-gray-100 font-medium text-sm"
                            >Batal</button
                        >
                        <button
                            type="submit"
                            disabled={$statusForm.processing}
                            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm shadow-lg shadow-blue-200"
                            >Update</button
                        >
                    </div>
                </form>
            </div>
        </div>
    {/if}
</AppLayout>
