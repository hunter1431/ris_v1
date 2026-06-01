import { defineStore } from 'pinia';
import { api } from './api';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('ris_token'),
  }),
  actions: {
    async login(credentials) {
      const { data } = await api.post('/login', credentials);
      this.user = data.user;
      this.token = data.token;
      localStorage.setItem('ris_token', data.token);
    },
    async loadMe() {
      if (!this.token) return;
      const { data } = await api.get('/me');
      this.user = data;
    },
    async logout() {
      await api.post('/logout');
      this.user = null;
      this.token = null;
      localStorage.removeItem('ris_token');
    },
  },
});
