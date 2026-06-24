import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    loading: false,
  }),
  getters: {
    isAuthenticated: (state) => !!state.user,
    userRole: (state) => state.user?.role,
  },
  actions: {
    async login(credentials) {
      this.loading = true;
      try {
        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.post('/api/login', credentials);
        this.user = response.data.user;
      } finally {
        this.loading = false;
      }
    },
    async fetchUser() {
      try {
        const response = await axios.get('/api/user');
        this.user = response.data;
      } catch {
        this.user = null;
      }
    },
    async logout() {
      await axios.post('/api/logout');
      this.user = null;
    }
  }
});
