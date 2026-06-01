import { defineStore } from 'pinia';
import { api } from './api';

export const useRisStore = defineStore('ris', {
  state: () => ({
    records: [],
    inventory: [],
    loading: false,
  }),
  actions: {
    async loadRis(params = {}) {
      this.loading = true;
      const { data } = await api.get('/ris', { params });
      this.records = data.data || data;
      this.loading = false;
    },
    async searchItems(search) {
      const { data } = await api.get('/inventory', { params: { search } });
      this.inventory = data.data || data;
    },
    async createRis(payload) {
      const { data } = await api.post('/ris', payload);
      await this.loadRis();
      return data;
    },
  },
});
