import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Login from '../components/Login.vue'
import Dashboard from '../components/Dashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', component: Login },
    { 
      path: '/dashboard', 
      component: Dashboard,
      meta: { requiresAuth: true }
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    await authStore.fetchUser();
    if (!authStore.isAuthenticated) return next('/');
  }
  next();
});

export default router
