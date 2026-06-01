<template>
  <section class="p-6">
    <div class="panel mx-auto max-w-3xl">
      <h2 class="panel-title">RIS QR Verification</h2>
      <div v-if="ris" class="space-y-3">
        <p><strong>RIS No:</strong> {{ ris.ris_no }}</p>
        <p><strong>Office:</strong> {{ ris.office }}</p>
        <p><strong>Status:</strong> {{ ris.status }}</p>
        <p><strong>Purpose:</strong> {{ ris.purpose }}</p>
        <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3 text-emerald-800">Verified record from system database.</div>
      </div>
      <p v-else class="text-slate-500">Checking verification token...</p>
    </div>
  </section>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { api } from '../stores/api';

const route = useRoute();
const ris = ref(null);

onMounted(async () => {
  const { data } = await api.get(`/verify-ris/${route.params.token}`);
  ris.value = data.data || data;
});
</script>
