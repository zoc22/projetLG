<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <div class="border-b border-slate-200 pb-8 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3 uppercase">
          <TrophyIcon :size="32" class="text-amber-500" /> Le Classement
        </h1>
        <p class="mt-2 text-slate-500 font-medium italic text-[10px] tracking-widest uppercase">Les 50 meilleurs recruteurs de tous les temps</p>
      </div>
      <div class="rounded-2xl bg-slate-900 px-6 py-4 text-white shadow-xl">
         <p class="text-[10px] font-black uppercase text-slate-400">Record Mondial</p>
         <p class="text-xl font-black text-[#A3E635]">1,204 Directs</p>
      </div>
    </div>

    <Card class="p-0 overflow-hidden border-none shadow-2xl shadow-slate-200">
       <table class="w-full text-left">
          <thead class="bg-slate-50 border-b border-slate-100">
             <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                <th class="px-6 py-5 w-20">Position</th>
                <th class="px-6 py-5">Affilié</th>
                <th class="px-6 py-5 text-right">Membres Directs</th>
             </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
             <tr v-for="n in 10" :key="n" :class="['hover:bg-slate-50/50 transition-colors', n <= 3 ? 'bg-amber-50/20' : '']">
                <td class="px-6 py-5">
                   <div class="flex items-center justify-center h-8 w-8 rounded-full border-2 border-slate-200 font-black text-xs text-slate-600">
                      {{ n }}
                   </div>
                </td>
                <td class="px-6 py-5">
                   <div class="flex items-center gap-3">
                      <div class="h-10 w-10 flex-shrink-0 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-400">
                         {{ leaders[n-1]?.name[0] }}
                      </div>
                      <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ leaders[n-1]?.name }}</p>
                   </div>
                </td>
                <td class="px-6 py-5 text-right">
                   <span class="text-lg font-black text-[#1E293B]">{{ leaders[n-1]?.count }}</span>
                </td>
             </tr>
          </tbody>
       </table>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { Trophy as TrophyIcon } from 'lucide-vue-next';
import Card from '@/components/ui/Card.vue';

const leaders = [
  { name: "JEFFREY AMAN", count: 1204 },
  { name: "TIM MILLER", count: 980 },
  { name: "CHRIS HOPKINS", count: 850 },
  { name: "EDWARD KEYES", count: 720 },
  { name: "BRETT TRIVETT", count: 680 },
  { name: "TRACY MILLER", count: 650 },
  { name: "NICKY GOMEZ", count: 620 },
  { name: "KELLY TILLAR", count: 590 },
  { name: "BEN GLINSKY", count: 550 },
  { name: "RYAN GOODKIN", count: 510 },
];
</script>

