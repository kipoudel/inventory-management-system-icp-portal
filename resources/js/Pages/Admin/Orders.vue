<script setup lang="ts">
import AdminLayout from '@/Components/AdminLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps<{
  orders: any
  filters: { status?: string }
}>()

const status = ref(props.filters.status || '')

watch(status, () => {
  router.get('/admin/orders', { status: status.value }, { preserveState: true, replace: true })
})

const statusClass: Record<string, string> = {
  pending:   'bg-amber-500/10 text-amber-400 border-amber-500/20',
  completed: 'bg-green-500/10 text-green-400 border-green-500/20',
  cancelled: 'bg-red-500/10 text-red-400 border-red-500/20',
}

function updateStatus(orderId: number, newStatus: string) {
  router.patch(`/admin/orders/${orderId}/status`, { status: newStatus })
}
</script>

<template>
  <AdminLayout title="Orders">
    <template #actions>
      <select v-model="status" class="px-3 py-1.5 bg-gray-800 border border-white/[0.08] rounded-lg text-white text-xs outline-none cursor-pointer">
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </template>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl overflow-hidden">
      <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
        <span class="text-sm font-semibold text-white">All Orders</span>
        <span class="text-xs text-gray-500">{{ orders.total }} total</span>
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
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="order in orders.data"
            :key="order.id"
            class="border-b border-white/[0.04] hover:bg-white/[0.02]"
          >
            <td class="px-5 py-3 font-mono text-xs text-gray-400">#{{ String(order.id).padStart(4,'0') }}</td>
            <td class="px-5 py-3 text-sm text-white">{{ order.user }}</td>
            <td class="px-5 py-3 text-xs text-gray-400">{{ order.items_count }} items</td>
            <td class="px-5 py-3 font-mono text-sm text-white">${{ Number(order.total_amount).toFixed(2) }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold border capitalize" :class="statusClass[order.status]">
                {{ order.status }}
              </span>
            </td>
            <td class="px-5 py-3 text-xs text-gray-500">{{ order.created_at }}</td>
            <td class="px-5 py-3">
              <div class="flex gap-1.5">
                <a :href="`/admin/orders/${order.id}`" class="px-2.5 py-1 text-xs bg-white/[0.04] hover:bg-white/[0.08] text-gray-300 rounded-md border border-white/[0.06] transition-colors">View</a>
                <button
                  v-if="order.status === 'pending'"
                  @click="updateStatus(order.id, 'completed')"
                  class="px-2.5 py-1 text-xs bg-green-500/10 hover:bg-green-500/20 text-green-400 rounded-md border border-green-500/20 transition-colors"
                >Complete</button>
                <button
                  v-if="order.status === 'pending'"
                  @click="updateStatus(order.id, 'cancelled')"
                  class="px-2.5 py-1 text-xs bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-md border border-red-500/20 transition-colors"
                >Cancel</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="px-5 py-3 border-t border-white/[0.06] flex items-center justify-between">
        <span class="text-xs text-gray-500">Page {{ orders.current_page }} of {{ orders.last_page }}</span>
        <div class="flex gap-1.5">
          <a v-if="orders.prev_page_url" :href="orders.prev_page_url" class="px-3 py-1.5 text-xs bg-white/[0.04] hover:bg-white/[0.08] text-gray-300 rounded-lg border border-white/[0.06] transition-colors">← Prev</a>
          <a v-if="orders.next_page_url" :href="orders.next_page_url" class="px-3 py-1.5 text-xs bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition-colors">Next →</a>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>