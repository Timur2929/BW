<template>
  <div class="section-card">
    <div class="section-header">
      <h3 class="section-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        Товары в заказе ({{ order.total_items }})
      </h3>
    </div>
    
    <div class="section-content">
      <div class="items-list">
        <div v-for="item in order.items" :key="item.id" class="item-card">
          <div class="item-image-container">
            <img 
              :src="item.image || item.recipe?.image || '/images/placeholder.jpg'" 
              :alt="item.name"
              class="item-image"
            />
          </div>
          
          <div class="item-details">
            <div class="item-main-info">
              <h4 class="item-name">{{ item.name }}</h4>
              <div class="item-meta">
                <span v-if="item.recipe?.category" class="item-category">
                  {{ item.recipe.category.name }}
                </span>
                <span class="item-id">ID: {{ item.recipe_id || 'N/A' }}</span>
              </div>
            </div>
            
            <div class="item-pricing">
              <div class="price-quantity">
                <span class="item-price">{{ formatPrice(item.price) }}</span>
                <span class="item-quantity">× {{ item.quantity }} шт.</span>
              </div>
              <div class="item-total">
                {{ formatPrice(item.price * item.quantity) }}
              </div>
            </div>
          </div>
          
          <div class="item-stock" v-if="item.recipe">
            <span class="stock-info" :class="{ 'low-stock': item.recipe.quantity < 10 }">
              В наличии: {{ item.recipe.quantity }} шт.
            </span>
          </div>
        </div>
      </div>
      
      <!-- Empty State -->
      <div v-if="order.items.length === 0" class="empty-items">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <p class="empty-text">Товары не найдены</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  order: Object
});

const formatPrice = (price) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB'
  }).format(price);
};
</script>

<style scoped>
.section-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.section-header {
  padding: 20px 24px;
  border-bottom: 1px solid #f3f4f6;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.section-title {
  display: flex;
  align-items: center;
  font-size: 18px;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.section-content {
  padding: 24px;
}

.items-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.item-card {
  display: flex;
  gap: 16px;
  padding: 20px;
  background: #f9fafb;
  border-radius: 12px;
  border: 1px solid #f3f4f6;
  transition: all 0.2s ease;
}

.item-card:hover {
  background: #f3f4f6;
  border-color: #e5e7eb;
  transform: translateY(-1px);
}

.item-image-container {
  flex-shrink: 0;
}

.item-image {
  width: 80px;
  height: 80px;
  border-radius: 8px;
  object-fit: cover;
  border: 2px solid #e5e7eb;
}

.item-details {
  flex: 1;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 16px;
}

.item-main-info {
  flex: 1;
}

.item-name {
  font-size: 16px;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 8px;
  line-height: 1.4;
}

.item-meta {
  display: flex;
  gap: 12px;
  align-items: center;
}

.item-category {
  font-size: 12px;
  color: #6b7280;
  background: #e5e7eb;
  padding: 4px 8px;
  border-radius: 6px;
  font-weight: 500;
}

.item-id {
  font-size: 12px;
  color: #9ca3af;
  font-family: monospace;
}

.item-pricing {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 8px;
}

.price-quantity {
  display: flex;
  gap: 8px;
  align-items: center;
}

.item-price {
  font-size: 16px;
  font-weight: 600;
  color: #059669;
}

.item-quantity {
  font-size: 14px;
  color: #6b7280;
  background: #e5e7eb;
  padding: 4px 8px;
  border-radius: 6px;
}

.item-total {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
}

.item-stock {
  display: flex;
  align-items: center;
}

.stock-info {
  font-size: 12px;
  padding: 4px 8px;
  border-radius: 6px;
  background: #d1fae5;
  color: #065f46;
  font-weight: 500;
}

.stock-info.low-stock {
  background: #fef3c7;
  color: #92400e;
}

.empty-items {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.empty-text {
  font-size: 16px;
  margin: 0;
}

@media (max-width: 768px) {
  .item-card {
    flex-direction: column;
    text-align: center;
  }
  
  .item-details {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  
  .item-pricing {
    align-items: center;
  }
  
  .item-meta {
    justify-content: center;
  }
}
</style>