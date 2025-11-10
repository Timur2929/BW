<template>
    <div v-if="shouldShow" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <!-- Mobile -->
        <div class="flex flex-1 justify-between sm:hidden">
            <Link 
                v-if="previousUrl"
                :href="previousUrl"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Назад
            </Link>
            <span v-else class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                Назад
            </span>
            
            <Link 
                v-if="nextUrl"
                :href="nextUrl"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Вперед
            </Link>
            <span v-else class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                Вперед
            </span>
        </div>

        <!-- Desktop -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Показано с
                    <span class="font-medium">{{ from }}</span>
                    по
                    <span class="font-medium">{{ to }}</span>
                    из
                    <span class="font-medium">{{ total }}</span>
                    результатов
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <Link 
                        v-for="(link, index) in visibleLinks"
                        :key="index"
                        :href="link.url || ''"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20"
                        :class="getLinkClasses(link)"
                        v-html="link.label"
                    />
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    links: {
        type: Array,
        default: () => []
    },
    from: {
        type: Number,
        default: 0
    },
    to: {
        type: Number,
        default: 0
    },
    total: {
        type: Number,
        default: 0
    }
});

// Безопасные вычисляемые свойства
const shouldShow = computed(() => {
    return props.links && props.links.length > 3;
});

const visibleLinks = computed(() => {
    if (!props.links || !Array.isArray(props.links)) {
        return [];
    }
    return props.links.map(link => ({
        url: link.url || null,
        label: link.label || '',
        active: link.active || false
    })).filter(link => 
        link.label.includes('Previous') || 
        link.label.includes('Next') || 
        !isNaN(parseInt(link.label)) ||
        link.label.includes('...')
    );
});

const previousUrl = computed(() => {
    if (!props.links || props.links.length === 0) return null;
    return props.links[0]?.url || null;
});

const nextUrl = computed(() => {
    if (!props.links || props.links.length === 0) return null;
    return props.links[props.links.length - 1]?.url || null;
});

const getLinkClasses = (link) => {
    const baseClasses = 'ring-1 ring-inset focus:outline-offset-0';
    
    if (!link.url) {
        return `${baseClasses} bg-gray-100 text-gray-400 cursor-not-allowed ring-gray-300`;
    }
    
    if (link.active) {
        return `${baseClasses} z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 ring-indigo-600`;
    }
    
    return `${baseClasses} text-gray-900 ring-gray-300 hover:bg-gray-50`;
};
</script>