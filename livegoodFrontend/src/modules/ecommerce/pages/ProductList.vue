<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3 uppercase">
        <ShoppingBagIcon :size="32" class="text-amber-500" /> Boutique
      </h1>
      <p class="mt-2 text-slate-500 font-medium font-sans">Produits de haute qualité à prix d'usine</p>
    </div>

    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-500 mx-auto"></div>
    </div>

    <div v-else class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
       <Card v-for="product in products" :key="product.id" class="group flex flex-col p-0 overflow-hidden shadow-xl shadow-slate-100 hover:scale-[1.02] transition-all">
          <div class="h-64 bg-slate-100 flex items-center justify-center relative">
             <ShoppingBagIcon :size="64" class="text-slate-200" />
             <div class="absolute top-4 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">-{{ Math.round((1 - product.price/product.publicPrice) * 100) }}% Économie</div>
          </div>
          <div class="p-6 flex-1">
             <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-2">{{ product.name }}</h3>
             <p class="text-xs text-slate-500 font-medium mb-6">{{ product.description }}</p>
             <div class="flex items-center justify-between mt-auto">
                <div>
                   <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest line-through">{{ product.publicPrice.toFixed(2) }} $</p>
                   <p class="text-xl font-black text-[#A3E635]">{{ product.price.toFixed(2) }} $ <span class="text-[10px] uppercase text-slate-400">Membre</span></p>
                </div>
                <Button size="sm" @click="addToCart(product.id)" :disabled="addingId === product.id" :class="successId === product.id ? 'bg-green-500 text-white border-green-500 hover:bg-green-600' : ''">
                  <CheckIcon v-if="successId === product.id" class="h-4 w-4" />
                  <span v-else>{{ addingId === product.id ? '...' : 'Ajouter' }}</span>
                </Button>
             </div>
          </div>
       </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { ShoppingBag as ShoppingBagIcon, Check as CheckIcon } from 'lucide-vue-next';
import Card from '@/src/components/ui/Card.vue';
import Button from '@/src/components/ui/Button.vue';

const products = ref<any[]>([]);
const loading = ref(true);
const addingId = ref<string | null>(null);
const successId = ref<string | null>(null);

const addToCart = async (id: string) => {
    addingId.value = id;
    try {
        // Simulation d'ajout au panier
        await new Promise(resolve => setTimeout(resolve, 800));
        successId.value = id;
        setTimeout(() => successId.value = null, 2000);
    } finally {
        addingId.value = null;
    }
};

onMounted(async () => {
    try {
        const response = await axios.get('/api/shop/products');
        products.value = response.data;
    } catch (error) {
        console.error("Error fetching products", error);
    } finally {
        loading.value = false;
    }
});
</script>
