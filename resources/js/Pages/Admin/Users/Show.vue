<template>
    <AdminLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Просмотр пользователя</h1>
                    <Link
                        :href="route('admin.users.index')"
                        class="text-gray-600 hover:text-gray-800 transition duration-200"
                    >
                        ← Назад к списку
                    </Link>
                </div>

                <!-- Обложка профиля -->
                <div v-if="user.cover_image" class="mb-6 rounded-lg overflow-hidden h-48">
                    <img 
                        :src="`/storage/${user.cover_image}`" 
                        :alt="user.full_name"
                        class="w-full h-full object-cover"
                    >
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Заголовок с аватаром -->
                    <div class="flex items-center space-x-6 mb-6">
                        <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                            <img 
                                v-if="user.avatar" 
                                :src="`/storage/${user.avatar}`" 
                                :alt="user.full_name"
                                class="w-full h-full object-cover"
                            >
                            <span v-else class="text-gray-600 font-semibold text-xl">
                                {{ user.first_name?.charAt(0) }}{{ user.last_name?.charAt(0) }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">{{ user.full_name }}</h2>
                            <p class="text-gray-600">{{ user.email }}</p>
                            <span 
                                :class="[
                                    'mt-2 inline-block px-3 py-1 text-sm font-semibold rounded-full',
                                    user.role === 'admin' 
                                        ? 'bg-purple-100 text-purple-800' 
                                        : 'bg-green-100 text-green-800'
                                ]"
                            >
                                {{ user.role === 'admin' ? 'Администратор' : 'Пользователь' }}
                            </span>
                        </div>
                    </div>

                    <!-- Информация -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t">
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">ID</label>
                                <p class="text-gray-900">{{ user.id }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Имя</label>
                                <p class="text-gray-900">{{ user.first_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Фамилия</label>
                                <p class="text-gray-900">{{ user.last_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Телефон</label>
                                <p class="text-gray-900">{{ user.phone || 'Не указан' }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Статистика</label>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-2xl font-bold text-blue-600">{{ user.recipes_count || 0 }}</p>
                                        <p class="text-sm text-gray-600">Рецептов</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-2xl font-bold text-green-600">{{ user.reviews_count || 0 }}</p>
                                        <p class="text-sm text-gray-600">Отзывов</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-2xl font-bold text-purple-600">{{ user.favorites_count || 0 }}</p>
                                        <p class="text-sm text-gray-600">В избранном</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Дата регистрации</label>
                                <p class="text-gray-900">{{ formatDate(user.created_at) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Последнее обновление</label>
                                <p class="text-gray-900">{{ formatDate(user.updated_at) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Действия -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                        <Link
                            :href="route('admin.users.edit', user.id)"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
                        >
                            Редактировать
                        </Link>
                        <Link
                            :href="route('admin.users.index')"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200"
                        >
                            Назад
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>