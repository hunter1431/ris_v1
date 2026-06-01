<template>
  <section class="p-6">
    <div class="panel">
      <h2 class="panel-title">{{ title }}</h2>
      <p class="text-slate-500">Module route included for the enterprise workflow.</p>
    </div>
  </section>
</template>

<script setup>
defineProps({ title: String });
</script>
