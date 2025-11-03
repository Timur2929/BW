<template>
<AdminLayout>
  <Head :title="`Заказ ${order.order_number}`" />

  <div class="min-h-screen bg-gray-50">
    <Sidebar :user="$page.props.auth.user" />

    <div class="main-content">
      <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
          <div class="header-content">
            <div class="header-left">
              <Link :href="route('admin.orders.index')" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Назад к заказам
              </Link>
              <h1 class="admin-title">
                Заказ {{ order.order_number }}
              </h1>
            </div>
            <div class="header-actions">
              <button @click="deleteOrder" class="btn-danger">
                Удалить заказ
              </button>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Order Details -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <OrderItemsSection :order="order" />

            <!-- Order Summary -->
            <OrderSummarySection :order="order" />
          </div>

          <!-- Sidebar -->
          <div class="space-y-6">
            <!-- Order Status -->
            <OrderStatusSection :order="order" @status-updated="$inertia.reload()" />

            <!-- Customer Info -->
            <CustomerInfoSection :order="order" @customer-updated="$inertia.reload()" />

            <!-- Delivery Info -->
            <DeliveryInfoSection :order="order" @delivery-updated="$inertia.reload()" />
          </div>
        </div>
      </div>
    </div>
  </div>
   </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import OrderItemsSection from './Partials/OrderItemsSection.vue';
import OrderSummarySection from './Partials/OrderSummarySection.vue';
import OrderStatusSection from './Partials/OrderStatusSection.vue';
import CustomerInfoSection from './Partials/CustomerInfoSection.vue';
import DeliveryInfoSection from './Partials/DeliveryInfoSection.vue';

const props = defineProps({
  order: Object
});

const deleteOrder = () => {
  if (confirm(`Вы уверены, что хотите удалить заказ ${props.order.order_number}?`)) {
    router.delete(route('admin.orders.destroy', props.order.id));
  }
};
</script>

<style scoped>
/* Стили аналогичные Index.vue */
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