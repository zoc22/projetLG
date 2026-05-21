<template>
  <div class="space-y-8">
    <div class="flex items-center justify-between border-b border-slate-200 pb-8">
      <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase text-sm">Mon Équipe</h1>
        <p class="mt-2 text-slate-500 font-medium">Gérez et suivez la croissance de votre organisation</p>
      </div>
      <div class="flex gap-4">
        <div v-if="!loading" class="hidden sm:block text-right">
          <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest leading-none mb-1">Total Membres</p>
          <p class="text-2xl font-black text-slate-900 leading-none">{{ team.length }}</p>
        </div>
        <div v-if="!loading" class="h-10 w-px bg-slate-200 hidden sm:block mx-2" />
        <Button @click="showEnrollModal = true" class="bg-[#A3E635] text-[#1E293B] font-black uppercase tracking-widest text-[10px]">
          Inscrire Nouveau
        </Button>
      </div>
    </div>

    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500 mx-auto"></div>
    </div>

    <Card v-else-if="team.length > 0" class="overflow-hidden border-none shadow-xl shadow-slate-200/50 p-0">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-100 font-black text-[10px] uppercase tracking-[0.2em] text-slate-400">
              <th class="px-6 py-5">Membre</th>
              <th class="px-6 py-5">Pays</th>
              <th class="px-6 py-5 text-center">Rang</th>
              <th class="px-6 py-5 text-center">Statut</th>
              <th class="px-6 py-5 text-right">Date d'inscription</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-for="member in team" :key="member.id" class="hover:bg-slate-50/50 transition-colors group cursor-pointer">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="h-10 w-10 flex-shrink-0 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 border border-slate-200 group-hover:border-[#A3E635] transition-colors">
                    {{ member.name[0] }}
                  </div>
                  <div>
                    <p class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ member.name }}</p>
                    <p class="text-[11px] font-medium text-slate-500">{{ member.email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                   <img :src="member.flag" class="h-3 w-5 rounded-sm object-cover shadow-sm" />
                   <span class="text-xs font-bold text-slate-600">{{ member.country }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-center">
                <span :class="['px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border', getRankStyles(member.rank)]">
                  {{ member.rank }}
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  <div :class="['h-2 w-2 rounded-full', member.active ? 'bg-green-500 shadow-lg shadow-green-200' : 'bg-slate-300']" />
                  <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest">
                    {{ member.active ? 'Payé' : 'Non-payé' }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <p class="text-xs font-bold text-slate-500 tracking-tight">{{ member.date }}</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
         <p class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Affichage de {{ team.length }} membres</p>
         <div class="flex gap-2">
            <Button variant="outline" size="sm" class="font-black text-[10px] uppercase">Précédent</Button>
            <Button variant="outline" size="sm" class="font-black text-[10px] uppercase">Suivant</Button>
         </div>
      </div>
    </Card>
    
    <Card v-else class="py-20 text-center">
       <p class="text-slate-400 italic font-bold uppercase text-[10px] tracking-widest">Aucun membre dans votre équipe pour le moment</p>
    </Card>

    <!-- Modal d'inscription -->
    <Modal :show="showEnrollModal" title="Inscrire un Nouveau Membre" @close="showEnrollModal = false">
      <div v-if="enrollmentSuccess" class="py-12 text-center">
         <div class="h-20 w-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <CheckIcon class="h-10 w-10 stroke-[3]" />
         </div>
         <h4 class="text-xl font-black text-slate-900 uppercase mb-2">Inscription Réussie !</h4>
         <p class="text-slate-500 font-medium">Le nouveau membre a été ajouté à votre équipe.</p>
      </div>
      <form v-else @submit.prevent="handleEnroll" class="space-y-6">
         <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
               <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Prénom</label>
               <input v-model="enrollmentForm.firstName" required type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none focus:border-[#A3E635]" />
            </div>
            <div class="space-y-1">
               <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nom</label>
               <input v-model="enrollmentForm.lastName" required type="text" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none focus:border-[#A3E635]" />
            </div>
         </div>
         <div class="space-y-1">
            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Email</label>
            <input v-model="enrollmentForm.email" required type="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none focus:border-[#A3E635]" />
         </div>
         <div class="space-y-1">
            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Pseudo souhaité</label>
            <input v-model="enrollmentForm.pseudo" required type="text" placeholder="@username" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none focus:border-[#A3E635]" />
         </div>
         <Button type="submit" class="w-full py-4 bg-indigo-600 text-white font-black uppercase tracking-widest text-xs" :disabled="enrolling">
            {{ enrolling ? 'Traitement en cours...' : 'Confirmer l\'Inscription' }}
         </Button>
      </form>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';
import Modal from '@/components/ui/Modal.vue';
import { UserPlus as UserPlusIcon, Check as CheckIcon } from 'lucide-vue-next';

const team = ref<any[]>([]);
const loading = ref(true);
const showEnrollModal = ref(false);
const enrolling = ref(false);
const enrollmentSuccess = ref(false);

const enrollmentForm = ref({
    firstName: '',
    lastName: '',
    email: '',
    pseudo: '',
    country: 'France'
});

const loadTeam = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/affiliation/team-members');
        team.value = response.data;
    } catch (error) {
        console.error("Error fetching team members", error);
    } finally {
        loading.value = false;
    }
};

onMounted(loadTeam);

const handleEnroll = async () => {
    enrolling.value = true;
    try {
        // En vrai on appellerait une API POST
        // Pour la démo on attend un peu et on montre le succès
        await new Promise(resolve => setTimeout(resolve, 1500));
        enrollmentSuccess.value = true;
        setTimeout(() => {
            showEnrollModal.value = false;
            enrollmentSuccess.value = false;
            enrollmentForm.value = { firstName: '', lastName: '', email: '', pseudo: '', country: 'France' };
            loadTeam(); // Rafraîchir pour voir le nouveau (si l'API simulait vraiment l'ajout)
        }, 2000);
    } catch (error) {
        console.error("Enrollment failed", error);
    } finally {
        enrolling.value = false;
    }
};

const getRankStyles = (rank: string) => {
  switch (rank) {
    case 'SILVER': return 'bg-slate-50 text-slate-400 border-slate-200 shadow-sm';
    case 'BRONZE': return 'bg-amber-50 text-amber-600 border-amber-200 shadow-sm';
    case 'GOLD': return 'bg-yellow-50 text-yellow-600 border-yellow-200 shadow-sm';
    default: return 'bg-slate-50 text-slate-500 border-slate-100';
  }
};
</script>

