<template>
    <Head title="Категории" />
    <div class="categories-page">
        <AppLayout />
        <main class="categories-main">
            <div class="flex justify-between items-center mb-6">
                <h2 class="categories-title">Популярные категории</h2>
                <Link
                    v-if="$page.props.auth.user && $page.props.auth.user.role === 'admin'"
                    :href="route('categories.create')"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
                >
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
                        >
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">{{ category.name }}</h3>
                        <p class="category-count">{{ category.recipes_count }} рецептов</p>
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
}

.categories-main {
    flex: 1;
    padding: 2rem;
    width: 100%;
}

.categories-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 2rem;
    color: white;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 2rem;
}

@media (min-width: 768px) {
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .categories-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.category-card {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
   
    border-radius: 0.5rem;
    padding: 1rem;
    color: white;
    background-color: #2d3748;
    
}

.category-card:hover {
    transform: translateY(-4px);
    border-color: #4299e1;
    box-shadow: 0 4px 6px rgba(66, 153, 225, 0.1);
}

.category-image-container {
    position: relative;
    aspect-ratio: 4/3;
    margin-bottom: 0.75rem;
    overflow: hidden;
    border-radius: 0.375rem;
}

.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image {
    transform: scale(1.05);
}

.category-info {
    padding: 0.5rem;
}

.category-name {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: white;
}

.category-count {
    color: white;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.category-count::before {
    content: "";
    font-size: 1rem;
}
</style> 