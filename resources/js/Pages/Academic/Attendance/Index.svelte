<script>
    import { Link, router, useForm } from "@inertiajs/svelte";
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        Users,
        Filter,
        CheckCircle,
        Save,
        Calendar,
        Search,
    } from "lucide-svelte";

    export let classrooms = [];
    export let students = [];
    export let date;
    export let classroom_id;

    let selectedClassroom = classroom_id || "";
    let selectedDate = date || new Date().toISOString().split("T")[0];

    // Store attendance state locally before submitting
    let attendanceData = {};

    // Initialize attendance data from props
    $: {
        if (students.length > 0) {
            students.forEach((student) => {
                // If attendance exists for this date, use it, otherwise default to 'h' (Hadir)
                const existing = student.attendances.find(
                    (a) => a.date === selectedDate,
                );
                attendanceData[student.id] = existing ? existing.status : "h";
            });
        }
    }

    const form = useForm({
        date: selectedDate,
        classroom_id: selectedClassroom,
        attendance: [],
    });

    function handleFilter() {
        router.get(
            route("attendance.index"),
            {
                date: selectedDate,
                classroom_id: selectedClassroom,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }

    function submit() {
        form.date = selectedDate;
        form.classroom_id = selectedClassroom;
        form.attendance = Object.entries(attendanceData).map(
            ([id, status]) => ({
                student_id: id,
                status: status,
            }),
        );

        form.post(route("attendance.store"), {
            onSuccess: () => {
                // Flash success message handled by layout
            },
        });
    }

    function setAll(status) {
        if (students.length > 0) {
            students.forEach((student) => {
                attendanceData[student.id] = status;
            });
            attendanceData = { ...attendanceData }; // Trigger reactivity
        }
    }

    function getStatusColor(status) {
        switch (status) {
            case "h":
                return "bg-emerald-100 text-emerald-700 border-emerald-200";
            case "s":
                return "bg-blue-100 text-blue-700 border-blue-200";
            case "i":
                return "bg-yellow-100 text-yellow-700 border-yellow-200";
            case "a":
                return "bg-red-100 text-red-700 border-red-200";
            default:
                return "bg-gray-100";
        }
    }
</script>

<svelte:head>
    <title>Presensi Kelas</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Presensi Harian
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Catat kehadiran santri per kelas
                </p>
            </div>

            {#if students.length > 0}
                <button
                    on:click={submit}
                    disabled={form.processing}
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all shadow-lg shadow-blue-200 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-95"
                >
                    <Save class="h-4 w-4" />
                    Simpan Presensi
                </button>
            {/if}
        </div>

        <!-- Filters Section -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Tanggal</label
                    >
                    <div class="relative">
                        <Calendar
                            class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                        />
                        <input
                            type="date"
                            bind:value={selectedDate}
                            on:change={handleFilter}
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm font-medium"
                        />
                    </div>
                </div>
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Pilih Kelas</label
                    >
                    <select
                        bind:value={selectedClassroom}
                        on:change={handleFilter}
                        class="w-full py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                    >
                        <option value="">-- Pilih Kelas --</option>
                        {#each classrooms as classroom}
                            <option value={classroom.id}
                                >{classroom.name}</option
                            >
                        {/each}
                    </select>
                </div>
                <div>
                    {#if selectedClassroom}
                        <div class="flex gap-2">
                            <button
                                on:click={() => setAll("h")}
                                class="flex-1 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-colors"
                                >Semua Hadir</button
                            >
                            <button
                                on:click={() => setAll("i")}
                                class="flex-1 py-2.5 bg-yellow-50 text-yellow-700 rounded-xl text-xs font-bold hover:bg-yellow-100 transition-colors"
                                >Semua Izin</button
                            >
                        </div>
                    {/if}
                </div>
            </div>
        </div>

        <!-- Attendance List -->
        {#if students.length > 0}
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
                                <th class="px-6 py-4 font-bold">Nama Santri</th>
                                <th class="px-6 py-4 font-bold text-center"
                                    >Status Kehadiran</th
                                >
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {#each students as student, index (student.id)}
                                <tr
                                    class="hover:bg-gray-50/50 transition-colors group"
                                >
                                    <td
                                        class="px-6 py-4 text-center text-gray-400 font-medium"
                                        >{index + 1}</td
                                    >
                                    <td
                                        class="px-6 py-4 font-medium text-gray-900"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-8 w-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold"
                                            >
                                                {student.name.charAt(0)}
                                            </div>
                                            {student.name}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            <button
                                                on:click={() =>
                                                    (attendanceData[
                                                        student.id
                                                    ] = "h")}
                                                class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm transition-all {attendanceData[
                                                    student.id
                                                ] === 'h'
                                                    ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200 scale-110'
                                                    : 'bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-500'}"
                                                title="Hadir"
                                            >
                                                H
                                            </button>
                                            <button
                                                on:click={() =>
                                                    (attendanceData[
                                                        student.id
                                                    ] = "s")}
                                                class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm transition-all {attendanceData[
                                                    student.id
                                                ] === 's'
                                                    ? 'bg-blue-500 text-white shadow-lg shadow-blue-200 scale-110'
                                                    : 'bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-500'}"
                                                title="Sakit"
                                            >
                                                S
                                            </button>
                                            <button
                                                on:click={() =>
                                                    (attendanceData[
                                                        student.id
                                                    ] = "i")}
                                                class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm transition-all {attendanceData[
                                                    student.id
                                                ] === 'i'
                                                    ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-200 scale-110'
                                                    : 'bg-gray-50 text-gray-400 hover:bg-yellow-50 hover:text-yellow-500'}"
                                                title="Izin"
                                            >
                                                I
                                            </button>
                                            <button
                                                on:click={() =>
                                                    (attendanceData[
                                                        student.id
                                                    ] = "a")}
                                                class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm transition-all {attendanceData[
                                                    student.id
                                                ] === 'a'
                                                    ? 'bg-red-500 text-white shadow-lg shadow-red-200 scale-110'
                                                    : 'bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500'}"
                                                title="Alpha"
                                            >
                                                A
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            {/each}
                        </tbody>
                    </table>
                </div>
            </div>
        {:else if selectedClassroom}
            <div
                class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm"
            >
                <Users class="h-12 w-12 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900">
                    Tidak ada santri
                </h3>
                <p class="text-gray-500">
                    Kelas ini belum memiliki data santri.
                </p>
            </div>
        {:else}
            <div
                class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm"
            >
                <Filter class="h-12 w-12 text-blue-300 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900">Pilih Kelas</h3>
                <p class="text-gray-500">
                    Silakan pilih kelas terlebih dahulu untuk menampilkan daftar
                    hadir.
                </p>
            </div>
        {/if}
    </div>
</AppLayout>
