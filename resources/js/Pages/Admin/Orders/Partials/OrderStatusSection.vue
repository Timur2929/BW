<template>
  <div class="section-card">
    <div class="section-header">
      <h3 class="section-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Статус заказа
      </h3>
    </div>
    
    <div class="section-content">
      <!-- Current Status -->
      <div class="current-status">
        <div class="status-display" :class="getStatusClass(order.status)">
          {{ getStatusText(order.status) }}
        </div>
        <p class="status-description">{{ getStatusDescription(order.status) }}</p>
      </div>
      
      <!-- Status Update Form -->
      <div class="status-update-form">
        <h4 class="form-title">Изменить статус</h4>
        
        <form @submit.prevent="updateStatus" class="form-grid">
          <select v-model="newStatus" class="status-select" :disabled="updating">
            <option value="pending">Ожидает подтверждения</option>
            <option value="processing">В обработке</option>
            <option value="shipped">Отправлен</option>
            <option value="delivered">Доставлен</option>
            <option value="cancelled">Отменён</option>
          </select>
          
          <button 
            type="submit" 
            class="btn-primary" 
            :disabled="updating || newStatus === order.status"
          >
            <span v-if="updating" class="loading-spinner"></span>
            {{ updating ? 'Обновление...' : 'Обновить статус' }}
          </button>
        </form>
        
        <!-- Status History -->
        <div class="status-history" v-if="statusHistory.length > 0">
          <h5 class="history-title">История изменений</h5>
          <div class="history-list">
            <div 
              v-for="history in statusHistory" 
              :key="history.id" 
              class="history-item"
            >
              <span class="history-status" :class="getStatusClass(history.status)">
                {{ getStatusText(history.status) }}
              </span>
              <span class="history-date">{{ formatDate(history.created_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  order: Object
});

const emit = defineEmits(['status-updated']);

const newStatus = ref(props.order.status);
const updating = ref(false);

const statusHistory = computed(() => {
  // В реальном приложении здесь была бы история статусов из базы данных
  return [
    {
      id: 1,
      status: 'pending',
      created_at: props.order.created_at
    }
  ];
});

const getStatusClass = (status) => {
  const classes = {
    pending: 'status-pending',
    processing: 'status-processing',
    shipped: 'status-shipped',
    delivered: 'status-delivered',
    cancelled: 'status-cancelled'
  };
  return classes[status] || 'status-pending';
};

const getStatusText = (status) => {
  const texts = {
    pending: 'Ожидает подтверждения',
    processing: 'В обработке',
    shipped: 'Отправлен',
    delivered: 'Доставлен',
    cancelled: 'Отменён'
  };
  return texts[status] || status;
};

const getStatusDescription = (status) => {
  const descriptions = {
    pending: 'Заказ ожидает подтверждения администратором',
    processing: 'Заказ находится в обработке',
    shipped: 'Заказ отправлен клиенту',
    delivered: 'Заказ успешно доставлен',
    cancelled: 'Заказ был отменён'
  };
  return descriptions[status] || 'Статус заказа';
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

const updateStatus = async () => {
  if (newStatus.value === props.order.status) return;
  
  updating.value = true;
  
  try {
    await router.put(route('admin.orders.updateStatus', props.order.id), {
      status: newStatus.value
    }, {
      onSuccess: () => {
        emit('status-updated');
      },
      onFinish: () => {
        updating.value = false;
      }
    });
  } catch (error) {
    updating.value = false;
    console.error('Error updating status:', error);
  }
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

.current-status {
  text-align: center;
  margin-bottom: 24px;
  padding: 20px;
  background: #f8fafc;
  border-radius: 8px;
}

.status-display {
  display: inline-block;
  padding: 12px 24px;
  border-radius: 25px;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-pending {
  background: #fef3c7;
  color: #92400e;
  border: 2px solid #f59e0b;
}

.status-processing {
  background: #dbeafe;
  color: #1e40af;
  border: 2px solid #3b82f6;
}

.status-shipped {
  background: #f0f9ff;
  color: #0369a1;
  border: 2px solid #0ea5e9;
}

.status-delivered {
  background: #d1fae5;
  color: #065f46;
  border: 2px solid #10b981;
}

.status-cancelled {
  background: #fee2e2;
  color: #991b1b;
  border: 2px solid #ef4444;
}

.status-description {
  color: #6b7280;
  font-size: 14px;
  margin: 0;
}

.status-update-form {
  border-top: 1px solid #f3f4f6;
  padding-top: 20px;
}

.form-title {
  font-size: 16px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 16px;
}

.form-grid {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 20px;
}

.status-select {
  padding: 10px 16px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  background: white;
  transition: border-color 0.2s;
}

.status-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 12px 20px;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary:hover:not(:disabled) {
  background: #2563eb;
  transform: translateY(-1px);
}

.btn-primary:disabled {
  background: #9ca3af;
  cursor: not-allowed;
  transform: none;
}

.loading-spinner {
  width: 16px;
  height: 16px;
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

.status-history {
  border-top: 1px solid #f3f4f6;
  padding-top: 16px;
}

.history-title {
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 12px;
}

.history-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.history-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
  background: #f9fafb;
  border-radius: 6px;
}

.history-status {
  font-size: 12px;
  font-weight: 500;
  padding: 4px 8px;
  border-radius: 4px;
}

.history-date {
  font-size: 11px;
  color: #6b7280;
}

@media (max-width: 768px) {
  .form-grid {
    flex-direction: column;
  }
}
</style>