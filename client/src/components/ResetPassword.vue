<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();

const form = ref({
  email: route.query.email || '',
  token: route.query.token || '',
  password: '',
  password_confirmation: ''
});

const error = ref('');
const loading = ref(false);

const handleReset = async () => {
  loading.value = true;
  error.value = '';
  try {
    await axios.post('/api/reset-password', form.value);
    alert('Contraseña configurada exitosamente. Ahora puedes iniciar sesión.');
    router.push('/');
  } catch (e) {
    if (e.response && e.response.status === 422) {
      error.value = 'El enlace ha expirado o es inválido. Por favor, solicita uno nuevo.';
    } else {
      error.value = 'Ocurrió un error inesperado.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-100 p-4">
    <div class="w-full max-w-sm bg-white p-8 rounded-2xl shadow-lg border border-slate-200">
      <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Configurar Contraseña</h2>
      
      <form @submit.prevent="handleReset" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-slate-700">Nueva Contraseña</label>
          <input v-model="form.password" type="password" required 
            class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-blue-500" />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-slate-700">Confirmar Contraseña</label>
          <input v-model="form.password_confirmation" type="password" required 
            class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-blue-500" />
        </div>

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

        <button type="submit" :disabled="loading"
          class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
          {{ loading ? 'Guardando...' : 'Guardar Contraseña' }}
        </button>
      </form>
    </div>
  </div>
</template>
