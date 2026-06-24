<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
  email: '',
  password: '',
});
const error = ref('');

const handleLogin = async () => {
  error.value = '';
  try {
    await authStore.login(form.value);
    router.push('/dashboard');
  } catch (e) {
    error.value = 'Credenciales inválidas o error de conexión.';
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-100 p-4">
    <div class="w-full max-w-sm bg-white p-8 rounded-2xl shadow-lg border border-slate-200">
      <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Iniciar Sesión</h2>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-slate-700">Correo</label>
          <input v-model="form.email" type="email" required 
            class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-slate-700">Contraseña</label>
          <input v-model="form.password" type="password" required 
            class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

        <button type="submit" :disabled="authStore.loading"
          class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
          {{ authStore.loading ? 'Entrando...' : 'Entrar' }}
        </button>
      </form>
    </div>
  </div>
</template>
