<template>
  <div class="space-y-8 pb-20">
    <div class="flex items-center justify-between border-b border-slate-200 pb-8 uppercase tracking-tighter">
      <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Vue Matrice 2x15</h1>
        <p class="mt-1 text-slate-500 font-bold text-xs uppercase tracking-widest italic">Placement automatique et débordement (Spillover)</p>
      </div>
      <div v-if="!loading" class="flex items-center gap-3">
         <select class="rounded-xl border-2 border-slate-200 bg-white px-4 py-2 text-xs font-black uppercase outline-none focus:border-[#A3E635]">
            <option>Niveau 1-5</option>
            <option>Niveau 6-10</option>
            <option>Niveau 11-15</option>
         </select>
         <Button class="bg-[#1E293B] text-white text-[10px] font-black uppercase" @click="fetchMatrix">Actualiser</Button>
      </div>
    </div>

    <!-- Grille de la Matrice -->
    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#A3E635] mx-auto"></div>
    </div>
    <div v-else class="space-y-12">
       <div v-for="level in matrixLevels" :key="level.level" class="flex flex-col items-center">
          <div class="mb-4 inline-block rounded-full bg-slate-100 px-4 py-1 text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 border border-slate-200">
             Niveau {{ level.level }}
          </div>
          
          <div class="flex justify-center flex-wrap gap-8">
             <div v-for="(member, idx) in level.members" :key="member ? member.id : idx" class="relative group">
                <!-- Node Card -->
                <div :class="['w-32 rounded-2xl border-2 p-3 shadow-lg transition-all cursor-pointer', member ? 'bg-white border-slate-100 hover:border-[#A3E635] hover:scale-105' : 'bg-slate-50 border-dashed border-slate-200 opacity-50']">
                   <div :class="['mb-2 flex h-8 w-8 items-center justify-center rounded-lg', member ? 'bg-slate-50 text-[#1E293B] group-hover:bg-[#A3E635]/10' : 'bg-slate-100 text-slate-300']">
                      <UserIcon :size="18" />
                   </div>
                   <p class="text-[9px] font-black text-slate-900 uppercase truncate">{{ member ? member.pseudo : 'Vide' }}</p>
                   <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ member ? member.rang_actuel : 'Position libre' }}</p>
                </div>
             </div>
          </div>
       </div>
    </div>

    <!-- Info Box -->
    <Card class="bg-blue-600 text-white border-none shadow-xl shadow-blue-200">
       <div class="flex items-center gap-6">
          <div class="rounded-2xl bg-white/20 p-4">
             <InfoIcon :size="24" />
          </div>
          <div>
             <h4 class="text-sm font-black uppercase tracking-widest">Fonctionnement</h4>
             <p class="text-xs font-medium text-white/80 leading-relaxed mt-1">
                La matrice LiveGood se remplit de gauche à droite, niveau par niveau. 
                Les membres peuvent être placés par vous, ou par vos parrains (Débordement).
                Chaque membre actif dans votre matrice vous rapporte <strong>0.25 $ / mois</strong>.
             </p>
          </div>
       </div>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { User as UserIcon, Info as InfoIcon } from 'lucide-vue-next';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';

const matrixLevels = ref<any[]>([]);
const loading = ref(true);

const fetchMatrix = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/genealogy/matrix');
        matrixLevels.value = response.data.levels;
    } catch (error) {
        console.error("Error fetching matrix", error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchMatrix);
</script>

