<template>
    <Head title="Заказ оформлен" />
    <AppLayout>
        <div class="py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <!-- Иконка успеха -->
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    <!-- Заголовок -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Заказ успешно оформлен!</h1>
                    
                    <!-- Номер заказа -->
                    <p class="text-lg text-gray-600 mb-2">
                        Номер вашего заказа: <strong>{{ order.order_number }}</strong>
                    </p>
                    
                    <!-- Информация о заказе -->
                    <div class="bg-gray-50 rounded-lg p-6 mt-6 text-left">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Детали заказа</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Итого:</span>
                                <span class="font-medium text-gray-900">{{ formatPrice(order.total) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Статус:</span>
                                <span class="font-medium text-green-600 capitalize">{{ order.status }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Товаров:</span>
                                <span class="font-medium text-gray-900">{{ order.total_items }} шт.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Контактная информация -->
                    <div class="bg-blue-50 rounded-lg p-6 mt-6 text-left">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Контактная информация</h3>
                        <p class="text-blue-800">{{ order.first_name }} {{ order.last_name }}</p>
                        <p class="text-blue-800">{{ order.email }}</p>
                        <p class="text-blue-800">{{ order.phone }}</p>
                        <p class="text-blue-800 mt-2">{{ order.city }}, {{ order.address }}</p>
                    </div>

                    <!-- Сообщение -->
                    <p class="text-gray-600 mt-6 mb-8">
                        Спасибо за ваш заказ! Мы свяжемся с вами в ближайшее время для подтверждения заказа.
                    </p>

                    <!-- Кнопки -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link 
                            :href="route('recipes.index')"
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold"
                        >
                            Продолжить покупки
                        </Link>
                        <button
                            @click="printPage"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold"
                        >
                            Распечатать заказ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    order: Object
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 0
    }).format(price);
};

const printPage = () => {
    window.print();
};
</script>