<template>
  <section class="space-y-6 p-6">
    <div class="grid gap-4 md:grid-cols-4">
      <Metric label="Total RIS" :value="cards.total_ris || 0" />
      <Metric label="Pending RIS" :value="cards.pending_ris || 0" />
      <Metric label="Approved RIS" :value="cards.approved_ris || 0" />
      <Metric label="Issued RIS" :value="cards.issued_ris || 0" />
    </div>
    <div class="grid gap-4 lg:grid-cols-2">
      <div class="panel h-80">
        <h2 class="panel-title">Monthly Requests</h2>
        <Line :data="monthlyData" :options="{ responsive: true, maintainAspectRatio: false }" />
      </div>
      <div class="panel h-80">
        <h2 class="panel-title">Most Requested Items</h2>
        <Bar :data="itemsData" :options="{ responsive: true, maintainAspectRatio: false }" />
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { Bar, Line } from 'vue-chartjs';
import { BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, LineElement, PointElement, Tooltip } from 'chart.js';
import Metric from './Metric.vue';
import { api } from '../stores/api';

ChartJS.register(CategoryScale, LinearScale, BarElement, LineElement, PointElement, Tooltip, Legend);

const cards = ref({});
const monthly = ref([]);
const items = ref([]);

const monthlyData = computed(() => ({
  labels: monthly.value.map((row) => row.month),
  datasets: [{ label: 'Requests', data: monthly.value.map((row) => row.total), borderColor: '#2563eb', backgroundColor: '#2563eb' }],
}));

const itemsData = computed(() => ({
  labels: items.value.map((row) => row.description),
  datasets: [{ label: 'Requested', data: items.value.map((row) => row.total), backgroundColor: '#0f766e' }],
}));

onMounted(async () => {
  const { data } = await api.get('/dashboard');
  cards.value = data.cards;
  monthly.value = data.monthly_requests;
  items.value = data.most_requested_items;
});
</script>
