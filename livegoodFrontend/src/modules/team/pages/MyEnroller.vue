<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3 uppercase">
        <UsersIcon :size="32" class="text-indigo-500" /> Mon Recruteur
      </h1>
      <p class="mt-2 text-slate-500 font-medium">La personne qui vous a parrainé dans l'aventure LiveGood</p>
    </div>

    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto"></div>
    </div>
    <Card v-else-if="enroller" class="max-w-2xl mx-auto overflow-hidden p-0 border-none shadow-2xl shadow-slate-200">
       <div class="bg-indigo-600 p-10 flex flex-col items-center text-white">
          <div class="h-24 w-24 rounded-2xl bg-white/20 flex items-center justify-center text-white font-black text-4xl mb-4 backdrop-blur-md">
             {{ enroller.name.charAt(0) }}
          </div>
          <h2 class="text-2xl font-black uppercase tracking-tight">{{ enroller.name }}</h2>
          <span class="text-[10px] font-black uppercase bg-[#A3E635] text-[#1E293B] px-3 py-1 rounded-full mt-2">{{ enroller.rank }}</span>
       </div>
       <div class="p-10 space-y-6">
          <div class="grid grid-cols-2 gap-4 text-center">
             <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Email</p>
                <p class="text-xs font-black text-slate-900">{{ enroller.email }}</p>
             </div>
             <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Pseudo</p>
                <p class="text-xs font-black text-slate-900">@{{ enroller.pseudo }}</p>
             </div>
          </div>
          <Button class="w-full bg-[#1E293B] text-white py-4 font-black uppercase text-[10px] tracking-widest">
             Contacter mon parrain
          </Button>
       </div>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Users as UsersIcon } from 'lucide-vue-next';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';

const enroller = ref<any>(null);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/affiliation/enroller');
        enroller.value = response.data;
    } catch (error) {
        console.error("Error fetching enroller", error);
    } finally {
        loading.value = false;
    }
});
</script>

