<template>
    <Modal :show="show" @close="close">
        <div class="p-6">
            <h2 class="text-lg font-medium text-white mb-4">
                {{ title }}
            </h2>

            <div class="text-gray-300 mb-6">
                {{ message }}
            </div>

            <div class="flex justify-end space-x-3">
                <button 
                    @click="close" 
                    class="px-4 py-2 text-gray-300 hover:text-white transition-colors"
                >
                    Отмена
                </button>
                <button 
                    @click="confirm" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                >
                    Удалить
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from './Modal.vue';

const emit = defineEmits(['close', 'confirm']);

defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Подтверждение удаления'
    },
    message: {
        type: String,
        default: 'Вы уверены, что хотите удалить этот элемент?'
    }
});

const close = () => emit('close');
const confirm = () => emit('confirm');
</script>