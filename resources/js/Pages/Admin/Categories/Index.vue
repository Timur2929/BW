<template>
    <AdminLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Заголовок и кнопка создания -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-white">Управление категориями</h1>
                    <Link 
                        :href="route('admin.categories.create')"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Создать категорию
                    </Link>
                </div>

                <!-- Уведомления -->
                <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-600 text-white p-4 rounded-lg mb-6">
                    {{ $page.props.flash.success }}
                </div>

                <!-- Таблица категорий -->
                <div v-if="categories.length > 0" class="bg-gray-800 rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Изображение
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Название
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Рецептов
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Дата создания
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                    Действия
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            <tr v-for="category in categories" :key="category.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img 
                                        v-if="category.image" 
                                        :src="category.image" 
                                        :alt="category.name"
                                        class="w-12 h-12 rounded-lg object-cover"
                                    >
                                    <div v-else class="w-12 h-12 bg-gray-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">{{ category.name }}</div>
                                    <div v-if="category.description" class="text-sm text-gray-300 truncate max-w-xs">
                                        {{ category.description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ category.recipes_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ category.created_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <Link 
                                        :href="route('admin.categories.edit', category.id)"
                                        class="text-blue-400 hover:text-blue-300 transition-colors"
                                    >
                                        Редактировать
                                    </Link>
                                    <button 
                                        @click="confirmDelete(category)"
                                        class="text-red-400 hover:text-red-300 transition-colors"
                                    >
                                        Удалить
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Сообщение если нет категорий -->
                <div v-else class="bg-gray-800 rounded-lg p-8 text-center">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-white">Нет категорий</h3>
                    <p class="mt-2 text-gray-400">Создайте первую категорию для рецептов</p>
                </div>

                <!-- Модальное окно подтверждения удаления -->
                <div v-if="showDeleteModal" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50">
                    <div class="fixed inset-0 transform transition-all" @click="showDeleteModal = false">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <div class="mb-6 bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto max-w-lg">
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-white mb-4">
                                Удаление категории
                            </h2>

                            <div class="text-gray-300 mb-6">
                                Вы уверены, что хотите удалить категорию "{{ categoryToDelete?.name }}"?
                                <span v-if="categoryToDelete?.recipes_count > 0" class="text-red-400 block mt-2">
                                    Внимание: в этой категории {{ categoryToDelete?.recipes_count }} рецептов!
                                </span>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button 
                                    @click="showDeleteModal = false" 
                                    class="px-4 py-2 text-gray-300 hover:text-white transition-colors"
                                >
                                    Отмена
                                </button>
                                <button 
                                    @click="deleteCategory" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                >
                                    Удалить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true,
        default: () => []
    }
});

const showDeleteModal = ref(false);
const categoryToDelete = ref(null);

const confirmDelete = (category) => {
    categoryToDelete.value = category;
    showDeleteModal.value = true;
};

const deleteCategory = () => {
    if (categoryToDelete.value) {
        useForm().delete(route('admin.categories.destroy', categoryToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                categoryToDelete.value = null;
            }
        });
    }
};
</script>