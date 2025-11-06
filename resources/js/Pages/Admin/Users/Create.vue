<template>
    <AdminLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Создать пользователя</h1>
                
                <!-- Уведомления -->
                <div v-if="$page.props.flash.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ $page.props.flash.error }}
                </div>

                <form @submit.prevent="submit" class="bg-white rounded-lg shadow p-6">
                    <div class="space-y-6">
                        <!-- Основная информация -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
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
                        </div>

                        <!-- Контакты -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
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
                        </div>

                        <!-- Пароли -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Пароль *
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
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Подтверждение пароля *
                                </label>
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                            </div>
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

                        <!-- Файлы -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Изображения профиля</h3>
                            
                            <!-- Аватар -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Аватар
                                </label>
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                            <img 
                                                v-if="avatarPreview" 
                                                :src="avatarPreview" 
                                                class="h-full w-full object-cover"
                                            >
                                            <span v-else class="text-gray-400 text-sm">Аватар</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <input
                                            type="file"
                                            @change="handleAvatarUpload"
                                            accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                        >
                                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF до 2MB</p>
                                        <p v-if="form.errors.avatar" class="mt-1 text-sm text-red-600">
                                            {{ form.errors.avatar }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Обложка -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Обложка профиля
                                </label>
                                <div class="flex flex-col space-y-2">
                                    <div class="h-32 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                                        <img 
                                            v-if="coverPreview" 
                                            :src="coverPreview" 
                                            class="h-full w-full object-cover"
                                        >
                                        <span v-else class="text-gray-400 text-sm">Обложка</span>
                                    </div>
                                    <input
                                        type="file"
                                        @change="handleCoverUpload"
                                        accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    >
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF до 5MB</p>
                                    <p v-if="form.errors.cover_image" class="text-sm text-red-600">
                                        {{ form.errors.cover_image }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
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
                            {{ form.processing ? 'Создание...' : 'Создать пользователя' }}
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
import { ref } from 'vue';

const avatarPreview = ref(null);
const coverPreview = ref(null);

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    avatar: null,
    cover_image: null,
});

const handleAvatarUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.avatar = file;
        // Создаем превью
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleCoverUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.cover_image = file;
        // Создаем превью
        const reader = new FileReader();
        reader.onload = (e) => {
            coverPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post(route('admin.users.store'), {
        forceFormData: true,
    });
};
</script>