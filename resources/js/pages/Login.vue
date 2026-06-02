<template>
  <section class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo & Branding -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 shadow-lg mb-4">
          <i class="pi pi-document text-2xl text-white"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">RIS V1</h1>
        <p class="text-gray-600 mt-2">Government Requisition and Issue Slip System</p>
      </div>

      <!-- Login Form Card -->
      <div class="panel">
        <form @submit.prevent="submit" class="space-y-5">
          <!-- Email Field -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="pi pi-envelope text-blue-600 mr-2"></i>Email Address
            </label>
            <input 
              v-model="email" 
              type="email" 
              class="field w-full"
              placeholder="Enter your email"
              required
            />
          </div>

          <!-- Password Field -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              <i class="pi pi-lock text-blue-600 mr-2"></i>Password
            </label>
            <input 
              v-model="password" 
              type="password" 
              class="field w-full"
              placeholder="Enter your password"
              required
            />
          </div>

          <!-- Error Message -->
          <div v-if="error" class="rounded-lg border border-red-200 bg-red-50 p-4 flex gap-3">
            <i class="pi pi-exclamation-circle text-red-600 flex-shrink-0 mt-0.5"></i>
            <div class="text-sm text-red-700">{{ error }}</div>
          </div>

          <!-- Submit Button -->
          <button 
            type="submit" 
            :disabled="loading"
            class="btn-primary w-full gap-2"
          >
            <i :class="loading ? 'pi pi-spin pi-spinner' : 'pi pi-sign-in'"></i>
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <!-- Demo Credentials -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-3">Demo Account</p>
          <div class="space-y-2 text-sm">
            <p class="flex items-center gap-2 text-gray-700">
              <i class="pi pi-envelope text-blue-600"></i>
              <code class="bg-gray-100 px-2 py-1 rounded font-mono">admin@example.com</code>
            </p>
            <p class="flex items-center gap-2 text-gray-700">
              <i class="pi pi-lock text-blue-600"></i>
              <code class="bg-gray-100 px-2 py-1 rounded font-mono">password</code>
            </p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <p class="text-center text-xs text-gray-500 mt-6">
        © 2026 Government RIS System. All rights reserved.
      </p>
    </div>
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
const loading = ref(false);
const error = ref('');

async function submit() {
  loading.value = true;
  error.value = '';

  try {
    await auth.login({ email: email.value, password: password.value });
    router.push('/');
  } catch (exception) {
    error.value = exception.response?.data?.message
      || exception.response?.data?.errors?.email?.[0]
      || 'Login failed. Please check your credentials and try again.';
  } finally {
    loading.value = false;
  }
}
</script>
