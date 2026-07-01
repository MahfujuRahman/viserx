import { defineStore } from 'pinia';
import { computed, toRefs } from 'vue';
import { api } from '../services/api';
import { authState, clearSession, setSession } from '../services/session';

export const useAuthStore = defineStore('auth', () => {
    const { token, refreshToken, user } = toRefs(authState);

    const isAuthenticated = computed(() => !!token.value);

    async function login(email, password) {
        const response = await api.post('auth/login', { email, password });
        setSession({
            token: response.data.access_token,
            refreshToken: response.data.refresh_token,
            user: response.data.user || null,
        });
        return response.data;
    }

    async function register(name, email, password, password_confirmation) {
        const response = await api.post('auth/register', { name, email, password, password_confirmation });
        setSession({
            token: response.data.access_token,
            refreshToken: response.data.refresh_token,
            user: response.data.user || null,
        });
        return response.data;
    }

    async function logout() {
        try {
            await api.post('auth/logout');
        } catch (e) {
            // ignore
        }
        clearSession();
    }

    return {
        user,
        token,
        refreshToken,
        isAuthenticated,
        api,
        login,
        register,
        logout,
        clearSession,
    };
});
