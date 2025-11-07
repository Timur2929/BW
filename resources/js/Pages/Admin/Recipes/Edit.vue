<template>
    <AdminLayout>
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl">
                        Редактирование рецепта: {{ recipe.name }}
                    </h2>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <Link 
                        :href="route('admin.recipes.index')"
                        class="ml-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    >
                        Назад к списку
                    </Link>
                </div>
            </div>

            <div class="mt-8">
                <RecipeForm 
                    :recipe="recipe"
                    :categories="categories"
                    :statuses="statuses"
                    :is-editing="true"
                    @submitted="updateRecipe"
                />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import RecipeForm from './Partials/RecipeForm.vue';

const props = defineProps({
    recipe: Object,
    categories: Array,
    statuses: Object
});

const updateRecipe = (form) => {
    form.put(route('admin.recipes.update', props.recipe.id));
};
</script>