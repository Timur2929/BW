<template>
<AdminLayout>
  <Head title="Управление заказами" />

  <div class="min-h-screen bg-gray-50">
    <Sidebar :user="$page.props.auth.user" />

    <div class="main-content">
      <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
          <div class="header-content">
            <h1 class="admin-title">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              Управление заказами
            </h1>
            <div class="header-actions">
              <Link :href="route('admin.orders.statistics')" class="btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Статистика
              </Link>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
          <div class="filters-grid">
            <div class="search-box">
              <input
                type="text"
                v-model="filters.search"
                @input="debouncedSearch"
                placeholder="Поиск по номеру заказа..."
                class="search-input"
              />
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>

            <select v-model="filters.status" @change="updateFilters" class="filter-select">
              <option v-for="(label, value) in statusOptions" :key="value" :value="value">
                {{ label }}
              </option>
            </select>
          </div>
        </div>

        <!-- Orders Table -->
        <div class="table-container">
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th class="table-header">Номер заказа</th>
                  <th class="table-header">Клиент</th>
                  <th class="table-header">Дата</th>
                  <th class="table-header">Сумма</th>
                  <th class="table-header">Статус</th>
                  <th class="table-header">Товары</th>
                  <th class="table-header">Действия</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in orders.data" :key="order.id" class="table-row">
                  <td class="table-cell">
                    <span class="font-mono text-sm text-gray-600 font-semibold">{{ order.order_number }}</span>
                  </td>
                  <td class="table-cell">
                    <div class="customer-info">
                      <span class="customer-name">{{ order.first_name }} {{ order.last_name }}</span>
                      <span class="customer-email">{{ order.email }}</span>
                    </div>
                  </td>
                  <td class="table-cell text-gray-600">
                    {{ formatDate(order.created_at) }}
                  </td>
                  <td class="table-cell">
                    <span class="font-semibold text-green-600">{{ formatPrice(order.total) }}</span>
                  </td>
                  <td class="table-cell">
                    <span class="status-badge" :class="getStatusClass(order.status)">
                      {{ getStatusText(order.status) }}
                    </span>
                  </td>
                  <td class="table-cell">
                    <div class="items-count">
                      {{ order.total_items }} шт.
                    </div>
                  </td>
                  <td class="table-cell">
                    <div class="action-buttons">
                      <Link :href="route('admin.orders.show', order.id)" class="btn-action view">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </Link>
                      <button 
                        @click="updateOrderStatus(order)" 
                        class="btn-action edit"
                        title="Изменить статус"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </button>
                      <button 
                        @click="deleteOrder(order)" 
                        class="btn-action delete"
                        title="Удалить заказ"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State -->
          <div v-if="orders.data.length === 0" class="empty-state">
            <div class="empty-state-content">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              <h3 class="empty-state-title">Заказы не найдены</h3>
              <p class="empty-state-text">Попробуйте изменить параметры фильтрации</p>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="orders.data.length > 0" class="pagination-container">
          <Pagination :links="orders.links" />
        </div>
      </div>
    </div>

    <!-- Status Update Modal -->
    <div v-if="showStatusModal" class="modal-overlay">
      <div class="modal-container">
        <div class="modal-header">
          <h3 class="modal-title">Изменение статуса заказа</h3>
          <button @click="closeModal" class="modal-close">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="modal-content">
          <p class="mb-4 text-gray-600">Заказ: <strong>{{ selectedOrder?.order_number }}</strong></p>
          <select v-model="newStatus" class="status-select text-gray-800">
            <option value="pending">Ожидает подтверждения</option>
            <option value="processing">В обработке</option>
            <option value="shipped">Отправлен</option>
            <option value="delivered">Доставлен</option>
            <option value="cancelled">Отменён</option>
          </select>
        </div>
        <div class="modal-actions">
          <button @click="closeModal" class="btn-cancel">Отмена</button>
          <button @click="confirmStatusUpdate" class="btn-primary">Обновить</button>
        </div>
      </div>
    </div>
  </div>
  </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  orders: Object,
  filters: Object,
  statusOptions: Object
});

const filters = ref({
  search: props.filters.search,
  status: props.filters.status
});

const showStatusModal = ref(false);
const selectedOrder = ref(null);
const newStatus = ref('');

let searchTimeout = null;

const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    updateFilters();
  }, 500);
};

const updateFilters = () => {
  router.get(route('admin.orders.index'), filters.value, {
    preserveState: true,
    replace: true
  });
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('ru-RU');
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB'
  }).format(price);
};

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
    pending: 'Ожидает',
    processing: 'В обработке',
    shipped: 'Отправлен',
    delivered: 'Доставлен',
    cancelled: 'Отменён'
  };
  return texts[status] || status;
};

