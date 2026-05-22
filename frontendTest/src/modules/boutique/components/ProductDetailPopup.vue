<template>
  <transition name="fade">
    <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="$emit('close')">
      <div class="bg-white dark:bg-gray-800 w-full max-w-4xl max-h-[90vh] rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row animate-scaleIn">
        <!-- Close Button -->
        <button @click="$emit('close')" class="absolute top-6 right-6 z-10 w-10 h-10 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white transition-all">
          <i class="fas fa-times"></i>
        </button>

        <!-- Product Image -->
        <div class="w-full md:w-1/2 h-64 md:h-auto relative">
          <img :src="product.image" class="w-full h-full object-cover">
          <div class="absolute top-4 left-4 flex flex-col gap-2">
            <span v-if="product.badge" class="px-4 py-1.5 bg-blue-600 text-white text-xs font-black rounded-xl uppercase tracking-widest shadow-lg">
              {{ product.badge }}
            </span>
          </div>
        </div>

        <!-- Product Info -->
        <div class="w-full md:w-1/2 p-8 md:p-12 overflow-y-auto">
          <div class="flex items-center gap-2 mb-4">
            <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-lg uppercase tracking-wider">
              {{ product.category }}
            </span>
            <div class="flex text-yellow-400 text-xs">
              <i class="fas fa-star" v-for="i in 5" :key="i"></i>
            </div>
            <span class="text-theme-muted text-[10px] font-bold">(124 avis)</span>
          </div>

          <h2 class="text-3xl font-black text-theme leading-tight mb-4">
            {{ product.name }}
          </h2>
          
          <p class="text-theme-muted leading-relaxed mb-8">
            {{ product.desc }} Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
          </p>

          <div class="bg-[var(--background)] rounded-2xl p-6 mb-8 border border-[var(--border)]">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-black text-theme-muted uppercase tracking-widest opacity-60">Prix Membre</span>
              <span class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ product.price }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-black text-theme-muted uppercase tracking-widest opacity-60">Prix Public</span>
              <span class="text-xl font-bold text-theme-muted line-through">{{ product.oldPrice }}</span>
            </div>
            <div class="mt-4 pt-4 border-t border-[var(--border)]">
              <p class="text-[10px] text-green-600 dark:text-green-400 font-bold uppercase">Économisez {{ calculateSavings }} en devenant membre !</p>
            </div>
          </div>

          <div class="flex gap-4">
            <button @click="handleAddToCart" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white h-14 rounded-2xl font-black transition-all shadow-xl shadow-blue-500/20 active:scale-95 flex items-center justify-center gap-3">
              <i class="fas fa-shopping-cart"></i>
              Ajouter au panier
            </button>
            <button @click="handleToggleFavorite" :class="['w-14 h-14 rounded-2xl border-2 flex items-center justify-center transition-all active:scale-95', isFavorite ? 'bg-red-50 border-red-100 text-red-500' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-400']">
              <i :class="[isFavorite ? 'fas' : 'far', 'fa-heart text-xl']"></i>
            </button>
          </div>

          <div class="mt-8 flex items-center gap-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
            <div class="flex items-center gap-2">
              <i class="fas fa-truck text-blue-500"></i>
              <span>Expédition 24/48h</span>
            </div>
            <div class="flex items-center gap-2">
              <i class="fas fa-shield-alt text-blue-500"></i>
              <span>Garantie 90 jours</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '@/stores/cartStore'

const props = defineProps({
  product: Object,
  show: Boolean
})

const emit = defineEmits(['close'])
const cartStore = useCartStore()

const isFavorite = computed(() => cartStore.isFavorite(props.product.id))

const calculateSavings = computed(() => {
  const publicPrice = parseFloat(props.product.oldPrice.replace('$', ''))
  const memberPrice = parseFloat(props.product.price.replace('$', ''))
  return (publicPrice - memberPrice).toFixed(2) + '$'
})

const handleAddToCart = () => {
  cartStore.addToCart(props.product)
}

const handleToggleFavorite = () => {
  cartStore.toggleFavorite(props.product)
}
</script>

<style scoped>
.animate-scaleIn {
  animation: scaleIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
