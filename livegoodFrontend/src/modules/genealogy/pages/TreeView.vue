<template>
  <div class="space-y-8 pb-20">
    <div class="flex items-center justify-between border-b border-slate-200 pb-8 uppercase tracking-tighter">
      <div>
        <h1 class="text-3xl font-black text-slate-900">Arbre Généalogique</h1>
        <p class="mt-1 text-slate-500 font-bold text-xs uppercase tracking-widest italic">Structure de parrainage direct (Unilevel)</p>
      </div>
      <div class="flex gap-2">
         <Button variant="outline" class="font-black text-[10px]" @click="view = 'tree'">ARBRE</Button>
         <Button variant="ghost" class="font-black text-[10px]" @click="view = 'matrix'">MATRICE</Button>
      </div>
    </div>

    <!-- Vue Arbre -->
    <div v-if="view === 'tree'" class="flex flex-col items-center py-10 bg-white rounded-3xl shadow-2xl shadow-slate-200/40 relative overflow-hidden">
       <!-- Filtre rapide -->
       <div class="absolute top-6 left-6 flex items-center gap-3">
          <input type="text" placeholder="Chercher un membre..." class="rounded-full bg-slate-50 border border-slate-200 px-4 py-2 text-xs font-bold focus:border-[#A3E635] outline-none" />
       </div>

       <div v-if="loading" class="py-20">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#A3E635] mx-auto"></div>
       </div>

       <div v-else-if="root" class="relative z-10 flex flex-col items-center">
          <div class="mb-12 flex flex-col items-center">
             <div class="h-20 w-20 rounded-2xl bg-[#1E293B] flex items-center justify-center text-white shadow-xl shadow-slate-300 ring-4 ring-[#A3E635] ring-offset-4 ring-offset-white">
                <UserIcon :size="40" />
             </div>
             <p class="mt-4 text-sm font-black text-[#1E293B] uppercase tracking-widest">{{ root.name }} (Moi)</p>
             <span class="text-[10px] font-black text-[#A3E635] uppercase bg-[#1E293B] px-2 py-0.5 rounded mt-1">{{ root.rank }}</span>
          </div>

          <!-- Lignes de connexion (SVG simplified) -->
          <div class="relative w-full max-w-4xl flex justify-around">
             <div v-for="child in children" :key="child.name" class="flex flex-col items-center relative">
                <!-- Vertical Line -->
                <div class="absolute -top-12 h-12 w-px bg-slate-200" />
                
                <div class="mt-1 group cursor-pointer text-center">
                   <div class="h-14 w-14 rounded-xl bg-white border-2 border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-[#A3E635] group-hover:text-[#1E293B] transition-all shadow-lg shadow-slate-100 mb-2">
                       <UserIcon :size="24" />
                   </div>
                   <p class="text-[10px] font-black text-slate-900 uppercase truncate w-24">{{ child.name }}</p>
                   <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ child.rank }}</p>
                </div>

                <!-- Sub-children count -->
                <div v-if="child.count > 0" class="mt-4 flex flex-col items-center">
                    <div class="h-8 w-px bg-slate-100" />
                    <div class="rounded-full bg-slate-50 border border-slate-100 px-3 py-1 text-[9px] font-black text-slate-400">
                       + {{ child.count }} Filleuls
                    </div>
                </div>
             </div>

             <!-- Horizontal Connector -->
             <div class="absolute -top-12 left-[10%] right-[10%] h-px bg-slate-200" />
          </div>
       </div>
    </div>

    <!-- Légende -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
       <div v-for="item in legend" :key="item.label" class="flex items-center gap-3 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
          <div :class="['h-3 w-3 rounded-full', item.color]" />
          <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">{{ item.label }}</span>
       </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import api from '@/core/api/client';
import { User as UserIcon } from 'lucide-vue-next';
import Button from '@/components/ui/Button.vue';

const view = ref('tree');
const root = ref<any>(null);
const children = ref<any[]>([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await api.get('/genealogy/tree');
        root.value = response.data.root;
        children.value = response.data.children;
    } catch (error) {
        console.error("Error fetching tree", error);
    } finally {
        loading.value = false;
    }
});

const legend = [
  { label: "Basique", color: "bg-slate-200" },
  { label: "Bronze", color: "bg-amber-400" },
  { label: "Argent", color: "bg-slate-400" },
  { label: "Or", color: "bg-yellow-400" },
];
</script>

