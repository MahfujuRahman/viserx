<template>
    <nav v-if="hasPages" class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">
            Showing {{ meta.from }} to {{ meta.to }} of {{ meta.total }} results
        </p>

        <div class="flex flex-wrap items-center gap-2">
            <button
                type="button"
                class="inline-flex items-center rounded-lg border px-3 py-2 text-sm font-medium transition-colors"
                :class="meta.current_page === 1
                    ? 'cursor-not-allowed border-slate-200 bg-white text-slate-300'
                    : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                :disabled="meta.current_page === 1"
                @click="changePage(meta.current_page - 1)"
            >
                Previous
            </button>

            <template v-for="item in pageItems" :key="item.key">
                <span v-if="item.type === 'ellipsis'" class="px-2 text-sm text-slate-400">...</span>
                <button
                    v-else
                    type="button"
                    class="inline-flex min-w-10 items-center justify-center rounded-lg border px-3 py-2 text-sm font-medium transition-colors"
                    :class="item.page === meta.current_page
                        ? 'border-indigo-600 bg-indigo-600 text-white shadow-sm'
                        : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                    @click="changePage(item.page)"
                >
                    {{ item.page }}
                </button>
            </template>

            <button
                type="button"
                class="inline-flex items-center rounded-lg border px-3 py-2 text-sm font-medium transition-colors"
                :class="meta.current_page === meta.last_page
                    ? 'cursor-not-allowed border-slate-200 bg-white text-slate-300'
                    : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'"
                :disabled="meta.current_page === meta.last_page"
                @click="changePage(meta.current_page + 1)"
            >
                Next
            </button>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    meta: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['change']);

const hasPages = computed(() => props.meta && props.meta.last_page > 1);

const pageItems = computed(() => {
    if (!hasPages.value) {
        return [];
    }

    const current = props.meta.current_page;
    const last = props.meta.last_page;
    const items = [];

    const pushPage = (page) => {
        items.push({
            type: 'page',
            page,
            key: `page-${page}`,
        });
    };

    pushPage(1);

    const start = Math.max(2, current - 1);
    const end = Math.min(last - 1, current + 1);

    if (start > 2) {
        items.push({
            type: 'ellipsis',
            key: 'ellipsis-start',
        });
    }

    for (let page = start; page <= end; page += 1) {
        pushPage(page);
    }

    if (end < last - 1) {
        items.push({
            type: 'ellipsis',
            key: 'ellipsis-end',
        });
    }

    if (last > 1) {
        pushPage(last);
    }

    return items;
});

function changePage(page) {
    if (!props.meta || page < 1 || page > props.meta.last_page || page === props.meta.current_page) {
        return;
    }

    emit('change', page);
}
</script>
