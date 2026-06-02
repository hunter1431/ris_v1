<template>
  <div class="card group">
    <div class="flex items-start justify-between">
      <div class="flex-1">
        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">{{ label }}</p>
        <div class="mt-3 flex items-baseline gap-2">
          <strong class="text-4xl font-bold text-gray-900">{{ value }}</strong>
          <span class="text-sm font-medium text-green-600">+5%</span>
        </div>
      </div>
      <div class="flex-shrink-0">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 group-hover:from-blue-200 group-hover:to-blue-100 transition-all duration-300">
          <i :class="icon" style="font-size: 1.5rem"></i>
        </div>
      </div>
    </div>
    <div class="mt-4 h-1 bg-gradient-to-r from-blue-500 to-blue-300 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
  </div>
</template>

<script setup>
defineProps({ 
  label: String, 
  value: [String, Number],
  icon: { type: String, default: 'pi pi-chart-bar' }
});
</script>
