<template>
  <div class="space-y-8 animate-in fade-in duration-500">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase text-sm">Webinaires et Formations</h1>
      <p class="mt-2 text-slate-500 font-medium tracking-tight italic uppercase text-[10px]">Restez informé des dernières sessions Live</p>
    </div>

    <Card v-if="loading" class="animate-pulse py-20 text-center">
       <div class="h-12 w-12 rounded-full border-b-2 border-indigo-500 mx-auto animate-spin" />
    </Card>
    <div v-else-if="webinars" class="grid grid-cols-1 gap-8 lg:grid-cols-2">
       <Card class="bg-gradient-to-br from-indigo-600 to-blue-700 text-white border-none">
          <div class="flex flex-col h-full">
             <div class="flex items-center gap-2 mb-4">
                <div class="rounded-full bg-red-500 h-3 w-3 animate-pulse shadow-lg shadow-red-500/50" />
                <span class="text-[10px] font-black uppercase tracking-widest">{{ webinars.current.status }}</span>
             </div>
             <h3 class="text-2xl font-black leading-tight mb-4 uppercase">{{ webinars.current.title }}</h3>
             <p class="text-indigo-100 font-medium text-sm mb-8 leading-relaxed">
                {{ webinars.current.description }}
             </p>
             <div class="mt-auto">
                <Button class="bg-[#A3E635] text-[#1E293B] font-black uppercase text-[10px] tracking-[0.2em] w-full py-4">
                   Rejoindre Zoom
                </Button>
             </div>
          </div>
       </Card>

       <Card>
          <h3 class="text-lg font-black text-[#1E293B] uppercase tracking-tight mb-6">Replays Récents</h3>
          <div class="space-y-6">
             <div v-for="replay in webinars.replays" :key="replay.id" class="flex items-center gap-4 group cursor-pointer border-b border-slate-50 pb-4">
                <div class="h-16 w-24 bg-slate-900 rounded-xl flex items-center justify-center text-[#A3E635] shrink-0 group-hover:scale-105 transition-transform overflow-hidden relative">
                    <PlayIcon :size="24" class="relative z-10" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent" />
                </div>
                <div>
                   <p class="text-xs font-black text-[#1E293B] uppercase tracking-tight mb-1">{{ replay.title }}</p>
                   <p class="text-[10px] font-bold text-slate-400">Date: {{ replay.date }} • {{ replay.duration }}</p>
                </div>
             </div>
          </div>
       </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Play as PlayIcon } from 'lucide-vue-next';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';

const webinars = ref<any>(null);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/training/webinars');
        webinars.value = response.data;
    } catch (error) {
        console.error("Error fetching webinars", error);
    } finally {
        loading.value = false;
    }
});
</script>

