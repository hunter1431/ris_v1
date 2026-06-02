import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../pages/Dashboard.vue';
import RisCreate from '../pages/RisCreate.vue';
import RisList from '../pages/RisList.vue';
import Inventory from '../pages/Inventory.vue';
import Reports from '../pages/Reports.vue';
import Approval from '../pages/Approval.vue';
import Settings from '../pages/Settings.vue';
import VerifyRis from '../pages/VerifyRis.vue';
import Login from '../pages/Login.vue';
import Users from '../pages/Users.vue';
import Placeholder from '../pages/Placeholder.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', component: Login, meta: { guest: true } },
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
    { path: '/ris/create', component: RisCreate, meta: { requiresAuth: true } },
    { path: '/ris', component: RisList, meta: { requiresAuth: true } },
    { path: '/approval', component: Approval, meta: { requiresAuth: true } },
    { path: '/inventory', component: Inventory, meta: { requiresAuth: true } },
    { path: '/reports', component: Reports, meta: { requiresAuth: true } },
    { path: '/users', component: Users, meta: { requiresAuth: true } },
    { path: '/settings', component: Settings, meta: { requiresAuth: true } },
    { path: '/verify-ris/:token', component: VerifyRis, meta: { public: true } },
  ],
});

router.beforeEach((to) => {
  const token = localStorage.getItem('ris_token');

  if (to.meta.requiresAuth && !token) {
    return { path: '/login', query: { redirect: to.fullPath } };
  }

  if (to.meta.guest && token) {
    return { path: '/' };
  }
});

export default router;
