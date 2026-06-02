<template>
  <section class="space-y-6">
    <div class="panel">
      <div class="flex items-center gap-4">
        <div class="flex-shrink-0">
          <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600">
            <i class="pi pi-cube" style="font-size: 1.5rem"></i>
          </div>
        </div>
        <div>
          <h2 class="panel-title m-0">{{ title }}</h2>
          <p class="text-gray-600 text-sm mt-1">Module route included for the enterprise workflow. This section is ready for content.</p>
        </div>
      </div>
    </div>
    
    <!-- Coming Soon Card -->
    <div class="panel border-l-4 border-blue-500 bg-gradient-to-br from-blue-50 to-transparent">
      <div class="flex items-start gap-4">
        <div class="flex-shrink-0 mt-1">
          <i class="pi pi-info-circle text-blue-600" style="font-size: 1.5rem"></i>
        </div>
        <div>
          <h3 class="font-semibold text-gray-900 mb-2">Coming Soon</h3>
          <p class="text-gray-600 text-sm">This module is part of your RIS Enterprise system and will be configured based on your business requirements. Contact your administrator for more information.</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({ title: String });
</script>
