<script>
  import { useForm } from '@inertiajs/svelte';
  import { BookOpen, Lock, Mail, Eye, EyeOff } from 'lucide-svelte';

  const form = useForm({
    email: '',
    password: '',
    remember: false,
  });

  let showPassword = false;

  function submit() {
    $form.post('/login', {
      onFinish: () => $form.reset('password'),
    });
  }
</script>

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50 flex items-center justify-center p-4">
  <div class="max-w-md w-full">
    <!-- Logo & Title -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-white shadow-xl shadow-blue-100 mb-4 border border-blue-50">
        <BookOpen class="h-8 w-8 text-blue-600" />
      </div>
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">SIM-PP</h1>
      <p class="text-gray-500 mt-2">Sistem Informasi Manajemen Pondok Pesantren</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-3xl shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
      <div class="p-8">
        <div class="mb-8">
          <h2 class="text-xl font-bold text-gray-800">Selamat Datang Kembali</h2>
          <p class="text-gray-500 text-sm">Silakan login untuk mengakses dashboard panel</p>
        </div>

        <form on:submit|preventDefault={submit} class="space-y-6">
          <!-- Email Input -->
          <div class="space-y-1.5">
            <label for="email" class="text-sm font-semibold text-gray-700 ml-1">Email Sekolah / User</label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-blue-500 transition-colors">
                <Mail class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" />
              </div>
              <input
                id="email"
                type="email"
                bind:value={$form.email}
                required
                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                placeholder="ustadz@alikhlas.com"
              />
            </div>
            {#if $form.errors.email}
              <p class="text-red-500 text-xs mt-1 ml-1">{$form.errors.email}</p>
            {/if}
          </div>

          <!-- Password Input -->
          <div class="space-y-1.5">
            <div class="flex items-center justify-between ml-1">
              <label for="password" class="text-sm font-semibold text-gray-700">Kata Sandi</label>
              <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">Lupa password?</a>
            </div>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none group-focus-within:text-blue-500 transition-colors">
                <Lock class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" />
              </div>
              <input
                id="password"
                type={showPassword ? 'text' : 'password'}
                bind:value={$form.password}
                required
                class="block w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 text-gray-900 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none"
                placeholder="••••••••"
              />
              <button
                type="button"
                on:click={() => showPassword = !showPassword}
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition"
              >
                {#if showPassword}
                  <EyeOff class="h-5 w-5" />
                {:else}
                  <Eye class="h-5 w-5" />
                {/if}
              </button>
            </div>
            {#if $form.errors.password}
              <p class="text-red-500 text-xs mt-1 ml-1">{$form.errors.password}</p>
            {/if}
          </div>

          <div class="flex items-center ml-1">
            <input
              id="remember"
              type="checkbox"
              bind:checked={$form.remember}
              class="h-4 w-4 text-blue-600 rounded-md border-gray-300 focus:ring-blue-500"
            />
            <label for="remember" class="ml-2 block text-sm text-gray-500">
              Ingat saya di perangkat ini
            </label>
          </div>

          <button
            type="submit"
            disabled={$form.processing}
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-xl shadow-blue-200 transition-all transform active:scale-[0.98] disabled:opacity-70 flex items-center justify-center gap-2"
          >
            {#if $form.processing}
              <div class="h-5 w-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
              Sedang masuk...
            {:else}
              Masuk ke Dashboard
            {/if}
          </button>
        </form>

        <div class="mt-8 text-center pt-8 border-t border-dotted border-gray-100">
          <p class="text-gray-500 text-sm">
            Belum mendaftarkan pondok Anda? 
            <a href="#" class="text-blue-600 font-bold hover:text-blue-700 ml-1 transition">Hubungi Kami</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <p class="mt-8 text-center text-gray-400 text-xs">
      &copy; 2026 SIM-PP Team. All rights reserved. <br>
      Made with ❤️ for Indonesian Pesantren
    </p>
  </div>
</div>

<style>
  :global(body) {
    font-family: 'Inter', sans-serif;
  }
</style>
