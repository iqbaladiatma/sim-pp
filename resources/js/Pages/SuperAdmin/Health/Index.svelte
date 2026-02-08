<script>
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        Search,
        HeartPulse,
        HardDrive,
        Database,
        Server,
        RefreshCw,
    } from "lucide-svelte";
    import { router } from "@inertiajs/svelte";

    export let status;
    export let system;
    export let timestamp;

    function refresh() {
        router.reload({ only: ["status", "system", "timestamp"] });
    }

    function formatBytes(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const dm = 2;
        const sizes = ["Bytes", "KB", "MB", "GB", "TB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return (
            parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i]
        );
    }
</script>

<svelte:head>
    <title>Status Sistem</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4"
        >
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Status Sistem
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Monitoring kesehatan dan performa aplikasi
                </p>
            </div>

            <button
                on:click={refresh}
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-200"
            >
                <RefreshCw class="h-4 w-4" />
                Refresh Status
            </button>
        </div>

        <!-- Health Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Database Status -->
            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4"
            >
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                    <Database class="h-6 w-6" />
                </div>
                <div class="flex-1">
                    <h3
                        class="text-sm font-bold text-gray-900 uppercase tracking-wide"
                    >
                        Database Connection
                    </h3>
                    <div class="mt-2 flex items-center gap-2">
                        {#if status.database === "connected"}
                            <span
                                class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"
                            ></span>
                            <span class="text-emerald-600 font-medium text-sm"
                                >Terhubung (Normal)</span
                            >
                        {:else}
                            <span
                                class="inline-flex h-2.5 w-2.5 rounded-full bg-red-500"
                            ></span>
                            <span class="text-red-600 font-medium text-sm"
                                >Terputus / Error</span
                            >
                        {/if}
                    </div>
                    <p class="text-xs text-gray-400 mt-2">
                        Check performed at: {new Date(
                            timestamp,
                        ).toLocaleTimeString()}
                    </p>
                </div>
            </div>

            <!-- Server Status -->
            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4"
            >
                <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                    <Server class="h-6 w-6" />
                </div>
                <div class="flex-1">
                    <h3
                        class="text-sm font-bold text-gray-900 uppercase tracking-wide"
                    >
                        Application Server
                    </h3>
                    <div class="grid grid-cols-2 gap-4 mt-3">
                        <div>
                            <p
                                class="text-[10px] text-gray-400 font-bold uppercase"
                            >
                                PHP Version
                            </p>
                            <p class="text-sm font-medium text-gray-700">
                                {system.php_version}
                            </p>
                        </div>
                        <div>
                            <p
                                class="text-[10px] text-gray-400 font-bold uppercase"
                            >
                                Laravel Version
                            </p>
                            <p class="text-sm font-medium text-gray-700">
                                {system.laravel_version}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disk Usage -->
            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4"
            >
                <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                    <HardDrive class="h-6 w-6" />
                </div>
                <div class="flex-1">
                    <h3
                        class="text-sm font-bold text-gray-900 uppercase tracking-wide"
                    >
                        Disk Usage (Server)
                    </h3>
                    <div class="mt-4 relative pt-1">
                        <div
                            class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-100"
                        >
                            <div
                                style="width: {((system.disk_total -
                                    system.disk_free) /
                                    system.disk_total) *
                                    100}%"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-orange-500"
                            ></div>
                        </div>
                        <div
                            class="flex justify-between text-xs font-medium text-gray-600"
                        >
                            <span
                                >Used: {formatBytes(
                                    system.disk_total - system.disk_free,
                                )}</span
                            >
                            <span>Total: {formatBytes(system.disk_total)}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cache Status -->
            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4"
            >
                <div class="p-3 bg-pink-50 text-pink-600 rounded-xl">
                    <HeartPulse class="h-6 w-6" />
                </div>
                <div class="flex-1">
                    <h3
                        class="text-sm font-bold text-gray-900 uppercase tracking-wide"
                    >
                        Cache System
                    </h3>
                    <div class="mt-2 flex items-center gap-2">
                        {#if status.cache === "working"}
                            <span
                                class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"
                            ></span>
                            <span class="text-emerald-600 font-medium text-sm"
                                >Working Properly</span
                            >
                        {:else}
                            <span
                                class="inline-flex h-2.5 w-2.5 rounded-full bg-red-500"
                            ></span>
                            <span class="text-red-600 font-medium text-sm"
                                >Issue Detected</span
                            >
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
</AppLayout>
