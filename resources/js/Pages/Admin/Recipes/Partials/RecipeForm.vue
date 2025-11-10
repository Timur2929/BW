<template>
    <form @submit.prevent="submit">
        <div class="shadow sm:rounded-md sm:overflow-hidden">
            <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Основная информация</h3>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Название украшения
                            </label>
                            <div class="mt-1">
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md text-gray-900"
                                    :class="{ 'border-red-300': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.name }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">
                                Категория
                            </label>
                            <div class="mt-1">
                                <select
                                    v-model="form.category_id"
                                    id="category_id"
                                    name="category_id"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                    :class="{ 'border-red-300': form.errors.category_id }"
                                >
                                    <option value="">Выберите категорию</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.category_id }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="cooking_time" class="block text-sm font-medium text-gray-700">
                                Время изготовления
                            </label>
                            <div class="mt-1">
                                <input
                                    v-model="form.cooking_time"
                                    type="text"
                                    name="cooking_time"
                                    id="cooking_time"
                                    class="shadow-sm focus:ring-indigo-500 text-gray-900 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    :class="{ 'border-red-300': form.errors.cooking_time }"
                                    placeholder="например: 2 дня"
                                />
                                <p v-if="form.errors.cooking_time" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.cooking_time }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="price" class="block text-sm font-medium text-gray-700">
                                Цена (руб.)
                            </label>
                            <div class="mt-1">
                                <input
                                    v-model="form.price"
                                    type="number"
                                    step="0.01"
                                    name="price"
                                    id="price"
                                    class="shadow-sm focus:ring-indigo-500 text-gray-900 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    :class="{ 'border-red-300': form.errors.price }"
                                />
                                <p v-if="form.errors.price" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.price }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">
                                Количество в наличии
                            </label>
                            <div class="mt-1">
                                <input
                                    v-model="form.quantity"
                                    type="number"
                                    name="quantity"
                                    id="quantity"
                                    class="shadow-sm focus:ring-indigo-500 text-gray-900 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    :class="{ 'border-red-300': form.errors.quantity }"
                                />
                                <p v-if="form.errors.quantity" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.quantity }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Описание
                            </label>
                            <div class="mt-1">
                                <textarea
                                    v-model="form.description"
                                    id="description"
                                    name="description"
                                    rows="3"
                                    class="shadow-sm focus:ring-indigo-500 text-gray-900 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                    :class="{ 'border-red-300': form.errors.description }"
                                />
                                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">
                                    {{ form.errors.description }}
                                </p>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700">Главное изображение</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Загрузить файл</span>
                                            <input 
                                                id="image" 
                                                name="image" 
                                                type="file" 
                                                class="sr-only" 
                                                @change="handleImageUpload"
                                                accept="image/*"
                                            />
                                        </label>
                                        <p class="pl-1">или перетащите</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF до 2MB</p>
                                </div>
                            </div>
                            <p v-if="form.errors.image" class="mt-2 text-sm text-red-600">
                                {{ form.errors.image }}
                            </p>
                            <div v-if="imagePreview" class="mt-4">
                                <img :src="imagePreview" class="h-32 w-32 object-cover rounded-lg" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ingredients -->
                <div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Материалы</h3>
                        <button 
                            type="button" 
                            @click="addIngredient"
                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm  leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150"
                        >
                            Добавить материал
                        </button>
                    </div>
                    <div class="mt-6 space-y-4">
                        <div v-for="(ingredient, index) in form.ingredients" :key="index" class="flex gap-4 items-start">
                            <div class="flex-1">
                                <input
                                    v-model="ingredient.name"
                                    type="text"
                                    placeholder="Название материала"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 block w-full sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>
                            <div class="w-24">
                                <input
                                    v-model="ingredient.amount"
                                    type="text"
                                    placeholder="Количество"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 block w-full sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>
                            <div class="w-24">
                                <input
                                    v-model="ingredient.unit"
                                    type="text"
                                    placeholder="Ед. изм."
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 block w-full sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>
                            <button 
                                type="button" 
                                @click="removeIngredient(index)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Удалить
                            </button>
                        </div>
                    </div>
                    <p v-if="form.errors.ingredients" class="mt-2 text-sm text-red-600">
                        {{ form.errors.ingredients }}
                    </p>
                </div>

                <!-- Steps -->
                <div>
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Особенности</h3>
                        <button 
                            type="button" 
                            @click="addStep"
                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition ease-in-out duration-150"
                        >
                            Добавить особенность
                        </button>
                    </div>
                    <div class="mt-6 space-y-4">
                        <div v-for="(step, index) in form.steps" :key="index" class="flex gap-4 items-start">
                            <div class="w-16">
                                <input
                                    v-model="step.order"
                                    type="number"
                                    class="shadow-sm focus:ring-indigo-500 text-gray-900 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    min="1"
                                />
                            </div>
                            <div class="flex-1">
                                <textarea
                                    v-model="step.description"
                                    rows="2"
                                    placeholder="Описание особенности"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 block w-full sm:text-sm border-gray-300 rounded-md"
                                />
                            </div>
                            <button 
                                type="button" 
                                @click="removeStep(index)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Удалить
                            </button>
                        </div>
                    </div>
                    <p v-if="form.errors.steps" class="mt-2 text-sm text-red-600">
                        {{ form.errors.steps }}
                    </p>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <Link 
                    :href="route(cancelRoute)"
                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Отмена
                </Link>
                <button
                    type="submit"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Сохранение...' : 'Сохранить' }}
                </button>
            </div>
        </div>
    </form>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    recipe: {
        type: Object,
        default: null
    },
    categories: Array,
    submitRoute: String,
    method: {
        type: String,
        default: 'post'
    },
    cancelRoute: {
        type: String,
        default: 'admin.recipes.index'
    }
});

const imagePreview = ref(null);

const form = useForm({
    name: props.recipe?.name || '',
    description: props.recipe?.description || '',
    category_id: props.recipe?.category?.id || '',
    cooking_time: props.recipe?.cooking_time || '',
    price: props.recipe?.price || 0,
    quantity: props.recipe?.quantity || 0,
    image: null,
    ingredients: props.recipe?.ingredients || [{ name: '', amount: '', unit: '' }],
    steps: props.recipe?.steps || [{ order: 1, description: '' }]
});

function addIngredient() {
    form.ingredients.push({ name: '', amount: '', unit: '' });
}

function removeIngredient(index) {
    form.ingredients.splice(index, 1);
}

function addStep() {
    const nextOrder = form.steps.length > 0 ? Math.max(...form.steps.map(s => s.order)) + 1 : 1;
    form.steps.push({ order: nextOrder, description: '' });
}

function removeStep(index) {
    form.steps.splice(index, 1);
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function submit() {
    if (props.method === 'put') {
        form.put(props.submitRoute);
    } else {
        form.post(props.submitRoute);
    }
}

// Set initial image preview if editing
if (props.recipe?.image) {
    imagePreview.value = props.recipe.image;
}
</script>