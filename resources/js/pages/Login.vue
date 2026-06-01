<template>
  <section class="grid min-h-screen place-items-center bg-slate-100 p-6">
    <form class="panel w-full max-w-md space-y-4" @submit.prevent="submit">
      <div>
        <h1 class="text-2xl font-bold">RIS V1 Login</h1>
        <p class="mt-1 text-sm text-slate-500">Sign in to access the government RIS workflow.</p>
      </div>
      <input v-model="email" class="field w-full" type="email" placeholder="Email" />
      <input v-model="password" class="field w-full" type="password" placeholder="Password" />
      <button class="btn-primary w-full">Login</button>
      <p class="text-sm text-slate-500">Seed account: admin@example.com / password</p>
    </form>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const auth = useAuthStore();
const router = useRouter();
const email = ref('admin@example.com');
const password = ref('password');

async function submit() {
  await auth.login({ email: email.value, password: password.value });
  router.push('/');
}
</script>
