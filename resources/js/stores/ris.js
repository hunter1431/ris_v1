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
    async loadItems(params = {}) {
      this.loading = true;
      const { data } = await api.get('/inventory', { params });
      this.inventory = data.data || data;
      this.loading = false;
    },
    async searchItems(search) {
      await this.loadItems({ search });
    },
    async createItem(payload) {
      const { data } = await api.post('/inventory', payload);
      await this.loadItems();
      return data;
    },
    async updateItem(id, payload) {
      const { data } = await api.put(`/inventory/${id}`, payload);
      await this.loadItems();
      return data;
    },
    async deleteItem(id) {
      await api.delete(`/inventory/${id}`);
      await this.loadItems();
    },
    async createRis(payload) {
      const { data } = await api.post('/ris', payload);
      await this.loadRis();
      return data;
    },
  },
});
