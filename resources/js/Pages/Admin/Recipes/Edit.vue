<template>
    <AdminLayout>
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-white-900 sm:text-3xl sm:truncate">
                        Редактирование украшения
                    </h2>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <Link 
                        :href="route('admin.recipes.index')"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Назад к списку
                    </Link>
                </div>
            </div>

            <div class="mt-8">
                <RecipeForm 
                    :recipe="recipe"
                    :categories="categories"
                    :submit-route="route('admin.recipes.update', recipe.id)"
                    method="put"
                    cancel-route="admin.recipes.index"
                />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import RecipeForm from './Partials/RecipeForm.vue';

defineProps({
    recipe: Object,
    categories: Array
});
</script>