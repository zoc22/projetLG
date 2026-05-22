<template>
  <div class="section-content max-w-7xl mx-auto pb-20 px-4 bg-transparent">
    <!-- Scrolling Message Banner -->
    <div class="bg-blue-600 text-white py-2 overflow-hidden rounded-xl mb-6 relative shadow-lg shadow-blue-500/20">
      <div class="animate-scroll whitespace-nowrap font-bold text-sm uppercase tracking-widest">
        🚀 Nouveaux stocks arrivés ! - Livraison gratuite dès 100$ d'achat - Partagez votre lien et gagnez des commissions - LiveGood : La révolution du bien-être à prix coûtant - 🚀
      </div>
    </div>

    <!-- Marketplace Header / Hero -->
    <div class="relative rounded-3xl overflow-hidden mb-12 h-64 md:h-[450px] group shadow-2xl border border-white/10">
      <img src="https://images.unsplash.com/photo-1512069772995-ec65ed45afd6?auto=format&fit=crop&w=1200&q=80" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
      <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/50 to-transparent flex flex-col justify-center p-8 md:p-16">
        <div class="max-w-2xl">
          <span class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-black rounded-full uppercase tracking-widest mb-6 inline-block shadow-lg border border-blue-400/30">Offre Exclusive Membre</span>
          <h1 class="text-4xl md:text-6xl font-black text-white leading-tight drop-shadow-lg">La Marketplace <br><span class="text-blue-500">LiveGood</span></h1>
          <p class="text-gray-200 mt-6 text-sm md:text-xl max-w-lg leading-relaxed drop-shadow-md">Accédez à des produits de qualité pharmaceutique sans les marges exorbitantes du commerce traditionnel. Économisez jusqu'à 80% !</p>
          <div class="flex flex-wrap gap-4 mt-10">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-2xl font-black transition-all shadow-xl shadow-blue-500/40 active:scale-95 flex items-center gap-2">
              <i class="fas fa-rocket"></i> Explorer tout
            </button>
            <button class="bg-white/20 hover:bg-white/30 backdrop-blur-xl text-white border border-white/40 px-10 py-4 rounded-2xl font-black transition-all active:scale-95 flex items-center gap-2">
              <i class="fas fa-fire text-orange-400"></i> Meilleurs ventes
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Product Image Slider (Attention Grabber) -->
    <div class="mb-16 overflow-hidden py-8 bg-[var(--card)] rounded-[2.5rem] border border-[var(--border)] shadow-sm">
      <div class="px-8 mb-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
            <i class="fas fa-star"></i>
          </div>
          <h3 class="text-xl font-black text-theme uppercase tracking-widest">Tendances Actuelles</h3>
        </div>
        <div class="flex gap-2">
          <button class="w-8 h-8 rounded-full border border-[var(--border)] flex items-center justify-center text-theme opacity-40 hover:text-blue-600 transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
          <button class="w-8 h-8 rounded-full border border-[var(--border)] flex items-center justify-center text-theme opacity-40 hover:text-blue-600 transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
        </div>
      </div>
      <div class="relative">
        <div class="animate-scroll-products py-2 px-8">
          <div v-for="(p, idx) in featuredProducts" :key="idx" 
            @click="openProduct(p)"
            class="w-72 rounded-3xl overflow-hidden shadow-lg cursor-pointer hover:shadow-2xl hover:-translate-y-2 transition-all bg-[var(--card)] shrink-0 border border-[var(--border)] group flex flex-col">
            <div class="h-44 relative overflow-hidden">
              <img :src="p.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
              <div class="absolute top-3 left-3">
                <span class="px-2 py-1 bg-blue-600/90 backdrop-blur-md text-white text-[8px] font-black rounded-lg uppercase tracking-widest">{{ p.category }}</span>
              </div>
            </div>
            <div class="p-5 flex-1 flex flex-col">
              <h4 class="text-sm font-black text-theme mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ p.name }}</h4>
              <div class="flex items-center justify-between mt-auto pt-4 border-t border-[var(--border)]">
                <span class="text-blue-600 dark:text-blue-400 font-black">{{ p.price }}</span>
                <div class="flex items-center gap-1 text-[10px] text-theme-muted font-bold">
                  <i class="fas fa-eye"></i>
                  <span>{{ cartStore.getViewCount(p.id) }}</span>
                </div>
              </div>
            </div>
          </div>
          <!-- Repeat for smooth loop -->
          <div v-for="(p, idx) in featuredProducts" :key="'rep-'+idx" 
            @click="openProduct(p)"
            class="w-72 rounded-3xl overflow-hidden shadow-lg cursor-pointer hover:shadow-2xl hover:-translate-y-2 transition-all bg-[var(--card)] shrink-0 border border-[var(--border)] group flex flex-col">
            <div class="h-44 relative overflow-hidden">
              <img :src="p.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
              <div class="absolute top-3 left-3">
                <span class="px-2 py-1 bg-blue-600/90 backdrop-blur-md text-white text-[8px] font-black rounded-lg uppercase tracking-widest">{{ p.category }}</span>
              </div>
            </div>
            <div class="p-5 flex-1 flex flex-col">
              <h4 class="text-sm font-black text-theme mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ p.name }}</h4>
              <div class="flex items-center justify-between mt-auto pt-4 border-t border-[var(--border)]">
                <span class="text-blue-600 dark:text-blue-400 font-black">{{ p.price }}</span>
                <div class="flex items-center gap-1 text-[10px] text-theme-muted font-bold">
                  <i class="fas fa-eye"></i>
                  <span>{{ cartStore.getViewCount(p.id) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Categories and Search - UX IMPROVED: Not sticky to avoid blocking content -->
    <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-12 bg-[var(--card)] p-6 rounded-[2rem] border border-[var(--border)] shadow-xl shadow-gray-200/50 dark:shadow-none">
      <div class="flex flex-wrap items-center gap-3 overflow-x-auto pb-2 scrollbar-hide w-full lg:w-auto">
        <button v-for="cat in categories" :key="cat" 
          @click="activeCategory = cat"
          :class="['px-6 py-3.5 rounded-2xl font-black text-[10px] transition-all whitespace-nowrap uppercase tracking-widest flex items-center gap-2', 
                   activeCategory === cat ? 'bg-blue-600 text-white shadow-xl shadow-blue-500/30 scale-105' : 'bg-[var(--background)] text-theme opacity-60 border border-transparent hover:opacity-100 hover:bg-gray-100 dark:hover:bg-gray-700']">
          <span v-if="activeCategory === cat" class="w-1.5 h-1.5 rounded-full bg-white"></span>
          {{ cat }}
        </button>
      </div>
      <div class="flex items-center gap-4 w-full lg:w-auto">
        <div class="relative flex-1 lg:w-80 group">
          <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-theme opacity-40 group-focus-within:text-blue-500 transition-colors"></i>
          <input v-model="searchQuery" type="text" placeholder="Rechercher un produit..." 
            class="w-full bg-[var(--background)] border-2 border-transparent focus:border-blue-500/50 rounded-2xl py-4 pl-14 pr-4 text-sm text-theme outline-none transition-all placeholder:opacity-40">
        </div>
        <div class="flex gap-3">
          <button @click="navigateTo('favorites')" class="w-14 h-14 rounded-2xl bg-[var(--card)] text-theme opacity-40 hover:opacity-100 hover:text-red-500 border border-[var(--border)] flex items-center justify-center shadow-lg transition-all active:scale-95 relative group">
            <i class="fas fa-heart"></i>
            <span v-if="cartStore.favorites.length > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-4 border-[var(--card)] shadow-lg group-hover:scale-110 transition-transform">
              {{ cartStore.favorites.length }}
            </span>
          </button>
          <button @click="navigateTo('cart')" class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-xl shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all relative group">
            <i class="fas fa-shopping-bag"></i>
            <span v-if="cartStore.cartCount > 0" class="absolute -top-2 -right-2 bg-yellow-400 text-blue-900 text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-4 border-[var(--card)] shadow-lg group-hover:scale-110 transition-transform">
              {{ cartStore.cartCount }}
            </span>
          </button>
        </div>
      </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
      <div v-for="product in filteredProducts" :key="product.id" class="section-content">
        <ProductCard :product="product" @view="openProduct" />
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredProducts.length === 0" class="py-32 text-center bg-white dark:bg-gray-800 rounded-[3rem] border border-dashed border-gray-200 dark:border-gray-700">
      <div class="w-28 h-28 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-8 text-gray-300">
        <i class="fas fa-shopping-basket text-4xl"></i>
      </div>
      <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4">Oups ! Rien ici...</h3>
      <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">Nous n'avons trouvé aucun produit correspondant à vos critères de recherche. Essayez d'autres mots-clés.</p>
      <button @click="resetFilters" class="mt-10 px-10 py-4 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 shadow-xl shadow-blue-500/20 transition-all active:scale-95">Voir tout le catalogue</button>
    </div>

    <!-- Info Banners Slider -->
    <div class="grid md:grid-cols-3 gap-8 mt-24">
      <div v-for="banner in marketplaceBanners" :key="banner.id" 
        :class="['p-10 rounded-[2.5rem] bg-gradient-to-br text-white shadow-2xl hover:scale-[1.03] transition-all cursor-pointer group border border-white/10', banner.bg]">
        <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-8 group-hover:rotate-12 transition-transform shadow-lg border border-white/30">
          <i class="fas fa-shield-heart text-3xl"></i>
        </div>
        <h4 class="text-2xl font-black mb-4 leading-tight">{{ banner.title }}</h4>
        <p class="text-white/80 text-sm leading-relaxed mb-8">{{ banner.text }}</p>
        <div class="flex items-center gap-3 font-black text-[10px] uppercase tracking-widest bg-white/10 w-fit px-4 py-2 rounded-xl backdrop-blur-sm border border-white/20">
          <span>Découvrir</span>
          <i class="fas fa-chevron-right text-[8px] group-hover:translate-x-2 transition-transform"></i>
        </div>
      </div>
    </div>

    <!-- Viewed Recently -->
    <div v-if="cartStore.viewedProducts.length > 0" class="mt-24 bg-white/50 dark:bg-gray-800/20 p-10 rounded-[3rem] border border-gray-100 dark:border-gray-700/50 backdrop-blur-sm">
      <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest mb-10 flex items-center gap-3">
        <i class="fas fa-history text-blue-600"></i>
        Consultés Récemment
      </h3>
      <div class="flex gap-6 overflow-x-auto pb-6 scrollbar-hide">
        <div v-for="p in cartStore.viewedProducts" :key="p.id" 
          @click="openProduct(p)"
          class="w-64 bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-lg shrink-0 cursor-pointer hover:shadow-2xl hover:-translate-y-2 transition-all group">
          <div class="relative overflow-hidden rounded-2xl mb-4">
            <img :src="p.image" class="w-full h-44 object-cover group-hover:scale-110 transition-transform duration-700">
            <div class="absolute bottom-2 right-2 px-2 py-1 bg-white/90 dark:bg-gray-900/90 rounded-lg text-[10px] font-black text-blue-600">{{ p.price }}</div>
          </div>
          <p class="font-bold text-sm text-gray-900 dark:text-white truncate group-hover:text-blue-600 transition-colors">{{ p.name }}</p>
          <div class="flex items-center gap-1 mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
            <i class="fas fa-eye text-blue-500"></i>
            <span>{{ cartStore.getViewCount(p.id) }} vues</span>
          </div>
        </div>
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
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import ProductCard from './components/ProductCard.vue'
import ProductDetailPopup from './components/ProductDetailPopup.vue'
import { productsData, marketplaceBanners } from '@/data/mockProducts'
import { useCartStore } from '@/stores/cartStore'

const router = useRouter()
const cartStore = useCartStore()

const categories = ['Tous', 'Vitamines', 'Nutrition', 'Superfoods', 'Bien-être', 'Beauté', 'Accessoires']
const activeCategory = ref('Tous')
const searchQuery = ref('')
const selectedProduct = ref(null)

const featuredProducts = computed(() => productsData.slice(0, 8))

const filteredProducts = computed(() => {
  return productsData.filter(p => {
    const matchesCat = activeCategory.value === 'Tous' || p.category === activeCategory.value
    const matchesSearch = p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                          p.desc.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesCat && matchesSearch
  })
})

const openProduct = (product) => {
  selectedProduct.value = product
  cartStore.addView(product)
}

const navigateTo = (route) => {
  router.push({ name: route })
}

const resetFilters = () => {
  activeCategory.value = 'Tous'
  searchQuery.value = ''
}
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
