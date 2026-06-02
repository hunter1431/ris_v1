<template>
  <section class="space-y-6">
    <!-- Page Header -->
    <div>
      <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
      <p class="text-gray-600 mt-1">Welcome back! Here's your RIS overview.</p>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
      <Metric label="Total RIS" :value="cards.total_ris || 0" icon="pi pi-file-text" />
      <Metric label="Pending RIS" :value="cards.pending_ris || 0" icon="pi pi-hourglass" />
      <Metric label="Approved RIS" :value="cards.approved_ris || 0" icon="pi pi-check-circle" />
      <Metric label="Issued RIS" :value="cards.issued_ris || 0" icon="pi pi-truck" />
    </div>

    <!-- Charts Section -->
    <div class="grid gap-6 lg:grid-cols-2">
      <!-- Monthly Requests Chart -->
      <div class="panel">
        <div class="mb-4">
          <h3 class="panel-title">Monthly Requests Trend</h3>
          <p class="text-sm text-gray-600">RIS submissions over the last 12 months</p>
        </div>
        <div class="h-80">
          <Line :data="monthlyData" :options="chartOptions" />
        </div>
      </div>

      <!-- Most Requested Items Chart -->
      <div class="panel">
        <div class="mb-4">
          <h3 class="panel-title">Most Requested Items</h3>
          <p class="text-sm text-gray-600">Top items by request frequency</p>
        </div>
        <div class="h-80">
          <Bar :data="itemsData" :options="chartOptions" />
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="panel">
      <h3 class="panel-title">Quick Actions</h3>
      <div class="grid gap-3 sm:grid-cols-3">
        <RouterLink to="/ris/create" class="btn w-full gap-2">
          <i class="pi pi-plus"></i>
          <span>Create New RIS</span>
        </RouterLink>
        <RouterLink to="/ris" class="btn w-full gap-2">
          <i class="pi pi-list"></i>
          <span>View All RIS</span>
        </RouterLink>
        <RouterLink to="/reports" class="btn w-full gap-2">
          <i class="pi pi-chart-bar"></i>
          <span>View Reports</span>
        </RouterLink>
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

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      labels: {
        font: { size: 12, family: "'Segoe UI', system-ui" },
        color: '#6b7280',
        padding: 16,
      },
    },
  },
  scales: {
    y: {
      grid: { color: '#f3f4f6' },
      ticks: { color: '#9ca3af', font: { size: 12 } },
    },
    x: {
      grid: { display: false },
      ticks: { color: '#9ca3af', font: { size: 12 } },
    },
  },
};

const monthlyData = computed(() => ({
  labels: monthly.value.map((row) => row.month),
  datasets: [
    {
      label: 'RIS Requests',
      data: monthly.value.map((row) => row.total),
      borderColor: '#0284c7',
      backgroundColor: 'rgba(2, 132, 199, 0.1)',
      borderWidth: 3,
      tension: 0.4,
      fill: true,
      pointBackgroundColor: '#0284c7',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointRadius: 5,
    },
  ],
}));

const itemsData = computed(() => ({
  labels: items.value.map((row) => row.description).slice(0, 6),
  datasets: [
    {
      label: 'Request Count',
      data: items.value.map((row) => row.total).slice(0, 6),
      backgroundColor: [
        '#0284c7',
        '#16a34a',
        '#ea580c',
        '#7c3aed',
        '#db2777',
        '#0891b2',
      ],
      borderRadius: 8,
      borderSkipped: false,
    },
  ],
}));

onMounted(async () => {
  try {
    const { data } = await api.get('/dashboard');
    cards.value = data.cards;
    monthly.value = data.monthly_requests;
    items.value = data.most_requested_items;
  } catch (error) {
    console.error('Failed to load dashboard:', error);
  }
});
</script>
