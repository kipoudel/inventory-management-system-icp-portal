<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ title?: string }>()

const navItems = [
  { label: 'Dashboard', href: '/admin', icon: '▦' },
  { label: 'Products', href: '/admin/products', icon: '📦' },
  { label: 'Orders', href: '/admin/orders', icon: '📋' },
]

const mobileMenuOpen = ref(false)

function logout() {
  router.post('/admin/logout')
}
</script>

<template>
  <div class="min-h-screen bg-gray-950 flex">
    <!-- Mobile Menu Button -->
    <button
      @click="mobileMenuOpen = !mobileMenuOpen"
      class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-gray-900 border border-white/[0.06] rounded-lg text-white"
    >
      <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
      <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <!-- Overlay -->
    <div
      v-if="mobileMenuOpen"
      @click="mobileMenuOpen = false"
      class="lg:hidden fixed inset-0 bg-black/60 z-40"
    />

    <!-- Sidebar -->
    <aside
      class="w-56 bg-gray-900 border-r border-white/[0.06] flex flex-col fixed h-full z-40 transition-transform duration-300"
      :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
      <div class="p-5 border-b border-white/[0.06]">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-xs font-bold text-white font-mono">ICP</div>
          <div>
            <div class="text-sm font-semibold text-white">ICP Portal</div>
            <div class="text-[10px] text-gray-500">Admin Dashboard</div>
          </div>
        </div>
      </div>

      <nav class="flex-1 p-3 space-y-0.5">
        <Link
          v-for="item in navItems"
          :key="item.href"
          :href="item.href"
          @click="mobileMenuOpen = false"
          class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-all duration-150"
          :class="$page.url.startsWith(item.href) && item.href !== '/admin'
            || $page.url === item.href
            ? 'bg-indigo-500/10 text-indigo-300 border border-indigo-500/20'
            : 'text-gray-500 hover:bg-white/[0.04] hover:text-white'"
        >
          <span class="text-base">{{ item.icon }}</span>
          {{ item.label }}
        </Link>
      </nav>

      <div class="p-3 border-t border-white/[0.06]">
        <div class="flex items-center gap-2.5 px-3 py-2 mb-1">
          <div class="w-7 h-7 bg-indigo-500/20 rounded-full flex items-center justify-center text-indigo-300 text-xs font-semibold">
            {{ $page.props.auth.user?.name?.charAt(0) }}
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-xs font-medium text-white truncate">{{ $page.props.auth.user?.name }}</div>
            <div class="text-[10px] text-gray-500 truncate">Administrator</div>
          </div>
        </div>
        <button
          @click="logout"
          class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-gray-500 hover:bg-white/[0.04] hover:text-red-400 transition-all duration-150"
        >
          <span>↩</span> Logout
        </button>
      </div>
    </aside>

    <main class="flex-1 lg:ml-56 w-full">
      <div class="sticky top-0 z-10 bg-gray-900/80 backdrop-blur border-b border-white/[0.06] px-4 lg:px-6 py-4 flex items-center justify-between">
        <h1 class="text-sm font-semibold text-white ml-12 lg:ml-0">{{ title }}</h1>
        <slot name="actions" />
      </div>

      <div class="p-4 lg:p-6">
        <div
          v-if="$page.props.flash?.success"
          class="mb-4 px-4 py-3 bg-green-500/10 border border-green-500/20 rounded-lg text-green-400 text-sm"
        >
          {{ $page.props.flash.success }}
        </div>
        <slot />
      </div>
    </main>
  </div>
</template>
