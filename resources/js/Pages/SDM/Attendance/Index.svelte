<script>
    import { Link, router, useForm } from "@inertiajs/svelte";
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        Calendar,
        Save,
        CheckCircle2,
        XCircle,
        Clock,
        AlertCircle,
    } from "lucide-svelte";

    export let teachers = [];
    export let date;
    export let filters = { date: "" };

    let selectedDate =
        filters.date || date || new Date().toISOString().split("T")[0];

    const form = useForm({
        date: selectedDate,
        attendance: [],
    });

    $: if (teachers) {
        form.attendance = teachers.map((teacher) => ({
            teacher_id: teacher.id,
            status: teacher.attendance_status || "hadir",
            check_in: teacher.check_in || "",
            check_out: teacher.check_out || "",
        }));
    }

    function handleDateChange() {
        router.get(
            route("teacher-attendance.index"),
            { date: selectedDate },
            {
                preserveState: true,
                replace: true,
            },
        );
    }

    function submit() {
        form.date = selectedDate;
        form.post(route("teacher-attendance.store"), {
            preserveScroll: true,
            onSuccess: () => {
                // Flash message handled by layout
            },
        });
    }

    function setAllStatus(status) {
        form.attendance = form.attendance.map((item) => ({ ...item, status }));
    }

    function getStatusColor(status) {
        switch (status) {
            case "hadir":
                return "bg-emerald-100 text-emerald-700 border-emerald-200";
            case "sakit":
                return "bg-blue-100 text-blue-700 border-blue-200";
            case "izin":
                return "bg-yellow-100 text-yellow-700 border-yellow-200";
            case "alpha":
                return "bg-red-100 text-red-700 border-red-200";
            default:
                return "bg-gray-100 text-gray-700 border-gray-200";
        }
    }
</script>

<svelte:head>
    <title>Presensi Guru</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Presensi Guru & Staf
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data kehadiran harian pegawai
                </p>
            </div>

            <button
                on:click={submit}
                disabled={form.processing}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl font-medium transition-colors shadow-lg shadow-gray-200"
            >
                <Save class="h-4 w-4" />
                Simpan Perubahan
            </button>
        </div>

        <!-- Filter & Actions -->
        <div
            class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4"
        >
            <div class="flex items-center gap-2 w-full md:w-auto">
                <Calendar class="h-5 w-5 text-gray-400" />
                <input
                    type="date"
                    bind:value={selectedDate}
                    on:change={handleDateChange}
                    class="rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm w-full md:w-auto"
                />
            </div>

            <div class="flex gap-2">
                <button
                    on:click={() => setAllStatus("hadir")}
                    class="px-3 py-1.5 text-xs font-medium bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition-colors"
                    >Semua Hadir</button
                >
                <button
                    on:click={() => setAllStatus("sakit")}
                    class="px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors"
                    >Semua Sakit</button
                >
                <button
                    on:click={() => setAllStatus("izin")}
                    class="px-3 py-1.5 text-xs font-medium bg-yellow-50 text-yellow-700 rounded-lg hover:bg-yellow-100 transition-colors"
                    >Semua Izin</button
                >
            </div>
        </div>

        <!-- Attendance List -->
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100"
                    >
                        <tr>
                            <th class="px-6 py-4 font-bold w-12 text-center"
                                >No</th
                            >
                            <th class="px-6 py-4 font-bold">Nama Guru</th>
                            <th class="px-6 py-4 font-bold text-center"
                                >Status Kehadiran</th
                            >
                            <th class="px-6 py-4 font-bold text-center"
                                >Jam Masuk</th
                            >
                            <th class="px-6 py-4 font-bold text-center"
                                >Jam Keluar</th
                            >
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each teachers as teacher, index (teacher.id)}
                            <tr
                                class="hover:bg-gray-50/50 transition-colors group"
                            >
                                <td
                                    class="px-6 py-4 text-center text-gray-400 font-medium"
                                    >{index + 1}</td
                                >
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">
                                        {teacher.user.name}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {teacher.nip || "-"}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-1">
                                        {#each ["hadir", "sakit", "izin", "alpha"] as s}
                                            <button
                                                type="button"
                                                on:click={() => {
                                                    form.attendance[
                                                        index
                                                    ].status = s;
                                                }}
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase transition-all border {form
                                                    .attendance[index]
                                                    .status === s
                                                    ? getStatusColor(s)
                                                    : 'bg-white text-gray-400 border-gray-200 hover:border-gray-300'}"
                                            >
                                                {s}
                                            </button>
                                        {/each}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input
                                        type="time"
                                        bind:value={
                                            form.attendance[index].check_in
                                        }
                                        class="p-1 text-center text-sm rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 w-24"
                                    />
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input
                                        type="time"
                                        bind:value={
                                            form.attendance[index].check_out
                                        }
                                        class="p-1 text-center text-sm rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 w-24"
                                    />
                                </td>
                            </tr>
                        {:else}
                            <tr>
                                <td
                                    colspan="5"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    Data guru tidak ditemukan. Silakan tambahkan
                                    data guru terlebih dahulu.
                                </td>
                            </tr>
                        {/each}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</AppLayout>
