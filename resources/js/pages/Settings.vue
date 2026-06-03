<template>
  <section class="space-y-5 p-6">
    <div class="panel">
      <h2 class="panel-title">PDF Digital Signatures</h2>
      <form class="grid gap-3 md:grid-cols-[220px_1fr_160px]" @submit.prevent="upload">
        <select v-model="signatureType" class="field">
          <option value="requester">Requester</option>
          <option value="approver">Approver</option>
          <option value="issuer">Issued By</option>
          <option value="receiver">Received By</option>
        </select>
        <input class="field" type="file" accept="image/png,image/jpeg" @change="file = $event.target.files[0]" />
        <button class="btn-primary">Upload Signature</button>
      </form>
      <p class="mt-3 text-sm text-slate-500">Uploaded signatures are automatically printed in the matching slot on the RIS PDF.</p>
    </div>
    <div class="panel">
      <h2 class="panel-title">QR Verification</h2>
      <p class="text-slate-600">Every generated RIS PDF includes a QR code that points to the public verification page.</p>
      <code class="mt-3 block rounded bg-slate-100 p-3">/verify-ris/{qr_token}</code>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { api } from '../stores/api';

const toast = useToast();
const signatureType = ref('requester');
const file = ref(null);

async function upload() {
  if (!file.value) {
    toast.add({ severity: 'warn', summary: 'No File', detail: 'Please select a signature file before uploading.' });
    return;
  }

  try {
    const formData = new FormData();
    formData.append('signature_type', signatureType.value);
    formData.append('signature', file.value);
    await api.post('/signatures', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
    toast.add({ severity: 'success', summary: 'Uploaded', detail: 'Signature uploaded successfully.' });
    file.value = null;
  } catch (exception) {
    toast.add({ severity: 'error', summary: 'Error', detail: exception.response?.data?.message || 'Unable to upload signature.' });
  }
}
</script>
