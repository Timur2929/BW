<template>
  <Head title="Украшения" />
  
  <AppLayout>
    <div class="dark-theme">
      <!-- Hero секция -->
      <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
          <h1 class="hero-title">Исследуйте мир украшений</h1>
          <p class="hero-subtitle">Украшения для настоящих ценителей</p>
          
          <div class="hero-search">
            <div class="search-container">
              <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
              </svg>
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Найти украшение..."
                class="search-input"
                @keyup.enter="filterRecipes"
              >
              <button @click="filterRecipes" class="search-btn">
                Найти
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Основной контент -->
      <div class="main-container">
        <!-- Боковая панель фильтров -->
        <aside class="sidebar">
          <div class="sidebar-section">
            <h3 class="sidebar-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" y1="21" x2="4" y2="14"></line>
                <line x1="4" y1="10" x2="4" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12" y2="3"></line>
                <line x1="20" y1="21" x2="20" y2="16"></line>
                <line x1="20" y1="12" x2="20" y2="3"></line>
                <line x1="1" y1="14" x2="7" y2="14"></line>
                <line x1="9" y1="8" x2="15" y2="8"></line>
                <line x1="17" y1="16" x2="23" y2="16"></line>
              </svg>
              Фильтры
            </h3>
            
            <div class="filter-group">
              <label class="filter-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Тип поиска
              </label>
              <select v-model="searchType" class="filter-select">
                <option value="all">Везде</option>
                <option value="title">По названию</option>
                <option value="ingredients">По компонентам</option>
              </select>
            </div>
            
            <div class="filter-group">
              <label class="filter-label">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                Сортировка
              </label>
              <select v-model="sortBy" class="filter-select" @change="filterRecipes">
                <option value="newest">Сначала новые</option>
                <option value="popular">Популярные</option>
                <option value="rating">По рейтингу</option>
              </select>
            </div>
            
            <button @click="filterRecipes" class="apply-filters-btn">
              Применить фильтры
            </button>
          </div>
          
          <div class="sidebar-section" v-if="$page.props.auth.user">
            <Link :href="route('recipes.submit')" class="submit-recipe-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
              </svg>
              Добавить украшение
            </Link>
          </div>
        </aside>

        <!-- Сетка рецептов -->
        <main class="recipes-content">
          <div v-if="recipes.data && recipes.data.length > 0" class="recipes-grid">
            <article v-for="recipe in recipes.data" :key="recipe.id" class="recipe-card">
              <Link :href="route('recipes.show', recipe.id)" class="recipe-card-link">
                <div class="recipe-image-container">
                  <img :src="recipe.image || '/images/placeholder-dark.png'" :alt="recipe.title" class="recipe-image" @error="$event.target.src = '/images/placeholder-dark.png'"/>
                  <div class="recipe-badge">
                    <span class="recipe-time">{{ recipe.cooking_time }}</span>
                    <span class="recipe-category" v-if="recipe.category">{{ recipe.category.name }}</span>
                  </div>
                </div>
                
                <div class="recipe-info">
                  <h3 class="recipe-title">{{ recipe.title }}</h3>
                  <p class="recipe-description">{{ truncateDescription(recipe.description) }}</p>
                  
                  <div class="recipe-meta">
                    <div class="recipe-rating">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                      </svg>
                      <span>{{ recipe.rating || '0.0' }}</span>
                    </div>
                    
                    <div class="recipe-author">
                      <img :src="recipe.user.avatar || '/images/profile/default-avatar-dark.png'" :alt="recipe.user.full_name" class="author-avatar" />
                      <span>{{ recipe.user.full_name }}</span>
                    </div>
                  </div>
                </div>
              </Link>
            </article>
          </div>
          
          <div v-else-if="recipes.data && recipes.data.length === 0" class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <h3>Украшения не найдены</h3>
            <p>Попробуйте изменить параметры поиска</p>
            <button @click="resetFilters" class="reset-filters-btn">Сбросить фильтры</button>
          </div>
          
          <div v-else class="loading-state">
            <div class="loading-spinner"></div>
            <p>Загружаем украшения...</p>
          </div>

          <!-- Пагинация -->
          <div v-if="recipes.links && recipes.links.length > 3" class="pagination">
            <Link 
              v-for="link in recipes.links" 
              :key="link.label"
              :href="link.url"
              class="pagination-link"
              :class="{ 
                'pagination-active': link.active,
                'pagination-disabled': !link.url
              }"
              v-html="link.label"
            />
          </div>
        </main>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    recipes: Object,
    filters: Object
});

const searchQuery = ref(props.filters?.search || '');
const searchType = ref(props.filters?.searchType || 'all');
const sortBy = ref(props.filters?.sort || 'newest');

const truncateDescription = (text, length = 100) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

const filterRecipes = () => {
  router.get(route('recipes.index'), {
    search: searchQuery.value,
    searchType: searchType.value,
    sort: sortBy.value
  }, {
    preserveState: true,
    preserveScroll: true
  });
};

const resetFilters = () => {
  searchQuery.value = '';
  searchType.value = 'all';
  sortBy.value = 'newest';
  filterRecipes();
};

watch(searchQuery, (value) => {
    if (value === '') {
        filterRecipes();
    }
});
</script>

<style scoped>
/* Темная тема */
.dark-theme {
  background-color: #0f172a;
  color: #e2e8f0;
  min-height: 100vh;
}

