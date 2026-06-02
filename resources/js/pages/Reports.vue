<template>
  <section class="p-6">
    <div class="panel mx-auto max-w-3xl space-y-4">
      <h2 class="panel-title">Reports</h2>
      <button type="button" @click="downloadReport('/api/reports/ris-summary', 'ris-summary.xlsx')" class="btn">
        Download RIS Summary
      </button>
      <button type="button" @click="downloadReport('/api/reports/inventory', 'inventory-report.xlsx')" class="btn">
        Download Inventory Report
      </button>
    </div>
  </section>
</template>

<script setup>
import { api } from '../stores/api';

async function downloadReport(url, filename) {
  const response = await api.get(url, { responseType: 'blob' });
  const blob = new Blob([response.data], { type: response.headers['content-type'] });
  const objectUrl = URL.createObjectURL(blob);
  const link = document.createElement('a');

  link.href = objectUrl;
  link.download = filename;
  document.body.appendChild(link);
  link.click();
  link.remove();
  URL.revokeObjectURL(objectUrl);
}
</script>
