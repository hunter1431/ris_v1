<template>
  <section class="p-6">
    <div class="panel space-y-4">
      <h2 class="panel-title">Inventory</h2>
      <input v-model="search" class="field" placeholder="Search stock no, code, or description" @input="store.searchItems(search)" />
      <div class="grid gap-2">
        <div v-for="item in store.inventory" :key="item.id" class="grid rounded border border-slate-200 p-3 md:grid-cols-4">
          <strong>{{ item.stock_no }}</strong>
          <span>{{ item.description }}</span>
          <span>{{ item.unit }}</span>
          <span>{{ item.quantity_on_hand }}</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { useRisStore } from '../stores/ris';

const search = ref('');
const store = useRisStore();
</script>
