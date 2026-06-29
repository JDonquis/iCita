import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import PersonnelList from '../components/PersonnelList.vue'
import Login from '../components/Login.vue'
import ForgotPassword from '../components/ForgotPassword.vue'
import ResetPassword from '../components/ResetPassword.vue'
import Dashboard from '../components/Dashboard.vue'
import Dashboard from '../Pages/Dashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', component: Login },
    { path: '/forgot-password', component: ForgotPassword },
    { path: '/reset-password', component: ResetPassword },
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
    if (!authStore.isAuthenticated) {
      return next('/');
    }
  }
  next();
});

export default router
