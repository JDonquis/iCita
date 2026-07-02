import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import Login from '../components/Login.vue'
import ForgotPassword from '../components/ForgotPassword.vue'
import ResetPassword from '../components/ResetPassword.vue'
import Dashboard from '../Pages/Dashboard.vue'
import PersonnelIndex from '../Pages/personnel/index.vue'
import createPersonnel from '../Pages/personnel/create.vue'
import editPersonnel from '../Pages/personnel/edit.vue'

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
    {
      path: '/personnel',
      component: PersonnelIndex,
      meta: { requiresAuth: true }
    },
    {
      path: '/personnel/create',
      component: createPersonnel,
      meta: { requiresAuth: true }
    },
    {
      path: '/personnel/edit/:id',
      component: editPersonnel,
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
