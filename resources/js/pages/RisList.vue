<template>
  <section class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">RIS Requests</h2>
        <p class="text-gray-600 mt-1">Manage and track all your requisition requests.</p>
      </div>
      <div class="flex gap-2">
        <button @click="store.loadRis()" class="btn gap-2">
          <i class="pi pi-refresh"></i>
          <span class="hidden sm:inline">Refresh</span>
        </button>
        <RouterLink to="/ris/create" class="btn-primary gap-2">
          <i class="pi pi-plus"></i>
          <span class="hidden sm:inline">New RIS</span>
        </RouterLink>
      </div>
    </div>

    <!-- RIS Table -->
    <div class="panel overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 border-b-2 border-gray-200">
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">RIS No.</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide hidden sm:table-cell">Office</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Status</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide hidden md:table-cell">Level</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="ris in store.records" :key="ris.id" class="hover:bg-gray-50 transition-colors duration-200">
              <!-- RIS Number -->
              <td class="px-4 py-4">
                <div class="flex flex-col gap-1">
                  <strong class="text-gray-900">{{ ris.ris_no }}</strong>
                  <span class="text-xs text-gray-500 sm:hidden">{{ ris.office }}</span>
                </div>
              </td>

              <!-- Office -->
              <td class="px-4 py-4 text-gray-600 hidden sm:table-cell">{{ ris.office }}</td>

              <!-- Status -->
              <td class="px-4 py-4">
                <span :class="['badge', getStatusBadgeClass(ris.status)]">
                  <i :class="getStatusIcon(ris.status)"></i>
                  {{ ris.status }}
                </span>
              </td>

              <!-- Approval Level -->
              <td class="px-4 py-4 text-gray-600 hidden md:table-cell">
                <div class="flex items-center gap-1">
                  <i class="pi pi-level-up text-blue-600"></i>
                  Level {{ ris.current_approval_level }}
                </div>
              </td>

              <!-- Actions -->
              <td class="px-4 py-4">
                <div class="flex gap-2 justify-center flex-wrap">
                  <button type="button" @click="downloadRisPdf(ris.id)" class="btn p-2" title="Download PDF">
                    <i class="pi pi-download"></i>
                  </button>
                  <a :href="ris.verification_url" target="_blank" class="btn p-2" title="Verify QR">
                    <i class="pi pi-qrcode"></i>
                  </a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="!store.records || store.records.length === 0" class="flex flex-col items-center justify-center py-12">
        <i class="pi pi-inbox text-5xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg font-medium">No RIS requests found</p>
        <p class="text-gray-400 text-sm">Create a new RIS request to get started</p>
        <RouterLink to="/ris/create" class="btn-primary gap-2 mt-4">
          <i class="pi pi-plus"></i>
          Create RIS
        </RouterLink>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRisStore } from '../stores/ris';
import { api } from '../stores/api';

const store = useRisStore();

const statusIcons = {
  draft: 'pi-file',
  pending: 'pi-hourglass',
  approved: 'pi-check-circle',
  issued: 'pi-truck',
  completed: 'pi-check',
  cancelled: 'pi-times-circle',
};

const statusBadges = {
  draft: 'badge-warning',
  pending: 'badge-warning',
  approved: 'badge-success',
  issued: 'badge-success',
  completed: 'badge-success',
  cancelled: 'badge-danger',
};

function getStatusIcon(status) {
  return `pi ${statusIcons[status] || 'pi-file'}`;
}

function getStatusBadgeClass(status) {
  return statusBadges[status] || 'badge-primary';
}

onMounted(() => store.loadRis());

async function downloadRisPdf(risId) {
  const response = await api.get(`/ris/${risId}/pdf`, { responseType: 'blob' });
  const blob = new Blob([response.data], { type: response.headers['content-type'] });
  const objectUrl = URL.createObjectURL(blob);
  const link = document.createElement('a');

  link.href = objectUrl;
  link.download = `ris-${risId}.pdf`;
  document.body.appendChild(link);
  link.click();
  link.remove();
  URL.revokeObjectURL(objectUrl);
}
</script>
