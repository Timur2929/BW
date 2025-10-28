<template>
  <div class="section-card">
    <div class="section-header">
      <h3 class="section-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Информация о клиенте
      </h3>
    </div>
    
    <div class="section-content">
      <!-- Customer Details -->
      <div class="customer-details">
        <div class="detail-group">
          <label class="detail-label">Имя и фамилия:</label>
          <span class="detail-value">{{ order.first_name }} {{ order.last_name }}</span>
        </div>
        
        <div class="detail-group">
          <label class="detail-label">Email:</label>
          <a :href="`mailto:${order.email}`" class="detail-value link">
            {{ order.email }}
          </a>
        </div>
        
        <div class="detail-group">
          <label class="detail-label">Телефон:</label>
          <a :href="`tel:${order.phone}`" class="detail-value link">
            {{ order.phone }}
          </a>
        </div>
        
        <div class="detail-group" v-if="order.user">
          <label class="detail-label">Аккаунт:</label>
          <span class="detail-value">
            {{ order.user.first_name }} {{ order.user.last_name }}
            <span class="user-id">(ID: {{ order.user.id }})</span>
          </span>
        </div>
        
        <div class="detail-group" v-else>
          <label class="detail-label">Тип заказа:</label>
          <span class="detail-value tag guest">Гостевой заказ</span>
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
        
        <form v-if="editing" @submit.prevent="updateCustomerInfo" class="edit-form">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Имя</label>
              <input 
                v-model="form.first_name" 
                type="text" 
                class="form-input"
                required
              >
            </div>
            
            <div class="form-group">
              <label class="form-label">Фамилия</label>
              <input 
                v-model="form.last_name" 
                type="text" 
                class="form-input"
                required
              >
            </div>
            
            <div class="form-group">
              <label class="form-label">Email</label>
              <input 
                v-model="form.email" 
                type="email" 
                class="form-input"
                required
              >
            </div>
            
            <div class="form-group">
              <label class="form-label">Телефон</label>
              <input 
                v-model="form.phone" 
                type="tel" 
                class="form-input"
                required
              >
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
      
      <!-- Customer Stats -->
      <div class="customer-stats" v-if="order.user">
        <h4 class="stats-title">Статистика клиента</h4>
        <div class="stats-grid">
          <div class="stat-item">
            <span class="stat-number">{{ customerStats.totalOrders }}</span>
            <span class="stat-label">Всего заказов</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">{{ formatPrice(customerStats.totalSpent) }}</span>
            <span class="stat-label">Всего потрачено</span>
          </div>
          <div class="stat-item">
            <span class="stat-number">{{ customerStats.avgOrderValue }}</span>
            <span class="stat-label">Средний чек</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  order: Object
});

const emit = defineEmits(['customer-updated']);

const editing = ref(false);
const updating = ref(false);

const form = reactive({
  first_name: props.order.first_name,
  last_name: props.order.last_name,
  email: props.order.email,
  phone: props.order.phone
});

const customerStats = computed(() => {
  // В реальном приложении эти данные приходили бы с бэкенда
  return {
    totalOrders: 3,
    totalSpent: 12500,
    avgOrderValue: '4,167 ₽'
  };
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
  form.first_name = props.order.first_name;
  form.last_name = props.order.last_name;
  form.email = props.order.email;
  form.phone = props.order.phone;
};

const updateCustomerInfo = async () => {
  updating.value = true;
  
  try {
    await router.put(route('admin.orders.update', props.order.id), form, {
      onSuccess: () => {
        editing.value = false;
        emit('customer-updated');
      },
      onFinish: () => {
        updating.value = false;
      }
    });
  } catch (error) {
    updating.value = false;
    console.error('Error updating customer info:', error);
  }
};

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

.customer-details {
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

.detail-value.link {
  color: #3b82f6;
  text-decoration: none;
}

.detail-value.link:hover {
  text-decoration: underline;
}

.user-id {
  font-size: 12px;
  color: #9ca3af;
  font-family: monospace;
}

.tag {
  display: inline-block;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 500;
}

.tag.guest {
  background: #f3f4f6;
  color: #6b7280;
}

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

.form-label {
  font-size: 14px;
  font-weight: 500;
  color: #374151;
}

.form-input {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s;
}

.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
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

.customer-stats {
  border-top: 1px solid #f3f4f6;
  padding-top: 20px;
}

.stats-title {
  font-size: 16px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.stat-item {
  text-align: center;
  padding: 16px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.stat-number {
  display: block;
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 12px;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .detail-group {
    flex-direction: column;
    gap: 4px;
  }
  
  .detail-value {
    text-align: left;
  }
}
</style>