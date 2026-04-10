<script setup lang="ts">
import AdminLayout from '@/Components/AdminLayout.vue'
import { useForm, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps<{
  products: any
  categories: Array<{ id: number; name: string }>
  filters: { search?: string; category_id?: number }
}>()

const showModal = ref(false)
const editingProduct = ref<any>(null)

const search = ref(props.filters.search || '')
const categoryId = ref(props.filters.category_id || '')

watch([search, categoryId], () => {
  router.get('/admin/products', {
    search: search.value,
    category_id: categoryId.value,
  }, { preserveState: true, replace: true })
})

const form = useForm({
  name: '',
  sku: '',
  category_id: '',
  price: '',
  stock_quantity: '',
})

function openCreate() {
  editingProduct.value = null
  form.reset()
  showModal.value = true
}

function openEdit(product: any) {
  editingProduct.value = product
  form.name = product.name
  form.sku = product.sku
  form.category_id = product.category_id
  form.price = product.price
  form.stock_quantity = product.stock_quantity
  showModal.value = true
}

function submit() {
  if (editingProduct.value) {
    form.put(`/admin/products/${editingProduct.value.id}`, {
      onSuccess: () => { showModal.value = false; form.reset() }
    })
  } else {
    form.post('/admin/products', {
      onSuccess: () => { showModal.value = false; form.reset() }
    })
  }
}

function deleteProduct(product: any) {
  if (confirm(`Delete "${product.name}"? This cannot be undone.`)) {
    router.delete(`/admin/products/${product.id}`)
  }
}

const stockBadge = (qty: number) => {
  if (qty === 0) return { label: 'Out of Stock', cls: 'bg-red-500/10 text-red-400 border-red-500/20' }
  if (qty < 5)  return { label: 'Low Stock', cls: 'bg-amber-500/10 text-amber-400 border-amber-500/20' }
  return { label: 'In Stock', cls: 'bg-green-500/10 text-green-400 border-green-500/20' }
}
</script>

<template>
  <AdminLayout title="Products">
    <template #actions>
      <button @click="openCreate" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition-colors">
        + Add Product
      </button>
    </template>

    <div class="bg-gray-900 border border-white/[0.06] rounded-xl overflow-hidden">
      <div class="px-5 py-4 border-b border-white/[0.06] flex items-center gap-3">
        <input
          v-model="search"
          placeholder="Search name or SKU…"
          class="px-3 py-2 bg-gray-800 border border-white/[0.08] focus:border-indigo-500 rounded-lg text-white text-xs outline-none w-52 transition-colors"
        />
        <select
          v-model="categoryId"
          class="px-3 py-2 bg-gray-800 border border-white/[0.08] rounded-lg text-white text-xs outline-none cursor-pointer"
        >
          <option value="">All Categories</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
        <span class="ml-auto text-xs text-gray-500">{{ products.total }} products</span>
      </div>

      <table class="w-full">
        <thead>
          <tr class="border-b border-white/[0.06]">
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Product</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">SKU</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Category</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Price</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            <th class="text-left px-5 py-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="product in products.data"
            :key="product.id"
            class="border-b border-white/[0.04] hover:bg-white/[0.02]"
          >
            <td class="px-5 py-3 text-sm font-medium text-white">{{ product.name }}</td>
            <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ product.sku }}</td>
            <td class="px-5 py-3 text-xs text-gray-400">{{ product.category?.name }}</td>
            <td class="px-5 py-3 font-mono text-sm text-white">${{ Number(product.price).toFixed(2) }}</td>
            <td class="px-5 py-3 text-sm" :class="product.stock_quantity === 0 ? 'text-red-400' : product.stock_quantity < 5 ? 'text-amber-400' : 'text-white'">
              {{ product.stock_quantity }}
            </td>
            <td class="px-5 py-3">
              <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="stockBadge(product.stock_quantity).cls">
                {{ stockBadge(product.stock_quantity).label }}
              </span>
            </td>
            <td class="px-5 py-3">
              <div class="flex gap-1.5">
                <button @click="openEdit(product)" class="px-2.5 py-1 text-xs bg-white/[0.04] hover:bg-white/[0.08] text-gray-300 rounded-md transition-colors border border-white/[0.06]">Edit</button>
                <button @click="deleteProduct(product)" class="px-2.5 py-1 text-xs bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-md transition-colors border border-red-500/20">Delete</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="px-5 py-3 border-t border-white/[0.06] flex items-center justify-between">
        <span class="text-xs text-gray-500">Page {{ products.current_page }} of {{ products.last_page }}</span>
        <div class="flex gap-1.5">
          <a
            v-if="products.prev_page_url"
            :href="products.prev_page_url"
            class="px-3 py-1.5 text-xs bg-white/[0.04] hover:bg-white/[0.08] text-gray-300 rounded-lg border border-white/[0.06] transition-colors"
          >← Prev</a>
          <a
            v-if="products.next_page_url"
            :href="products.next_page_url"
            class="px-3 py-1.5 text-xs bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition-colors"
          >Next →</a>
        </div>
      </div>
    </div>

    <!-- MODAL -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4" @click.self="showModal = false">
        <div class="bg-gray-900 border border-white/[0.08] rounded-2xl p-7 w-full max-w-md">
          <h2 class="text-base font-semibold text-white mb-1">{{ editingProduct ? 'Edit Product' : 'Add Product' }}</h2>
          <p class="text-xs text-gray-500 mb-6">{{ editingProduct ? 'Update the product details below' : 'Fill in the details to add a new product' }}</p>

          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="block text-xs text-gray-400 mb-1.5 uppercase tracking-wide">Product Name</label>
              <input v-model="form.name" type="text" required placeholder="e.g. Wireless Keyboard"
                class="w-full px-3.5 py-2.5 bg-gray-800 border rounded-lg text-white text-sm outline-none transition-colors"
                :class="form.errors.name ? 'border-red-500' : 'border-white/[0.08] focus:border-indigo-500'"
              />
              <p v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs text-gray-400 mb-1.5 uppercase tracking-wide">SKU</label>
                <input v-model="form.sku" type="text" required placeholder="e.g. EL-1042"
                  class="w-full px-3.5 py-2.5 bg-gray-800 border rounded-lg text-white text-sm font-mono outline-none transition-colors"
                  :class="form.errors.sku ? 'border-red-500' : 'border-white/[0.08] focus:border-indigo-500'"
                />
                <p v-if="form.errors.sku" class="mt-1 text-xs text-red-400">{{ form.errors.sku }}</p>
              </div>
              <div>
                <label class="block text-xs text-gray-400 mb-1.5 uppercase tracking-wide">Category</label>
                <select v-model="form.category_id" required
                  class="w-full px-3.5 py-2.5 bg-gray-800 border border-white/[0.08] focus:border-indigo-500 rounded-lg text-white text-sm outline-none transition-colors"
                  :class="form.errors.category_id ? 'border-red-500' : 'border-white/[0.08]'"
                >
                  <option value="">Select…</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <p v-if="form.errors.category_id" class="mt-1 text-xs text-red-400">{{ form.errors.category_id }}</p>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs text-gray-400 mb-1.5 uppercase tracking-wide">Price ($)</label>
                <input v-model="form.price" type="number" step="0.01" min="0" required
                  class="w-full px-3.5 py-2.5 bg-gray-800 border border-white/[0.08] focus:border-indigo-500 rounded-lg text-white text-sm outline-none"
                  :class="form.errors.price ? 'border-red-500' : ''"
                />
                <p v-if="form.errors.price" class="mt-1 text-xs text-red-400">{{ form.errors.price }}</p>
              </div>
              <div>
                <label class="block text-xs text-gray-400 mb-1.5 uppercase tracking-wide">Stock Qty</label>
                <input v-model="form.stock_quantity" type="number" min="0" required
                  class="w-full px-3.5 py-2.5 bg-gray-800 border border-white/[0.08] focus:border-indigo-500 rounded-lg text-white text-sm outline-none"
                  :class="form.errors.stock_quantity ? 'border-red-500' : ''"
                />
                <p v-if="form.errors.stock_quantity" class="mt-1 text-xs text-red-400">{{ form.errors.stock_quantity }}</p>
              </div>
            </div>

            <div class="flex gap-2 justify-end pt-2">
              <button type="button" @click="showModal = false" class="px-4 py-2 text-xs text-gray-400 bg-white/[0.04] hover:bg-white/[0.08] border border-white/[0.06] rounded-lg transition-colors">
                Cancel
              </button>
              <button type="submit" :disabled="form.processing" class="px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 rounded-lg transition-colors">
                {{ form.processing ? 'Saving...' : editingProduct ? 'Save Changes' : 'Add Product' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>
  </AdminLayout>
</template>
