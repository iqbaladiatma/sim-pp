<script>
    import { Link, router } from "@inertiajs/svelte";
    import AppLayout from "../../../Layouts/AppLayout.svelte";
    import {
        Search,
        MessageCircle,
        CheckCircle,
        XCircle,
        Clock,
    } from "lucide-svelte";

    export let logs = { data: [], links: [] };
    export let filters = { search: "" };

    let search = filters.search;

    function handleSearch() {
        router.get(
            route("whatsapp-logs.index"),
            { search },
            {
                preserveState: true,
                replace: true,
            },
        );
    }

    function formatDate(date) {
        if (!date) return "-";
        return new Date(date).toLocaleString("id-ID", {
            day: "numeric",
            month: "short",
            hour: "2-digit",
            minute: "2-digit",
        });
    }

    function getStatusColor(status) {
        switch (status) {
            case "sent":
                return "text-emerald-600 bg-emerald-50";
            case "failed":
                return "text-red-600 bg-red-50";
            default:
                return "text-yellow-600 bg-yellow-50";
        }
    }
</script>

<svelte:head>
    <title>Whatsapp Log</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header -->
        <h1
            class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-3"
        >
            <MessageCircle class="h-6 w-6 text-emerald-600" />
            Riwayat Pesan Whatsapp
        </h1>

        <!-- Search -->
        <div
            class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm max-w-md"
        >
            <div class="relative">
                <Search
                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400"
                />
                <input
                    type="text"
                    bind:value={search}
                    on:input={handleSearch}
                    placeholder="Cari nomor atau isi pesan..."
                    class="w-full pl-10 pr-4 py-2 bg-gray-50 border-transparent focus:bg-white focus:border-blue-500 rounded-xl text-sm transition-all"
                />
            </div>
        </div>

        <!-- Logs Table -->
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100"
                    >
                        <tr>
                            <th class="px-6 py-4 font-bold">Waktu Dikirim</th>
                            <th class="px-6 py-4 font-bold">Nomor Tujuan</th>
                            <th class="px-6 py-4 font-bold w-1/2">Pesan</th>
                            <th class="px-6 py-4 font-bold text-center"
                                >Status</th
                            >
                            <th class="px-6 py-4 font-bold">Response API</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {#each logs.data as log (log.id)}
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td
                                    class="px-6 py-4 text-gray-600 whitespace-nowrap"
                                >
                                    {formatDate(log.sent_at || log.created_at)}
                                </td>
                                <td
                                    class="px-6 py-4 font-mono font-medium text-gray-900"
                                >
                                    {log.phone_number}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <div
                                        class="line-clamp-2"
                                        title={log.message}
                                    >
                                        {log.message}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-bold uppercase tracking-wider {getStatusColor(
                                            log.status,
                                        )}"
                                    >
                                        {log.status}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 text-xs text-gray-400 font-mono"
                                >
                                    <div
                                        class="truncate max-w-[150px]"
                                        title={log.response}
                                    >
                                        {log.response || "-"}
                                    </div>
                                </td>
                            </tr>
                        {:else}
                            <tr>
                                <td
                                    colspan="5"
                                    class="px-6 py-12 text-center text-gray-500"
                                >
                                    Belum ada log pesan.
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
                    Menampilkan {logs.from || 0} - {logs.to || 0} dari {logs.total ||
                        0} data
                </span>
                <div class="flex gap-2">
                    {#each logs.links as link}
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
</AppLayout>
