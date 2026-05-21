<template>
  <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-700">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between border-b border-slate-200 pb-8">
      <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
          <WalletIcon :size="32" class="text-[#A3E635]" /> Mes Gains
        </h1>
        <p class="mt-2 text-slate-500 font-medium tracking-tight">Suivi détaillé de vos commissions et bonus</p>
      </div>
      <div v-if="loading" class="animate-pulse">
         <div class="h-12 w-48 bg-slate-100 rounded-2xl"></div>
      </div>
      <div v-else-if="earnings" class="flex items-center gap-4">
        <div class="bg-white px-6 py-3 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Payé</p>
            <p class="text-2xl font-black text-[#1E293B] leading-none">{{ earnings.lifeTimeEarnings.toFixed(2) }} $</p>
        </div>
        <Button class="bg-[#1E293B] text-white py-4 px-6 font-black uppercase text-xs tracking-widest">
           Retirer mes gains
        </Button>
      </div>
    </div>

    <!-- Commissions Breakdown -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <Card v-for="bonus in bonusTypes" :key="bonus.title" class="group hover:border-[#A3E635] transition-all cursor-default">
         <div class="flex items-start justify-between">
            <div :class="['p-3 rounded-xl', bonus.bg, bonus.color]">
               <component :is="bonus.icon" :size="20" />
            </div>
            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Type: {{ bonus.type }}</span>
         </div>
         <h3 class="mt-6 text-sm font-black text-[#192131] uppercase tracking-tight">{{ bonus.title }}</h3>
         <p class="mt-1 text-2xl font-black text-[#1E293B]">{{ bonus.amount }} $</p>
         <div class="mt-4 flex items-center gap-2">
            <div class="h-1 flex-1 bg-slate-100 rounded-full overflow-hidden">
               <div :class="['h-full rounded-full', bonus.line]" :style="{ width: bonus.percent + '%' }" />
            </div>
            <span class="text-[10px] font-bold text-slate-400">{{ bonus.percent }}%</span>
         </div>
      </Card>
    </div>

    <!-- Historique des Paiements -->
    <div class="space-y-4">
       <h2 class="text-xl font-black text-[#1E293B] uppercase text-sm tracking-widest">Historique des périodes</h2>
       <Card class="p-0 overflow-hidden border-none shadow-xl shadow-slate-200/50">
          <table class="w-full text-left">
             <thead class="bg-slate-50 border-b border-slate-100 italic uppercase">
                <tr class="text-[10px] font-black text-slate-400">
                   <th class="px-6 py-5">Période</th>
                   <th class="px-6 py-5">Fast Start</th>
                   <th class="px-6 py-5 text-center">Matrice</th>
                   <th class="px-6 py-5 text-center">Total</th>
                   <th class="px-6 py-5 text-right">Statut</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-slate-50 font-sans">
                <tr v-for="period in history" :key="period.range" class="hover:bg-slate-50/40 transition-colors group">
                   <td class="px-6 py-5">
                      <div class="flex flex-col">
                         <span class="text-sm font-black text-[#1E293B] uppercase tracking-tight">{{ period.range }}</span>
                         <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Payé le {{ period.date }}</span>
                      </div>
                   </td>
                   <td class="px-6 py-5 text-sm font-bold text-slate-600">{{ period.fs }} $</td>
                   <td class="px-6 py-5 text-center text-sm font-bold text-slate-600">{{ period.matrix }} $</td>
                   <td class="px-6 py-5 text-center">
                      <span class="text-lg font-black text-[#1E293B]">{{ period.total }} $</span>
                   </td>
                   <td class="px-6 py-5 text-right">
                      <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full border border-emerald-100 shadow-sm">
                         <CheckCircleIcon :size="12" />
                         <span class="text-[10px] font-black uppercase tracking-widest">Validé</span>
                      </div>
                   </td>
                </tr>
             </tbody>
          </table>
       </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import api from '@/core/api/client';
import { 
  Wallet as WalletIcon, 
  Zap as ZapIcon, 
  Grid2X2 as GridIcon, 
  Users as UsersIcon,
  CheckCircle as CheckCircleIcon 
} from 'lucide-vue-next';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';

const earnings = ref<any>(null);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await api.get('/commissions/earnings');
        earnings.value = response.data;
    } catch (error) {
        console.error("Error fetching earnings", error);
    } finally {
        loading.value = false;
    }
});

const bonusTypes = computed(() => {
    if (!earnings.value) return [];
    const total = earnings.value.lifeTimeEarnings;
    return [
      { title: "Bonus Rapide (Fast Start)", amount: "850.00", type: "HEBDO", icon: ZapIcon, color: "text-amber-600", bg: "bg-amber-50", line: "bg-amber-400", percent: 58 },
      { title: "Bonus de Matrice", amount: "420.50", type: "MENSUEL", icon: GridIcon, color: "text-blue-600", bg: "bg-blue-50", line: "bg-blue-400", percent: 29 },
      { title: "Matching Bonus", amount: "179.50", type: "MENSUEL", icon: UsersIcon, color: "text-[#A3E635]", bg: "bg-green-50", line: "bg-[#A3E635]", percent: 13 },
    ];
});

const history = computed(() => earnings.value?.history || []);
</script>

