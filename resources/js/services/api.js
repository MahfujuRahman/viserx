import axios from 'axios';
import { authState, clearSession, updateTokens } from './session';

function getDefaultApiBaseURL() {
    if (typeof window === 'undefined') {
        return '/api';
    }

    const devHosts = ['localhost', '127.0.0.1', '::1'];

    if (window.location.port === '5173' && devHosts.includes(window.location.hostname)) {
        return 'http://127.0.0.1:8000/api';
    }

    return `${window.location.origin}/api`;
}

const baseURL = import.meta.env.VITE_API_BASE_URL || getDefaultApiBaseURL();

export const api = axios.create({
    baseURL,
    headers: { 'Content-Type': 'application/json' },
});

const authApi = axios.create({
    baseURL,
    headers: { 'Content-Type': 'application/json' },
});

let refreshPromise = null;

function redirectToLogin() {
    if (typeof window !== 'undefined' && window.location.pathname !== '/login') {
        window.location.replace('/login');
    }
}

async function refreshAccessToken() {
    if (!authState.refreshToken) {
        throw new Error('Missing refresh token');
    }

    if (!refreshPromise) {
        refreshPromise = authApi
            .post('auth/refresh', {}, {
                headers: {
                    Authorization: `Bearer ${authState.refreshToken}`,
                },
            })
            .then(({ data }) => {
                updateTokens({
                    token: data.access_token,
                    refreshToken: data.refresh_token,
                });

                return data.access_token;
            })
            .finally(() => {
                refreshPromise = null;
            });
    }

    return refreshPromise;
}

api.interceptors.request.use((config) => {
    if (authState.token) {
        config.headers = config.headers || {};
        config.headers.Authorization = `Bearer ${authState.token}`;
    }

    return config;
});

api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const status = error.response?.status;
        const originalRequest = error.config;

        if (status !== 401 || !originalRequest) {
            return Promise.reject(error);
        }

        const requestUrl = String(originalRequest.url || '');
        const shouldSkipRefresh =
            originalRequest._retry ||
            requestUrl.includes('auth/login') ||
            requestUrl.includes('auth/register') ||
            requestUrl.includes('auth/refresh') ||
            requestUrl.includes('auth/logout');

        if (shouldSkipRefresh) {
            return Promise.reject(error);
        }

        if (!authState.refreshToken) {
            clearSession();
            redirectToLogin();
            return Promise.reject(error);
        }

        try {
            originalRequest._retry = true;
            const accessToken = await refreshAccessToken();

            originalRequest.headers = originalRequest.headers || {};
            originalRequest.headers.Authorization = `Bearer ${accessToken}`;

            return api(originalRequest);
        } catch (refreshError) {
            clearSession();
            redirectToLogin();
            return Promise.reject(refreshError);
        }
    }
);
