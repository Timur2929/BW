<template>
  <Head title="Профиль" />
  
  <div class="min-h-screen bg-gray-50">
    <Sidebar :user="user" />
    
    <div class="main-content">
      <!-- Profile Header -->
      <div class="profile-header">
        <!-- Cover Photo -->
        <div class="cover-photo-container">
          <img 
            :src="user.cover_image" 
            alt="Фоновое изображение"
            class="cover-photo"
          />
          <div class="cover-overlay"></div>
        </div>
        
        <!-- Profile Info -->
        <div class="profile-info-container">
          <div class="avatar-wrapper">
            <img 
              :src="user.avatar_url" 
              :alt="user.first_name + ' ' + user.last_name"
              class="avatar-image"
            />
          </div>
          <div class="profile-details">
            <h1 class="user-name">{{ user.first_name }} {{ user.last_name }}</h1>
            <div class="contact-details">
              <span class="contact-item">{{ user.phone }}</span>
              <span class="contact-separator">•</span>
              <span class="contact-item">{{ user.email }}</span>
            </div>
            <button class="edit-profile-btn" @click="$inertia.visit(route('profile.edit'))">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
              </svg>
              Редактировать профиль
            </button>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="content-container">
        <!-- Admin Section -->
        <div v-if="user.role === 'admin'" class="admin-section">
          <div class="section-header">
            <h2 class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
              Заявки на рассмотрении
            </h2>
          </div>
          
          <div v-if="pendingRecipes && pendingRecipes.length > 0" class="recipes-grid">
            <div v-for="recipe in pendingRecipes" :key="recipe.id" class="recipe-card">
              <div class="recipe-image-wrapper">
                <img :src="recipe.image" :alt="recipe.title" class="recipe-img" />
                <div class="recipe-time-badge">{{ recipe.cooking_time }}</div>
              </div>
              <div class="recipe-content-wrapper">
                <h3 class="recipe-title">{{ recipe.title }}</h3>
                <div class="recipe-meta">
                  <div class="meta-item">
                    <span class="meta-label">Категория:</span>
                    <span class="meta-value">{{ recipe.category?.name }}</span>
                  </div>
                  <div class="meta-item">
                    <span class="meta-label">Время:</span>
                    <span class="meta-value">{{ recipe.cooking_time }}</span>
                  </div>
                  <div class="meta-item">
                    <span class="meta-label">Автор:</span>
                    <span class="meta-value">{{ recipe.author }}</span>
                  </div>
                </div>
                <div class="recipe-actions">
                  <Link :href="route('recipes.show', recipe.id)" class="view-recipe-btn">
                    Просмотреть рецепт
                  </Link>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="empty-state">
            <div class="empty-state-content">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="empty-state-text">Нет заявок на рассмотрение</p>
            </div>
          </div>
        </div>

        <!-- User Recipes Section -->
        <div class="user-recipes-section">
          <div class="section-header">
            <h2 class="section-title">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Ваши рецепты
            </h2>
          </div>
          
          <div v-if="user.recipes && user.recipes.length > 0" class="recipes-grid">
            <div v-for="recipe in user.recipes" :key="recipe.id" class="user-recipe-card">
              <Link :href="route('recipes.show', recipe.id)" class="recipe-link">
                <div class="recipe-img-container">
                  <img :src="recipe.image" :alt="recipe.title" class="recipe-main-img" />
                  <span v-if="recipe.status === 'pending'" class="status-badge pending">На рассмотрении</span>
                  <span v-else-if="recipe.status === 'approved'" class="status-badge approved">Одобрен</span>
                  <span v-else-if="recipe.status === 'rejected'" class="status-badge rejected">Отклонён</span>
                  <span v-else-if="recipe.status === 'revision'" class="status-badge revision">На доработке</span>
                  <span class="cooking-time">{{ recipe.cooking_time }}</span>
                </div>
                <div class="recipe-details">
                  <div class="author-info">
                    <span class="author-label">Автор:</span>
                    <span class="author-name">{{ user.first_name }} {{ user.last_name }}</span>
                  </div>
                  <div class="category-tag" v-if="recipe.category">
                    {{ recipe.category }}
                  </div>
                  <h3 class="recipe-main-title">{{ recipe.title }}</h3>
                  <p class="recipe-description">{{ recipe.description }}</p>
                  <div class="rating-container">
                    <div class="stars">
                      <span v-for="i in 5" :key="i" class="star-icon" :class="{ 'filled': i <= (recipe.rating || 0) }">★</span>
                    </div>
                    <span class="rating-value">{{ recipe.rating || '0.0' }}</span>
                  </div>
                </div>
              </Link>
              <div v-if="recipe.status === 'revision'" class="edit-recipe-btn-container">
                <Link :href="route('recipes.edit', recipe.id)" class="edit-recipe-btn">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                  </svg>
                  Редактировать
                </Link>
              </div>
            </div>
          </div>
          <div v-else class="empty-state">
            <div class="empty-state-content">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <p class="empty-state-text">У вас пока нет рецептов</p>
              <button @click="navigateToSubmitRecipe" class="add-recipe-btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Добавить рецепт
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    pendingRecipes: {
        type: Array,
        default: () => []
    }
});

