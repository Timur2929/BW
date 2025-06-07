<template>
    <Head title="Категории" />
    <div class="categories-page">
        <AppLayout />
        <main class="categories-main">
            <div class="flex justify-between items-center mb-8">
                <h2 class="categories-title">Популярные категории</h2>
                <Link
                    v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'"
                    :href="route('categories.create')"
                    class="create-category-btn"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Создать категорию
                </Link>
            </div>
            <div class="categories-grid">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="route('categories.show', category.id)"
                    class="category-card"
                >
                    <div class="category-image-container">
                        <img 
                            :src="category.image" 
                            :alt="category.name" 
                            class="category-image"
                            loading="lazy"
                        >
                        <div class="category-overlay"></div>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">{{ category.name }}</h3>
                        <p class="category-count">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            {{ category.recipes_count }} рецептов
                        </p>
                    </div>
                </Link>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    categories: {
        type: Array,
        required: true
    }
});
</script>

<style scoped>
.categories-page {
    display: flex;
    min-height: 100vh;
    background-color: #374151;
}

.categories-main {
    flex: 1;
    padding: 2.5rem;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.categories-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    position: relative;
    padding-bottom: 0.5rem;
}

.categories-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background-color: #3b82f6;
    border-radius: 3px;
}

.create-category-btn {
    display: flex;
    align-items: center;
    background-color: #3b82f6;
    color: white;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.create-category-btn:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.category-card {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border-radius: 0.75rem;
    overflow: hidden;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    position: relative;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.category-image-container {
    position: relative;
    aspect-ratio: 16/9;
    overflow: hidden;
}

.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.3), rgba(0,0,0,0.1));
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.category-card:hover .category-overlay {
    opacity: 0.9;
}

.category-card:hover .category-image {
    transform: scale(1.1);
}

.category-info {
    padding: 1.25rem;
}

.category-name {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1e293b;
}

.category-count {
    color: #64748b;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
}
</style>

