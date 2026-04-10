<script setup lang="ts">
import AdminLayout from '@/Components/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps<{
  stats: {
    total_products: number
    total_orders: number
    pending_orders: number
    low_stock: number
    revenue: number
  }
  recent_orders: Array<{
    id: number
    user: string
    total_amount: number
    status: string
    items_count: number
    created_at: string
  }>
}>()

const statusClass: Record<string, string> = {
  pending:   'bg-amber-500/10 text-amber-400 border-amber-500/20',
  completed: 'bg-green-500/10 text-green-400 border-green-500/20',
  cancelled: 'bg-red-500/10 text-red-400 border-red-500/20',
}
</script>

<template>
  <AdminLayout title="Dashboard">
    <template #actions>
      <Link href="/admin/products/create" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition-colors">
        + Add Product
      </Link>
    </template>

    <div class="grid grid-cols-4 gap-3 mb-6">
      <div class="bg-gray-900 border border-white/[0.06] rounded-xl p-5">
        <div class="text-2xl font-bold font-mono text-white">{{ stats.total_products }}</div>
        <div class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Total Products</div>
      </div>
      <div class="bg-gray-900 border border-white/[0.06] rounded-xl p-5">
        <div class="text-2xl font-bold font-mono text-white">${{ Number(stats.revenue).toFixed(0) }}</div>
        <div class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Revenue</div>
      </div>
      <div class="bg-gray-900 border border-white/[0.06] rounded-xl p-5">
        <div class="text-2xl font-bold font-mono text-amber-400">{{ stats.pending_orders }}</div>
        <div class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Pending Orders</div>
      </div>
      <div class="bg-gray-900 border border-white/[0.06] rounded-xl p-5">
        <div class="text-2xl font-bold font-mono text-red-400">{{ stats.low_stock }}</div>
        <div class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Low Stock</div>
      </div>
    </div>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl overflow-hidden">
      <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
        <span class="text-sm font-semibold text-white">Recent Orders</span>
        <Link href="/admin/orders" class="text-xs text-indigo-400 hover:text-indigo-300">View all →</Link>
      </div>
      <table class="w-full">
        <thead>
          <tr class="border-b border-white/[0.06]">
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Order</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Items</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Total</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="order in recent_orders"
            :key="order.id"
            class="border-b border-white/[0.04] hover:bg-white/[0.02] cursor-pointer"
            @click="$inertia.visit(`/admin/orders/${order.id}`)"
          >
            <td class="px-5 py-3 font-mono text-xs text-gray-400">#{{ String(order.id).padStart(4,'0') }}</td>
            <td class="px-5 py-3 text-sm text-white">{{ order.user }}</td>
            <td class="px-5 py-3 text-sm text-gray-400">{{ order.items_count }} items</td>
            <td class="px-5 py-3 font-mono text-sm text-white">${{ Number(order.total_amount).toFixed(2) }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold border capitalize" :class="statusClass[order.status]">
                {{ order.status }}
              </span>
            </td>
            <td class="px-5 py-3 text-xs text-gray-500">{{ order.created_at }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>