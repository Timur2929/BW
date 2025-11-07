<template>
    <AdminLayout>
        <div class="py-6">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-white mb-6">Создание категории</h1>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Изображение -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Изображение категории</label>
                        <div class="flex items-center space-x-4">
                            <div v-if="form.image" class="relative">
                                <img :src="imagePreview" class="w-32 h-32 rounded-lg object-cover">
                                <button 
                                    type="button"
                                    @click="removeImage"
                                    class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700"
                                >
                                    <XMarkIcon class="w-4 h-4" />
                                </button>
                            </div>
                            <div v-else class="border-2 border-dashed border-gray-600 rounded-lg p-4 text-center">
                                <PhotoIcon class="mx-auto w-12 h-12 text-gray-400" />
                                <label class="cursor-pointer">
                                    <span class="text-blue-400 hover:text-blue-300">Выберите изображение</span>
                                    <input 
                                        type="file" 
                                        @input="form.image = $event.target.files[0]" 
                                        accept="image/*"
                                        class="hidden"
                                    >
                                </label>
                            </div>
                        </div>
                        <div v-if="form.errors.image" class="text-red-400 text-sm mt-1">
                            {{ form.errors.image }}
                        </div>
                    </div>

                    <!-- Название -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Название категории</label>
                        <input 
                            type="text" 
                            id="name"
                            v-model="form.name"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg text-white px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.name }"
                        >
                        <div v-if="form.errors.name" class="text-red-400 text-sm mt-1">
                            {{ form.errors.name }}
                        </div>
                    </div>

                    <!-- Описание -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Описание</label>
                        <textarea 
                            id="description"
                            v-model="form.description"
                            rows="4"
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg text-white px-3 py-2 focus:border-blue-500 focus:ring-blue-500"
                            :class="{ 'border-red-500': form.errors.description }"
                        ></textarea>
                        <div v-if="form.errors.description" class="text-red-400 text-sm mt-1">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-3 pt-6">
                        <Link 
                            :href="route('admin.categories.index')"
                            class="px-4 py-2 text-gray-300 hover:text-white transition-colors"
                        >
                            Отмена
                        </Link>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors disabled:opacity-50"
                        >
                            Создать категорию
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
import { ref, computed } from 'vue';
import { PhotoIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const form = useForm({
    name: '',
    description: '',
    image: null
});

const imagePreview = computed(() => {
    if (form.image && typeof form.image === 'object') {
        return URL.createObjectURL(form.image);
    }
    return null;
});

const removeImage = () => {
    form.image = null;
};

const submit = () => {
    form.post(route('admin.categories.store'), {
        forceFormData: true,
    });
};
</script>