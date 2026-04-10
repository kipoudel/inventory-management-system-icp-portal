<script setup lang="ts">
import AdminLayout from '@/Components/AdminLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const props = defineProps<{
  order: {
    id: number
    status: string
    total_amount: number
    created_at: string
    user: { name: string; email: string }
    products: Array<{
      id: number
      name: string
      sku: string
      quantity: number
      unit_price: number
    }>
  }
}>()

const form = useForm({ status: props.order.status })

function updateStatus() {
  form.patch(`/admin/orders/${props.order.id}/status`)
}

const statusClass: Record<string, string> = {
  pending:   'bg-amber-500/10 text-amber-400 border-amber-500/20',
  completed: 'bg-green-500/10 text-green-400 border-green-500/20',
  cancelled: 'bg-red-500/10 text-red-400 border-red-500/20',
}
</script>

<template>
  <AdminLayout :title="`Order #${String(order.id).padStart(4,'0')}`">
    <template #actions>
      <Link href="/admin/orders" class="px-3 py-1.5 text-xs text-gray-400 bg-white/[0.04] hover:bg-white/[0.08] border border-white/[0.06] rounded-lg transition-colors">
        ← Back to Orders
      </Link>
    </template>

    <div class="grid grid-cols-[1fr_300px] gap-4">
      <div class="space-y-4">
        <div class="bg-gray-900 border border-white/[0.06] rounded-xl overflow-hidden">
          <div class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
            <span class="text-sm font-semibold text-white">Order Items</span>
            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold border capitalize" :class="statusClass[order.status]">
              {{ order.status }}
            </span>
          </div>

          <div class="p-5 space-y-3">
            <div
              v-for="product in order.products"
              :key="product.id"
              class="flex items-center gap-4 p-3 bg-gray-800/50 rounded-lg"
            >
              <div class="w-10 h-10 bg-indigo-500/10 rounded-lg flex items-center justify-center text-xl flex-shrink-0">📦</div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-white truncate">{{ product.name }}</div>
                <div class="text-xs font-mono text-gray-500">{{ product.sku }}</div>
              </div>
              <div class="text-xs text-gray-400">× {{ product.quantity }}</div>
              <div class="font-mono text-sm text-white">${{ (product.unit_price * product.quantity).toFixed(2) }}</div>
            </div>
          </div>

          <div class="px-5 py-4 border-t border-white/[0.06] flex items-center justify-between">
            <span class="text-sm text-gray-400">Total</span>
            <span class="font-mono text-lg font-bold text-white">${{ Number(order.total_amount).toFixed(2) }}</span>
          </div>
        </div>
      </div>

      <div class="space-y-4">
        <div class="bg-gray-900 border border-white/[0.06] rounded-xl overflow-hidden">
          <div class="px-5 py-4 border-b border-white/[0.06]">
            <span class="text-sm font-semibold text-white">Process Order</span>
          </div>
          <div class="p-5">
            <label class="block text-xs text-gray-400 mb-2 uppercase tracking-wide">Update Status</label>
            <select
              v-model="form.status"
              class="w-full px-3 py-2.5 bg-gray-800 border border-white/[0.08] focus:border-indigo-500 rounded-lg text-white text-xs font-mono outline-none mb-3 transition-colors"
            >
              <option value="pending">Pending</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
            <button
              @click="updateStatus"
              :disabled="form.processing"
              class="w-full py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 rounded-lg transition-colors"
            >
              {{ form.processing ? 'Saving...' : 'Save Status' }}
            </button>
          </div>
        </div>

        <div class="bg-gray-900 border border-white/[0.06] rounded-xl overflow-hidden">
          <div class="px-5 py-4 border-b border-white/[0.06]">
            <span class="text-sm font-semibold text-white">Customer</span>
          </div>
          <div class="p-5 space-y-3">
            <div class="flex justify-between items-center text-xs">
              <span class="text-gray-500">Name</span>
              <span class="text-white">{{ order.user.name }}</span>
            </div>
            <div class="flex justify-between items-center text-xs">
              <span class="text-gray-500">Email</span>
              <span class="text-white font-mono">{{ order.user.email }}</span>
            </div>
            <div class="flex justify-between items-center text-xs">
              <span class="text-gray-500">Date</span>
              <span class="text-white">{{ order.created_at }}</span>
            </div>
            <div class="flex justify-between items-center text-xs">
              <span class="text-gray-500">Order ID</span>
              <span class="text-white font-mono">#{{ String(order.id).padStart(4,'0') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>