/* Hero секция */
.hero-section {
  background-image: linear-gradient(to right, #1e293b, #0f172a);
  color: white;
  padding: 6rem 1.5rem;
  text-align: center;
  position: relative;
  overflow: hidden;
  border-bottom: 1px solid #1e293b;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at center, rgba(56, 182, 255, 0.1) 0%, transparent 70%);
  z-index: 0;
}

.hero-content {
  max-width: 800px;
  margin: 0 auto;
  position: relative;
  z-index: 1;
}

.hero-title {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 1rem;
  line-height: 1.2;
  background: linear-gradient(to right, #e2e8f0, #94a3b8);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-subtitle {
  font-size: 1.25rem;
  opacity: 0.8;
  margin-bottom: 2rem;
  color: #94a3b8;
}

.hero-search {
  max-width: 600px;
  margin: 0 auto;
}

.search-container {
  display: flex;
  background: #1e293b;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
  border: 1px solid #334155;
}

.search-icon {
  padding: 0.875rem 0 0.875rem 1rem;
  color: #64748b;
}

.search-input {
  flex: 1;
  padding: 0.875rem 1rem;
  background: transparent;
  border: none;
  font-size: 1rem;
  outline: none;
  color: #e2e8f0;
}

.search-input::placeholder {
  color: #64748b;
}

.search-btn {
  padding: 0 1.5rem;
  background: #3b82f6;
  border: none;
  color: white;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.search-btn:hover {
  background: #2563eb;
}

/* Основной контейнер */
.main-container {
  display: flex;
  max-width: 1200px;
  margin: 2rem auto;
  padding: 0 1.5rem;
  gap: 2rem;
}

/* Боковая панель */
.sidebar {
  flex: 0 0 280px;
}

.sidebar-section {
  background: #1e293b;
  border-radius: 10px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  border: 1px solid #334155;
}

.sidebar-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  color: #e2e8f0;
}

.sidebar-title svg {
  color: #3b82f6;
}

.filter-group {
  margin-bottom: 1.5rem;
}

.filter-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #94a3b8;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.filter-select {
  width: 100%;
  padding: 0.625rem 0.75rem;
  background: #1e293b;
  border: 1px solid #334155;
  border-radius: 6px;
  color: #e2e8f0;
  font-size: 0.875rem;
  cursor: pointer;
  transition: border-color 0.2s;
}

.filter-select:focus {
  outline: none;
  border-color: #3b82f6;
}

.apply-filters-btn {
  width: 100%;
  padding: 0.75rem;
  background-color: #3b82f6;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
  margin-top: 1rem;
}

.apply-filters-btn:hover {
  background-color: #2563eb;
}

.submit-recipe-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  width: 100%;
  padding: 0.75rem;
  background-color: #10b981;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
  text-decoration: none;
}

.submit-recipe-btn:hover {
  background-color: #059669;
}

/* Контент с рецептами */
.recipes-content {
  flex: 1;
}

.recipes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.recipe-card {
  background: #1e293b;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  border: 1px solid #334155;
  transition: transform 0.2s, box-shadow 0.2s;
}

.recipe-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
  border-color: #3b82f6;
}

.recipe-card-link {
  text-decoration: none;
  color: inherit;
  display: block;
  height: 100%;
}

.recipe-image-container {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.recipe-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.recipe-card:hover .recipe-image {
  transform: scale(1.05);
}

.recipe-badge {
  position: absolute;
  bottom: 1rem;
  left: 1rem;
  right: 1rem;
  display: flex;
  justify-content: space-between;
}

.recipe-time,
.recipe-category {
  background: rgba(15, 23, 42, 0.8);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 500;
  backdrop-filter: blur(4px);
}

.recipe-info {
  padding: 1.5rem;
}

.recipe-title {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
  color: #e2e8f0;
  line-height: 1.3;
}

.recipe-description {
  font-size: 0.875rem;
  color: #94a3b8;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.recipe-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1.5rem;
}

.recipe-rating {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #f59e0b;
  font-weight: 500;
  font-size: 0.875rem;
}

.recipe-author {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #94a3b8;
}

.author-avatar {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  object-fit: cover;
  border: 1px solid #334155;
}

/* Состояния */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: #1e293b;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  border: 1px solid #334155;
}

.empty-state svg {
  margin-bottom: 1.5rem;
  color: #3b82f6;
}

.empty-state h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #e2e8f0;
}

.empty-state p {
  color: #94a3b8;
  margin-bottom: 1.5rem;
}

.reset-filters-btn {
  padding: 0.5rem 1.25rem;
  background-color: #334155;
  color: #e2e8f0;
  border: none;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.reset-filters-btn:hover {
  background-color: #3b82f6;
}

.loading-state {
  text-align: center;
  padding: 4rem 2rem;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #334155;
  border-top-color: #3b82f6;
  border-radius: 50%;
  margin: 0 auto 1.5rem;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.loading-state p {
  color: #94a3b8;
}

/* Пагинация */
.pagination {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 3rem;
}

.pagination-link {
  padding: 0.5rem 0.75rem;
  border-radius: 6px;
  color: #94a3b8;
  text-decoration: none;
  font-size: 0.875rem;
  transition: all 0.2s;
  background: #1e293b;
  border: 1px solid #334155;
}

.pagination-link:hover:not(.pagination-disabled) {
  background-color: #334155;
  color: #e2e8f0;
}

.pagination-active {
  background-color: #3b82f6;
  color: white;
  border-color: #3b82f6;
}

.pagination-disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Адаптивность */
@media (max-width: 1024px) {
  .hero-title {
    font-size: 2.2rem;
  }
}

@media (max-width: 768px) {
  .main-container {
    flex-direction: column;
  }
  
  .sidebar {
    flex: 1;
  }
  
  .hero-title {
    font-size: 2rem;
  }
  
  .hero-subtitle {
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  .hero-section {
    padding: 4rem 1rem;
  }
  
  .recipes-grid {
    grid-template-columns: 1fr;
  }
  
  .search-btn {
    padding: 0 1rem;
  }
}
</style>