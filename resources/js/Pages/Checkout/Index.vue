<template>
    <Head title="Оформление заказа" />
    <AppLayout>
        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Заголовок -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Оформление заказа</h1>
                    <p class="text-green-600 mt-2">Заполните данные для завершения заказа</p>
                </div>

                <div v-if="cartItems.length === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="mx-auto w-24 h-24 text-gray-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Корзина пуста</h3>
                    <p class="text-gray-500 mb-6">Добавьте товары в корзину для оформления заказа</p>
                    <Link 
                        :href="route('recipes.index')" 
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                    >
                        Вернуться к покупкам
                    </Link>
                </div>

                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Левая колонка - форма -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <form @submit.prevent="submitOrder" class="space-y-6">
                                <!-- Контактная информация -->
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">Контактная информация</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Имя *</label>
                                            <input 
                                                v-model="form.first_name"
                                                type="text"
                                                required
                                                :class="{'border-red-500': form.errors.first_name}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-gray-700 focus:ring-green-500 focus:border-green-500"
                                                placeholder="Ваше имя"
                                            />
                                            <p v-if="form.errors.first_name" class="text-red-500 text-sm mt-1">{{ form.errors.first_name }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Фамилия *</label>
                                            <input 
                                                v-model="form.last_name"
                                                type="text"
                                                required
                                                :class="{'border-red-500': form.errors.last_name}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-gray-700 focus:ring-green-500 focus:border-green-500"
                                                placeholder="Ваша фамилия"
                                            />
                                            <p v-if="form.errors.last_name" class="text-red-500 text-sm mt-1">{{ form.errors.last_name }}</p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                            <input 
                                                v-model="form.email"
                                                type="email"
                                                required
                                                :class="{'border-red-500': form.errors.email}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-gray-700 focus:ring-green-500 focus:border-green-500"
                                                placeholder="your@email.com"
                                            />
                                            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Телефон *</label>
                                            <input 
                                                v-model="form.phone"
                                                type="tel"
                                                required
                                                :class="{'border-red-500': form.errors.phone}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-gray-700 focus:ring-green-500 focus:border-green-500"
                                                placeholder="+7 (XXX) XXX-XX-XX"
                                            />
                                            <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">{{ form.errors.phone }}</p>
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
                                                v-model="form.city"
                                                type="text"
                                                required
                                                :class="{'border-red-500': form.errors.city}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-gray-700 focus:ring-green-500 focus:border-green-500"
                                                placeholder="Город доставки"
                                            />
                                            <p v-if="form.errors.city" class="text-red-500 text-sm mt-1">{{ form.errors.city }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Адрес *</label>
                                            <input 
                                                v-model="form.address"
                                                type="text"
                                                required
                                                :class="{'border-red-500': form.errors.address}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-gray-700 focus:ring-green-500 focus:border-green-500"
                                                placeholder="Улица, дом, квартира"
                                            />
                                            <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">{{ form.errors.address }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Индекс</label>
                                                <input 
                                                    v-model="form.postal_code"
                                                    type="text"
                                                    :class="{'border-red-500': form.errors.postal_code}"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-gray-700 focus:ring-green-500 focus:border-green-500"
                                                    placeholder="Почтовый индекс"
                                                />
                                                <p v-if="form.errors.postal_code" class="text-red-500 text-sm mt-1">{{ form.errors.postal_code }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Комментарий -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Комментарий к заказу</label>
                                    <textarea 
                                        v-model="form.comment"
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 text-gray-700 focus:ring-green-500 focus:border-green-500"
                                        placeholder="Дополнительные пожелания..."
                                    ></textarea>
                                </div>

                                <!-- Кнопки -->
                                <div class="flex justify-between pt-6 border-t border-gray-200">
                                    <Link
                                        :href="route('cart.index')"
                                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                                    >
                                        ← Вернуться в корзину
                                    </Link>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed font-semibold"
                                    >
                                        <span v-if="form.processing">Оформление...</span>
                                        <span v-else>Подтвердить заказ</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Правая колонка - итоги -->
                    <div class="space-y-6">
                        <!-- Итоговая стоимость -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ваш заказ</h3>
                            
                            <div class="space-y-4 mb-4">
                                <div v-for="item in cartItems" :key="item.id" class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 flex-shrink-0">
                                            <img 
                                                :src="item.image" 
                                                :alt="item.name"
                                                class="w-full h-full object-cover rounded"
                                                @error="handleImageError"
                                            />
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ item.name }}</p>
                                            <p class="text-sm text-gray-600">Количество: {{ item.cartQuantity }}</p>
                                        </div>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ formatPrice(item.price * item.cartQuantity) }}</span>
                                </div>
                            </div>

                            <div class="space-y-3 border-t border-gray-200 pt-4">
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
                                        <span class="text-gray-900">Итого</span>
                                        <span class="text-gray-900">{{ formatPrice(total) }}</span>
                                    </div>
                                </div>
                            </div>
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
    </AppLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, computed, onMounted } from 'vue';

const cartItems = ref([]);

// Используем useForm от Inertia.js
const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    city: '',
    address: '',
    postal_code: '',
    comment: '',
    items: [],
    total: 0,
    subtotal: 0,
    delivery_price: 0,
    total_items: 0
});

// Загрузка корзины
onMounted(() => {
    loadCart();
});

const loadCart = () => {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cartItems.value = JSON.parse(savedCart);
    }
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
    return subtotal.value >= 3000 ? 0 : 300;
});

const total = computed(() => {
    return subtotal.value + deliveryPrice.value;
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 0
    }).format(price);
};

const handleImageError = (event) => {
    if (!event.target.dataset.errorHandled) {
        event.target.src = '/images/placeholder.png';
        event.target.dataset.errorHandled = 'true';
    }
};

// Оформление заказа
const submitOrder = () => {
    // Заполняем данные заказа
    form.items = cartItems.value;
    form.total = total.value;
    form.subtotal = subtotal.value;
    form.delivery_price = deliveryPrice.value;
    form.total_items = totalItems.value;

    form.post(route('orders.store'), {
        onSuccess: () => {
            // Корзина очистится после редиректа
            // Inertia сам перенаправит на страницу успеха
        },
        onError: (errors) => {
            console.error('Ошибки при оформлении:', errors);
        }
    });
};
</script>