const navigateToSubmitRecipe = () => {
    router.visit('/recipes/submit');
};

const approveRecipe = (recipeId) => {
    router.post(route('admin.recipes.approve', recipeId));
};

const rejectRecipe = (recipeId) => {
    router.post(route('admin.recipes.reject', recipeId));
};
</script>

<style scoped>
.main-content {
    margin-left: 280px;
    min-height: 100vh;
    background-color: #374151;
}

/* Profile Header Styles */
.profile-header {
    position: relative;
    margin-bottom: 60px;
}

.cover-photo-container {
    position: relative;
    height: 320px;
    overflow: hidden;
}

.cover-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.6));
}

.profile-info-container {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
    transform: translateY(-80px);
}

.avatar-wrapper {
    width: 160px;
    height: 160px;
    border: 5px solid white;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    background-color: white;
    margin-bottom: 20px;
}

.avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-details {
    padding-left: 20px;
}

.user-name {
    font-size: 28px;
    font-weight: 700;
    color: white;
    margin-bottom: 8px;
}

.contact-details {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    color: white;
    font-size: 16px;
}

.contact-separator {
    color: #d1d5db;
}

.edit-profile-btn {
    display: inline-flex;
    align-items: center;
    background-color: green;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.edit-profile-btn:hover {
    background-color: green;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* Content Container */
.content-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px 60px;
}

/* Section Styles */
.section-header {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.section-title {
    display: flex;
    align-items: center;
    font-size: 22px;
    font-weight: 600;
    color: white;
}

/* Recipes Grid */
.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

/* Recipe Card Styles */
.recipe-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.recipe-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.recipe-image-wrapper {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.recipe-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.recipe-card:hover .recipe-img {
    transform: scale(1.05);
}

.recipe-time-badge {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background-color: rgba(0,0,0,0.7);
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.recipe-content-wrapper {
    padding: 18px;
}

.recipe-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 12px;
    line-height: 1.3;
}

.recipe-meta {
    margin-bottom: 16px;
}

.meta-item {
    display: flex;
    margin-bottom: 8px;
    font-size: 14px;
}

.meta-label {
    font-weight: 500;
    color: #374151;
    min-width: 100px;
}

.meta-value {
    color: #6b7280;
}

.view-recipe-btn {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #374151;
    color: white;
    text-align: center;
    border-radius: 6px;
    font-weight: 500;
    transition: background-color 0.2s;
}

.view-recipe-btn:hover {
    background-color: green;
}

/* User Recipe Card */
.user-recipe-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.user-recipe-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.recipe-img-container {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.recipe-main-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.user-recipe-card:hover .recipe-main-img {
    transform: scale(1.05);
}

.status-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.pending {
    background-color: #fef3c7;
    color: #92400e;
}

.approved {
    background-color: #d1fae5;
    color: #065f46;
}

.rejected {
    background-color: #fee2e2;
    color: #991b1b;
}

.revision {
    background-color: #fef9c3;
    color: #854d0e;
}

.cooking-time {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background-color: rgba(0,0,0,0.7);
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.recipe-details {
    padding: 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.author-info {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 10px;
    font-size: 13px;
}

.author-label {
    color: #6b7280;
}

.author-name {
    color: #4b5563;
    font-weight: 500;
}

.category-tag {
    display: inline-block;
    background-color: #f3f4f6;
    color: #4b5563;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-bottom: 12px;
    align-self: flex-start;
}

.recipe-main-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
    line-height: 1.3;
}

.recipe-description {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.4;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.rating-container {
    display: flex;
    align-items: center;
    margin-top: auto;
}

.stars {
    display: flex;
}

.star-icon {
    color: #e5e7eb;
    font-size: 16px;
}

.star-icon.filled {
    color: #f59e0b;
}

.rating-value {
    color: #6b7280;
    font-size: 14px;
    margin-left: 6px;
}

.edit-recipe-btn-container {
    padding: 0 18px 18px;
}

.edit-recipe-btn {
    display: inline-flex;
    align-items: center;
    background-color: #f3f4f6;
    color: #4b5563;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.edit-recipe-btn:hover {
    background-color: #e5e7eb;
    color: #1f2937;
}

/* Empty State Styles */
.empty-state {
    background-color: white;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.empty-state-content {
    max-width: 400px;
    margin: 0 auto;
}

.empty-state-text {
    color: #6b7280;
    font-size: 16px;
    margin: 16px 0 24px;
}

.add-recipe-btn {
    display: inline-flex;
    align-items: center;
    background-color: #374151;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.add-recipe-btn:hover {
    background-color: green;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
</style>