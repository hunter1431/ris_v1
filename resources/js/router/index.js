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

export default createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', component: Login },
    { path: '/', component: Dashboard },
    { path: '/ris/create', component: RisCreate },
    { path: '/ris', component: RisList },
    { path: '/approval', component: Approval },
    { path: '/inventory', component: Inventory },
    { path: '/reports', component: Reports },
    { path: '/users', component: Users },
    { path: '/settings', component: Settings },
    { path: '/verify-ris/:token', component: VerifyRis },
  ],
});
