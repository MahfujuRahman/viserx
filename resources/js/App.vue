<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
        <Navbar v-if="authStore.isAuthenticated" />
        <main>
            <router-view v-slot="{ Component, route }">
                <component :is="Component" :key="route.fullPath" />
            </router-view>
        </main>
    </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
import { useAuthStore } from './stores/auth';

const authStore = useAuthStore();
</script>
