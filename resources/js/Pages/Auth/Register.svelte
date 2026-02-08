<script>
    import { useForm, Link } from "@inertiajs/svelte";
    import {
        BookOpen,
        User,
        Mail,
        Lock,
        ShieldCheck,
        ArrowRight,
    } from "lucide-svelte";

    const form = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
    });

    function submit() {
        $form.post("/register", {
            onFinish: () => $form.reset("password", "password_confirmation"),
        });
    }
</script>

<div
    class="min-h-screen bg-white font-sans text-gray-900 flex flex-col lg:flex-row shadow-2xl overflow-hidden"
>
    <!-- Left Side: Welcome & Marketing -->
    <div
        class="lg:w-1/2 bg-blue-600 p-12 flex flex-col justify-between relative overflow-hidden"
    >
        <div
            class="absolute top-0 right-0 -mt-20 -mr-20 h-96 w-96 bg-blue-500 rounded-full blur-3xl opacity-50"
        ></div>
        <div
            class="absolute bottom-0 left-0 -mb-20 -ml-20 h-96 w-96 bg-blue-700 rounded-full blur-3xl opacity-50"
        ></div>

        <div class="relative z-10">
            <Link href="/" class="inline-flex items-center gap-3 text-white">
                <div
                    class="h-10 w-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center"
                >
                    <BookOpen class="h-6 w-6" />
                </div>
                <span class="text-2xl font-black tracking-tighter">SIM-PP</span>
            </Link>
        </div>

        <div class="relative z-10 max-w-lg">
            <h2 class="text-5xl font-black text-white leading-tight mb-6">
                Mulai Transformasi <br />Digital Pesantren Anda.
            </h2>
            <p class="text-blue-100 text-lg font-medium leading-relaxed">
                Bergabunglah dengan 50+ pesantren yang telah memodernisasi
                manajemen kesantrian dan keuangan mereka bersama SIM-PP.
            </p>
        </div>

        <div class="relative z-10">
            <div
                class="bg-white/10 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 space-y-4"
            >
                <div class="flex -space-x-2">
                    {#each [1, 2, 3] as i}
                        <div
                            class="h-10 w-10 rounded-full border-2 border-blue-600 bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-400"
                        >
                            P{i}
                        </div>
                    {/each}
                </div>
                <p class="text-white text-sm font-bold">
                    "Sangat membantu dalam monitoring saku santri secara
                    real-time."
                </p>
                <p
                    class="text-blue-300 text-xs font-bold uppercase tracking-widest"
                >
                    — Admin Pondok Al-Ikhlas
                </p>
            </div>
        </div>
    </div>

    <!-- Right Side: Register Form -->
    <div
        class="lg:w-1/2 p-12 lg:p-24 flex items-center justify-center bg-gray-50/50"
    >
        <div class="w-full max-w-md space-y-10">
            <div class="space-y-4">
                <h1 class="text-4xl font-black tracking-tight">
                    Daftar Akun Baru
                </h1>
                <p class="text-gray-400 font-medium">
                    Lengkapi data untuk membuat admin panel pesantren.
                </p>
            </div>

            <form on:submit|preventDefault={submit} class="space-y-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label
                        for="name"
                        class="text-xs font-black uppercase tracking-widest text-gray-400 ml-1"
                        >Nama Lengkap</label
                    >
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors"
                        >
                            <User class="h-5 w-5" />
                        </div>
                        <input
                            id="name"
                            type="text"
                            bind:value={$form.name}
                            required
                            class="w-full bg-white border border-gray-100 rounded-[24px] py-4 pl-14 pr-5 text-sm font-bold shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none"
                            placeholder="Masukkan nama lengkap Anda"
                        />
                    </div>
                    {#if $form.errors.name}<p
                            class="text-red-500 text-xs mt-1 ml-1 font-bold"
                        >
                            {$form.errors.name}
                        </p>{/if}
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label
                        for="email"
                        class="text-xs font-black uppercase tracking-widest text-gray-400 ml-1"
                        >Email Sekolah</label
                    >
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors"
                        >
                            <Mail class="h-5 w-5" />
                        </div>
                        <input
                            id="email"
                            type="email"
                            bind:value={$form.email}
                            required
                            class="w-full bg-white border border-gray-100 rounded-[24px] py-4 pl-14 pr-5 text-sm font-bold shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none"
                            placeholder="sekolah@pesantren.com"
                        />
                    </div>
                    {#if $form.errors.email}<p
                            class="text-red-500 text-xs mt-1 ml-1 font-bold"
                        >
                            {$form.errors.email}
                        </p>{/if}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div class="space-y-2">
                        <label
                            for="password"
                            class="text-xs font-black uppercase tracking-widest text-gray-400 ml-1"
                            >Password</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors"
                            >
                                <Lock class="h-5 w-5" />
                            </div>
                            <input
                                id="password"
                                type="password"
                                bind:value={$form.password}
                                required
                                class="w-full bg-white border border-gray-100 rounded-[24px] py-4 pl-14 pr-5 text-sm font-bold shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none"
                                placeholder="••••••••"
                            />
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label
                            for="password_confirmation"
                            class="text-xs font-black uppercase tracking-widest text-gray-400 ml-1"
                            >Konfirmasi</label
                        >
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-blue-600 transition-colors"
                            >
                                <ShieldCheck class="h-5 w-5" />
                            </div>
                            <input
                                id="password_confirmation"
                                type="password"
                                bind:value={$form.password_confirmation}
                                required
                                class="w-full bg-white border border-gray-100 rounded-[24px] py-4 pl-14 pr-5 text-sm font-bold shadow-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none"
                                placeholder="••••••••"
                            />
                        </div>
                    </div>
                </div>
                {#if $form.errors.password}<p
                        class="text-red-500 text-xs mt-1 ml-1 font-bold"
                    >
                        {$form.errors.password}
                    </p>{/if}

                <div class="flex items-center gap-3 py-2">
                    <input
                        id="terms"
                        type="checkbox"
                        bind:checked={$form.terms}
                        class="h-5 w-5 rounded-lg border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label for="terms" class="text-sm font-bold text-gray-400"
                        >Saya setuju dengan <a href="#" class="text-blue-600"
                            >Syarat & Ketentuan</a
                        >.</label
                    >
                </div>

                <button
                    type="submit"
                    disabled={$form.processing}
                    class="w-full bg-gray-900 hover:bg-blue-600 text-white py-5 rounded-[24px] font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-gray-200 transition-all flex items-center justify-center gap-3 active:scale-95 disabled:opacity-50"
                >
                    {#if $form.processing}
                        <div
                            class="h-4 w-4 border-2 border-white/20 border-t-white rounded-full animate-spin"
                        ></div>
                        Memproses...
                    {:else}
                        Buat Akun Sekarang
                        <ArrowRight class="h-4 w-4" />
                    {/if}
                </button>

                <p class="text-center text-sm font-bold text-gray-400">
                    Sudah punya akun?
                    <Link href="/login" class="text-blue-600"
                        >Login di sini</Link
                    >
                </p>
            </form>
        </div>
    </div>
</div>
