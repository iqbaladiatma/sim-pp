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
        AlertTriangle,
        Scale,
        Calendar,
    } from "lucide-svelte";

    export let violations = { data: [], links: [] };
    export let students = [];
    export let filters = { search: "" };

    let search = filters.search;
    let isModalOpen = false;
    let isEditing = false;
    let editingId = null;

    const form = useForm({
        student_id: "",
        type: "Ringan",
        incident_date: new Date().toISOString().split("T")[0],
        points_deduction: 5,
        description: "",
        action_taken: "",
    });

    function openCreateModal() {
        isEditing = false;
        editingId = null;
        form.reset();
        form.incident_date = new Date().toISOString().split("T")[0];
        isModalOpen = true;
    }

    function openEditModal(violation) {
        isEditing = true;
        editingId = violation.id;
        form.student_id = violation.student_id;
        form.type = violation.type;
        form.incident_date = violation.incident_date;
        form.points_deduction = violation.points_deduction;
        form.description = violation.description;
        form.action_taken = violation.action_taken || "";
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
        form.reset();
    }

    function submit() {
        if (isEditing) {
            form.put(route("violations.update", editingId), {
                onSuccess: () => closeModal(),
            });
        } else {
            form.post(route("violations.store"), {
                onSuccess: () => closeModal(),
            });
        }
    }

    function deleteViolation(id) {
        if (
            confirm(
                "Apakah Anda yakin ingin menghapus data pelanggaran ini? Poin santri akan dikembalikan.",
            )
        ) {
            router.delete(route("violations.destroy", id));
        }
    }

    function handleSearch() {
        router.get(
            route("violations.index"),
            { search },
            {
                preserveState: true,
                replace: true,
            },
        );
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    }

    function getViolationColor(type) {
        switch (type) {
            case "Ringan":
                return "bg-yellow-50 text-yellow-700 border-yellow-200";
            case "Sedang":
                return "bg-orange-50 text-orange-700 border-orange-200";
            case "Berat":
                return "bg-red-50 text-red-700 border-red-200";
            default:
                return "bg-gray-50 text-gray-700";
        }
    }
</script>

<svelte:head>
    <title>Poin Pelanggaran</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Catatan Pelanggaran
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Monitor kedisiplinan dan poin pelanggaran santri
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-red-200"
            >
                <Plus class="h-4 w-4" />
                Catat Pelanggaran
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
                    placeholder="Cari nama santri..."
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
                            <th class="px-6 py-4 font-bold">Nama Santri</th>
                            <th class="px-6 py-4 font-bold"
                                >Jenis Pelanggaran</th
                            >
                            <th class="px-6 py-4 font-bold">Deskripsi</th>
                            <th class="px-6 py-4 font-bold text-center"
                                >Poin (-)</th
                            >
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each violations.data as violation (violation.id)}
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td
                                    class="px-6 py-4 text-gray-600 whitespace-nowrap"
                                >
                                    <div class="flex items-center gap-2">
                                        <Calendar
                                            class="h-3.5 w-3.5 text-gray-400"
                                        />
                                        {formatDate(violation.incident_date)}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {violation.student.name}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-md text-xs font-bold border {getViolationColor(
                                            violation.type,
                                        )}"
                                    >
                                        {violation.type}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 text-gray-600 max-w-xs truncate"
                                >
                                    {violation.description}
                                    {#if violation.action_taken}
                                        <span
                                            class="block text-xs text-blue-600 mt-1"
                                            >Tindakan: {violation.action_taken}</span
                                        >
                                    {/if}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold"
                                    >
                                        -{violation.points_deduction}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <button
                                            on:click={() =>
                                                openEditModal(violation)}
                                            class="p-2 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-blue-600 transition-colors"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </button>
                                        <button
                                            on:click={() =>
                                                deleteViolation(violation.id)}
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
                                    Belum ada catatan pelanggaran.
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
                    Menampilkan {violations.from || 0} - {violations.to || 0} dari
                    {violations.total || 0} data
                </span>
                <div class="flex gap-2">
                    {#each violations.links as link}
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
                            ? "Edit Data Pelanggaran"
                            : "Catat Pelanggaran Baru"}
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
                        <div class="col-span-2">
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
                                >Tanggal Kejadian</label
                            >
                            <input
                                type="date"
                                bind:value={$form.incident_date}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Kategori</label
                            >
                            <select
                                bind:value={$form.type}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="Ringan"
                                    >Ringan (Telat, Tidak Rapi)</option
                                >
                                <option value="Sedang"
                                    >Sedang (Bolos, Gaduh)</option
                                >
                                <option value="Berat"
                                    >Berat (Berkelahi, Mencuri)</option
                                >
                            </select>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Pengurangan Poin</label
                        >
                        <input
                            type="number"
                            bind:value={$form.points_deduction}
                            min="0"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 font-bold text-red-600"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Deskripsi Pelanggaran</label
                        >
                        <textarea
                            bind:value={$form.description}
                            rows="2"
                            placeholder="Kronologi kejadian..."
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        ></textarea>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Tindakan / Hukuman (Opsional)</label
                        >
                        <input
                            type="text"
                            bind:value={$form.action_taken}
                            placeholder="e.g. Istighfar 100x"
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
                            disabled={$form.processing}
                            class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium shadow-lg shadow-red-200 transition-colors flex items-center gap-2"
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
