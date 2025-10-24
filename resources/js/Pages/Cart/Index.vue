<template>
    <Head title="Корзина" />
    <AppLayout>
        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Заголовок -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-900">Корзина рецептов</h1>
                        <p class="text-gray-600 mt-1">Ваши выбранные рецепты для приготовления</p>
                    </div>

                    <!-- Пустая корзина -->
                    <div v-if="cartItems.length === 0" class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 text-gray-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Корзина пуста</h3>
                        <p class="text-gray-500 mb-6">Добавьте рецепты из каталога, чтобы начать готовить</p>
                        <Link 
                            :href="route('recipes.index')" 
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition-colors"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Перейти к рецептам
                        </Link>
                    </div>

                    <!-- Корзина с товарами -->
                    <div v-else class="p-6">
                        <!-- Список рецептов -->
                        <div class="space-y-6 mb-8">
                            <div 
                                v-for="item in cartItems" 
                                :key="item.id"
                                class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg border border-gray-200"
                            >
                                <!-- Изображение рецепта -->
                                <div class="flex-shrink-0 w-20 h-20">
                                    <img 
                                        :src="item.image" 
                                        :alt="item.name"
                                        class="w-full h-full object-cover rounded-md"
                                        @error="handleImageError"
                                    />
                                </div>

                                <!-- Информация о рецепте -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                                        <Link :href="route('recipes.show', item.id)" class="hover:text-green-600">
                                            {{ item.name }}
                                        </Link>
                                    </h3>
                                    
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ item.cooking_time }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <span>{{ item.rating || '0.0' }}</span>
                                        </div>
                                    </div>

                                    <!-- Ингредиенты -->
                                    <div class="mt-3">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Ингредиенты:</h4>
                                        <ul class="text-sm text-gray-600 space-y-1">
                                            <li 
                                                v-for="ingredient in item.ingredients.slice(0, 3)" 
                                                :key="ingredient.id"
                                                class="flex justify-between"
                                            >
                                                <span>{{ ingredient.name }}</span>
                                                <span class="text-gray-500">{{ ingredient.amount }} {{ ingredient.unit }}</span>
                                            </li>
                                            <li v-if="item.ingredients.length > 3" class="text-green-600 font-medium">
                                                + еще {{ item.ingredients.length - 3 }} ингредиентов
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Действия -->
                                <div class="flex flex-col items-end space-y-3">
                                    <button 
                                        @click="removeFromCart(item.id)"
                                        class="text-red-600 hover:text-red-800 p-2 transition-colors"
                                        title="Удалить из корзины"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    
                                    <div class="text-xs text-gray-500">
                                        Добавлено: {{ formatDate(item.added_at) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Статистика и действия -->
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Статистика -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Статистика корзины</h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Количество рецептов:</span>
                                            <span class="font-medium">{{ cartItems.length }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Общее время приготовления:</span>
                                            <span class="font-medium">{{ totalCookingTime }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Уникальных ингредиентов:</span>
                                            <span class="font-medium">{{ uniqueIngredientsCount }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Действия -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Действия</h3>
                                    <div class="space-y-3">
                                        <button 
                                            @click="generateShoppingList"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Создать список покупок
                                        </button>
                                        
                                        <button 
                                            @click="clearCart"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Очистить корзину
                                        </button>

                                        <Link 
                                            :href="route('recipes.index')"
                                            class="w-full flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Добавить еще рецепты
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно списка покупок -->
        <Modal :show="showShoppingList" @close="showShoppingList = false">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Список покупок</h3>
                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-600">Всего ингредиентов: {{ shoppingList.length }}</span>
                        <button 
                            @click="copyShoppingList"
                            class="text-sm text-blue-600 hover:text-blue-800"
                        >
                            Скопировать список
                        </button>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        <ul class="space-y-2">
                            <li 
                                v-for="(ingredient, index) in shoppingList" 
                                :key="index"
                                class="flex items-center p-3 bg-gray-50 rounded-lg"
                            >
                                <input 
                                    type="checkbox" 
                                    :id="`ingredient-${index}`"
                                    v-model="ingredient.checked"
                                    class="mr-3 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                />
                                <label 
                                    :for="`ingredient-${index}`"
                                    class="flex-1 text-sm"
                                    :class="ingredient.checked ? 'line-through text-gray-400' : 'text-gray-700'"
                                >
                                    <span class="font-medium">{{ ingredient.name }}</span> - 
                                    <span class="text-gray-600">{{ ingredient.totalAmount }} {{ ingredient.unit }}</span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button
                        @click="showShoppingList = false"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                    >
                        Закрыть
                    </button>
                    <button
                        @click="printShoppingList"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    >
                        Распечатать
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, onMounted } from 'vue';
import Modal from '@/Components/Modal.vue';
import { format } from 'date-fns';
import { ru } from 'date-fns/locale/ru';

const page = usePage();
const cartItems = ref([]);
const showShoppingList = ref(false);
const shoppingList = ref([]);

// Загрузка корзины при монтировании
onMounted(() => {
    loadCart();
});

// Загрузка корзины из localStorage
const loadCart = () => {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cartItems.value = JSON.parse(savedCart);
    }
};

// Сохранение корзины в localStorage
const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(cartItems.value));
};

// Удаление рецепта из корзины
const removeFromCart = (recipeId) => {
    cartItems.value = cartItems.value.filter(item => item.id !== recipeId);
    saveCart();
};

// Очистка всей корзины
const clearCart = () => {
    if (confirm('Вы уверены, что хотите очистить корзину?')) {
        cartItems.value = [];
        localStorage.removeItem('cart');
    }
};

// Обработка ошибок изображений
const handleImageError = (event) => {
    if (!event.target.dataset.errorHandled) {
        event.target.src = '/images/placeholder.png';
        event.target.dataset.errorHandled = 'true';
    }
};

// Форматирование даты
const formatDate = (dateString) => {
    return format(new Date(dateString), 'd MMMM yyyy', { locale: ru });
};

// Вычисляемые свойства
const totalCookingTime = computed(() => {
    const totalMinutes = cartItems.value.reduce((total, item) => {
        const timeMatch = item.cooking_time?.match(/(\d+)/);
        return total + (timeMatch ? parseInt(timeMatch[1]) : 0);
    }, 0);
    
    if (totalMinutes < 60) return `${totalMinutes} мин`;
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    return minutes > 0 ? `${hours} ч ${minutes} мин` : `${hours} ч`;
});

const uniqueIngredientsCount = computed(() => {
    const allIngredients = cartItems.value.flatMap(item => 
        item.ingredients?.map(ing => ing.name) || []
    );
    return new Set(allIngredients).size;
});

// Генерация списка покупок
const generateShoppingList = () => {
    const ingredientMap = new Map();
    
    cartItems.value.forEach(recipe => {
        recipe.ingredients?.forEach(ingredient => {
            const key = `${ingredient.name}_${ingredient.unit}`;
            if (ingredientMap.has(key)) {
                const existing = ingredientMap.get(key);
                existing.totalAmount += parseFloat(ingredient.amount) || 0;
            } else {
                ingredientMap.set(key, {
                    name: ingredient.name,
                    unit: ingredient.unit,
                    totalAmount: parseFloat(ingredient.amount) || 0,
                    checked: false
                });
            }
        });
    });
    
    shoppingList.value = Array.from(ingredientMap.values());
    showShoppingList.value = true;
};

// Копирование списка покупок
const copyShoppingList = async () => {
    const text = shoppingList.value
        .map(ing => `- ${ing.name}: ${ing.totalAmount} ${ing.unit}`)
        .join('\n');
    
    try {
        await navigator.clipboard.writeText(text);
        alert('Список покупок скопирован в буфер обмена!');
    } catch (err) {
        console.error('Ошибка копирования:', err);
    }
};

// Печать списка покупок
const printShoppingList = () => {
    const printWindow = window.open('', '_blank');
    const content = `
        <html>
            <head>
                <title>Список покупок</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h1 { color: #333; }
                    ul { list-style: none; padding: 0; }
                    li { padding: 5px 0; border-bottom: 1px solid #eee; }
                </style>
            </head>
            <body>
                <h1>Список покупок</h1>
                <ul>
                    ${shoppingList.value.map(ing => 
                        `<li>${ing.name} - ${ing.totalAmount} ${ing.unit}</li>`
                    ).join('')}
                </ul>
                <p><small>Сгенерировано: ${new Date().toLocaleDateString('ru-RU')}</small></p>
            </body>
        </html>
    `;
    
    printWindow.document.write(content);
    printWindow.document.close();
    printWindow.print();
};
</script>

<style scoped>
.nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: #6b7280;
    text-decoration: none;
    transition: all 0.2s;
}

.nav-item:hover {
    color: #059669;
    background-color: #f9fafb;
}

.nav-icon {
    margin-right: 0.75rem;
}

.nav-text {
    font-weight: 500;
}

/* Анимации */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>