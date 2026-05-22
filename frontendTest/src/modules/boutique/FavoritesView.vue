<template>
  <div class="section-content max-w-7xl mx-auto pb-20 px-4 bg-transparent">
    <div class="flex items-center justify-between mb-12">
      <h1 class="text-4xl font-black text-theme flex items-center gap-4">
        <i class="fas fa-heart text-red-500"></i>
        Mes Favoris
      </h1>
      <button @click="$router.push({ name: 'boutique' })" class="text-blue-600 font-bold hover:underline flex items-center gap-2">
        <i class="fas fa-arrow-left"></i> Retour à la boutique
      </button>
    </div>

    <div v-if="cartStore.favorites.length === 0" class="py-32 text-center bg-[var(--card)] rounded-[3rem] border border-[var(--border)] shadow-xl">
      <div class="w-28 h-28 bg-[var(--background)] rounded-full flex items-center justify-center mx-auto mb-8 text-gray-300">
        <i class="fas fa-heart text-4xl"></i>
      </div>
      <h3 class="text-3xl font-black text-theme mb-4">Aucun favori</h3>
      <p class="text-theme-muted max-w-sm mx-auto leading-relaxed mb-10">Vous n'avez pas encore marqué de produits comme favoris.</p>
      <button @click="$router.push({ name: 'boutique' })" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 shadow-xl shadow-blue-500/20 transition-all active:scale-95">Explorer la boutique</button>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
      <div v-for="product in cartStore.favorites" :key="product.id" class="section-content">
        <ProductCard :product="product" @view="openProduct" />
      </div>
    </div>

    <!-- Product Popup -->
    <ProductDetailPopup 
      v-if="selectedProduct" 
      :product="selectedProduct" 
      :show="!!selectedProduct" 
      @close="selectedProduct = null" 
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useCartStore } from '@/stores/cartStore'
import ProductCard from './components/ProductCard.vue'
import ProductDetailPopup from './components/ProductDetailPopup.vue'

const cartStore = useCartStore()
const selectedProduct = ref(null)

const openProduct = (product) => {
  selectedProduct.value = product
  cartStore.addView(product)
}
</script>
