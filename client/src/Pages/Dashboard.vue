<script setup>
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';
import AppLayout from '../Layouts/AppLayout.vue';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  await authStore.logout();
  router.push('/');
};
</script>

<template>
  <AppLayout titulo="Dashboard" subtitulo="Bienvenido al dashboard">
    <div class="glass-card  p-lg rounded-xl flex flex-col md:flex-row items-center justify-between shadow-[0px_4px_20px_rgba(0,102,136,0.05)] gap-4">
    
      <div class="px-4">
    
        <br/>
        <div class="text-center md:text-left">
          <h2 class="text-headline-md font-bold text-primary">Bienvenido, {{ authStore.user?.person?.full_name || 'Usuario' }}</h2>
          <p class="text-body-sm text-on-surface-variant mt-1">Rol: <span class="uppercase font-semibold text-secondary">{{ authStore.user?.role || 'Admin' }}</span></p>
        </div>
        <br/>
    
        <button @click="handleLogout" class="bg-error text-on-error px-[16px] py-[8px] rounded-lg font-button hover:bg-error/90 transition-all flex items-center gap-2 shadow-sm active:scale-95">
          <span class="material-symbols-outlined">logout</span>
          Cerrar Sesión
        </button>
    
        <br/>
        <br/>  
    
      </div>
    
    </div>
    
  </AppLayout>
</template>

<style scoped>
.glass-card {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(0, 102, 136, 0.15);
  transition: all 0.3s ease;
}

.glass-card:hover {
  transform: translateY(-2px);
  box-shadow: 0px 10px 30px rgba(0, 102, 136, 0.15);
}
</style>
