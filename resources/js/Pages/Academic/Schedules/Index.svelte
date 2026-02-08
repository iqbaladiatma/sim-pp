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
        Clock,
        Calendar,
        BookOpen,
    } from "lucide-svelte";

    export let schedules = [];
    export let classrooms = [];
    export let subjects = [];
    export let teachers = [];
    export let filters = { classroom_id: "", day: "", teacher_id: "" };

    let selectedClassroom = filters.classroom_id;
    let selectedDay = filters.day;
    let selectedTeacher = filters.teacher_id;

    let isModalOpen = false;
    let isEditing = false;
    let editingId = null;

    const days = [
        { value: "senin", label: "Senin" },
        { value: "selasa", label: "Selasa" },
        { value: "rabu", label: "Rabu" },
        { value: "kamis", label: "Kamis" },
        { value: "jumat", label: "Jumat" },
        { value: "sabtu", label: "Sabtu" },
        { value: "minggu", label: "Minggu" },
    ];

    const form = useForm({
        classroom_id: "",
        subject_id: "",
        teacher_id: "",
        day_of_week: "senin",
        start_time: "07:00",
        end_time: "08:00",
    });

    function openCreateModal() {
        isEditing = false;
        editingId = null;
        form.reset();
        isModalOpen = true;
    }

    function openEditModal(schedule) {
        isEditing = true;
        editingId = schedule.id;
        form.classroom_id = schedule.classroom_id;
        form.subject_id = schedule.subject_id;
        form.teacher_id = schedule.teacher_id;
        form.day_of_week = schedule.day_of_week;
        form.start_time = schedule.start_time;
        form.end_time = schedule.end_time;
        isModalOpen = true;
    }

    function closeModal() {
        isModalOpen = false;
        form.reset();
    }

    function submit() {
        if (isEditing) {
            form.put(route("schedules.update", editingId), {
                onSuccess: () => closeModal(),
            });
        } else {
            form.post(route("schedules.store"), {
                onSuccess: () => closeModal(),
            });
        }
    }

    function deleteSchedule(id) {
        if (confirm("Apakah Anda yakin ingin menghapus jadwal ini?")) {
            router.delete(route("schedules.destroy", id));
        }
    }

    function handleFilter() {
        router.get(
            route("schedules.index"),
            {
                classroom_id: selectedClassroom,
                day: selectedDay,
                teacher_id: selectedTeacher,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }

    function formatTime(timeString) {
        return timeString.substring(0, 5); // HH:MM
    }
</script>

<svelte:head>
    <title>Jadwal Pelajaran</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Jadwal Pelajaran
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola jadwal KBM harian santri
                </p>
            </div>

            <button
                on:click={openCreateModal}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
            >
                <Plus class="h-4 w-4" />
                Tambah Jadwal
            </button>
        </div>

        <!-- Filters Section -->
        <div
            class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4"
        >
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-500 mb-1"
                    >Filter Kelas</label
                >
                <select
                    bind:value={selectedClassroom}
                    on:change={handleFilter}
                    class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
                    <option value="">Semua Kelas</option>
                    {#each classrooms as classroom}
                        <option value={classroom.id}>{classroom.name}</option>
                    {/each}
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-500 mb-1"
                    >Filter Hari</label
                >
                <select
                    bind:value={selectedDay}
                    on:change={handleFilter}
                    class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
                    <option value="">Semua Hari</option>
                    {#each days as day}
                        <option value={day.value}>{day.label}</option>
                    {/each}
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-500 mb-1"
                    >Filter Pengajar</label
                >
                <select
                    bind:value={selectedTeacher}
                    on:change={handleFilter}
                    class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                >
                    <option value="">Semua Pengajar</option>
                    {#each teachers as teacher}
                        <option value={teacher.id}>{teacher.name}</option>
                    {/each}
                </select>
            </div>
        </div>

        <!-- Schedule Grid/List -->
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
        >
            {#each schedules as schedule (schedule.id)}
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all group relative"
                >
                    <!-- Color Bar based on Day -->
                    <div
                        class="h-1.5 w-full {schedule.day_of_week === 'jumat'
                            ? 'bg-red-400'
                            : 'bg-blue-400'}"
                    ></div>

                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-start">
                            <span
                                class="px-2 py-1 bg-gray-100 rounded-lg text-xs font-bold uppercase text-gray-600"
                            >
                                {schedule.day_of_week}
                            </span>
                            <div
                                class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity absolute top-3 right-3 bg-white p-1 rounded-lg shadow-sm"
                            >
                                <button
                                    on:click={() => openEditModal(schedule)}
                                    class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-md"
                                >
                                    <Pencil class="h-3.5 w-3.5" />
                                </button>
                                <button
                                    on:click={() => deleteSchedule(schedule.id)}
                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md"
                                >
                                    <Trash2 class="h-3.5 w-3.5" />
                                </button>
                            </div>
                        </div>

                        <div>
                            <h3
                                class="font-bold text-gray-900 text-lg leading-tight"
                            >
                                {schedule.subject.name}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 font-mono">
                                {schedule.classroom.name}
                            </p>
                        </div>

                        <div
                            class="pt-3 border-t border-gray-50 flex flex-col gap-2"
                        >
                            <div
                                class="flex items-center gap-2 text-sm text-gray-600"
                            >
                                <Clock class="h-3.5 w-3.5 text-blue-400" />
                                <span class="font-medium"
                                    >{formatTime(schedule.start_time)} - {formatTime(
                                        schedule.end_time,
                                    )}</span
                                >
                            </div>
                            <div
                                class="flex items-center gap-2 text-sm text-gray-600"
                            >
                                <div
                                    class="h-4 w-4 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-[10px] font-bold"
                                >
                                    {schedule.teacher?.name.charAt(0) || "?"}
                                </div>
                                <span class="text-xs truncate max-w-[150px]"
                                    >{schedule.teacher?.name ||
                                        "Belum ditentukan"}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            {:else}
                <div
                    class="col-span-full py-20 text-center text-gray-500 italic bg-white rounded-2xl border border-gray-100"
                >
                    Belum ada jadwal yang sesuai dengan filter.
                </div>
            {/each}
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
                        {isEditing ? "Edit Jadwal" : "Tambah Jadwal Baru"}
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
                                >Hari</label
                            >
                            <select
                                bind:value={$form.day_of_week}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 capitalize"
                                required
                            >
                                {#each days as day}
                                    <option value={day.value}
                                        >{day.label}</option
                                    >
                                {/each}
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Kelas</label
                            >
                            <select
                                bind:value={$form.classroom_id}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                required
                            >
                                <option value="">-- Pilih Kelas --</option>
                                {#each classrooms as classroom}
                                    <option value={classroom.id}
                                        >{classroom.name}</option
                                    >
                                {/each}
                            </select>
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Mata Pelajaran</label
                        >
                        <select
                            bind:value={$form.subject_id}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                            <option value="">-- Pilih Mapel --</option>
                            {#each subjects as subject}
                                <option value={subject.id}
                                    >{subject.name} ({subject.code})</option
                                >
                            {/each}
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Pengajar</label
                        >
                        <select
                            bind:value={$form.teacher_id}
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">-- Pilih Pengajar --</option>
                            {#each teachers as teacher}
                                <option value={teacher.id}
                                    >{teacher.name}</option
                                >
                            {/each}
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Jam Mulai</label
                            >
                            <input
                                type="time"
                                bind:value={$form.start_time}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Jam Selesai</label
                            >
                            <input
                                type="time"
                                bind:value={$form.end_time}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                        </div>
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
                            >Simpan Jadwal</button
                        >
                    </div>
                </form>
            </div>
        </div>
    {/if}
</AppLayout>
