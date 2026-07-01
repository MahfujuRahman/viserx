import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
    {
        path: '/',
        redirect: '/products',
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../pages/Login.vue'),
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../pages/Register.vue'),
    },
    {
        path: '/products',
        name: 'Products',
        component: () => import('../pages/Products.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/products/create',
        name: 'ProductCreate',
        component: () => import('../pages/ProductForm.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/products/:id/edit',
        name: 'ProductEdit',
        component: () => import('../pages/ProductForm.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/categories',
        name: 'Categories',
        component: () => import('../pages/Categories.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/categories/create',
        name: 'CategoryCreate',
        component: () => import('../pages/CategoryForm.vue'),
        meta: { requiresAuth: true },
    },
    {
        path: '/categories/:id/edit',
        name: 'CategoryEdit',
        component: () => import('../pages/CategoryForm.vue'),
        meta: { requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (typeof window === 'undefined') {
        next();
        return;
    }

    const authStore = useAuthStore();

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next('/login');
        return;
    }

    if ((to.name === 'Login' || to.name === 'Register') && authStore.isAuthenticated) {
        next('/products');
        return;
    } else {
        next();
    }
});

export default router;
