<script>
    import { page, router, Link } from "@inertiajs/svelte";
    import {
        LayoutDashboard,
        Users,
        BookOpen,
        Wallet,
        Settings,
        LogOut,
        Menu,
        ChevronDown,
        Bell,
        School,
        Home,
        Calendar,
        ShieldAlert,
        ClipboardCheck,
        CreditCard,
        Tag,
        TrendingDown,
        GraduationCap,
        Building2,
        Banknote,
        MessageSquare,
        ShoppingCart,
        BarChart3,
        Bed,
        Building,
        UserCheck,
        ScrollText,
        History,
        Activity,
        Key,
        Landmark,
        FileText,
        HeartPulse,
        ShieldCheck,
        Receipt,
    } from "lucide-svelte";

    // State untuk sidebar di mobile
    let isSidebarOpen = false;
    let isUserMenuOpen = false;

    const user = $page.props.auth.user;
    const tenant = $page.props.auth.tenant;

    function toggleSidebar() {
        isSidebarOpen = !isSidebarOpen;
    }

    function handleLogout() {
        router.post("/logout");
    }

    // Helper untuk inisial nama
    const getInitial = (name) => (name ? name.charAt(0).toUpperCase() : "?");
</script>

<div class="flex h-screen bg-gray-50 font-sans">
    <!-- Sidebar -->
    <aside
        class="flex flex-col fixed inset-y-0 left-0 z-50 w-64 transform bg-white shadow-xl border-r border-gray-100 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 {isSidebarOpen
            ? 'translate-x-0'
            : '-translate-x-full'}"
    >
        <div class="flex h-20 items-center px-6 border-b border-gray-50">
            <div class="flex items-center gap-3">
                <div
                    class="h-10 w-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200"
                >
                    <BookOpen class="h-6 w-6" />
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">
                        SIM-PP
                    </h1>
                    {#if tenant}
                        <p
                            class="text-[10px] font-bold text-blue-600 uppercase tracking-widest"
                        >
                            {tenant.slug}
                        </p>
                    {/if}
                </div>
            </div>
        </div>

        <nav
            class="flex-1 overflow-y-auto overflow-x-hidden mt-6 px-4 space-y-1 pb-4 scrollbar-thin scrollbar-thumb-gray-200 scrollbar-track-transparent"
        >
            <Link
                href="/dashboard"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url ===
                    '/dashboard' || $page.url === '/super-admin/dashboard'
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 font-bold'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
            >
                <LayoutDashboard class="h-5 w-5" />
                <span>Dashboard</span>
            </Link>

            <Link
                href="/reports"
                class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                    '/reports',
                )
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 font-bold'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
            >
                <BarChart3 class="h-5 w-5" />
                <span>Analisis & Insight</span>
            </Link>

            {#if user?.role === "super_admin"}
                <div
                    class="pt-6 pb-2 px-4 shadow-sm border-b border-gray-50/50 mb-2"
                >
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"
                    >
                        Pusat Kontrol
                    </p>
                </div>
                <Link
                    href="/super-admin/tenants"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/super-admin/tenants',
                    )
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Building2 class="h-5 w-5" />
                    <span>Lembaga Pesantren</span>
                </Link>
                <Link
                    href="/super-admin/health"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/super-admin/health',
                    )
                        ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <HeartPulse class="h-5 w-5" />
                    <span>Status Sistem</span>
                </Link>
            {/if}

            {#if user?.role === "wali_santri"}
                <div
                    class="pt-6 pb-2 px-4 shadow-sm border-b border-gray-50/50 mb-2"
                >
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"
                    >
                        Wali Santri Hub
                    </p>
                </div>
                <Link
                    href="/dashboard"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url ===
                    '/dashboard'
                        ? 'bg-emerald-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <UserCheck class="h-5 w-5" />
                    <span>Perkembangan Santri</span>
                </Link>
            {/if}

            {#if user?.role === "admin_pondok" || user?.role === "ustadz" || user?.role === "super_admin"}
                <div class="pt-6 pb-2 px-4">
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"
                    >
                        Kesiswaan & Asrama
                    </p>
                </div>
                <Link
                    href="/students"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/students',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Users class="h-5 w-5" />
                    <span>Database Santri</span>
                </Link>

                <Link
                    href="/dormitory-rooms"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/dormitory-rooms',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Bed class="h-5 w-5" />
                    <span>Plotting Kamar</span>
                </Link>

                <Link
                    href="/violations"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/violations',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Activity class="h-5 w-5" />
                    <span>Poin Pelanggaran</span>
                </Link>

                <Link
                    href="/permissions"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/permissions',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Key class="h-5 w-5" />
                    <span>Izin Keluar/Pulang</span>
                </Link>

                <div class="pt-6 pb-2 px-4 text-gray-300">
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"
                    >
                        Kurikulum & Pengajaran
                    </p>
                </div>

                <Link
                    href="/classrooms"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/classrooms',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <School class="h-5 w-5" />
                    <span>Rombongan Belajar</span>
                </Link>

                <Link
                    href="/attendance"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/attendance',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <UserCheck class="h-5 w-5" />
                    <span>Absensi Kelas</span>
                </Link>

                <Link
                    href="/tahfidz"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/tahfidz',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <ScrollText class="h-5 w-5" />
                    <span>Setoran Tahfidz</span>
                </Link>

                <Link
                    href="/grades"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/grades',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <GraduationCap class="h-5 w-5" />
                    <span>E-Rapor & Nilai</span>
                </Link>

                <div class="pt-6 pb-2 px-4">
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"
                    >
                        Ekonomi & Keuangan
                    </p>
                </div>

                <Link
                    href="/student-bills"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/student-bills',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Receipt class="h-5 w-5" />
                    <span>Invoice & Tagihan</span>
                </Link>

                <Link
                    href="/wallets"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/wallets',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Landmark class="h-5 w-5" />
                    <span>E-Money / Tabungan</span>
                </Link>

                <Link
                    href="/merchant"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/merchant',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <ShoppingCart class="h-5 w-5" />
                    <span>Kantin Smart Card</span>
                </Link>

                <Link
                    href="/expenses"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/expenses',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <TrendingDown class="h-5 w-5" />
                    <span>Kas Keluar Pondok</span>
                </Link>

                <Link
                    href="/bill-categories"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/bill-categories',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Settings class="h-5 w-5" />
                    <span>Kategori Tagihan</span>
                </Link>

                <div class="pt-6 pb-2 px-4">
                    <p
                        class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]"
                    >
                        SDM & Kepegawaian
                    </p>
                </div>

                <Link
                    href="/teachers"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/teachers',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <ShieldCheck class="h-5 w-5" />
                    <span>Direktori Ustadz</span>
                </Link>

                <Link
                    href="/teacher-attendance"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/teacher-attendance',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <ClipboardCheck class="h-5 w-5" />
                    <span>Presensi Ustadz</span>
                </Link>

                <Link
                    href="/payrolls"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/payrolls',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Banknote class="h-5 w-5" />
                    <span>Slip Gaji / Payroll</span>
                </Link>

                <Link
                    href="/whatsapp-logs"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url.startsWith(
                        '/whatsapp-logs',
                    )
                        ? 'bg-blue-600 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <MessageSquare class="h-5 w-5" />
                    <span>Broadcast WA</span>
                </Link>
            {/if}

            <div class="pt-6 mt-4 border-t border-gray-100 mb-20">
                <Link
                    href={route("profile.show")}
                    class="flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all {$page.url ===
                    '/user/profile'
                        ? 'bg-gray-900 text-white shadow-lg font-bold'
                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'}"
                >
                    <Settings class="h-5 w-5" />
                    <span>Konfigurasi Sytem</span>
                </Link>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div
            class="flex-shrink-0 w-full p-4 border-t border-gray-50 bg-white z-10"
        >
            <button
                on:click={handleLogout}
                class="flex w-full items-center gap-3 rounded-2xl px-4 py-3.5 text-red-500 hover:bg-red-50 transition-all font-medium"
            >
                <LogOut class="h-5 w-5" />
                <span>Keluar Sistem</span>
            </button>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex flex-1 flex-col overflow-hidden">
        <!-- Header -->
        <header
            class="flex h-20 items-center justify-between bg-white px-8 shadow-sm border-b border-gray-100 z-10"
        >
            <div class="flex items-center gap-4">
                <button
                    on:click={toggleSidebar}
                    class="text-gray-500 focus:outline-none lg:hidden"
                >
                    <Menu class="h-6 w-6" />
                </button>

                <div class="hidden md:block">
                    <h2 class="text-xl font-bold text-gray-800">
                        {tenant ? tenant.name : "SIM-PP Central"}
                    </h2>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <span
                            class="inline-block h-2 w-2 rounded-full bg-green-500"
                        ></span>
                        Terhubung sebagai {user?.role.replace("_", " ")}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <!-- Notifications -->
                <button
                    class="relative h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 transition-colors"
                >
                    <Bell class="h-5 w-5" />
                    <span
                        class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500 border-2 border-white"
                    ></span>
                </button>

                <!-- User Profile Dropdown -->
                <div class="relative">
                    <button
                        on:click={() => (isUserMenuOpen = !isUserMenuOpen)}
                        class="flex items-center gap-3 p-1 rounded-2xl hover:bg-gray-50 transition-colors"
                    >
                        <div
                            class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-100"
                        >
                            {getInitial(user?.name)}
                        </div>
                        <div class="text-left hidden lg:block pr-2">
                            <p class="text-sm font-bold text-gray-900">
                                {user?.name}
                            </p>
                            <p
                                class="text-[10px] text-gray-400 font-bold uppercase tracking-widest"
                            >
                                {user?.role.replace("_", " ")}
                            </p>
                        </div>
                        <ChevronDown
                            class="h-4 w-4 text-gray-400 hidden lg:block"
                        />
                    </button>

                    {#if isUserMenuOpen}
                        <div
                            class="absolute right-0 mt-3 w-56 rounded-2xl bg-white shadow-2xl shadow-gray-200/50 border border-gray-100 py-2 z-50 animate-in fade-in zoom-in duration-200"
                        >
                            <div class="px-4 py-3 border-b border-gray-50 mb-1">
                                <p
                                    class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"
                                >
                                    Akun Anda
                                </p>
                                <p
                                    class="text-sm font-medium text-gray-800 truncate"
                                >
                                    {user?.email}
                                </p>
                            </div>
                            <Link
                                href={route("profile.show")}
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                >Profil Saya</Link
                            >
                            <Link
                                href={route("profile.show")}
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                >Pengaturan Keamanan</Link
                            >
                            <div class="pt-1 mt-1 border-t border-gray-50">
                                <button
                                    on:click={handleLogout}
                                    class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium"
                                >
                                    Logout
                                </button>
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto overflow-x-hidden bg-gray-50 p-8">
            <slot />
        </main>
    </div>

    <!-- Mobile Overlay -->
    {#if isSidebarOpen}
        <div
            on:click={toggleSidebar}
            class="fixed inset-0 z-40 bg-gray-900/40 backdrop-blur-sm lg:hidden transition-opacity"
        ></div>
    {/if}
</div>

<style>
    :global(.animate-in) {
        animation-fill-mode: forwards;
    }
</style>
