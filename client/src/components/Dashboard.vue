<script setup>
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  await authStore.logout();
  router.push('/');
};
</script>

<template>
  <div class="p-8">
    <h1 class="text-3xl font-bold text-blue-900">Bienvenido, {{ authStore.user?.person?.full_name }}</h1>
    <p class="text-slate-600 mt-2">Rol: <span class="uppercase font-semibold">{{ authStore.user?.role }}</span></p>
    
    <button @click="handleLogout" class="mt-6 bg-red-500 text-white px-4 py-2 rounded-lg">
      Cerrar Sesión
    </button>
  </div>
</template>
