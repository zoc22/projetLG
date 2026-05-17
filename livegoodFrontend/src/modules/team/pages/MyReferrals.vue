<template>
  <div class="space-y-8">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase text-sm">Mes Recommandations</h1>
      <p class="mt-2 text-slate-500 font-medium">Liste des membres que vous avez personnellement parrainés.</p>
    </div>
    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-slate-400 mx-auto"></div>
    </div>
    <Card v-else-if="referrals.length > 0" class="p-0 overflow-hidden">
       <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
             <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                   <th class="px-6 py-4">Nom / Pseudo</th>
                   <th class="px-6 py-4">Email</th>
                   <th class="px-6 py-4">Rang</th>
                   <th class="px-6 py-4">Date</th>
                </tr>
             </thead>
             <tbody class="divide-y divide-slate-100">
                <tr v-for="ref in referrals" :key="referrals.id" class="hover:bg-slate-50/50 transition-colors">
                   <td class="px-6 py-4 font-bold text-slate-900">
                      {{ ref.name }} <br/>
                      <span class="text-[10px] text-slate-400 font-medium">@{{ ref.pseudo }}</span>
                   </td>
                   <td class="px-6 py-4 text-sm text-slate-500">{{ ref.email }}</td>
                   <td class="px-6 py-4">
                      <span class="inline-block px-2 py-0.5 rounded bg-slate-100 text-[9px] font-black text-slate-600 border border-slate-200 uppercase">
                         {{ ref.rank }}
                      </span>
                   </td>
                   <td class="px-6 py-4 text-xs font-medium text-slate-400">{{ ref.date }}</td>
                </tr>
             </tbody>
          </table>
       </div>
    </Card>
    <Card v-else>
       <p class="text-slate-400 italic text-center py-20 font-bold uppercase text-[10px] tracking-widest">Aucune recommandation pour le moment</p>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Card from '@/src/components/ui/Card.vue';

const referrals = ref<any[]>([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/affiliation/referrals');
        referrals.value = response.data;
    } catch (error) {
        console.error("Error fetching referrals", error);
    } finally {
        loading.value = false;
    }
});
</script>
