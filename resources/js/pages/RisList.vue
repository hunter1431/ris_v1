<template>
  <section class="p-6">
    <div class="panel">
      <h2 class="panel-title">RIS List</h2>
      <button class="btn mb-4" @click="store.loadRis()">Refresh</button>
      <div class="space-y-2">
        <div v-for="ris in store.records" :key="ris.id" class="grid items-center gap-3 rounded border border-slate-200 p-3 md:grid-cols-6">
          <strong>{{ ris.ris_no }}</strong>
          <span>{{ ris.office }}</span>
          <span>{{ ris.status }}</span>
          <span>Approval Level {{ ris.current_approval_level }}</span>
          <a class="btn" :href="`/api/ris/${ris.id}/pdf`">Print RIS</a>
          <a class="btn" :href="ris.verification_url">Verify QR</a>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRisStore } from '../stores/ris';

const store = useRisStore();
onMounted(() => store.loadRis());
</script>
