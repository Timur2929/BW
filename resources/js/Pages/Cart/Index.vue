<template>
    <Head title="Корзина" />
    <AppLayout>
        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <!-- Заголовок -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white-900">Корзина покупок</h1>
                    <p class="text-green-600 mt-2">Ваши выбранные украшения</p>
                </div>

                <!-- Пустая корзина -->
                <div v-if="cartItems.length === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="mx-auto w-24 h-24 text-gray-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Корзина пуста</h3>
                    <p class="text-gray-500 mb-6">Добавьте украшения из каталога, чтобы сделать заказ</p>
                    <Link 
                        :href="route('recipes.index')" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Продолжить покупки
                    </Link>
                </div>

                <!-- Корзина с товарами -->
                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Левая колонка - товары -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="p-6 border-b border-gray-200">
                                <h2 class="text-xl font-semibold text-gray-900">Товары в корзине</h2>
                            </div>

                            <div class="divide-y divide-gray-200">
                                <div 
                                    v-for="item in cartItems" 
                                    :key="item.id"
                                    class="p-6"
                                >
                                    <div class="flex items-start space-x-4">
                                        <!-- Изображение товара -->
                                        <div class="flex-shrink-0 w-24 h-24">
                                            <img 
                                                :src="item.image" 
                                                :alt="item.name"
                                                class="w-full h-full object-cover rounded-lg"
                                                @error="handleImageError"
                                            />
                                        </div>

                                        <!-- Информация о товаре -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900">
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
                                                <div class="flex items-center" :class="getStockStatus(item).class">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                    <span>{{ getStockStatus(item).text }}</span>
                                                </div>
                                            </div>

                                            <!-- Цена и количество -->
                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <!-- Счетчик количества -->
                                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                                        <button 
                                                            @click="decreaseQuantity(item)"
                                                            :disabled="item.cartQuantity <= 1"
                                                            class="px-3 py-1 text-gray-600 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        >
                                                            -
                                                        </button>
                                                        <span class="px-3 py-1 text-gray-900 font-medium">
                                                            {{ item.cartQuantity }}
                                                        </span>
                                                        <button 
                                                            @click="increaseQuantity(item)"
                                                            :disabled="item.cartQuantity >= item.quantity"
                                                            class="px-3 py-1 text-gray-600 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        >
                                                            +
                                                        </button>
                                                    </div>

                                                    <div class="text-lg font-semibold text-gray-900">
                                                        {{ formatPrice(item.price * item.cartQuantity) }}
                                                    </div>
                                                </div>

                                                <!-- Удаление -->
                                                <button 
                                                    @click="removeFromCart(item.id)"
                                                    class="text-red-600 hover:text-red-800 p-2 transition-colors"
                                                    title="Удалить из корзины"
                                                >
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Кнопка продолжить покупки -->
                        <div class="mt-6">
                            <Link 
                                :href="route('recipes.index')"
                                class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Продолжить покупки
                            </Link>
                        </div>
                    </div>

                    <!-- Правая колонка - итоги и оформление -->
                    <div class="space-y-6">
                        <!-- Итоговая стоимость -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Итоги заказа</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Товары ({{ totalItems }})</span>
                                    <span class="font-medium text-gray-600">{{ formatPrice(subtotal) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Доставка</span>
                                    <span class="font-medium text-gray-600">{{ deliveryPrice === 0 ? 'Бесплатно' : formatPrice(deliveryPrice) }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span class="text-gray-600">Итого</span>
                                        <span class="text-gray-600">{{ formatPrice(total) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопка оформления заказа -->
                            <button 
                                @click="showCheckoutForm = true"
                                class="w-full mt-6 bg-gray-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition-colors font-semibold"
                            >
                                Перейти к оформлению
                            </button>
                        </div>

                        <!-- Гарантии -->
                        <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                            <h4 class="font-semibold text-blue-900 mb-2">Гарантии</h4>
                            <ul class="text-sm text-blue-800 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Бесплатная доставка при заказе от 3000 Р
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Гарантия качества
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Возврат в течение 14 дней
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Модальное окно оформления заказа -->
        <Modal :show="showCheckoutForm" @close="showCheckoutForm = false" max-width="2xl">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Оформление заказа</h3>
                
                <form @submit.prevent="submitOrder" class="space-y-6">
                    <!-- Контактная информация -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Контактная информация</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Имя *</label>
                                <input 
                                    v-model="orderForm.first_name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-green-700 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Ваше имя"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Фамилия *</label>
                                <input 
                                    v-model="orderForm.last_name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-green-700 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Ваша фамилия"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input 
                                    v-model="orderForm.email"
                                    type="email"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-green-700 focus:ring-green-500 focus:border-green-500"
                                    placeholder="your@email.com"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Телефон *</label>
                                <input 
                                    v-model="orderForm.phone"
                                    type="tel"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-green-700 focus:ring-green-500 focus:border-green-500"
                                    placeholder="+7 (XXX) XXX-XX-XX"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Адрес доставки -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Адрес доставки</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Город *</label>
                                <input 
                                    v-model="orderForm.city"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-green-700 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Город доставки"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Адрес *</label>
                                <input 
                                    v-model="orderForm.address"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-green-700 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Улица, дом, квартира"
                                />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Индекс</label>
                                    <input 
                                        v-model="orderForm.postal_code"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-green-700 focus:ring-green-500 focus:border-green-500"
                                        placeholder="Почтовый индекс"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Комментарий -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий к заказу</label>
                        <textarea 
                            v-model="orderForm.comment"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-green-700 focus:ring-green-500 focus:border-green-500"
                            placeholder="Дополнительные пожелания..."
                        ></textarea>
                    </div>

                    <!-- Итоги заказа -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Итоги заказа</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Товары:</span>
                                <span>{{ formatPrice(subtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Доставка:</span>
                                <span>{{ deliveryPrice === 0 ? 'Бесплатно' : formatPrice(deliveryPrice) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2">
                                <div class="flex justify-between font-semibold">
                                    <span>Итого:</span>
                                    <span class="text-gray-600">{{ formatPrice(total) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-4">
                        <button
                            type="button"
                            @click="showCheckoutForm = false"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Отмена
                        </button>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ isSubmitting ? 'Оформление...' : 'Оформить заказ' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>
    </AppLayout>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, onMounted } from 'vue';
import Modal from '@/Components/Modal.vue';

const page = usePage();
const cartItems = ref([]);
const showCheckoutForm = ref(false);
const isSubmitting = ref(false);

// Форма заказа
const orderForm = ref({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    city: '',
    address: '',
    postal_code: '',
    comment: ''
});

// Загрузка корзины
onMounted(() => {
    loadCart();
    // Заполняем форму данными пользователя, если он авторизован
    if (page.props.auth.user) {
        orderForm.value.email = page.props.auth.user.email || '';
        // Можно добавить другие поля из профиля пользователя
    }
});

const loadCart = () => {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cartItems.value = JSON.parse(savedCart);
    }
};

const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(cartItems.value));
};

// Управление количеством
const increaseQuantity = (item) => {
    if (item.cartQuantity < item.quantity) {
        item.cartQuantity++;
        saveCart();
    }
};

const decreaseQuantity = (item) => {
    if (item.cartQuantity > 1) {
        item.cartQuantity--;
        saveCart();
    }
};

const removeFromCart = (recipeId) => {
    cartItems.value = cartItems.value.filter(item => item.id !== recipeId);
    saveCart();
};

// Вычисляемые свойства
const subtotal = computed(() => {
    return cartItems.value.reduce((total, item) => {
        return total + (item.price * item.cartQuantity);
    }, 0);
});

const totalItems = computed(() => {
    return cartItems.value.reduce((total, item) => total + item.cartQuantity, 0);
});

const deliveryPrice = computed(() => {
    // Бесплатная доставка от 3000 рублей
    return subtotal.value >= 3000 ? 0 : 300;
});

const total = computed(() => {
    return subtotal.value + deliveryPrice.value;
});

// Вспомогательные функции
const formatPrice = (price) => {
    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 0
    }).format(price);
};

const getStockStatus = (item) => {
    if (item.quantity > 10) {
        return { text: 'В наличии', class: 'text-green-600' };
    } else if (item.quantity > 0) {
        return { text: `Осталось ${item.quantity} шт.`, class: 'text-orange-600' };
    } else {
        return { text: 'Нет в наличии', class: 'text-red-600' };
    }
};

const handleImageError = (event) => {
    if (!event.target.dataset.errorHandled) {
        event.target.src = '/images/placeholder.png';
        event.target.dataset.errorHandled = 'true';
    }
};

// Оформление заказа
const submitOrder = async () => {
    isSubmitting.value = true;
    
    try {
        // Здесь будет отправка заказа на сервер
        const orderData = {
            ...orderForm.value,
            items: cartItems.value,
            total: total.value,
            subtotal: subtotal.value,
            delivery_price: deliveryPrice.value
        };

        console.log('Отправка заказа:', orderData);
        
        // Имитация отправки
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // После успешного оформления очищаем корзину
        cartItems.value = [];
        localStorage.removeItem('cart');
        showCheckoutForm.value = false;
        
        alert('Заказ успешно оформлен! С вами свяжутся для подтверждения.');
        
    } catch (error) {
        console.error('Ошибка оформления заказа:', error);
        alert('Произошла ошибка при оформлении заказа. Попробуйте еще раз.');
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<style scoped>
/* Дополнительные стили при необходимости */
</style>