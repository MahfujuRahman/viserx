import { reactive } from 'vue';

const ACCESS_TOKEN_KEY = 'access_token';
const REFRESH_TOKEN_KEY = 'refresh_token';
const LEGACY_ACCESS_TOKEN_KEY = 'token';
const USER_KEY = 'user';

function readStoredValue(key, fallbackKey = null) {
    if (typeof window === 'undefined') {
        return null;
    }

    return localStorage.getItem(key) || (fallbackKey ? localStorage.getItem(fallbackKey) : null);
}

function readStoredUser() {
    if (typeof window === 'undefined') {
        return null;
    }

    try {
        return JSON.parse(localStorage.getItem(USER_KEY) || 'null');
    } catch {
        return null;
    }
}

function persistSession() {
    if (typeof window === 'undefined') {
        return;
    }

    if (authState.token) {
        localStorage.setItem(ACCESS_TOKEN_KEY, authState.token);
        localStorage.setItem(LEGACY_ACCESS_TOKEN_KEY, authState.token);
    } else {
        localStorage.removeItem(ACCESS_TOKEN_KEY);
        localStorage.removeItem(LEGACY_ACCESS_TOKEN_KEY);
    }

    if (authState.refreshToken) {
        localStorage.setItem(REFRESH_TOKEN_KEY, authState.refreshToken);
    } else {
        localStorage.removeItem(REFRESH_TOKEN_KEY);
    }

    if (authState.user) {
        localStorage.setItem(USER_KEY, JSON.stringify(authState.user));
    } else {
        localStorage.removeItem(USER_KEY);
    }
}

export const authState = reactive({
    token: readStoredValue(ACCESS_TOKEN_KEY, LEGACY_ACCESS_TOKEN_KEY),
    refreshToken: readStoredValue(REFRESH_TOKEN_KEY),
    user: readStoredUser(),
});

export function setSession({ token, refreshToken, user }) {
    authState.token = token || null;
    authState.refreshToken = refreshToken || null;
    authState.user = user ?? null;
    persistSession();
}

export function updateTokens({ token, refreshToken }) {
    if (typeof token !== 'undefined') {
        authState.token = token || null;
    }

    if (typeof refreshToken !== 'undefined') {
        authState.refreshToken = refreshToken || null;
    }

    persistSession();
}

export function clearSession() {
    authState.token = null;
    authState.refreshToken = null;
    authState.user = null;
    persistSession();
}
