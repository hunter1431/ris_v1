<template>
  <section class="space-y-5 p-6">
    <div class="panel">
      <h2 class="panel-title">Multi-level Approval Matrix</h2>
      <div class="grid gap-3 md:grid-cols-3">
        <div class="rounded-lg border border-slate-200 p-4">
          <p class="text-sm text-slate-500">Level 1</p>
          <strong>Division Head</strong>
          <p class="mt-2 text-sm text-slate-500">Reviews purpose, division, and requested quantities.</p>
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
          <p class="text-sm text-slate-500">Level 2</p>
          <strong>Supply Officer</strong>
          <p class="mt-2 text-sm text-slate-500">Final approval before issuance and inventory deduction.</p>
        </div>
        <div class="rounded-lg border border-slate-200 p-4">
          <p class="text-sm text-slate-500">Status Flow</p>
          <strong>Draft -> Pending -> Approved -> Issued</strong>
          <p class="mt-2 text-sm text-slate-500">Every action is saved in the approvals table.</p>
        </div>
      </div>
    </div>
    <div class="panel">
      <h2 class="panel-title">Pending RIS</h2>
      <button class="btn mb-4" @click="store.loadRis({ status: 'pending' })">Load Pending</button>
      <div class="space-y-2">
        <div v-for="ris in store.records" :key="ris.id" class="grid items-center gap-3 rounded border border-slate-200 p-3 md:grid-cols-5">
          <strong>{{ ris.ris_no }}</strong>
          <span>{{ ris.office }}</span>
          <span>Level {{ ris.current_approval_level }}</span>
          <span>{{ ris.status }}</span>
          <button class="btn-primary" @click="approve(ris.id)">Approve Next Step</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { useToast } from 'primevue/usetoast';
import { useRisStore } from '../stores/ris';
import { api } from '../stores/api';

const store = useRisStore();
const toast = useToast();

async function approve(id) {
  try {
    await api.post(`/ris/${id}/approve`);
    toast.add({ severity: 'success', summary: 'Approved', detail: 'RIS next approval step completed.' });
    await store.loadRis({ status: 'pending' });
  } catch (exception) {
    toast.add({ severity: 'error', summary: 'Error', detail: exception.response?.data?.message || 'Unable to approve RIS.' });
  }
}
</script>
