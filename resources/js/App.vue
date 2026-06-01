<template>
  <RouterView v-if="$route.path === '/login'" />
  <main v-else class="min-h-screen bg-slate-100 text-slate-900">
    <aside class="fixed inset-y-0 left-0 hidden w-64 border-r border-slate-200 bg-white p-5 lg:block">
      <div class="mb-8 text-2xl font-bold">RIS V1</div>
      <nav class="space-y-1">
        <RouterLink v-for="item in nav" :key="item.to" :to="item.to" class="nav-link">
          <i :class="item.icon"></i>
          <span>{{ item.label }}</span>
        </RouterLink>
      </nav>
    </aside>
    <section class="lg:pl-64">
      <header class="border-b border-slate-200 bg-white px-6 py-4">
        <h1 class="text-xl font-semibold">Government Requisition and Issue Slip System</h1>
      </header>
      <RouterView />
    </section>
  </main>
</template>

<script setup>
const nav = [
  { to: '/', label: 'Dashboard', icon: 'pi pi-chart-bar' },
  { to: '/ris/create', label: 'Create RIS', icon: 'pi pi-plus-circle' },
  { to: '/ris', label: 'RIS List', icon: 'pi pi-list' },
  { to: '/approval', label: 'RIS Approval', icon: 'pi pi-check-circle' },
  { to: '/inventory', label: 'Inventory', icon: 'pi pi-box' },
  { to: '/reports', label: 'Reports', icon: 'pi pi-file-excel' },
  { to: '/users', label: 'Users', icon: 'pi pi-users' },
  { to: '/settings', label: 'Settings', icon: 'pi pi-cog' },
];
</script>
