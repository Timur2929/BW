<template>
    <form @submit.prevent="submit">
        <div class="space-y-8 divide-y divide-gray-200">
            <!-- Основная информация -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Основная информация</h3>
                    <p class="mt-1 text-sm text-gray-500">Основные данные о рецепте</p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                    <!-- Название -->
                    <div class="sm:col-span-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Название рецепта *
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Категория -->
                    <div class="sm:col-span-2">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">
                            Категория *
                        </label>
                        <select
                            v-model="form.category_id"
                            id="category_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
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

                    <!-- Описание -->
                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Описание *
                        </label>
                        <textarea
                            v-model="form.description"
                            id="description"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300': form.errors.description }"
                        />
                        <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Время приготовления -->
                    <div class="sm:col-span-2">
                        <label for="cooking_time" class="block text-sm font-medium text-gray-700">
                            Время приготовления *
                        </label>
                        <input
                            v-model="form.cooking_time"
                            type="text"
                            id="cooking_time"
                            placeholder="30 мин"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300': form.errors.cooking_time }"
                        />
                        <p v-if="form.errors.cooking_time" class="mt-2 text-sm text-red-600">
                            {{ form.errors.cooking_time }}
                        </p>
                    </div>

                    <!-- Количество порций -->
                    <div class="sm:col-span-2">
                        <label for="servings" class="block text-sm font-medium text-gray-700">
                            Количество порций
                        </label>
                        <input
                            v-model="form.servings"
                            type="number"
                            id="servings"
                            min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                    </div>

                    <!-- Сложность -->
                    <div class="sm:col-span-2">
                        <label for="difficulty" class="block text-sm font-medium text-gray-700">
                            Сложность
                        </label>
                        <select
                            v-model="form.difficulty"
                            id="difficulty"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option value="easy">Легко</option>
                            <option value="medium">Средне</option>
                            <option value="hard">Сложно</option>
                        </select>
                    </div>

                    <!-- Цена и количество -->
                    <div class="sm:col-span-3">
                        <label for="price" class="block text-sm font-medium text-gray-700">
                            Цена *
                        </label>
                        <input
                            v-model="form.price"
                            type="number"
                            id="price"
                            step="0.01"
                            min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300': form.errors.price }"
                        />
                        <p v-if="form.errors.price" class="mt-2 text-sm text-red-600">
                            {{ form.errors.price }}
                        </p>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="quantity" class="block text-sm font-medium text-gray-700">
                            Количество *
                        </label>
                        <input
                            v-model="form.quantity"
                            type="number"
                            id="quantity"
                            min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300': form.errors.quantity }"
                        />
                        <p v-if="form.errors.quantity" class="mt-2 text-sm text-red-600">
                            {{ form.errors.quantity }}
                        </p>
                    </div>

                    <!-- Статус (только при редактировании) -->
                    <div v-if="isEditing" class="sm:col-span-3">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Статус *
                        </label>
                        <select
                            v-model="form.status"
                            id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            :class="{ 'border-red-300': form.errors.status }"
                        >
                            <option v-for="(label, value) in statuses" :key="value" :value="value">
                                {{ label }}
                            </option>
                        </select>
                        <p v-if="form.errors.status" class="mt-2 text-sm text-red-600">
                            {{ form.errors.status }}
                        </p>
                    </div>

                    <!-- Основное изображение -->
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Основное изображение *
                        </label>
                        <div class="mt-1 flex items-center">
                            <img
                                v-if="form.image && typeof form.image === 'string'"
                                :src="form.image"
                                class="h-32 w-32 rounded-lg object-cover"
                            />
                            <img
                                v-else-if="imagePreview"
                                :src="imagePreview"
                                class="h-32 w-32 rounded-lg object-cover"
                            />
                            <div v-else class="h-32 w-32 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                <span class="text-gray-400">Изображение</span>
                            </div>
                            <input
                                type="file"
                                ref="imageInput"
                                class="ml-5 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                @change="handleImageUpload"
                                accept="image/*"
                            />
                        </div>
                        <p v-if="form.errors.image" class="mt-2 text-sm text-red-600">
                            {{ form.errors.image }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ингредиенты -->
            <div class="pt-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Ингредиенты</h3>
                        <p class="mt-1 text-sm text-gray-500">Список необходимых ингредиентов</p>
                    </div>
                    <button
                        type="button"
                        @click="addIngredient"
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Добавить ингредиент
                    </button>
                </div>

                <div class="mt-6 space-y-4">
                    <div
                        v-for="(ingredient, index) in form.ingredients"
                        :key="index"
                        class="flex items-start space-x-4"
                    >
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Название</label>
                            <input
                                v-model="ingredient.name"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-300': form.errors[`ingredients.${index}.name`] }"
                            />
                        </div>
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Количество</label>
                            <input
                                v-model="ingredient.amount"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-300': form.errors[`ingredients.${index}.amount`] }"
                            />
                        </div>
                        <div class="w-24">
                            <label class="block text-sm font-medium text-gray-700">Ед. изм.</label>
                            <input
                                v-model="ingredient.unit"
                                type="text"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                :class="{ 'border-red-300': form.errors[`ingredients.${index}.unit`] }"
                            />
                        </div>
                        <button
                            type="button"
                            @click="removeIngredient(index)"
                            class="mt-6 inline-flex items-center rounded-md border border-transparent bg-red-100 px-3 py-2 text-sm font-medium leading-4 text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                        >
                            Удалить
                        </button>
                    </div>
                </div>
            </div>

            <!-- Шаги приготовления -->
            <div class="pt-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Шаги приготовления</h3>
                        <p class="mt-1 text-sm text-gray-500">Пошаговое описание процесса приготовления</p>
                    </div>
                    <button
                        type="button"
                        @click="addStep"
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Добавить шаг
                    </button>
                </div>

                <div class="mt-6 space-y-6">
                    <div
                        v-for="(step, index) in form.steps"
                        :key="index"
                        class="border border-gray-200 rounded-lg p-6"
                    >
                        <div class="flex items-start justify-between mb-4">
                            <h4 class="text-lg font-medium text-gray-900">Шаг {{ index + 1 }}</h4>
                            <button
                                type="button"
                                @click="removeStep(index)"
                                class="text-red-600 hover:text-red-900"
                            >
                                Удалить шаг
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label class="block text-sm font-medium text-gray-700">Описание *</label>
                                <textarea
                                    v-model="step.description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors[`steps.${index}.description`] }"
                                />
                            </div>
                            
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Порядок *</label>
                                <input
                                    v-model="step.order"
                                    type="number"
                                    min="1"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    :class="{ 'border-red-300': form.errors[`steps.${index}.order`] }"
                                />
                            </div>

                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">
                                    Изображение шага (опционально)
                                </label>
                                <div class="mt-1 flex items-center">
                                    <img
                                        v-if="step.image && typeof step.image === 'string'"
                                        :src="step.image"
                                        class="h-24 w-24 rounded-lg object-cover"
                                    />
                                    <img
                                        v-else-if="stepImagesPreview[index]"
                                        :src="stepImagesPreview[index]"
                                        class="h-24 w-24 rounded-lg object-cover"
                                    />
                                    <div v-else class="h-24 w-24 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                        <span class="text-gray-400">Изображение</span>
                                    </div>
                                    <input
                                        type="file"
                                        class="ml-5 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        @change="(e) => handleStepImageUpload(e, index)"
                                        accept="image/*"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="pt-8">
                <div class="flex justify-end space-x-3">
                    <Link
                        :href="route('admin.recipes.index')"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Отмена
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50"
                    >
                        {{ isEditing ? 'Обновить рецепт' : 'Создать рецепт' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    recipe: Object,
    categories: Array,
    statuses: Object,
    isEditing: Boolean
});

const emit = defineEmits(['submitted']);

const imageInput = ref(null);
const imagePreview = ref(null);
const stepImagesPreview = ref({});

// Инициализация формы
const form = useForm({
    name: props.recipe?.name || '',
    description: props.recipe?.description || '',
    category_id: props.recipe?.category_id || '',
    cooking_time: props.recipe?.cooking_time || '',
    servings: props.recipe?.servings || 1,
    difficulty: props.recipe?.difficulty || 'medium',
    price: props.recipe?.price || 0,
    quantity: props.recipe?.quantity || 0,
    status: props.recipe?.status || 'pending',
    image: props.recipe?.image || null,
    ingredients: props.recipe?.ingredients?.map(ing => ({
        name: ing.name,
        amount: ing.amount,
        unit: ing.unit
    })) || [{ name: '', amount: '', unit: '' }],
    steps: props.recipe?.steps?.map(step => ({
        description: step.description,
        order: step.order,
        image: step.image || null
    })) || [{ description: '', order: 1, image: null }],
    step_images: {}
});

// Обработка загрузки основного изображения
const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        
        // Создаем превью
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Обработка загрузки изображений шагов
const handleStepImageUpload = (event, index) => {
    const file = event.target.files[0];
    if (file) {
        form.step_images[index] = file;
        
        // Создаем превью
        const reader = new FileReader();
        reader.onload = (e) => {
            stepImagesPreview.value[index] = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Добавление ингредиента
const addIngredient = () => {
    form.ingredients.push({ name: '', amount: '', unit: '' });
};

// Удаление ингредиента
const removeIngredient = (index) => {
    if (form.ingredients.length > 1) {
        form.ingredients.splice(index, 1);
    }
};

// Добавление шага
const addStep = () => {
    const nextOrder = Math.max(...form.steps.map(s => s.order), 0) + 1;
    form.steps.push({ description: '', order: nextOrder, image: null });
};

// Удаление шага
const removeStep = (index) => {
    if (form.steps.length > 1) {
        form.steps.splice(index, 1);
    }
};

// Отправка формы
const submit = () => {
    emit('submitted', form);
};
</script>