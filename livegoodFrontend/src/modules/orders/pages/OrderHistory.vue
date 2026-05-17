<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3 uppercase">
        <HistoryIcon :size="32" class="text-slate-400" /> Historique Commandes
      </h1>
      <p class="mt-2 text-slate-500 font-medium italic text-[10px] tracking-widest uppercase">Suivi de vos achats et factures</p>
    </div>
    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-slate-400 mx-auto"></div>
    </div>
    <Card v-else class="p-0 overflow-hidden shadow-xl shadow-slate-100 border-none">
       <table class="w-full text-left">
          <thead class="bg-slate-50 border-b border-slate-100">
             <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                <th class="px-6 py-5">N° Commande</th>
                <th class="px-6 py-5">Date</th>
                <th class="px-6 py-5">Produit</th>
                <th class="px-6 py-5">Total</th>
                <th class="px-6 py-5 text-right">Action</th>
             </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
             <tr v-for="order in orders" :key="order.id" class="hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-5 font-mono text-xs font-bold text-slate-400">#{{ order.id }}</td>
                <td class="px-6 py-5 text-sm font-bold text-slate-900">{{ order.date }}</td>
                <td class="px-6 py-5">
                   <p class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ order.product }}</p>
                </td>
                <td class="px-6 py-5 text-sm font-black text-[#A3E635]">{{ order.total.toFixed(2) }} $</td>
                <td class="px-6 py-5 text-right">
                   <Button variant="ghost" size="sm" class="text-xs font-black uppercase text-slate-400">Détails</Button>
                </td>
             </tr>
          </tbody>
       </table>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { History as HistoryIcon } from 'lucide-vue-next';
import Card from '@/src/components/ui/Card.vue';
import Button from '@/src/components/ui/Button.vue';

const orders = ref<any[]>([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/profile/orders');
        orders.value = response.data;
    } catch (error) {
        console.error("Error fetching orders", error);
    } finally {
        loading.value = false;
    }
});
</script>
