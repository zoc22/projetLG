<template>
  <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-700">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase text-sm">Conditions de Rang</h1>
      <p class="mt-2 text-slate-500 font-medium font-sans">Suivez votre progression vers le sommet du plan de rémunération.</p>
    </div>

    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#A3E635] mx-auto"></div>
    </div>

    <div v-else class="grid grid-cols-1 gap-8 lg:grid-cols-2">
       <Card v-for="(req, rankName) in rankData?.requirements" :key="rankName" class="border-t-4 shadow-xl shadow-slate-100" :style="{ borderTopColor: getRankColor(rankName as string) }">
          <div class="mb-6 flex items-center justify-between">
             <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ rankName }}</h3>
             <div v-if="rankData.currentRank === rankName.toUpperCase()" class="rounded-full bg-[#A3E635] px-3 py-1 text-[10px] font-black text-[#1E293B] uppercase tracking-widest border border-green-200">
                Rang Actuel
             </div>
          </div>

          <div class="space-y-6">
             <div v-for="(condition, key) in req" :key="key" class="space-y-3">
                <div class="flex items-center justify-between font-bold">
                   <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">{{ formatKey(key as string) }}</p>
                   <p class="text-xs font-black text-slate-900 bg-slate-100 px-2 py-1 rounded">{{ (condition as any).current }} / {{ (condition as any).required }}</p>
                </div>
                <div class="h-3 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100 shadow-inner">
                   <div 
                    :class="['h-full transition-all duration-1000 shadow-sm', (condition as any).met ? 'bg-green-500' : 'bg-amber-400']"
                    :style="{ width: Math.min(((condition as any).current / (condition as any).required) * 100, 100) + '%' }"
                   />
                </div>
             </div>
          </div>
       </Card>
    </div>

    <!-- Info Bonus -->
    <Card class="bg-slate-900 text-white border-none shadow-2xl shadow-slate-300">
       <div class="flex flex-col md:flex-row items-center gap-8 py-4">
          <div class="rounded-2xl bg-white/10 p-6 backdrop-blur-md border border-white/5">
            <TrophyIcon :size="48" class="text-[#A3E635]" />
          </div>
          <div class="text-center md:text-left">
             <h4 class="text-2xl font-black uppercase tracking-widest mb-2">Bonus Leaders Diamant</h4>
             <p class="text-slate-400 font-medium leading-relaxed mb-6 max-w-xl">
               Les Diamants partagent 2% des ventes mondiales totales de l'entreprise chaque mois. C'est le but ultime de tout affilié LiveGood.
             </p>
             <Button class="bg-[#A3E635] text-slate-900 font-black uppercase text-[10px] tracking-widest px-8">
                En savoir plus sur le plan de carrière
             </Button>
          </div>
       </div>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Trophy as TrophyIcon } from 'lucide-vue-next';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';

const rankData = ref<any>(null);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/rank/my-rank');
        rankData.value = response.data;
    } catch (error) {
        console.error("Error fetching rank", error);
    } finally {
        loading.value = false;
    }
});

const getRankColor = (rank: string) => {
    switch (rank.toLowerCase()) {
        case 'bronze': return '#B45309';
        case 'silver': return '#94A3B8';
        case 'gold': return '#EAB308';
        default: return '#1E293B';
    }
};

const formatKey = (key: string) => {
    switch (key) {
        case 'activeDirects': return 'Membres Directs Actifs';
        case 'totalTeam': return 'Total de l\'Organisation';
        default: return key;
    }
};
</script>

