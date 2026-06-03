<template>
  <section class="space-y-5 p-6">
    <form class="panel grid gap-3 md:grid-cols-[1fr_1fr_1fr_220px_140px]" @submit.prevent="createUser">
      <input v-model="form.name" class="field" placeholder="Full name" />
      <input v-model="form.email" class="field" type="email" placeholder="Email" />
      <input v-model="form.password" class="field" type="password" placeholder="Password" />
      <select v-model="form.role" class="field">
        <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
      </select>
      <button class="btn-primary">Add User</button>
    </form>
    <div class="panel">
      <h2 class="panel-title">User Management</h2>
      <div class="space-y-2">
        <div v-for="user in users" :key="user.id" class="grid rounded border border-slate-200 p-3 md:grid-cols-3">
          <strong>{{ user.name }}</strong>
          <span>{{ user.email }}</span>
          <span>{{ user.roles?.map((role) => role.name).join(', ') }}</span>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { api } from '../stores/api';

const toast = useToast();
const users = ref([]);
const roles = ref([]);
const form = reactive({ name: '', email: '', password: '', role: 'Employee/Requester' });

async function load() {
  const { data } = await api.get('/users');
  users.value = data.users;
  roles.value = data.roles;
  form.role = roles.value[0]?.name || 'Employee/Requester';
}

async function createUser() {
  try {
    await api.post('/users', form);
    toast.add({ severity: 'success', summary: 'Created', detail: 'User created successfully.' });
    form.name = '';
    form.email = '';
    form.password = '';
    await load();
  } catch (exception) {
    toast.add({ severity: 'error', summary: 'Error', detail: exception.response?.data?.message || 'Unable to create user.' });
  }
}

onMounted(load);
</script>
