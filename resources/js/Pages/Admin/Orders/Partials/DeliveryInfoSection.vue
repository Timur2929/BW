<template>
  <div class="section-card">
    <div class="section-header">
      <h3 class="section-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Информация о доставке
      </h3>
    </div>
    
    <div class="section-content">
      <!-- Delivery Details -->
      <div class="delivery-details">
        <div class="detail-group">
          <label class="detail-label">Город:</label>
          <span class="detail-value">{{ order.city }}</span>
        </div>
        
        <div class="detail-group">
          <label class="detail-label">Адрес:</label>
          <span class="detail-value">{{ order.address }}</span>
        </div>
        
        <div class="detail-group" v-if="order.postal_code">
          <label class="detail-label">Почтовый индекс:</label>
          <span class="detail-value">{{ order.postal_code }}</span>
        </div>
        
        <div class="detail-group">
          <label class="detail-label">Стоимость доставки:</label>
          <span class="detail-value price">{{ formatPrice(order.delivery_price) }}</span>
        </div>
        
        <div class="detail-group" v-if="order.comment">
          <label class="detail-label">Комментарий:</label>
          <div class="comment-box">
            <p class="comment-text">{{ order.comment }}</p>
          </div>
        </div>
      </div>
      
      <!-- Edit Form -->
      <div class="edit-section">
        <button 
          @click="toggleEdit" 
          class="btn-edit"
          :class="{ active: editing }"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          {{ editing ? 'Отменить' : 'Редактировать' }}
        </button>
        
        <form v-if="editing" @submit.prevent="updateDeliveryInfo" class="edit-form">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Город *</label>
              <input 
                v-model="form.city" 
                type="text" 
                class="form-input"
                required
              >
            </div>
            
            <div class="form-group">
              <label class="form-label">Почтовый индекс</label>
              <input 
                v-model="form.postal_code" 
                type="text" 
                class="form-input"
                placeholder="000000"
              >
            </div>
            
            <div class="form-group full-width">
              <label class="form-label">Адрес доставки *</label>
              <textarea 
                v-model="form.address" 
                class="form-textarea"
                rows="3"
                required
              ></textarea>
            </div>
            
            <div class="form-group">
              <label class="form-label">Стоимость доставки *</label>
              <div class="price-input-container">
                <input 
                  v-model="form.delivery_price" 
                  type="number" 
                  step="0.01"
                  min="0"
                  class="form-input price-input"
                  required
                >
                <span class="currency-symbol">₽</span>
              </div>
            </div>
            
            <div class="form-group full-width">
              <label class="form-label">Комментарий к заказу</label>
              <textarea 
                v-model="form.comment" 
                class="form-textarea"
                rows="3"
                placeholder="Дополнительная информация от клиента..."
              ></textarea>
            </div>
          </div>
          
          <div class="form-actions">
            <button 
              type="button" 
              @click="cancelEdit" 
              class="btn-cancel"
              :disabled="updating"
            >
              Отмена
            </button>
            <button 
              type="submit" 
              class="btn-save"
              :disabled="updating"
            >
              <span v-if="updating" class="loading-spinner"></span>
              {{ updating ? 'Сохранение...' : 'Сохранить' }}
            </button>
          </div>
        </form>
      </div>
      
      <!-- Delivery Tracking -->
      <div class="tracking-section" v-if="order.status === 'shipped'">
        <h4 class="tracking-title">Отслеживание доставки</h4>
        <div class="tracking-info">
          <div class="tracking-number">
            <label>Трек-номер:</label>
            <span class="tracking-value">RU{{ order.id.toString().padStart(10, '0') }}</span>
            <button class="btn-copy" @click="copyTrackingNumber">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
              </svg>
            </button>
          </div>
          <div class="tracking-status">
            <label>Статус:</label>
            <span class="status-badge in-transit">В пути</span>
          </div>
          <div class="tracking-estimate">
            <label>Примерная доставка:</label>
            <span class="estimate-date">15-17 декабря</span>
          </div>
        </div>
      </div>
      
      <!-- Map Preview -->
      <div class="map-section">
        <div class="map-placeholder">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <p class="map-text">Карта доставки</p>
          <p class="map-address">{{ order.city }}, {{ order.address }}</p>
          <button class="btn-view-map">
            Посмотреть на карте
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  order: Object
});

const emit = defineEmits(['delivery-updated']);

const editing = ref(false);
const updating = ref(false);

const form = reactive({
  city: props.order.city,
  address: props.order.address,
  postal_code: props.order.postal_code || '',
  delivery_price: props.order.delivery_price,
  comment: props.order.comment || ''
});

const toggleEdit = () => {
  editing.value = !editing.value;
  if (!editing.value) {
    resetForm();
  }
};

const cancelEdit = () => {
  editing.value = false;
  resetForm();
};

