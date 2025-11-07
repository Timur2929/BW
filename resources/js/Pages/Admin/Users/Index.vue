<template>
    <AdminLayout>
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Заголовок и кнопка -->
            <div class="sm:flex sm:items-center sm:justify-between mb-8">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl font-bold leading-7 text-white-900 sm:truncate">Управление пользователями</h1>
                    <p class="mt-1 text-sm text-white-500">Всего пользователей: {{ users.total }}</p>
                </div>
                <Link 
                    :href="route('admin.users.create')"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <PlusIcon class="h-4 w-4 mr-2" />
                    Создать пользователя
                </Link>
            </div>

            <!-- Таблица -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Пользователь
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Контакты
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Роль
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Статистика
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Дата регистрации
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                                <!-- Пользователь -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                                <img 
                                                    v-if="user.avatar" 
                                                    :src="`/storage/${user.avatar}`" 
                                                    :alt="user.full_name"
                                                    class="h-10 w-10 object-cover"
                                                >
                                                <span v-else class="text-gray-600 font-semibold text-sm">
                                                    {{ getInitials(user) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ user.full_name || `${user.first_name} ${user.last_name}` }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: {{ user.id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Контакты -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ user.email }}</div>
                                    <div class="text-sm text-gray-500" v-if="user.phone">
                                        {{ user.phone }}
                                    </div>
                                    <div class="text-sm text-gray-400" v-else>
                                        Телефон не указан
                                    </div>
                                </td>

                                <!-- Роль -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        :class="[
                                            'inline-flex px-2 text-xs font-semibold leading-5 rounded-full',
                                            user.role === 'admin' 
                                                ? 'bg-purple-100 text-purple-800' 
                                                : 'bg-green-100 text-green-800'
                                        ]"
                                    >
                                        {{ user.role === 'admin' ? 'Администратор' : 'Пользователь' }}
                                    </span>
                                </td>

                                <!-- Статистика -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="space-y-1">
                                        <div class="flex items-center text-sm">
                                            <BookOpenIcon class="h-4 w-4 mr-1 text-blue-500" />
                                            Рецепты: {{ user.recipes_count || 0 }}
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <StarIcon class="h-4 w-4 mr-1 text-yellow-500" />
                                            Отзывы: {{ user.reviews_count || 0 }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Дата -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ formatDate(user.created_at) }}
                                </td>

                                <!-- Действия -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <Link 
                                            :href="route('admin.users.show', user.id)"
                                            class="text-blue-600 hover:text-blue-900 inline-flex items-center"
                                            title="Просмотр"
                                        >
                                            <EyeIcon class="h-4 w-4" />
                                        </Link>
                                        <Link 
                                            :href="route('admin.users.edit', user.id)"
                                            class="text-indigo-600 hover:text-indigo-900 inline-flex items-center"
                                            title="Редактировать"
                                        >
                                            <PencilIcon class="h-4 w-4" />
                                        </Link>
                                        <button 
                                            @click="deleteUser(user)"
                                            class="text-red-600 hover:text-red-900 inline-flex items-center"
                                            title="Удалить"
                                            :disabled="user.id === $page.props.auth.user.id"
                                        >
                                            <TrashIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Сообщение если нет пользователей -->
                <div v-if="users.data.length === 0" class="text-center py-12">
                    <UserGroupIcon class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Нет пользователей</h3>
                    <p class="mt-1 text-sm text-gray-500">Начните с создания нового пользователя.</p>
                </div>
            </div>

            <!-- Пагинация -->
            <div class="mt-6" v-if="users.data.length > 0">
                <Pagination :links="users.links" />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { inject } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import {
    PlusIcon,
    EyeIcon,
    PencilIcon,
    TrashIcon,
    UserGroupIcon,
    BookOpenIcon,
    StarIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    users: Object
});

const swal = inject('$swal');

const getInitials = (user) => {
    const first = user.first_name?.charAt(0) || '';
    const last = user.last_name?.charAt(0) || '';
    return (first + last).toUpperCase() || 'U';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const deleteUser = (user) => {
    if (user.id === window.Laravel?.user?.id) {
        swal({
            title: 'Ошибка',
            text: 'Вы не можете удалить свой собственный аккаунт',
            icon: 'error',
            confirmButtonText: 'Понятно'
        });
        return;
    }

    swal({
        title: 'Вы уверены?',
        text: `Пользователь "${user.full_name || user.first_name + ' ' + user.last_name}" будет удален безвозвратно!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.users.destroy', user.id), {
                preserveScroll: true,
                onSuccess: () => {
                    swal({
                        title: 'Успешно!',
                        text: 'Пользователь удален',
                        icon: 'success',
                        timer: 2000
                    });
                }
            });
        }
    });
};
</script>