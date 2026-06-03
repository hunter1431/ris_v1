<template>
  <section class="space-y-6">
    <!-- Page Header -->
    <div>
      <h2 class="text-2xl font-bold text-gray-900">Create Requisition</h2>
      <p class="text-gray-600 mt-1">Submit a new RIS request for your department.</p>
    </div>

    <!-- Form -->
    <form @submit.prevent="submit" class="panel max-w-6xl space-y-6">
      <!-- Header Information Section -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
          <i class="pi pi-info-circle text-blue-600"></i>
          Request Information
        </h3>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Entity Name</label>
            <input v-model="form.entity_name" type="text" class="field w-full" placeholder="Department/Entity" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fund Cluster</label>
            <input v-model="form.fund_cluster" type="text" class="field w-full" placeholder="Fund Cluster Code" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Office</label>
            <input v-model="form.office" type="text" class="field w-full" placeholder="Office Name" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Responsibility Center</label>
            <input v-model="form.responsibility_center_code" type="text" class="field w-full" placeholder="RCC Code" />
          </div>
          <div class="sm:col-span-2 lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Purpose</label>
            <input v-model="form.purpose" type="text" class="field w-full" placeholder="Purpose of request" />
          </div>
        </div>
      </div>

      <!-- Items Section -->
      <div class="border-t border-gray-200 pt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
          <i class="pi pi-shopping-cart text-green-600"></i>
          Items Required
        </h3>
        
        <!-- Item Rows -->
        <div class="space-y-3">
          <div v-for="(row, index) in form.details" :key="index" class="panel p-4 bg-gray-50 border border-gray-200">
            <div class="grid gap-3 grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1fr_150px_120px_60px]">
              <!-- Item Search -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Search Item</label>
                <input 
                  v-model="row.search" 
                  type="text"
                  class="field w-full"
                  placeholder="e.g. Bond Paper, Ballpen" 
                  @change="search(row.search)"
                />
              </div>
              
              <!-- Quantity -->
              <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Quantity</label>
                <input 
                  v-model.number="row.qty_requested" 
                  type="number" 
                  min="1" 
                  class="field w-full"
                  placeholder="Qty"
                />
              </div>
              
              <!-- Auto Fill Button -->
              <div class="flex items-end">
                <button 
                  type="button" 
                  @click="pickFirst(row)"
                  class="btn w-full gap-1 text-xs"
                >
                  <i class="pi pi-check"></i>
                  <span class="hidden sm:inline">Auto Fill</span>
                </button>
              </div>
              
              <!-- Remove Button -->
              <div class="flex items-end">
                <button 
                  v-if="form.details.length > 1"
                  type="button" 
                  @click="form.details.splice(index, 1)"
                  class="btn w-full gap-1 text-xs text-red-600 hover:bg-red-50"
                >
                  <i class="pi pi-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Add Item Button -->
        <button 
          type="button" 
          @click="addRow"
          class="btn mt-4 gap-2"
        >
          <i class="pi pi-plus"></i>
          Add Another Item
        </button>
      </div>

      <!-- Action Buttons -->
      <div class="border-t border-gray-200 pt-6 flex gap-3 sm:flex-row flex-col">
        <RouterLink to="/ris" class="btn gap-2">
          <i class="pi pi-arrow-left"></i>
          <span>Back to List</span>
        </RouterLink>
        <button type="submit" class="btn-primary gap-2 flex-1 sm:flex-none">
          <i class="pi pi-send"></i>
          <span>Submit RIS Request</span>
        </button>
      </div>
    </form>
  </section>
</template>

<script setup>
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import { useRisStore } from '../stores/ris';

const store = useRisStore();
const router = useRouter();
const toast = useToast();
const form = reactive({
  entity_name: 'DPWH / LGU Office',
  fund_cluster: '',
  division_id: 1,
  office: '',
  responsibility_center_code: '',
  purpose: '',
  details: [{ item_id: null, search: '', qty_requested: 1 }],
});

function addRow() {
  form.details.push({ item_id: null, search: '', qty_requested: 1 });
}

async function search(term) {
  await store.searchItems(term);
}

function pickFirst(row) {
  const item = store.inventory[0];
  if (!item) return;
  row.item_id = item.id;
  row.search = `${item.stock_no} - ${item.description} | Available: ${item.quantity_on_hand}`;
}

async function submit() {
  try {
    await store.createRis({ ...form, details: form.details.map(({ item_id, qty_requested }) => ({ item_id, qty_requested })) });
    toast.add({ severity: 'success', summary: 'Created', detail: 'RIS request created successfully.' });
    router.push('/ris');
  } catch (exception) {
    toast.add({ severity: 'error', summary: 'Error', detail: exception.response?.data?.message || 'Unable to submit RIS request.' });
  }
}
</script>
