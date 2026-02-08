<script>
    import AppLayout from "../Layouts/AppLayout.svelte";
    import { Construction, ArrowLeft } from "lucide-svelte";
    import { Link } from "@inertiajs/svelte";
</script>

<AppLayout>
    <div
        class="min-h-[70vh] flex flex-col items-center justify-center p-8 text-center bg-white rounded-[48px] border border-gray-100 shadow-sm overflow-hidden relative"
    >
        <!-- Background Decor -->
        <div
            class="absolute top-0 right-0 -mt-20 -mr-20 h-64 w-64 bg-blue-50 rounded-full blur-3xl opacity-50"
        ></div>
        <div
            class="absolute bottom-0 left-0 -mb-20 -ml-20 h-64 w-64 bg-indigo-50 rounded-full blur-3xl opacity-50"
        ></div>

        <div class="relative z-10 space-y-8 max-w-lg">
            <div
                class="inline-flex h-24 w-24 items-center justify-center rounded-[32px] bg-blue-600 text-white shadow-2xl shadow-blue-200 animate-bounce"
            >
                <Construction class="h-12 w-12" />
            </div>

            <div class="space-y-4">
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">
                    Halaman Sedang <br />
                    <span class="text-blue-600">Dalam Pengembangan.</span>
                </h1>
                <p class="text-gray-400 font-medium leading-relaxed">
                    Kami sedang menyiapkan fitur ini dengan standar kualitas
                    terbaik untuk mendukung kemajuan pesantren Anda. Nantikan
                    pembaruan selanjutnya!
                </p>
            </div>

            <div class="pt-4">
                <Link
                    href="/dashboard"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-gray-900 hover:bg-black text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-gray-200 active:scale-95"
                >
                    <ArrowLeft class="h-4 w-4" />
                    Kembali ke Dashboard
                </Link>
            </div>
        </div>
    </div>
</AppLayout>
