<template>
  <section class="space-y-6">
    <!-- Page Header -->
    <div class="space-y-2">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Inventory Management</h2>
          <p class="text-gray-600 mt-1">View, create, update, and delete inventory items.</p>
        </div>
        <button
          v-if="canManageInventory"
          class="btn btn-primary"
          @click="openForm()"
        >
          Add Item
        </button>
      </div>
      <div class="panel">
        <div class="flex items-center gap-2">
          <i class="pi pi-search text-gray-400"></i>
          <input
            v-model="search"
            type="text"
            class="field flex-1 border-0 px-0 focus:ring-0 bg-transparent"
            placeholder="Search by stock no, code, or description..."
            @input="searchItems"
          />
        </div>
      </div>
    </div>

    <div v-if="showForm" class="panel space-y-4">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h3 class="text-lg font-semibold text-gray-900">{{ editingItem ? 'Edit Item' : 'New Item' }}</h3>
          <p class="text-sm text-gray-500">Supply Officer inventory management form.</p>
        </div>
        <button class="btn btn-ghost" @click="closeForm">Cancel</button>
      </div>

      <div class="grid gap-4 lg:grid-cols-2">
        <label class="space-y-1">
          <span class="text-sm font-medium text-gray-700">Stock Number</span>
          <input v-model="form.stock_no" class="field w-full" type="text" />
        </label>
        <label class="space-y-1">
          <span class="text-sm font-medium text-gray-700">Item Code</span>
          <input v-model="form.item_code" class="field w-full" type="text" />
        </label>
        <label class="space-y-1 lg:col-span-2">
          <span class="text-sm font-medium text-gray-700">Description</span>
          <input v-model="form.description" class="field w-full" type="text" />
        </label>
        <label class="space-y-1">
          <span class="text-sm font-medium text-gray-700">Unit</span>
          <input v-model="form.unit" class="field w-full" type="text" />
        </label>
        <label class="space-y-1">
          <span class="text-sm font-medium text-gray-700">Quantity On Hand</span>
          <input v-model.number="form.quantity_on_hand" class="field w-full" type="number" min="0" />
        </label>
        <label class="space-y-1">
          <span class="text-sm font-medium text-gray-700">Reorder Level</span>
          <input v-model.number="form.reorder_level" class="field w-full" type="number" min="0" />
        </label>
        <label class="space-y-1">
          <span class="text-sm font-medium text-gray-700">Status</span>
          <select v-model="form.status" class="field w-full">
            <option value="active">Active</option>
            <option value="low_stock">Low Stock</option>
            <option value="out_of_stock">Out of Stock</option>
            <option value="inactive">Inactive</option>
          </select>
        </label>
      </div>

      <div class="flex items-center gap-2">
        <button class="btn btn-primary" @click="saveItem" :disabled="saving">
          {{ saving ? 'Saving...' : editingItem ? 'Update Item' : 'Create Item' }}
        </button>
        <button class="btn btn-ghost" @click="closeForm" type="button">Close</button>
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
              <th v-if="canManageInventory" class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="item in store.inventory" :key="item.id" class="hover:bg-gray-50 transition-colors duration-200">
              <td class="px-4 py-4">
                <div class="flex flex-col gap-1">
                  <strong class="text-blue-600">{{ item.stock_no }}</strong>
                  <span class="text-xs text-gray-500 sm:hidden">{{ item.item_code }}</span>
                </div>
              </td>
              <td class="px-4 py-4">
                <span class="text-gray-900 font-medium">{{ item.description }}</span>
              </td>
              <td class="px-4 py-4 text-gray-600 hidden sm:table-cell">{{ item.item_code }}</td>
              <td class="px-4 py-4">
                <span class="badge badge-primary">{{ item.unit }}</span>
              </td>
              <td class="px-4 py-4 text-center">
                <div class="inline-flex items-center justify-center px-3 py-1 rounded-lg font-semibold"
                  :class="item.quantity_on_hand > item.reorder_level
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800'">
                  {{ item.quantity_on_hand }}
                </div>
              </td>
              <td v-if="canManageInventory" class="px-4 py-4 text-right space-x-2">
                <button class="btn btn-sm btn-secondary" @click="openForm(item)">Edit</button>
                <button class="btn btn-sm btn-danger" @click="confirmDelete(item)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="!store.inventory || store.inventory.length === 0" class="flex flex-col items-center justify-center py-12">
        <i class="pi pi-box text-5xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg font-medium">No items found</p>
        <p class="text-gray-400 text-sm">Try a different search term</p>
      </div>
    </div>

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
import { computed, onMounted, ref } from 'vue';
import { useRisStore } from '../stores/ris';
import { useAuthStore } from '../stores/auth';
import { useToast } from 'primevue/usetoast';

const search = ref('');
const store = useRisStore();
const auth = useAuthStore();
const toast = useToast();
const showForm = ref(false);
const saving = ref(false);
const editingItem = ref(null);

const form = ref({
  stock_no: '',
  item_code: '',
  description: '',
  unit: '',
  quantity_on_hand: 0,
  reorder_level: 0,
  status: 'active',
});

const canManageInventory = computed(() => {
  return auth.user?.roles?.some((role) => ['Supply Officer', 'Super Admin'].includes(role.name));
});

function resetForm() {
  form.value = {
    stock_no: '',
    item_code: '',
    description: '',
    unit: '',
    quantity_on_hand: 0,
    reorder_level: 0,
    status: 'active',
  };
  editingItem.value = null;
}

function openForm(item = null) {
  if (item) {
    editingItem.value = item;
    form.value = {
      stock_no: item.stock_no,
      item_code: item.item_code,
      description: item.description,
      unit: item.unit,
      quantity_on_hand: item.quantity_on_hand,
      reorder_level: item.reorder_level,
      status: item.status,
    };
  } else {
    resetForm();
  }

  showForm.value = true;
}

function closeForm() {
  showForm.value = false;
  resetForm();
}

async function saveItem() {
  saving.value = true;
  try {
    if (editingItem.value) {
      await store.updateItem(editingItem.value.id, form.value);
      toast.add({ severity: 'success', summary: 'Updated', detail: 'Inventory item has been updated.' });
    } else {
      await store.createItem(form.value);
      toast.add({ severity: 'success', summary: 'Created', detail: 'Inventory item has been created.' });
    }
    closeForm();
  } catch (exception) {
    toast.add({ severity: 'error', summary: 'Error', detail: exception.response?.data?.message || 'Unable to save inventory item.' });
  } finally {
    saving.value = false;
  }
}

async function confirmDelete(item) {
  if (!window.confirm(`Delete item ${item.stock_no}? This cannot be undone.`)) {
    return;
  }

  try {
    await store.deleteItem(item.id);
    toast.add({ severity: 'success', summary: 'Deleted', detail: 'Inventory item has been deleted.' });
  } catch (exception) {
    toast.add({ severity: 'error', summary: 'Error', detail: exception.response?.data?.message || 'Unable to delete inventory item.' });
  }
}

async function searchItems() {
  await store.searchItems(search.value);
}

onMounted(async () => {
  await store.loadItems();
});
</script>
