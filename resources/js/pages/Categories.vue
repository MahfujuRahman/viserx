<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Categories</h2>
                <p class="mt-1 text-sm text-slate-500">Organize your products by category</p>
            </div>
            <router-link
                to="/categories/create"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Category
            </router-link>
        </div>

        <div v-if="loading" class="flex justify-center py-20">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
        </div>

        <div v-else-if="error" class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-lg border border-red-200">
            {{ error }}
        </div>

        <div v-else-if="categories.length === 0" class="text-center py-20 bg-white rounded-xl border border-slate-200 shadow-sm">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-slate-900">No categories</h3>
            <p class="mt-1 text-sm text-slate-500">Create your first category to get started.</p>
            <router-link
                to="/categories/create"
                class="mt-6 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Category
            </router-link>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="category in categories"
                :key="category.id"
                class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition-shadow"
            >
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-slate-900 truncate">{{ category.name }}</h3>
                        <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ category.description || 'No description' }}</p>
                        <div class="mt-3 flex items-center text-sm text-slate-500">
                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            {{ category.products_count || 0 }} product(s)
                        </div>
                    </div>
                    <div class="flex items-center space-x-1 ml-4">
                        <router-link
                            :to="`/categories/${category.id}/edit`"
                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                            title="Edit"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </router-link>
                        <button
                            @click="deleteCategory(category.id)"
                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Delete"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <Pagination :meta="pagination" @change="handlePageChange" />
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Pagination from '../components/Pagination.vue';
import { api } from '../services/api';

const route = useRoute();
const router = useRouter();
const categories = ref([]);
const pagination = ref(null);
const loading = ref(true);
const error = ref('');
const currentPage = computed(() => {
    const page = Number(route.query.page || 1);
    return Number.isInteger(page) && page > 0 ? page : 1;
});

async function fetchCategories() {
    loading.value = true;
    error.value = '';
    try {
        const response = await api.get('categories', {
            params: {
                page: currentPage.value,
            },
        });

        if (!response.data.data.length && currentPage.value > 1 && response.data.meta?.last_page < currentPage.value) {
            handlePageChange(response.data.meta?.last_page || 1);
            return;
        }

        categories.value = response.data.data;
        pagination.value = response.data.meta;
    } catch (e) {
        console.error('Failed to load categories', e);
        error.value = e?.message || e?.response?.data?.message || 'Failed to load categories';
    } finally {
        loading.value = false;
    }
}

function handlePageChange(page) {
    const query = { ...route.query };

    if (page <= 1) {
        delete query.page;
    } else {
        query.page = String(page);
    }

    router.push({ query });
}

async function deleteCategory(id) {
    if (!confirm('Are you sure you want to delete this category?')) return;
    try {
        await api.delete(`categories/${id}`);
        await fetchCategories();
    } catch (e) {
        alert('Failed to delete category');
    }
}

watch(
    () => currentPage.value,
    () => {
        fetchCategories();
    },
    { immediate: true }
);
</script>