const updateOrderStatus = (order) => {
  selectedOrder.value = order;
  newStatus.value = order.status;
  showStatusModal.value = true;
};

const confirmStatusUpdate = () => {
  router.put(route('admin.orders.updateStatus', selectedOrder.value.id), {
    status: newStatus.value
  }, {
    onSuccess: () => {
      closeModal();
    }
  });
};

const deleteOrder = (order) => {
  if (confirm(`Вы уверены, что хотите удалить заказ ${order.order_number}?`)) {
    router.delete(route('admin.orders.destroy', order.id));
  }
};

const closeModal = () => {
  showStatusModal.value = false;
  selectedOrder.value = null;
  newStatus.value = '';
};
</script>

<style scoped>
.main-content {
  margin-left: 280px;
  min-height: 100vh;
  background-color: #f8fafc;
}

.admin-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 30px;
}

.admin-header {
  background: white;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.admin-title {
  display: flex;
  align-items: center;
  font-size: 24px;
  font-weight: 700;
  color: #1f2937;
}

.filters-section {
  background: white;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.filters-grid {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 16px;
  align-items: center;
}

.search-box {
  position: relative;
  max-width: 400px;
}

.search-input {
  width: 100%;
  padding: 10px 40px 10px 16px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
}

.search-box svg {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
}

.filter-select {
  padding: 10px 16px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  background: white;
}

.table-container {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.table-responsive {
  overflow-x: auto;
}

.admin-table {
  width: 100%;
  border-collapse: collapse;
}

.table-header {
  background-color: #f8fafc;
  padding: 16px;
  text-align: left;
  font-weight: 600;
  color: #374151;
  border-bottom: 1px solid #e5e7eb;
}

.table-row {
  border-bottom: 1px solid #f3f4f6;
  transition: background-color 0.2s;
}

.table-row:hover {
  background-color: #f9fafb;
}

.table-cell {
  padding: 16px;
  white-space: nowrap;
}

.customer-info {
  display: flex;
  flex-direction: column;
}

.customer-name {
  font-weight: 500;
  color: #1f2937;
}

.customer-email {
  font-size: 12px;
  color: #6b7280;
}

.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
}

.status-pending {
  background-color: #fef3c7;
  color: #92400e;
}

.status-processing {
  background-color: #dbeafe;
  color: #1e40af;
}

.status-shipped {
  background-color: #f0f9ff;
  color: #0369a1;
}

.status-delivered {
  background-color: #d1fae5;
  color: #065f46;
}

.status-cancelled {
  background-color: #fee2e2;
  color: #991b1b;
}

.items-count {
  background-color: #f3f4f6;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 500;
  color: #6b7280;
}

.action-buttons {
  display: flex;
  gap: 8px;
}

.btn-action {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 8px;
  border-radius: 6px;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.btn-action.view {
  background-color: #3b82f6;
  color: white;
}

.btn-action.view:hover {
  background-color: #2563eb;
}

.btn-action.edit {
  background-color: #10b981;
  color: white;
}

.btn-action.edit:hover {
  background-color: #059669;
}

.btn-action.delete {
  background-color: #ef4444;
  color: white;
}

.btn-action.delete:hover {
  background-color: #dc2626;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 50;
}

.modal-container {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 24px;
  border-bottom: 1px solid #e5e7eb;
}

.modal-title {
  font-size: 18px;
  font-weight: 600;
  color: #1f2937;
}

.modal-close {
  padding: 4px;
  border: none;
  background: none;
  cursor: pointer;
  color: #6b7280;
}

.modal-content {
  padding: 24px;
}

.status-select {
  width: 100%;
  padding: 10px 16px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
}

.modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding: 24px;
  border-top: 1px solid #e5e7eb;
}

.btn-cancel, .btn-primary {
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-cancel {
  background-color: #6b7280;
  color: white;
}

.btn-cancel:hover {
  background-color: #4b5563;
}

.btn-primary {
  background-color: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background-color: #2563eb;
}

.btn-secondary {
  display: inline-flex;
  align-items: center;
  background-color: #6b7280;
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.2s;
}

.btn-secondary:hover {
  background-color: #4b5563;
}

/* Empty State */
.empty-state {
  padding: 60px 20px;
  text-align: center;
}

.empty-state-title {
  font-size: 18px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
}

.empty-state-text {
  color: #6b7280;
  margin-bottom: 20px;
}

/* Pagination */
.pagination-container {
  margin-top: 24px;
  display: flex;
  justify-content: center;
}

@media (max-width: 768px) {
  .main-content {
    margin-left: 0;
  }
  
  .admin-container {
    padding: 16px;
  }
  
  .filters-grid {
    grid-template-columns: 1fr;
  }
  
  .header-content {
    flex-direction: column;
    gap: 16px;
    align-items: flex-start;
  }
}
</style>