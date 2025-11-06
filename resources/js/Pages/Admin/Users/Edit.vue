<template>
    <AdminLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Редактировать пользователя</h1>
                
                <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-4">
                        <!-- Имя -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Имя *
                            </label>
                            <input
                                id="first_name"
                                v-model="form.first_name"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.first_name }"
                            >
                            <p v-if="form.errors.first_name" class="text-red-500 text-sm mt-1">
                                {{ form.errors.first_name }}
                            </p>
                        </div>

                        <!-- Фамилия -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Фамилия *
                            </label>
                            <input
                                id="last_name"
                                v-model="form.last_name"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.last_name }"
                            >
                            <p v-if="form.errors.last_name" class="text-red-500 text-sm mt-1">
                                {{ form.errors.last_name }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email *
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.email }"
                            >
                            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Телефон -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Телефон
                            </label>
                            <input
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.phone }"
                            >
                            <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">
                                {{ form.errors.phone }}
                            </p>
                        </div>

                        <!-- Пароль -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Новый пароль
                            </label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.password }"
                            >
                            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                                {{ form.errors.password }}
                            </p>
                            <p class="text-gray-500 text-sm mt-1">
                                Оставьте пустым, если не хотите менять пароль
                            </p>
                        </div>

                        <!-- Подтверждение пароля -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Подтверждение пароля
                            </label>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <!-- Роль -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                Роль *
                            </label>
                            <select
                                id="role"
                                v-model="form.role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.role }"
                            >
                                <option value="user">Пользователь</option>
                                <option value="admin">Администратор</option>
                            </select>
                            <p v-if="form.errors.role" class="text-red-500 text-sm mt-1">
                                {{ form.errors.role }}
                            </p>
                        </div>

                        <!-- Аватар -->
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">
                                Аватар
                            </label>
                            <div class="flex items-center space-x-4 mb-2">
                                <div v-if="user.avatar" class="w-16 h-16 rounded-full overflow-hidden">
                                    <img :src="`/storage/${user.avatar}`" :alt="user.full_name" class="w-full h-full object-cover">
                                </div>
                                <div v-else class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-600 font-semibold text-sm">
                                        {{ user.first_name?.charAt(0) }}{{ user.last_name?.charAt(0) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Текущий аватар</p>
                                </div>
                            </div>
                            <input
                                id="avatar"
                                type="file"
                                @input="form.avatar = $event.target.files[0]"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.avatar }"
                            >
                            <p v-if="form.errors.avatar" class="text-red-500 text-sm mt-1">
                                {{ form.errors.avatar }}
                            </p>
                        </div>

                        <!-- Обложка -->
                        <div>
                            <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">
                                Обложка профиля
                            </label>
                            <div v-if="user.cover_image" class="mb-2">
                                <img :src="`/storage/${user.cover_image}`" :alt="user.full_name" class="w-full h-32 object-cover rounded-md">
                                <p class="text-sm text-gray-600 mt-1">Текущая обложка</p>
                            </div>
                            <input
                                id="cover_image"
                                type="file"
                                @input="form.cover_image = $event.target.files[0]"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                :class="{ 'border-red-500': form.errors.cover_image }"
                            >
                            <p v-if="form.errors.cover_image" class="text-red-500 text-sm mt-1">
                                {{ form.errors.cover_image }}
                            </p>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <Link
                            :href="route('admin.users.index')"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200"
                        >
                            Отмена
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 transition duration-200"
                        >
                            {{ form.processing ? 'Обновление...' : 'Обновить' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object
});

const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    phone: props.user.phone,
    password: '',
    password_confirmation: '',
    role: props.user.role,
    avatar: null,
    cover_image: null
});

const submit = () => {
    form.post(route('admin.users.update', props.user.id), {
        forceFormData: true,
        method: 'put',
    });
};
</script>