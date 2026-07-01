<template>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <router-link to="/categories" class="text-sm text-indigo-600 hover:text-indigo-500 flex items-center mb-4">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Categories
            </router-link>
            <h2 class="text-2xl font-bold text-slate-900">{{ isEdit ? 'Edit Category' : 'Create Category' }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ isEdit ? 'Update the category details' : 'Add a new category to organize your products' }}</p>
        </div>

        <form @submit.prevent="handleSubmit" class="bg-white rounded-xl border border-slate-200 shadow-sm p-8 space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Category Name</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="Enter category name"
                        class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    />
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        placeholder="Enter category description"
                        class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                    ></textarea>
                </div>
            </div>

            <div v-if="error" class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-lg border border-red-200">
                {{ error }}
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <router-link
                    to="/categories"
                    class="px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors"
                >
                    Cancel
                </router-link>
                <button
                    type="submit"
                    :disabled="loading"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                    </svg>
                    {{ loading ? 'Saving...' : isEdit ? 'Update Category' : 'Create Category' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { api } from '../services/api';

const route = useRoute();
const router = useRouter();
const isEdit = computed(() => !!route.params.id);

const form = ref({ name: '', description: '' });
const loading = ref(false);
const error = ref('');

async function fetchCategory() {
    if (!isEdit.value) return;
    try {
        const response = await api.get(`categories/${route.params.id}`);
        const category = response.data.data;
        form.value = {
            name: category.name,
            description: category.description || '',
        };
    } catch (e) {
        error.value = 'Failed to load category';
    }
}

async function handleSubmit() {
    error.value = '';
    loading.value = true;
    try {
        if (isEdit.value) {
            await api.put(`categories/${route.params.id}`, form.value);
        } else {
            await api.post('categories', form.value);
        }
        router.push('/categories');
    } catch (e) {
        const data = e.response?.data;
        error.value = data?.errors
            ? Object.values(data.errors).flat().join(', ')
            : data?.message || 'Failed to save category';
    } finally {
        loading.value = false;
    }
}

onMounted(fetchCategory);
</script>