const resetForm = () => {
  form.city = props.order.city;
  form.address = props.order.address;
  form.postal_code = props.order.postal_code || '';
  form.delivery_price = props.order.delivery_price;
  form.comment = props.order.comment || '';
};

const updateDeliveryInfo = async () => {
  updating.value = true;
  
  try {
    await router.put(route('admin.orders.update', props.order.id), form, {
      onSuccess: () => {
        editing.value = false;
        emit('delivery-updated');
      },
      onFinish: () => {
        updating.value = false;
      }
    });
  } catch (error) {
    updating.value = false;
    console.error('Error updating delivery info:', error);
  }
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB'
  }).format(price);
};

const copyTrackingNumber = () => {
  const trackingNumber = `RU${props.order.id.toString().padStart(10, '0')}`;
  navigator.clipboard.writeText(trackingNumber);
  // Здесь можно добавить уведомление об успешном копировании
  alert(`Трек-номер ${trackingNumber} скопирован в буфер обмена`);
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

.delivery-details {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 20px;
}

.detail-group {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 8px 0;
}

.detail-label {
  font-size: 14px;
  color: #6b7280;
  font-weight: 500;
  flex: 1;
}

.detail-value {
  font-size: 14px;
  color: #1f2937;
  font-weight: 500;
  flex: 2;
  text-align: right;
}

.detail-value.price {
  color: #059669;
  font-weight: 600;
}

.comment-box {
  background: #f8fafc;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  flex: 2;
}

.comment-text {
  margin: 0;
  font-size: 14px;
  color: #374151;
  line-height: 1.5;
}

/* Edit Form Styles */
.edit-section {
  border-top: 1px solid #f3f4f6;
  padding-top: 20px;
  margin-bottom: 20px;
}

.btn-edit {
  display: flex;
  align-items: center;
  padding: 8px 16px;
  background: #6b7280;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-edit:hover {
  background: #4b5563;
}

.btn-edit.active {
  background: #ef4444;
}

.btn-edit.active:hover {
  background: #dc2626;
}

.edit-form {
  margin-top: 16px;
  padding: 20px;
  background: #f9fafb;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  font-size: 14px;
  font-weight: 500;
  color: #374151;
}

.form-input, .form-textarea {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s;
  font-family: inherit;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.price-input-container {
  position: relative;
}

.price-input {
  padding-right: 30px;
}

.currency-symbol {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #6b7280;
  font-weight: 500;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.btn-cancel, .btn-save {
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-cancel {
  background: #6b7280;
  color: white;
}

.btn-cancel:hover:not(:disabled) {
  background: #4b5563;
}

.btn-save {
  display: flex;
  align-items: center;
  background: #10b981;
  color: white;
}

.btn-save:hover:not(:disabled) {
  background: #059669;
}

.btn-cancel:disabled, .btn-save:disabled {
  background: #9ca3af;
  cursor: not-allowed;
}

.loading-spinner {
  width: 14px;
  height: 14px;
  border: 2px solid transparent;
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-right: 8px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Tracking Section */
.tracking-section {
  border-top: 1px solid #f3f4f6;
  padding-top: 20px;
  margin-bottom: 20px;
}

.tracking-title {
  font-size: 16px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.tracking-info {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.tracking-number, .tracking-status, .tracking-estimate {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.tracking-number label, .tracking-status label, .tracking-estimate label {
  font-size: 14px;
  color: #6b7280;
  font-weight: 500;
}

.tracking-value {
  font-family: monospace;
  font-weight: 600;
  color: #1f2937;
}

.btn-copy {
  display: flex;
  align-items: center;
  padding: 4px;
  background: #e5e7eb;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-copy:hover {
  background: #d1d5db;
}

.status-badge {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 500;
}

.status-badge.in-transit {
  background: #fef3c7;
  color: #92400e;
}

.estimate-date {
  font-weight: 600;
  color: #1f2937;
}

/* Map Section */
.map-section {
  border-top: 1px solid #f3f4f6;
  padding-top: 20px;
}

.map-placeholder {
  text-align: center;
  padding: 40px 20px;
  background: #f8fafc;
  border: 2px dashed #d1d5db;
  border-radius: 8px;
}

.map-text {
  font-size: 16px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.map-address {
  font-size: 14px;
  color: #6b7280;
  margin-bottom: 16px;
}

.btn-view-map {
  padding: 8px 16px;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-view-map:hover {
  background: #2563eb;
  transform: translateY(-1px);
}

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .detail-group {
    flex-direction: column;
    gap: 4px;
  }
  
  .detail-value {
    text-align: left;
  }
  
  .tracking-number, .tracking-status, .tracking-estimate {
    flex-direction: column;
    gap: 8px;
    align-items: flex-start;
  }
}
</style>