<script setup>
import { ref } from 'vue';
import { RouterLink } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();
const isOpen = ref(false);
</script>

<template>
  <nav class="bg-white shadow-sm border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <span class="text-xl font-bold text-blue-900">iCita</span>
        </div>
        
        <!-- Mobile menu button -->
        <div class="flex items-center sm:hidden">
          <button @click="isOpen = !isOpen" class="text-slate-600 hover:text-blue-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>

        <!-- Desktop menu -->
        <div class="hidden sm:flex sm:items-center sm:space-x-4">
          <RouterLink to="/dashboard" class="text-slate-600 hover:text-blue-600">Inicio</RouterLink>
          <RouterLink to="/personnel" class="text-slate-600 hover:text-blue-600">Personal</RouterLink>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-if="isOpen" class="sm:hidden bg-white border-b border-slate-200">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <RouterLink to="/dashboard" class="block px-3 py-2 text-slate-600 hover:bg-slate-50">Inicio</RouterLink>
        <RouterLink to="/personnel" class="block px-3 py-2 text-slate-600 hover:bg-slate-50">Personal</RouterLink>
      </div>
    </div>
  </nav>
</template>
