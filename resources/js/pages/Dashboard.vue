<template>
  <section class="space-y-6 p-6">
    <div class="grid gap-4 md:grid-cols-4">
      <Metric label="Total RIS" value="0" />
      <Metric label="Pending RIS" value="0" />
      <Metric label="Approved RIS" value="0" />
      <Metric label="Issued RIS" value="0" />
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
import { Bar, Line } from 'vue-chartjs';
import { BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, LineElement, PointElement, Tooltip } from 'chart.js';
import Metric from './Metric.vue';

ChartJS.register(CategoryScale, LinearScale, BarElement, LineElement, PointElement, Tooltip, Legend);

const monthlyData = {
  labels: ['Jan', 'Feb', 'Mar'],
  datasets: [{ label: 'Requests', data: [120, 98, 150], borderColor: '#2563eb', backgroundColor: '#2563eb' }],
};

const itemsData = {
  labels: ['Bond Paper', 'Ballpen', 'Folders'],
  datasets: [{ label: 'Requested', data: [300, 210, 160], backgroundColor: '#0f766e' }],
};
</script>
