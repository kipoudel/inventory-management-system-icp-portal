<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  email: 'test@example.com',
  password: 'password',
  remember: false,
})

function submit() {
  form.post('/admin/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-950 flex items-center justify-center px-4">
    <div class="w-full max-w-sm">
      <div class="flex items-center gap-3 mb-8 justify-center">
        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-sm font-bold text-white font-mono">ICP</div>
        <div>
          <div class="text-base font-semibold text-white">ICP Portal</div>
          <div class="text-xs text-gray-500">Admin Dashboard</div>
        </div>
      </div>

      <div class="bg-gray-900 border border-white/[0.08] rounded-2xl p-8">
        <h2 class="text-xl font-semibold text-white mb-1">Welcome back</h2>
        <p class="text-gray-500 text-sm mb-6">Sign in to manage inventory and orders</p>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wide">Email</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-3.5 py-2.5 bg-gray-800 border rounded-lg text-white text-sm outline-none transition-colors"
              :class="form.errors.email ? 'border-red-500' : 'border-white/[0.08] focus:border-indigo-500'"
            />
            <p v-if="form.errors.email" class="mt-1 text-xs text-red-400">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-400 mb-1.5 uppercase tracking-wide">Password</label>
            <input
              v-model="form.password"
              type="password"
              required
              class="w-full px-3.5 py-2.5 bg-gray-800 border border-white/[0.08] focus:border-indigo-500 rounded-lg text-white text-sm outline-none transition-colors"
            />
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white text-sm font-semibold rounded-lg transition-colors"
          >
            {{ form.processing ? 'Signing in...' : 'Sign in →' }}
          </button>
        </form>

        <div class="mt-5 p-3 bg-gray-800/50 rounded-lg text-center font-mono text-xs text-gray-500">
          test@example.com / password
        </div>
      </div>
    </div>
  </div>
</template>