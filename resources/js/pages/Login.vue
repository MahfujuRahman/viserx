<template>
    <div class="min-h-screen flex">
        <div class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full">
                <div class="text-center mb-10">
                    <div class="mx-auto w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900">Welcome back</h2>
                    <p class="mt-2 text-sm text-slate-500">Sign in to your account to continue</p>
                </div>

                <form @submit.prevent="handleLogin" class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            required
                            placeholder="you@example.com"
                            class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input
                            id="password"
                            v-model="password"
                            type="password"
                            required
                            placeholder="Enter your password"
                            class="block w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        />
                    </div>

                    <div v-if="error" class="bg-red-50 text-red-600 text-sm px-4 py-3 rounded-lg border border-red-200">
                        {{ error }}
                    </div>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                        </svg>
                        {{ loading ? 'Signing in...' : 'Sign in' }}
                    </button>

                    <p class="text-center text-sm text-slate-500">
                        Don't have an account?
                        <router-link to="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Create one</router-link>
                    </p>
                </form>
            </div>
        </div>
        <div class="hidden lg:flex flex-1 bg-gradient-to-br from-indigo-600 to-indigo-800 items-center justify-center">
            <div class="max-w-md text-center px-8">
                <svg class="w-20 h-20 text-white/80 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="text-2xl font-bold text-white mb-3">Product Manager</h3>
                <p class="text-indigo-200 text-sm leading-relaxed">
                    Manage your products and categories with ease. A professional CRUD application built with Laravel and Vue 3.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const email = ref('demo@example.com');
const password = ref('password');
const error = ref('');
const loading = ref(false);
const authStore = useAuthStore();
const router = useRouter();

async function handleLogin() {
    error.value = '';
    loading.value = true;
    try {
        await authStore.login(email.value, password.value);
        router.push('/products');
    } catch (e) {
        error.value = e.response?.data?.error || e.response?.data?.message || 'Invalid email or password';
    } finally {
        loading.value = false;
    }
}
</script>
