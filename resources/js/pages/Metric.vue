<template>
  <div class="panel">
    <p class="text-sm text-slate-500">{{ label }}</p>
    <strong class="mt-2 block text-3xl">{{ value }}</strong>
  </div>
</template>

<script setup>
defineProps({ label: String, value: [String, Number] });
</script>
