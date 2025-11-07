<template>
    <AdminLayout>
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-2xl font-semibold text-gray-900">Управление рецептами</h1>
                    <p class="mt-2 text-sm text-gray-700">
                        Список всех рецептов в системе
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link 
                        :href="route('admin.recipes.create')"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                    >
                        Добавить рецепт
                    </Link>
                </div>
            </div>

            <!-- Фильтры -->
            <div class="mt-6 bg-white shadow rounded-lg p-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input
                            v-model="filters.search"
                            type="text"
                            placeholder="Поиск по названию, описанию или автору..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            @input="debouncedSearch"
                        />
                    </div>
                    <div class="w-full sm:w-48">
                        <select
                            v-model="filters.status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            @change="updateFilters"
                        >
                            <option v-for="(label, value) in statuses" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Таблица -->
            <div class="mt-8 flex flex-col">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            Рецепт
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Автор
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Категория
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Статус
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Цена / Кол-во
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Дата
                                        </th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Действия</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="recipe in recipes.data" :key="recipe.id">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full object-cover" 
                                                         :src="recipe.image" 
                                                         :alt="recipe.name" />
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900">{{ recipe.name }}</div>
                                                    <div class="text-gray-500 line-clamp-1">{{ recipe.description }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ recipe.user.full_name }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ recipe.category?.name || 'Без категории' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span :class="statusBadgeClasses(recipe.status)" class="inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                                {{ statusText(recipe.status) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ recipe.price }} ₽ / {{ recipe.quantity }} шт.
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ formatDate(recipe.created_at) }}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex justify-end space-x-2">
                                                <Link 
                                                    :href="route('admin.recipes.edit', recipe.id)"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    Редактировать
                                                </Link>
                                                <button
                                                    @click="deleteRecipe(recipe)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Удалить
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Пагинация -->
            <Pagination :links="recipes.links" class="mt-6" />
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import { debounce } from 'lodash';

const props = defineProps({
    recipes: Object,
    filters: Object,
    statuses: Object
});

const filters = ref({
    search: props.filters.search || '',
    status: props.filters.status || 'all'
});

// Дебаунс для поиска
const debouncedSearch = debounce(() => {
    updateFilters();
}, 500);

const updateFilters = () => {
    router.get(route('admin.recipes.index'), filters.value, {
        preserveState: true,
        replace: true
    });
};

const statusBadgeClasses = (status) => {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'approved': 'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800',
        'revision': 'bg-blue-100 text-blue-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const statusText = (status) => {
    const texts = {
        'pending': 'На модерации',
        'approved': 'Одобрено',
        'rejected': 'Отклонено',
        'revision': 'На доработке'
    };
    return texts[status] || status;
};

const formatDate = (date) => {
    return new Date(date).toLocaleDate('ru-RU');
};

const deleteRecipe = (recipe) => {
    if (confirm(`Вы уверены, что хотите удалить рецепт "${recipe.name}"?`)) {
        router.delete(route('admin.recipes.destroy', recipe.id));
    }
};
</script>