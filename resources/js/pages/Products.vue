<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="sm:flex sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Products</h2>
                <p class="mt-1 text-sm text-slate-500">Manage your product inventory</p>
            </div>
            <router-link
                to="/products/create"
                class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Product
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

        <div v-else-if="products.length === 0" class="text-center py-20 bg-white rounded-xl border border-slate-200 shadow-sm">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-slate-900">No products</h3>
            <p class="mt-1 text-sm text-slate-500">Get started by creating your first product.</p>
            <router-link
                to="/products/create"
                class="mt-6 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
            >
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Product
            </router-link>
        </div>

        <div v-else class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-for="product in products" :key="product.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-slate-900">{{ product.name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-500 max-w-xs truncate">{{ product.description || '-' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-slate-900">${{ parseFloat(product.price).toFixed(2) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ product.category?.name || 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <router-link
                                    :to="`/products/${product.id}/edit`"
                                    class="inline-flex items-center px-3 py-1.5 text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-md transition-colors mr-2"
                                >
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </router-link>
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="inline-flex items-center px-3 py-1.5 text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-md transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :meta="pagination" @change="handlePageChange" />
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import Pagination from '../components/Pagination.vue';
import { api } from '../services/api';

const route = useRoute();
const router = useRouter();
const products = ref([]);
const pagination = ref(null);
const loading = ref(true);
const error = ref('');
const currentPage = computed(() => {
    const page = Number(route.query.page || 1);
    return Number.isInteger(page) && page > 0 ? page : 1;
});

async function fetchProducts() {
    loading.value = true;
    error.value = '';
    try {
        const response = await api.get('products', {
            params: {
                page: currentPage.value,
            },
        });

        if (!response.data.data.length && currentPage.value > 1 && response.data.meta?.last_page < currentPage.value) {
            handlePageChange(response.data.meta?.last_page || 1);
            return;
        }

        products.value = response.data.data;
        pagination.value = response.data.meta;
    } catch (e) {
        console.error('Failed to load products', e);
        error.value = e?.message || e?.response?.data?.message || 'Failed to load products';
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

async function deleteProduct(id) {
    const result = await Swal.fire({
        title: 'Delete product?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626',
    });

    if (!result.isConfirmed) return;

    try {
        await api.delete(`products/${id}`);
        await fetchProducts();
        await Swal.fire({
            title: 'Deleted!',
            text: 'The product has been deleted.',
            icon: 'success',
            confirmButtonColor: '#4f46e5',
        });
    } catch (e) {
        await Swal.fire({
            title: 'Delete failed',
            text: e?.response?.data?.message || 'Failed to delete product',
            icon: 'error',
            confirmButtonColor: '#4f46e5',
        });
    }
}

watch(
    () => currentPage.value,
    () => {
        fetchProducts();
    },
    { immediate: true }
);
</script>
