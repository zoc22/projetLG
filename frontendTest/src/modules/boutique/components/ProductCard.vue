<template>
  <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
    <div class="relative h-56 overflow-hidden bg-gray-100 dark:bg-gray-700 cursor-pointer" @click="$emit('view', product)">
      <img :src="product.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
      <div class="absolute top-3 left-3 flex flex-col gap-2">
        <span v-if="product.badge" class="px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-lg uppercase tracking-wider shadow-lg">
          {{ product.badge }}
        </span>
        <span class="px-3 py-1 bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-white text-[10px] font-bold rounded-lg uppercase tracking-wider shadow-sm backdrop-blur-sm">
          {{ product.category }}
        </span>
      </div>
      <button 
        @click.stop="cartStore.toggleFavorite(product)"
        class="absolute top-3 right-3 w-10 h-10 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center transition-colors shadow-lg opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 duration-300"
        :class="cartStore.isFavorite(product.id) ? 'text-red-500' : 'text-gray-400 hover:text-red-500'"
      >
        <i :class="[cartStore.isFavorite(product.id) ? 'fas' : 'far', 'fa-heart']"></i>
      </button>
    </div>

    <div class="p-6">
      <div class="flex items-center justify-between mb-2">
        <h3 @click="$emit('view', product)" class="font-bold text-theme text-lg leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors cursor-pointer line-clamp-1">{{ product.name }}</h3>
        <div class="flex items-center gap-1 text-[10px] text-theme-muted font-bold">
          <i class="fas fa-eye text-blue-500"></i>
          <span>{{ cartStore.getViewCount(product.id) }}</span>
        </div>
      </div>
      <p class="text-sm text-theme-muted mt-2 line-clamp-2 leading-relaxed">{{ product.desc }}</p>
      
      <div class="mt-8 flex items-center justify-between border-t border-[var(--border)] pt-4">
        <div>
          <p class="text-[10px] text-theme-muted uppercase font-black tracking-widest opacity-60">Prix Membre</p>
          <p class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ product.price }}</p>
        </div>
        <div class="text-right">
          <p class="text-[10px] text-theme-muted uppercase font-black tracking-widest opacity-60">Prix Public</p>
          <p class="text-lg font-bold text-theme-muted line-through">{{ product.oldPrice }}</p>
        </div>
      </div>

      <div class="mt-6 flex gap-3">
        <button 
          @click="cartStore.addToCart(product)"
          class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xs shadow-lg shadow-blue-500/20 transition-all active:scale-95"
        >
          <i class="fas fa-shopping-cart mr-2"></i> Ajouter
        </button>
        <button 
          @click="$emit('view', product)"
          class="w-12 h-12 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-200 rounded-2xl flex items-center justify-center transition-all active:scale-95"
        >
          <i class="fas fa-eye"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cartStore'

const cartStore = useCartStore()

defineProps({
  product: Object
})

defineEmits(['view'])
</script>
