<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-black">
        <template v-if="!minimal">
            <!-- Навигация -->
            <nav class="bg-gray-800/80 backdrop-blur-xl border-b border-gray-700/50 shadow-2xl">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Логотип -->
                            <div class="flex-shrink-0 flex items-center">
                                <Link :href="route('admin.articles.create')" class="text-xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                                    Админ-панель
                                </Link>
                            </div>

                            <!-- Навигационные ссылки -->
                            <div class="hidden sm:ml-8 sm:flex sm:space-x-6">
                                <Link
                                    :href="route('admin.articles.index')"
                                    class="relative group inline-flex items-center px-3 py-2 text-sm font-medium transition-all duration-300"
                                    :class="route().current('articles.*') 
                                        ? 'text-white' 
                                        : 'text-gray-400 hover:text-white'"
                                >
                                    <span class="relative z-10 flex items-center">
                                        <svg class="w-5 h-5 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Статьи
                                    </span>
                                    <span v-if="route().current('articles.*')" class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-lg border border-purple-500/30"></span>
                                </Link>

                               <Link
    :href="route('admin.orders.index')"
    class="relative group inline-flex items-center px-3 py-2 text-sm font-medium transition-all duration-300"
    :class="route().current('admin.orders.*') 
        ? 'text-white' 
        : 'text-gray-400 hover:text-white'"
>
    <span class="relative z-10 flex items-center">
        <svg class="w-5 h-5 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        Заказы
    </span>
    <span v-if="route().current('admin.orders.*')" class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-lg border border-purple-500/30"></span>
</Link>

                               <Link
    :href="route('admin.users.index')"
    class="relative group inline-flex items-center px-3 py-2 text-sm font-medium transition-all duration-300 text-gray-400 hover:text-white"
>
    <svg class="w-5 h-5 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
    </svg>
    Пользователи
</Link>

                                <Link
                                    :href="route('admin.articles.index')"
                                    class="relative group inline-flex items-center px-3 py-2 text-sm font-medium transition-all duration-300 text-gray-400 hover:text-white"
                                >
                                    <svg class="w-5 h-5 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    Категории
                                </Link>

                                <Link
                                    :href="route('admin.articles.index')"
                                    class="relative group inline-flex items-center px-3 py-2 text-sm font-medium transition-all duration-300 text-gray-400 hover:text-white"
                                >
                                    <svg class="w-5 h-5 mr-2 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    Украшения
                                </Link>
                            </div>
                        </div>

                        <!-- Правая часть навигации -->
                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <div class="ml-3 relative">
                                <div class="flex items-center space-x-6">
                                    <span class="text-gray-300 font-medium bg-gray-700/50 px-3 py-1 rounded-lg border border-gray-600/50">
                                        {{ $page.props.auth.user.name }}
                                    </span>
                                    <Link
                                        :href="route('profile.edit')"
                                        class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center group"
                                    >
                                        <svg class="w-5 h-5 mr-1 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Профиль
                                    </Link>
                                    <form @submit.prevent="logout" class="flex items-center">
                                        <button
                                            type="submit"
                                            class="text-gray-400 hover:text-red-400 transition-colors duration-300 flex items-center group"
                                        >
                                            <svg class="w-5 h-5 mr-1 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Выйти
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </template>

        <!-- Основной контент -->
        <main class="relative">
            <!-- Декоративный фон -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-32 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-32 w-80 h-80 bg-pink-500/10 rounded-full blur-3xl"></div>
            </div>
            <div class="relative z-10">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    minimal: {
        type: Boolean,
        default: false
    }
});

const logout = () => {
    router.post(route('logout'));
};
</script>

<style scoped>
/* Плавные переходы для всех интерактивных элементов */
* {
    transition-property: color, background-color, border-color, transform, opacity;
    transition-duration: 300ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Эффекты при наведении для навигационных ссылок */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}
</style>