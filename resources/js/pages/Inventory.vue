<template>
  <section class="space-y-6">
    <!-- Page Header -->
    <div>
      <h2 class="text-2xl font-bold text-gray-900">Inventory Management</h2>
      <p class="text-gray-600 mt-1">View and search available inventory items.</p>
    </div>

    <!-- Search Bar -->
    <div class="panel">
      <div class="flex items-center gap-2">
        <i class="pi pi-search text-gray-400"></i>
        <input 
          v-model="search" 
          type="text"
          class="field flex-1 border-0 px-0 focus:ring-0 bg-transparent"
          placeholder="Search by stock no, code, or description..."
          @input="store.searchItems(search)"
        />
      </div>
    </div>

    <!-- Inventory Table -->
    <div class="panel overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 border-b-2 border-gray-200">
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Stock No.</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Description</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide hidden sm:table-cell">Code</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Unit</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Qty On Hand</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="item in store.inventory" :key="item.id" class="hover:bg-gray-50 transition-colors duration-200">
              <!-- Stock No -->
              <td class="px-4 py-4">
                <div class="flex flex-col gap-1">
                  <strong class="text-blue-600">{{ item.stock_no }}</strong>
                  <span class="text-xs text-gray-500 sm:hidden">{{ item.item_code }}</span>
                </div>
              </td>

              <!-- Description -->
              <td class="px-4 py-4">
                <span class="text-gray-900 font-medium">{{ item.description }}</span>
              </td>

              <!-- Code (hidden on mobile) -->
              <td class="px-4 py-4 text-gray-600 hidden sm:table-cell">{{ item.item_code }}</td>

              <!-- Unit -->
              <td class="px-4 py-4">
                <span class="badge badge-primary">{{ item.unit }}</span>
              </td>

              <!-- Quantity -->
              <td class="px-4 py-4 text-center">
                <div class="inline-flex items-center justify-center px-3 py-1 rounded-lg font-semibold"
                  :class="item.quantity_on_hand > item.reorder_level 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800'">
                  {{ item.quantity_on_hand }}
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="!store.inventory || store.inventory.length === 0" class="flex flex-col items-center justify-center py-12">
        <i class="pi pi-box text-5xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg font-medium">No items found</p>
        <p class="text-gray-400 text-sm">Try a different search term</p>
      </div>
    </div>

    <!-- Legend -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
      <p class="text-xs font-semibold text-blue-900 uppercase tracking-wide mb-2">Legend</p>
      <div class="grid gap-3 sm:grid-cols-2 text-sm">
        <div class="flex items-center gap-2">
          <div class="w-3 h-3 rounded-full bg-green-500"></div>
          <span class="text-gray-700">Quantity above reorder level</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-3 h-3 rounded-full bg-red-500"></div>
          <span class="text-gray-700">Quantity below reorder level</span>
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
