<template>
  <div class="section-content max-w-7xl mx-auto pb-20 px-4 bg-transparent">
    <div class="flex items-center justify-between mb-12">
      <h1 class="text-4xl font-black text-theme flex items-center gap-4">
        <i class="fas fa-shopping-bag text-blue-600"></i>
        Mon Panier
      </h1>
      <button @click="$router.push({ name: 'boutique' })" class="text-blue-600 font-bold hover:underline flex items-center gap-2">
        <i class="fas fa-arrow-left"></i> Retour à la boutique
      </button>
    </div>

    <div v-if="cartStore.cartItems.length === 0" class="py-32 text-center bg-[var(--card)] rounded-[3rem] border border-[var(--border)] shadow-xl">
      <div class="w-28 h-28 bg-[var(--background)] rounded-full flex items-center justify-center mx-auto mb-8 text-gray-300">
        <i class="fas fa-shopping-cart text-4xl"></i>
      </div>
      <h3 class="text-3xl font-black text-theme mb-4">Votre panier est vide</h3>
      <p class="text-theme-muted max-w-sm mx-auto leading-relaxed mb-10">Il semble que vous n'ayez pas encore ajouté de produits à votre panier.</p>
      <button @click="$router.push({ name: 'boutique' })" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 shadow-xl shadow-blue-500/20 transition-all active:scale-95">Explorer la boutique</button>
    </div>

    <div v-else class="grid lg:grid-cols-3 gap-12">
      <div class="lg:col-span-2 space-y-6">
        <div v-for="item in cartStore.cartItems" :key="item.id" class="flex flex-col md:flex-row gap-6 p-6 bg-[var(--card)] rounded-3xl border border-[var(--border)] shadow-sm hover:shadow-md transition-all">
          <div class="w-full md:w-32 h-32 rounded-2xl overflow-hidden shrink-0">
            <img :src="item.image" class="w-full h-full object-cover">
          </div>
          <div class="flex-1 flex flex-col justify-between py-1">
            <div>
              <div class="flex items-center justify-between mb-2">
                <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[8px] font-black rounded-lg uppercase tracking-widest">{{ item.category }}</span>
                <button @click="cartStore.removeFromCart(item.id)" class="text-theme opacity-40 hover:opacity-100 hover:text-red-500 transition-colors">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
              <h4 class="text-xl font-black text-theme mb-1">{{ item.name }}</h4>
              <p class="text-xs text-theme-muted line-clamp-1 opacity-80">{{ item.desc }}</p>
            </div>
            <div class="flex items-center justify-between mt-6">
              <div class="flex items-center gap-4 bg-[var(--background)] p-1.5 rounded-xl border border-[var(--border)]">
                <button @click="item.quantity > 1 ? item.quantity-- : null" class="w-8 h-8 rounded-lg bg-[var(--card)] flex items-center justify-center text-theme shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700">-</button>
                <span class="font-bold text-sm w-4 text-center text-theme">{{ item.quantity }}</span>
                <button @click="item.quantity++" class="w-8 h-8 rounded-lg bg-[var(--card)] flex items-center justify-center text-theme shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700">+</button>
              </div>
              <p class="text-xl font-black text-blue-600 dark:text-blue-400">{{ item.price }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-1">
        <div class="bg-[var(--card)] p-8 rounded-[2.5rem] border border-[var(--border)] shadow-xl sticky top-6">
          <h3 class="text-xl font-black text-theme mb-8 uppercase tracking-widest">Récapitulatif</h3>
          <div class="space-y-4 mb-8">
            <div class="flex justify-between text-sm font-bold text-theme-muted opacity-80">
              <span>Sous-total ({{ cartStore.cartCount }} articles)</span>
              <span>{{ cartStore.cartTotal }}$</span>
            </div>
            <div class="flex justify-between text-sm font-bold text-theme-muted opacity-80">
              <span>Livraison</span>
              <span class="text-green-600">GRATUIT</span>
            </div>
            <div class="pt-4 border-t border-[var(--border)] flex justify-between">
              <span class="text-lg font-black text-theme">Total</span>
              <span class="text-2xl font-black text-blue-600">{{ cartStore.cartTotal }}$</span>
            </div>
          </div>
          <button class="w-full py-5 bg-blue-600 text-white rounded-2xl font-black shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all active:scale-95 mb-4">
            Passer la commande
          </button>
          <p class="text-[10px] text-center text-theme-muted font-bold uppercase tracking-widest">Paiement 100% sécurisé</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cartStore'

const cartStore = useCartStore()
</script>
