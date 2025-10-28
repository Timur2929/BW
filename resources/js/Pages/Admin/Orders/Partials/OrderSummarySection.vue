<template>
  <div class="section-card">
    <div class="section-header">
      <h3 class="section-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
        </svg>
        Сводка заказа
      </h3>
    </div>
    
    <div class="section-content">
      <div class="summary-grid">
        <div class="summary-item">
          <span class="summary-label">Стоимость товаров:</span>
          <span class="summary-value">{{ formatPrice(order.subtotal) }}</span>
        </div>
        
        <div class="summary-item">
          <span class="summary-label">Доставка:</span>
          <span class="summary-value">{{ formatPrice(order.delivery_price) }}</span>
        </div>
        
        <div class="summary-item discount" v-if="order.discount > 0">
          <span class="summary-label">Скидка:</span>
          <span class="summary-value">-{{ formatPrice(order.discount) }}</span>
        </div>
        
        <div class="summary-divider"></div>
        
        <div class="summary-item total">
          <span class="summary-label">Общая сумма:</span>
          <span class="summary-value">{{ formatPrice(order.total) }}</span>
        </div>
        
        <div class="summary-item">
          <span class="summary-label">Количество товаров:</span>
          <span class="summary-value">{{ order.total_items }} шт.</span>
        </div>
        
        <div class="summary-item">
          <span class="summary-label">Метод доставки:</span>
          <span class="summary-value">Курьерская доставка</span>
        </div>
        
        <div class="summary-item">
          <span class="summary-label">Оплата:</span>
          <span class="summary-value status-badge pending">Ожидает оплаты</span>
        </div>
      </div>
      
      <div class="timeline-section">
        <h4 class="timeline-title">История заказа</h4>
        <div class="timeline">
          <div class="timeline-item" :class="{ active: true }">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
              <span class="timeline-date">{{ formatDate(order.created_at) }}</span>
              <span class="timeline-event">Заказ создан</span>
            </div>
          </div>
          
          <div class="timeline-item" :class="{ active: order.status !== 'pending' }">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
              <span class="timeline-date" v-if="order.status !== 'pending'">{{ formatDate(order.updated_at) }}</span>
              <span class="timeline-event">Подтвержден</span>
            </div>
          </div>
          
          <div class="timeline-item" :class="{ active: ['shipped', 'delivered'].includes(order.status) }">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
              <span class="timeline-date" v-if="['shipped', 'delivered'].includes(order.status)">{{ formatDate(order.updated_at) }}</span>
              <span class="timeline-event">Отправлен</span>
            </div>
          </div>
          
          <div class="timeline-item" :class="{ active: order.status === 'delivered' }">
            <div class="timeline-marker"></div>
            <div class="timeline-content">
              <span class="timeline-date" v-if="order.status === 'delivered'">{{ formatDate(order.updated_at) }}</span>
              <span class="timeline-event">Доставлен</span>
            </div>
          </div>
        </div>
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

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
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

.summary-grid {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 24px;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
}

.summary-item.discount .summary-value {
  color: #ef4444;
}

.summary-item.total {
  border-top: 2px solid #e5e7eb;
  padding-top: 16px;
  margin-top: 8px;
}

.summary-label {
  font-size: 14px;
  color: #6b7280;
}

.summary-value {
  font-size: 14px;
  font-weight: 600;
  color: #1f2937;
}

.summary-item.total .summary-value {
  font-size: 18px;
  color: #059669;
}

.summary-divider {
  height: 1px;
  background: #e5e7eb;
  margin: 8px 0;
}

.status-badge {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 500;
}

.status-badge.pending {
  background: #fef3c7;
  color: #92400e;
}

.timeline-section {
  border-top: 1px solid #f3f4f6;
  padding-top: 20px;
}

.timeline-title {
  font-size: 16px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.timeline {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.timeline-item {
  display: flex;
  gap: 12px;
  align-items: flex-start;
}

.timeline-marker {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #d1d5db;
  border: 2px solid white;
  box-shadow: 0 0 0 2px #d1d5db;
  flex-shrink: 0;
  margin-top: 4px;
}

.timeline-item.active .timeline-marker {
  background: #10b981;
  box-shadow: 0 0 0 2px #10b981;
}

.timeline-content {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.timeline-date {
  font-size: 12px;
  color: #6b7280;
}

.timeline-event {
  font-size: 14px;
  color: #374151;
  font-weight: 500;
}

.timeline-item.active .timeline-event {
  color: #10b981;
}

@media (max-width: 768px) {
  .summary-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
  }
}
</style>