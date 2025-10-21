<template>
    <AppLayout>
        <Head title="Отправить рецепт" />

        <div class="py-12 bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center mb-8">
                        <svg class="w-8 h-8 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <h2 class="text-3xl font-bold text-white">Отправить украшение на модерацию</h2>
                    </div>

                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Основная информация --> 
                        <div class="space-y-6 bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <h3 class="text-xl font-semibold text-blue-400 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Основная информация
                            </h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Название украшения</label>
                                <input 
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Серьги с серафинитом"
                                    required
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-400">{{ form.errors.name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Описание</label>
                                <textarea 
                                    v-model="form.description"
                                    rows="4"
                                    class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Расскажите о вашем украшении..."
                                    required
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-400">{{ form.errors.description }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Время приготовления</label>
                                    <input 
                                        v-model="form.cooking_time"
                                        type="text"
                                        placeholder="2 дня "
                                        class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    />
                                    <p v-if="form.errors.cooking_time" class="mt-1 text-sm text-red-400">{{ form.errors.cooking_time }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1">Категория</label>
                                    <select 
                                        v-model="form.category_id"
                                        class="mt-1 block w-full bg-gray-600 border border-gray-500 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    >
                                        <option value="" class="text-gray-400">Выберите категорию</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id" class="text-white">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-400">{{ form.errors.category_id }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Фото украшения</label>
                                <div class="mt-2 flex items-center">
                                    <input 
                                        type="file"
                                        @change="handleImageUpload"
                                        accept="image/*"
                                        class="hidden"
                                        ref="imageInput"
                                        required
                                    />
                                    <button
                                        type="button"
                                        @click="$refs.imageInput.click()"
                                        class="px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors"
                                    >
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Выбрать фото
                                    </button>
                                    <div v-if="previewImage" class="ml-4 relative group">
                                        <img :src="previewImage" class="h-24 w-24 object-cover rounded-lg border-2 border-gray-600" />
                                        <button 
                                            type="button"
                                            @click="previewImage = null; form.image = null"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <p v-if="form.errors.image" class="mt-1 text-sm text-red-400">{{ form.errors.image }}</p>
                            </div>
                        </div>

                        <!-- Ингредиенты -->
                        <div class="space-y-6 bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-semibold text-blue-400 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    Компоненты
                                </h3>
                                <button 
                                    type="button"
                                    @click="addIngredient"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors"
                                >
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Добавить 
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div v-for="(ingredient, index) in form.ingredients" :key="index" class="flex gap-4 items-center bg-gray-600 p-4 rounded-lg">
                                    <div class="flex-1">
                                        <label class="block text-xs font-medium text-gray-300 mb-1">Название</label>
                                        <input 
                                            v-model="ingredient.name"
                                            type="text"
                                            placeholder="Бусы из алмазов"
                                            class="w-full bg-gray-500 border border-gray-400 rounded-md px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                            required
                                        />
                                    </div>
                                    <div class="w-24">
                                        <label class="block text-xs font-medium text-gray-300 mb-1">Количество</label>
                                        <input 
                                            v-model="ingredient.amount"
                                            type="text"
                                            placeholder="2"
                                            class="w-full bg-gray-500 border border-gray-400 rounded-md px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                            required
                                        />
                                    </div>
                                    <div class="w-24">
                                        <label class="block text-xs font-medium text-gray-300 mb-1">Единица</label>
                                        <input 
                                            v-model="ingredient.unit"
                                            type="text"
                                            placeholder="шт"
                                            class="w-full bg-gray-500 border border-gray-400 rounded-md px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-transparent"
                                            required
                                        />
                                    </div>
                                    <button 
                                        type="button"
                                        @click="removeIngredient(index)"
                                        class="mt-5 p-1 text-red-400 hover:text-red-300 transition-colors"
                                        :disabled="form.ingredients.length === 1"
                                        :class="{'opacity-50 cursor-not-allowed': form.ingredients.length === 1}"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Шаги приготовления -->
                        <div class="space-y-6 bg-gray-700 p-6 rounded-lg border border-gray-600">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xl font-semibold text-blue-400 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Шаги приготовления
                                </h3>
                                <button 
                                    type="button"
                                    @click="addStep"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-colors"
                                >
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Добавить шаг
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div v-for="(step, index) in form.steps" :key="index" class="bg-gray-600 rounded-lg p-5 border border-gray-500">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="flex items-center">
                                            <span class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full font-bold mr-3">
                                                {{ step.order }}
                                            </span>
                                            <h4 class="font-medium text-white">Шаг приготовления</h4>
                                        </div>
                                        <button 
                                            type="button"
                                            @click="removeStep(index)"
                                            class="text-red-400 hover:text-red-300 transition-colors"
                                            :disabled="form.steps.length === 1"
                                            :class="{'opacity-50 cursor-not-allowed': form.steps.length === 1}"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-300 mb-1">Описание шага</label>
                                        <textarea 
                                            v-model="step.description"
                                            rows="3"
                                            placeholder="Подробно опишите этот шаг..."
                                            class="mt-1 block w-full bg-gray-500 border border-gray-400 rounded-lg px-4 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            required
                                        ></textarea>
                                    </div>

                                    <div>
                                        <input 
                                            type="file"
                                            @change="(e) => handleStepImageUpload(e, index)"
                                            accept="image/*"
                                            class="hidden"
                                            :ref="el => { if (el) stepImageInputs[index] = el }"
                                        />
                                        <button
                                            type="button"
                                            @click="stepImageInputs[index]?.click()"
                                            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-gray-400 rounded-lg font-medium text-sm text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-600 transition-colors"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Добавить фото к шагу
                                        </button>
                                        <div v-if="stepPreviews[index]" class="mt-3 relative inline-block">
                                            <img :src="stepPreviews[index]" class="h-24 w-24 object-cover rounded-lg border-2 border-gray-500" />
                                            <button 
                                                type="button"
                                                @click="stepPreviews[index] = null; form.step_images[index] = null"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 hover:opacity-100 transition-opacity"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6">
                            <button
                                type="submit"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-500 border border-transparent rounded-lg font-medium text-white shadow-lg hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="form.processing || isSubmitting"
                            >
                                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                {{ isSubmitting ? 'Отправка...' : 'Отправить на модерацию' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    }
});

const form = useForm({
    name: '',
    description: '',
    cooking_time: '',
    category_id: '',
    image: null,
    ingredients: [{ name: '', amount: '', unit: '' }],
    steps: [{ description: '', order: 1 }],
    step_images: {}
});

const previewImage = ref(null);
const stepPreviews = ref({});
const stepImageInputs = ref({});
const isSubmitting = ref(false);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleStepImageUpload = (event, index) => {
    const file = event.target.files[0];
    if (file) {
        form.step_images[index] = file;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            stepPreviews.value[index] = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const addIngredient = () => {
    form.ingredients.push({ name: '', amount: '', unit: '' });
};

const removeIngredient = (index) => {
    form.ingredients.splice(index, 1);
};

const addStep = () => {
    const newOrder = form.steps.length + 1;
    form.steps.push({
        description: '',
        order: newOrder
    });
};

const removeStep = (index) => {
    form.steps.splice(index, 1);
    delete stepPreviews.value[index];
    delete form.step_images[index];
    
    // Обновляем порядок шагов
    form.steps.forEach((step, i) => {
        step.order = i + 1;
    });
};

const submit = () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;

    // Проверяем обязательные поля
    if (!form.image) {
        alert('Пожалуйста, добавьте основное фото рецепта');
        isSubmitting.value = false;
        return;
    }

    if (!form.category_id) {
        alert('Пожалуйста, выберите категорию');
        isSubmitting.value = false;
        return;
    }

    // Проверяем Компоненты
    if (!form.ingredients.every(ing => ing.name && ing.amount && ing.unit)) {
        alert('Пожалуйста, заполните все поля для каждого компоненты');
        isSubmitting.value = false;
        return;
    }

    // Проверяем шаги
    if (!form.steps.every(step => step.description)) {
        alert('Пожалуйста, добавьте описание для каждого шага');
        isSubmitting.value = false;
        return;
    }

    // Отправляем данные
    form.post(route('recipes.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            // Очищаем форму после успешной отправки
            form.reset();
            previewImage.value = null;
            stepPreviews.value = {};
            form.step_images = {};
            isSubmitting.value = false;
        },
        onError: (error) => {
            alert('Ошибка номер:', error)
            isSubmitting.value = false;
            // Прокручиваем к первой ошибке
            const firstError = document.querySelector('.text-red-400');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
};
</script>

<style scoped>
/* Плавные переходы для всех интерактивных элементов */
button, input, textarea, select {
    transition: all 0.2s ease;
}

/* Кастомный скроллбар */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #374151;
}

::-webkit-scrollbar-thumb {
    background: #4B5563;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #6B7280;
}
</style>