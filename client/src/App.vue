<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from './components/Navbar.vue';
import { RouterView } from 'vue-router';

const route = useRoute();
const showNavbar = computed(() => route.meta.requiresAuth);
</script>

<template>
  <div class="min-h-screen bg-slate-50">
    <Navbar v-if="showNavbar" />
    <main :class="showNavbar ? 'max-w-7xl mx-auto py-6 sm:px-6 lg:px-8' : ''">
      <RouterView />
    </main>
  </div>
</template>
