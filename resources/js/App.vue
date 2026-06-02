<template>
  <RouterView v-if="$route.path === '/login'" />
  <main v-else class="app-shell">
    <!-- Mobile Hamburger Menu Button -->
    <div class="fixed top-0 left-0 right-0 z-40 bg-white/95 border-b border-slate-200 px-4 py-3 flex items-center justify-between lg:hidden shadow-sm backdrop-blur-sm">
      <div class="text-xl font-bold text-blue-600">RIS V1</div>
      <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-gray-900">
        <i :class="mobileMenuOpen ? 'pi pi-times' : 'pi pi-bars'" style="font-size: 1.5rem"></i>
      </button>
    </div>

    <!-- Sidebar Navigation -->
    <aside :class="['fixed inset-y-0 left-0 z-30 w-64 bg-white/95 border-r border-slate-200 p-6 overflow-y-auto transition-all duration-300 shadow-lg lg:shadow-none',
      mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]">
      <div v-if="!isLargeScreen" class="text-2xl font-bold text-blue-600 mb-8">RIS V1</div>
      <div v-else class="text-2xl font-bold text-blue-600 mb-8">RIS V1</div>
      <nav class="space-y-2">
        <RouterLink v-for="item in nav" :key="item.to" :to="item.to" class="nav-link" @click="closeMobileMenu">
          <i :class="item.icon" style="font-size: 1.25rem; min-width: 1.5rem"></i>
          <span>{{ item.label }}</span>
        </RouterLink>
      </nav>
    </aside>

    <!-- Mobile Overlay -->
    <div v-if="mobileMenuOpen && !isLargeScreen" @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/30 z-20 lg:hidden"></div>

    <!-- Main Content -->
    <section class="lg:ml-64 pt-16 lg:pt-0">
      <!-- Header -->
      <header class="app-header sticky top-0 z-10 lg:relative">
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-bold text-gray-900 hidden sm:block">Government RIS System</h1>
          <h1 class="text-lg font-bold text-gray-900 sm:hidden">RIS System</h1>
          <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600 hidden sm:inline">{{ auth.user?.name || 'User' }}</span>
            <button @click="logout" class="btn gap-2 hidden sm:flex">
              <i class="pi pi-sign-out"></i>
              Logout
            </button>
            <button @click="logout" class="btn p-2 sm:hidden">
              <i class="pi pi-sign-out"></i>
            </button>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <div class="p-4 sm:p-6 lg:p-8">
        <RouterView />
      </div>
    </section>
  </main>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from './stores/auth';

const router = useRouter();
const auth = useAuthStore();
const mobileMenuOpen = ref(false);
const isLargeScreen = ref(window.innerWidth >= 1024);

const nav = [
  { to: '/', label: 'Dashboard', icon: 'pi pi-chart-bar' },
  { to: '/ris/create', label: 'Create RIS', icon: 'pi pi-plus-circle' },
  { to: '/ris', label: 'RIS List', icon: 'pi pi-list' },
  { to: '/approval', label: 'Approval', icon: 'pi pi-check-circle' },
  { to: '/inventory', label: 'Inventory', icon: 'pi pi-box' },
  { to: '/reports', label: 'Reports', icon: 'pi pi-file-excel' },
  { to: '/users', label: 'Users', icon: 'pi pi-users' },
  { to: '/settings', label: 'Settings', icon: 'pi pi-cog' },
];

function closeMobileMenu() {
  mobileMenuOpen.value = false;
}

async function logout() {
  try {
    await auth.logout();
  } finally {
    router.push('/login');
  }
}

function handleResize() {
  isLargeScreen.value = window.innerWidth >= 1024;
  if (isLargeScreen.value) mobileMenuOpen.value = false;
}

onMounted(() => {
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});
</script>
