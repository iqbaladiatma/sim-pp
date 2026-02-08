<script>
    import { Link, router, useForm } from "@inertiajs/svelte";
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        Search,
        Plus,
        Save,
        Award,
        BookOpen,
        AlertCircle,
    } from "lucide-svelte";

    export let exams = [];
    export let classrooms = [];
    export let subjects = [];
    export let students = [];
    export let filters = { exam_id: "", classroom_id: "", subject_id: "" };

    let selectedExam = filters.exam_id;
    let selectedClassroom = filters.classroom_id;
    let selectedSubject = filters.subject_id;

    let isExamModalOpen = false;
    let gradeData = {};

    const examForm = useForm({
        name: "",
        academic_year: "2024/2025",
        semester: "1",
        is_active: true,
    });

    const gradeForm = useForm({
        exam_id: "",
        subject_id: "",
        grades: [],
    });

    // Initialize grade data
    $: if (students.length > 0) {
        students.forEach((student) => {
            gradeData[student.id] =
                student.grade_score !== null ? student.grade_score : "";
        });
    }

    function handleFilter() {
        if (selectedExam && selectedClassroom && selectedSubject) {
            router.get(
                route("grades.index"),
                {
                    exam_id: selectedExam,
                    classroom_id: selectedClassroom,
                    subject_id: selectedSubject,
                },
                {
                    preserveState: true,
                    replace: true,
                },
            );
        }
    }

    function openExamModal() {
        examForm.reset();
        isExamModalOpen = true;
    }

    function closeExamModal() {
        isExamModalOpen = false;
    }

    function submitExam() {
        examForm.post(route("exams.store"), {
            onSuccess: () => {
                closeExamModal();
                // Optionally auto-select the new exam
            },
        });
    }

    function submitGrades() {
        gradeForm.exam_id = selectedExam;
        gradeForm.subject_id = selectedSubject;
        gradeForm.grades = Object.entries(gradeData)
            .map(([id, score]) => ({
                student_id: id,
                score: parseFloat(score), // Ensure score is a number
            }))
            .filter((g) => !isNaN(g.score)); // Only submit valid scores

        gradeForm.post(route("grades.store"), {
            onSuccess: () => {
                // Success handling handled by layout flash
            },
        });
    }
</script>

<svelte:head>
    <title>Input Nilai</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Input Nilai
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola nilai ujian dan rapor santri
                </p>
            </div>

            <div class="flex gap-2">
                <button
                    on:click={openExamModal}
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-xl font-medium transition-colors"
                >
                    <Plus class="h-4 w-4" />
                    Buat Ujian Baru
                </button>
                {#if students.length > 0}
                    <button
                        on:click={submitGrades}
                        disabled={gradeForm.processing}
                        class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
                    >
                        <Save class="h-4 w-4" />
                        Simpan Nilai
                    </button>
                {/if}
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Pilih Ujian</label
                    >
                    <select
                        bind:value={selectedExam}
                        class="w-full py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                    >
                        <option value="">-- Pilih Ujian --</option>
                        {#each exams as exam}
                            <option value={exam.id}
                                >{exam.name} ({exam.academic_year} - Sem {exam.semester})</option
                            >
                        {/each}
                    </select>
                </div>
                <div>
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Pilih Kelas</label
                    >
                    <select
                        bind:value={selectedClassroom}
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
                    <label
                        class="block text-sm font-medium text-gray-700 mb-1.5"
                        >Pilih Mapel</label
                    >
                    <select
                        bind:value={selectedSubject}
                        class="w-full py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm"
                    >
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        {#each subjects as subject}
                            <option value={subject.id}
                                >{subject.name} ({subject.code})</option
                            >
                        {/each}
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button
                    on:click={handleFilter}
                    disabled={!selectedExam ||
                        !selectedClassroom ||
                        !selectedSubject}
                    class="px-6 py-2 bg-gray-900 text-white rounded-xl text-sm font-medium hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    Tampilkan Data Santri
                </button>
            </div>
        </div>

        <!-- Grading Table or Placeholder -->
        {#if students.length > 0}
            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500"
            >
                <div
                    class="p-4 bg-blue-50 border-b border-blue-100 flex items-center justify-between"
                >
                    <div
                        class="flex items-center gap-2 text-blue-800 font-medium"
                    >
                        <Award class="h-5 w-5" />
                        <span
                            >Input Nilai: {subjects.find(
                                (s) => s.id == selectedSubject,
                            )?.name}</span
                        >
                    </div>
                    <span class="text-sm text-blue-600">
                        Kelas: {classrooms.find(
                            (c) => c.id == selectedClassroom,
                        )?.name}
                    </span>
                </div>

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
                                <th class="px-6 py-4 font-bold w-48 text-center"
                                    >Nilai (0-100)</th
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
                                                class="h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold"
                                            >
                                                {student.name.charAt(0)}
                                            </div>
                                            {student.name}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center">
                                            <input
                                                type="number"
                                                min="0"
                                                max="100"
                                                step="0.01"
                                                bind:value={
                                                    gradeData[student.id]
                                                }
                                                placeholder="0.00"
                                                class="w-24 text-center rounded-lg border-gray-200 focus:border-blue-500 focus:ring-blue-500 font-mono font-bold text-gray-800"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            {/each}
                        </tbody>
                    </table>
                </div>
            </div>
        {:else if selectedExam && selectedClassroom && selectedSubject}
            <div
                class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm"
            >
                <AlertCircle class="h-12 w-12 text-gray-300 mx-auto mb-4" />
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
                <BookOpen class="h-12 w-12 text-indigo-200 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900">
                    Mulai Input Nilai
                </h3>
                <p class="text-gray-500 max-w-sm mx-auto mt-2">
                    Silakan pilih Ujian, Kelas, dan Mata Pelajaran terlebih
                    dahulu untuk mulai memasukkan nilai santri.
                </p>
            </div>
        {/if}
    </div>

    <!-- Exam Creation Modal -->
    {#if isExamModalOpen}
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
                        Buat Data Ujian Baru
                    </h3>
                    <button
                        on:click={closeExamModal}
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <form
                    on:submit|preventDefault={submitExam}
                    class="p-6 space-y-4"
                >
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Nama Ujian</label
                        >
                        <input
                            type="text"
                            bind:value={$examForm.name}
                            placeholder="e.g. PAS Ganjil 2024"
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Tahun Ajaran</label
                            >
                            <input
                                type="text"
                                bind:value={$examForm.academic_year}
                                placeholder="2024/2025"
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Semester</label
                            >
                            <select
                                bind:value={$examForm.semester}
                                class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="1">Ganjil (1)</option>
                                <option value="2">Genap (2)</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="is_active"
                            bind:checked={$examForm.is_active}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        />
                        <label for="is_active" class="text-sm text-gray-700"
                            >Set sebagai Ujian Aktif</label
                        >
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button
                            type="button"
                            on:click={closeExamModal}
                            class="px-5 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 font-medium"
                            >Batal</button
                        >
                        <button
                            type="submit"
                            disabled={$examForm.processing}
                            class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-lg shadow-blue-200"
                            >Simpan</button
                        >
                    </div>
                </form>
            </div>
        </div>
    {/if}
</AppLayout>
