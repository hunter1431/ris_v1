import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../pages/Dashboard.vue';
import RisCreate from '../pages/RisCreate.vue';
import RisList from '../pages/RisList.vue';
import Inventory from '../pages/Inventory.vue';
import Reports from '../pages/Reports.vue';
import Placeholder from '../pages/Placeholder.vue';

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', component: Dashboard },
    { path: '/ris/create', component: RisCreate },
    { path: '/ris', component: RisList },
    { path: '/approval', component: Placeholder, props: { title: 'RIS Approval' } },
    { path: '/inventory', component: Inventory },
    { path: '/reports', component: Reports },
    { path: '/users', component: Placeholder, props: { title: 'User Management' } },
    { path: '/settings', component: Placeholder, props: { title: 'Settings' } },
  ],
});
