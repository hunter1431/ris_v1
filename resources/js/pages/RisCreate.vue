<template>
  <section class="p-6">
    <form class="panel mx-auto max-w-5xl space-y-5" @submit.prevent="submit">
      <h2 class="panel-title">Create RIS</h2>
      <div class="grid gap-4 md:grid-cols-3">
        <input v-model="form.entity_name" class="field" placeholder="Entity Name" />
        <input v-model="form.fund_cluster" class="field" placeholder="Fund Cluster" />
        <input v-model="form.office" class="field" placeholder="Office" />
        <input v-model="form.responsibility_center_code" class="field" placeholder="Responsibility Center Code" />
        <input v-model="form.purpose" class="field md:col-span-2" placeholder="Purpose" />
      </div>
      <div class="space-y-3">
        <div v-for="(row, index) in form.details" :key="index" class="grid gap-3 md:grid-cols-[1fr_120px_120px]">
          <input v-model="row.search" class="field" placeholder="Search item, e.g. Bond Paper" @change="search(row.search)" />
          <input v-model.number="row.qty_requested" class="field" type="number" min="1" placeholder="Qty" />
          <button class="btn" type="button" @click="pickFirst(row)">Auto Fill</button>
        </div>
      </div>
      <div class="flex gap-3">
        <button class="btn" type="button" @click="addRow">+ Add Item</button>
        <button class="btn-primary">Submit RIS</button>
      </div>
    </form>
  </section>
</template>

<script setup>
import { reactive } from 'vue';
import { useRisStore } from '../stores/ris';

const store = useRisStore();
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
  await store.createRis({ ...form, details: form.details.map(({ item_id, qty_requested }) => ({ item_id, qty_requested })) });
}
</script>
