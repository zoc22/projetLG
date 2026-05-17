<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <div class="border-b border-slate-200 pb-8 uppercase font-bold text-xs tracking-widest text-slate-400">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
        <TrendingUpIcon :size="32" class="text-indigo-500" /> Statistiques
      </h1>
      <p class="mt-2 text-slate-500 font-medium tracking-tight italic">Analyse de vos visites et conversions en temps réel</p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
       <Card v-for="s in stats" :key="s.label">
          <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">{{ s.label }}</p>
          <div class="mt-2 flex items-end justify-between">
             <p class="text-3xl font-black text-slate-900">{{ s.value }}</p>
             <span :class="['text-xs font-bold px-2 py-1 rounded bg-slate-50', s.trend > 0 ? 'text-green-600' : 'text-red-600']">
                {{ s.trend > 0 ? '+' : '' }}{{ s.trend }}%
             </span>
          </div>
       </Card>
    </div>

    <Card class="p-0 overflow-hidden shadow-2xl shadow-slate-200/50">
       <div class="bg-slate-900 p-6 flex items-center justify-between text-white">
          <h3 class="text-sm font-black uppercase tracking-widest">Trafic par Source</h3>
          <Button variant="ghost" size="sm" class="text-[#A3E635] font-black text-[10px]">DERNIERS 30 JOURS</Button>
       </div>
       <div class="p-8 space-y-8">
          <div v-for="source in sources" :key="source.name" class="space-y-2">
             <div class="flex items-center justify-between">
                <p class="text-xs font-black text-slate-700 uppercase tracking-tight">{{ source.name }}</p>
                <p class="text-xs font-bold text-slate-400">{{ source.percent }}%</p>
             </div>
             <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                <div :class="['h-full rounded-full', source.color]" :style="{ width: source.percent + '%' }" />
             </div>
          </div>
       </div>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { TrendingUp as TrendingUpIcon } from 'lucide-vue-next';
import Card from '@/src/components/ui/Card.vue';
import Button from '@/src/components/ui/Button.vue';

const stats = ref<any[]>([]);
const sources = ref<any[]>([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/statistics/dashboard');
        stats.value = response.data.summary;
        sources.value = response.data.sources;
    } catch (error) {
        console.error("Error fetching stats", error);
    } finally {
        loading.value = false;
    }
});
</script>
