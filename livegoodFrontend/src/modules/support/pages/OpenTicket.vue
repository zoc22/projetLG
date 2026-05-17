<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3 uppercase">
        <MailIcon :size="32" class="text-rose-500" /> Support Client
      </h1>
      <p class="mt-2 text-slate-500 font-medium">Nous sommes là pour vous aider 24/7</p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
       <Card class="lg:col-span-2">
          <div class="flex items-center justify-between mb-8">
             <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Envoyer un Message</h3>
             <Transition
               enter-active-class="transition duration-300 ease-out"
               enter-from-class="opacity-0 translate-x-4"
               enter-to-class="opacity-100 translate-x-0"
               leave-active-class="transition duration-200 ease-in"
               leave-from-class="opacity-100 translate-x-0"
               leave-to-class="opacity-0 translate-x-4"
             >
                <div v-if="showSuccess" class="bg-green-100 text-green-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                   <CheckIcon class="h-4 w-4" /> Message envoyé avec succès
                </div>
             </Transition>
          </div>
          <form class="space-y-6" @submit.prevent="sendTicket">
             <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                   <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Sujet</label>
                   <select v-model="form.subject" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none focus:border-[#A3E635]">
                      <option>Problème de paiement</option>
                      <option>Vérification de compte</option>
                      <option>Question sur les produits</option>
                      <option>Autre</option>
                   </select>
                </div>
                <div class="space-y-2">
                   <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">N° de Commande (optionnel)</label>
                   <input v-model="form.orderId" type="text" placeholder="#ORD-000" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none focus:border-[#A3E635]" />
                </div>
             </div>
             <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Votre Message</label>
                <textarea v-model="form.message" rows="6" placeholder="Comment pouvons-nous vous aider ?" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-bold outline-none focus:border-[#A3E635] resize-none"></textarea>
             </div>
             <Button type="submit" class="w-full py-4 text-slate-900 font-black uppercase tracking-widest text-xs" :disabled="submitting">
                {{ submitting ? 'Envoi...' : 'Envoyer le ticket' }}
             </Button>
          </form>

          <!-- Recent Tickets -->
          <div v-if="tickets.length > 0" class="mt-12 pt-12 border-t border-slate-100">
             <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Vos Tickets Récents</h4>
             <div class="space-y-4">
                <div v-for="ticket in tickets" :key="ticket.id" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100">
                   <div>
                      <p class="text-sm font-black text-slate-900">{{ ticket.subject }}</p>
                      <p class="text-[10px] font-bold text-slate-400">Réf: {{ ticket.id }} • {{ ticket.date }}</p>
                   </div>
                   <div :class="['px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest', ticket.status === 'RESOLU' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600']">
                      {{ ticket.status }}
                   </div>
                </div>
             </div>
          </div>
       </Card>

       <div class="space-y-6">
          <Card class="bg-slate-900 text-white border-none shadow-xl shadow-slate-300">
             <h4 class="text-xs font-black uppercase tracking-[0.2em] text-[#A3E635] mb-4">Email de support</h4>
             <p class="text-xl font-black mb-2">support@livegood.com</p>
             <p class="text-xs text-slate-400 font-medium">Réponse moyenne en moins de 12 heures.</p>
          </Card>
          
          <Card class="border-dashed border-2">
             <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-4">Adresse Postale</h4>
             <p class="text-xs text-slate-500 font-bold leading-relaxed">
                LiveGood, Inc<br/>
                1201 Jupiter Park Dr. Unit 5<br/>
                Jupiter, FL 33458
             </p>
          </Card>
       </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Mail as MailIcon, Check as CheckIcon } from 'lucide-vue-next';
import Card from '@/src/components/ui/Card.vue';
import Button from '@/src/components/ui/Button.vue';

const tickets = ref<any[]>([]);
const loading = ref(true);
const submitting = ref(false);
const showSuccess = ref(false);
const form = ref({ subject: 'Problème de paiement', message: '', orderId: '' });

const fetchTickets = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/support/tickets');
        tickets.value = response.data;
    } catch (error) {
        console.error("Error fetching tickets", error);
    } finally {
        loading.value = false;
    }
};

const sendTicket = async () => {
    if (!form.value.message) return;
    submitting.value = true;
    try {
        await axios.post('/api/support/tickets', form.value);
        form.value.message = '';
        form.value.orderId = '';
        showSuccess.value = true;
        await fetchTickets();
        setTimeout(() => {
            showSuccess.value = false;
        }, 5000);
    } catch (error) {
        console.error("Error sending ticket", error);
    } finally {
        submitting.value = false;
    }
};

onMounted(fetchTickets);
</script>
