<script>
    import { Link, router, useForm } from "@inertiajs/svelte";
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        Search,
        Plus,
        Trash2,
        X,
        Save,
        BookOpen,
        Star,
    } from "lucide-svelte";

    export let progress = { data: [], links: [] };
    export let students = [];
    export let filters = { student_id: "" };

    let isModalOpen = false;
    let selectedStudent = filters.student_id;

    const form = useForm({
        student_id: "",
        surah_name: "",
        start_verse: "",
        end_verse: "",
        type: "ziyadah",
        quality: "A",
        recorded_at: new Date().toISOString().split("T")[0],
        note: "",
    });

    function openCreateModal() {
        form.reset();
        form.recorded_at = new Date().toISOString().split("T")[0];
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
        form.reset();
    }

    function submit() {
        form.post(route("tahfidz.store"), {
            onSuccess: () => closeModal(),
        });
    }

    function deleteProgress(id) {
        if (confirm("Hapus catatan tahfidz ini?")) {
            router.delete(route("tahfidz.destroy", id));
        }
    }

    function handleFilter() {
        router.get(
            route("tahfidz.index"),
            { student_id: selectedStudent },
            {
                preserveState: true,
                replace: true,
            },
        );
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString("id-ID", {
            day: "numeric",
            month: "short",
            year: "numeric",
        });
    }

    function getQualityColor(quality) {
        switch (quality) {
            case "A":
                return "bg-emerald-100 text-emerald-700";
            case "B":
                return "bg-blue-100 text-blue-700";
            case "C":
                return "bg-yellow-100 text-yellow-700";
            case "D":
                return "bg-red-100 text-red-700";
            default:
                return "bg-gray-100 text-gray-700";
        }
    }
</script>

<svelte:head>
    <title>Progress Tahfidz</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Progress Tahfidz
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Catat setoran hafalan (Ziyadah & Murajaah)
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-emerald-200"
            >
                <Plus class="h-4 w-4" />
                Catat Setoran
            </button>
        </div>

        <!-- Filters Section -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div class="max-w-md">
                <label class="block text-xs font-medium text-gray-500 mb-1"
                    >Filter Santri</label
                >
                <select
                    bind:value={selectedStudent}
                    on:change={handleFilter}
                    class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
                    <option value="">Semua Santri</option>
                    {#each students as student}
                        <option value={student.id}>{student.name}</option>
                    {/each}
                </select>
            </div>
        </div>

        <!-- Data List -->
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
                            <th class="px-6 py-4 font-bold">Jenis</th>
                            <th class="px-6 py-4 font-bold">Hafalan</th>
                            <th class="px-6 py-4 font-bold text-center"
                                >Nilai</th
                            >
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each progress.data as item (item.id)}
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td
                                    class="px-6 py-4 text-gray-600 whitespace-nowrap"
                                >
                                    {formatDate(item.recorded_at)}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {item.student.name}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 rounded-md text-xs font-bold uppercase tracking-wider {item.type ===
                                        'ziyadah'
                                            ? 'bg-purple-50 text-purple-700 border border-purple-100'
                                            : 'bg-orange-50 text-orange-700 border border-orange-100'}"
                                    >
                                        {item.type}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <BookOpen
                                            class="h-4 w-4 text-gray-400"
                                        />
                                        <span class="font-medium"
                                            >{item.surah_name}</span
                                        >
                                        {#if item.start_verse}
                                            <span class="text-xs text-gray-500"
                                                >Ayat {item.start_verse}-{item.end_verse}</span
                                            >
                                        {/if}
                                    </div>
                                    {#if item.note}
                                        <p
                                            class="text-xs text-gray-400 mt-1 italic"
                                        >
                                            "{item.note}"
                                        </p>
                                    {/if}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full font-black text-sm {getQualityColor(
                                            item.quality,
                                        )}"
                                    >
                                        {item.quality}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        on:click={() => deleteProgress(item.id)}
                                        class="p-2 hover:bg-gray-100 rounded-lg text-gray-400 hover:text-red-600 transition-colors"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </td>
                            </tr>
                        {:else}
                            <tr>
                                <td
                                    colspan="6"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    Belum ada data setoran hafalan via sistem.
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
                    Menampilkan {progress.from || 0} - {progress.to || 0} dari {progress.total ||
                        0} data
                </span>
                <div class="flex gap-2">
                    {#each progress.links as link}
                        {#if link.url}
                            <Link
                                href={link.url}
                                class="px-3 py-1 text-xs rounded-lg {link.active
                                    ? 'bg-emerald-600 text-white'
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
                        Catat Setoran Hafalan
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
                            class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Tanggal</label
                            >
                            <input
                                type="date"
                                bind:value={$form.recorded_at}
                                class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Jenis Setoran</label
                            >
                            <select
                                bind:value={$form.type}
                                class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                            >
                                <option value="ziyadah">Ziyadah (Baru)</option>
                                <option value="murajaah"
                                    >Murajaah (Ulang)</option
                                >
                            </select>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama Surah</label
                        >
                        <input
                            type="text"
                            bind:value={$form.surah_name}
                            placeholder="e.g. Al-Baqarah"
                            class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Ayat Mulai</label
                            >
                            <input
                                type="number"
                                bind:value={$form.start_verse}
                                placeholder="1"
                                class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Ayat Selesai</label
                            >
                            <input
                                type="number"
                                bind:value={$form.end_verse}
                                placeholder="10"
                                class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
                            />
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Kualitas Hafalan (Nilai)</label
                        >
                        <div class="flex gap-4">
                            {#each ["A", "B", "C", "D"] as grade}
                                <label class="flex-1 cursor-pointer">
                                    <input
                                        type="radio"
                                        bind:group={$form.quality}
                                        value={grade}
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="text-center py-3 rounded-xl border border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 font-bold transition-all"
                                    >
                                        {grade}
                                    </div>
                                </label>
                            {/each}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Catatan Ustadz (Opsional)</label
                        >
                        <textarea
                            bind:value={$form.note}
                            rows="2"
                            placeholder="Komentar tentang kelancaran..."
                            class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500"
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
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium shadow-lg shadow-emerald-200 flex items-center gap-2"
                        >
                            <Save class="h-4 w-4" />
                            Simpan Progress
                        </button>
                    </div>
                </form>
            </div>
        </div>
    {/if}
</AppLayout>
