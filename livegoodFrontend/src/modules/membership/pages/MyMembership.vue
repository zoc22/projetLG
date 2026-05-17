<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3">
        <CreditCardIcon :size="32" class="text-green-500" /> Mon Adhésion
      </h1>
      <p class="mt-2 text-slate-500 font-medium italic uppercase text-[10px] tracking-widest">Statut et gestion de votre compte membre</p>
    </div>

    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#A3E635] mx-auto"></div>
    </div>

    <div v-else-if="user" class="grid grid-cols-1 gap-8 lg:grid-cols-2">
       <Card class="bg-[#1E293B] text-white border-none shadow-2xl shadow-slate-300">
          <div class="mb-8 flex items-center justify-between">
             <div class="rounded-xl bg-green-500/20 px-4 py-1 text-[10px] font-black uppercase text-[#A3E635] tracking-widest border border-[#A3E635]/30">
               {{ user.membership.status }}
             </div>
             <p class="text-[10px] font-black uppercase text-slate-400">Membre depuis: {{ user.membership.joinedDate }}</p>
          </div>
          
          <h3 class="text-4xl font-black tracking-tighter mb-2">Membre {{ user.membership.type }}</h3>
          <p class="text-slate-400 font-medium mb-8">Votre adhésion vous permet d'économiser jusqu'à 80% sur tous nos produits.</p>

          <div class="space-y-4 pt-6 border-t border-white/10">
             <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Prochaine Échéance</span>
                <span class="text-sm font-black text-white">{{ user.membership.nextBilling }}</span>
             </div>
             <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Montant</span>
                <span class="text-sm font-black text-[#A3E635]">{{ user.membership.amount }} $ (Payé)</span>
             </div>
          </div>
       </Card>

       <Card v-if="user.membership.type !== 'annuel'" class="flex flex-col justify-center text-center p-12">
          <div class="mb-6 mx-auto rounded-full bg-slate-50 p-6 text-slate-400">
             <ShieldCheckIcon :size="48" />
          </div>
          <h3 class="text-xl font-black text-slate-900 mb-4 uppercase tracking-tight">Devenir un Membre Élite ?</h3>
          <p class="text-sm text-slate-500 font-medium leading-relaxed mb-8">
             Passez à l'adhésion annuelle pour économiser 20% supplémentaires et ne plus vous soucier des renouvellements mensuels.
          </p>
          <Button class="bg-[#1E293B] text-white hover:bg-slate-800 font-black uppercase tracking-widest text-[10px] py-4">
             Passer à l'annuel (99.95 $)
          </Button>
       </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { CreditCard as CreditCardIcon, ShieldCheck as ShieldCheckIcon } from 'lucide-vue-next';
import Card from '@/src/components/ui/Card.vue';
import Button from '@/src/components/ui/Button.vue';

const user = ref<any>(null);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/profile/me');
        user.value = response.data;
    } catch (error) {
        console.error("Error fetching profile", error);
    } finally {
        loading.value = false;
    }
});
</script>
