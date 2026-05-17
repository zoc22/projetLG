<template>
  <div class="space-y-8 animate-in fade-in duration-500">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
      <div v-if="loading" class="animate-pulse">
         <div class="h-8 w-64 bg-slate-200 rounded mb-2"></div>
         <div class="h-4 w-48 bg-slate-100 rounded"></div>
      </div>
      <div v-else-if="profile">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Bonjour, {{ profile.name }} ! 👋</h1>
        <p class="text-slate-500 font-medium">Bon retour sur votre centre d'affaires LiveGood.</p>
      </div>
      <div class="flex items-center gap-3">
        <Button variant="outline" class="hidden sm:flex" @click="copyLink">
          <component :is="copying ? CheckIcon : CopyIcon" :size="16" class="mr-2" :class="copying ? 'text-green-500' : ''" />
          {{ copying ? 'Copié !' : 'Copier mon lien' }}
        </Button>
        <Button class="bg-[#1E293B] text-white hover:bg-slate-800">
          Acheter Maintenant
        </Button>
      </div>
    </div>

    <!-- Alerte Date Limite -->
    <div class="rounded-2xl bg-gradient-to-r from-red-500 to-rose-600 p-6 text-white shadow-xl shadow-red-200">
      <div class="flex items-start gap-4">
        <div class="rounded-xl bg-white/20 p-3 backdrop-blur-md">
          <ClockIcon :size="24" />
        </div>
        <div>
          <h3 class="text-lg font-black uppercase tracking-wider">Prochaine Date Limite</h3>
          <p class="text-3xl font-black mt-1">Jeudi 2 Mars</p>
          <p class="mt-2 text-sm font-medium text-white/90 leading-relaxed">
            N'oubliez pas ! Toutes les inscriptions validées avant jeudi soir seront comptabilisées pour la période de paie de cette semaine.
          </p>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
      <Card v-for="(stat, i) in stats" :key="i" class="relative overflow-hidden group hover:scale-[1.02] transition-all duration-300">
        <div class="flex items-center gap-4">
          <div :class="['rounded-2xl p-4', stat.bg, stat.color]">
            <component :is="stat.icon" :size="28" />
          </div>
          <div>
            <p class="text-xs font-bold uppercase tracking-[0.1em] text-slate-400">{{ stat.label }}</p>
            <p class="text-2xl font-black text-slate-900 tracking-tight">{{ stat.value }}</p>
          </div>
        </div>
        <!-- Décoration subtile -->
        <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:rotate-12 transition-transform">
          <component :is="stat.icon" :size="80" />
        </div>
      </Card>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <!-- Progression vers prochain rang -->
      <Card class="lg:col-span-2">
        <div class="mb-8 flex items-center justify-between">
          <div>
            <h3 class="text-xl font-black text-slate-900 uppercase text-sm tracking-tight">Objectif : {{ nextRankLabel }}</h3>
            <p class="text-xs text-slate-500 font-medium">{{ nextRankHint }}</p>
          </div>
          <button @click="showRankInfo = true" class="flex items-center gap-1.5 rounded-full bg-indigo-50 px-4 py-1.5 text-[10px] font-black uppercase text-indigo-600 border border-indigo-100 hover:bg-indigo-100 transition-colors">
            <InfoIcon :size="12" /> Comment se qualifier ?
          </button>
        </div>
        
        <div class="relative h-4 w-full overflow-hidden rounded-full bg-slate-100 border border-slate-200">
          <div 
            class="h-full rounded-full bg-gradient-to-r from-[#A3E635] to-green-500 transition-all duration-1000 shadow-sm"
            :style="{ width: progressionPercent + '%' }"
          ></div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4">
          <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
            <p class="text-[10px] font-bold uppercase text-slate-400">Directs Actifs</p>
            <p class="text-lg font-black text-slate-900">{{ activeDirects }} / {{ requiredDirects }}</p>
            <div class="mt-1 h-1 w-full bg-slate-200 rounded-full overflow-hidden">
               <div class="h-full bg-green-500" :style="{ width: Math.min(100, (activeDirects/requiredDirects)*100) + '%' }" />
            </div>
          </div>
          <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
            <p class="text-[10px] font-bold uppercase text-slate-400">Total Équipe</p>
            <p class="text-lg font-black text-slate-900">{{ totalTeam }} / {{ requiredTeam }}</p>
            <div class="mt-1 h-1 w-full bg-slate-200 rounded-full overflow-hidden">
               <div class="h-full bg-amber-500" :style="{ width: Math.min(100, (totalTeam/requiredTeam)*100) + '%' }" />
            </div>
          </div>
        </div>
      </Card>

      <!-- Dernières préinscriptions -->
      <Card>
        <h3 class="mb-6 text-xl font-black text-slate-900 tracking-tight text-center uppercase text-sm border-b border-slate-100 pb-4">Préinscriptions Récentes</h3>
        <div class="space-y-6">
          <div v-for="user in recentJoiners" :key="user.name" class="flex items-center justify-between group cursor-pointer hover:bg-slate-50/50 p-2 rounded-xl transition-colors">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-lg bg-slate-100 border border-slate-200">
                <img :src="user.flag" class="h-full w-full object-cover" />
              </div>
              <div class="min-w-0">
                <p class="truncate text-sm font-black text-slate-900 uppercase tracking-tight">{{ user.name }}</p>
                <p class="text-[10px] font-bold text-slate-400 tracking-widest">{{ user.time }}</p>
              </div>
            </div>
            <ChevronRightIcon :size="14" class="text-slate-300 opacity-0 group-hover:opacity-100 transition-opacity" />
          </div>
        </div>
        <Button variant="ghost" class="mt-8 w-full font-bold text-xs uppercase tracking-[0.2em] hover:bg-[#A3E635]/10">
          Voir tout l'historique
        </Button>
      </Card>
    </div>

    <!-- Modal Informations de Rang -->
    <Modal :show="showRankInfo" title="Qualifications des Rangs" @close="showRankInfo = false">
       <div class="space-y-6">
          <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 italic text-sm text-slate-600">
             "Le succès dans LiveGood repose sur deux piliers : vos efforts personnels (directs) et la force de votre organisation (équipe)."
          </div>
          
          <div class="space-y-4">
             <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 bg-white shadow-sm">
                <div class="h-8 w-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-black text-xs">BR</div>
                <div>
                   <h4 class="text-sm font-black text-slate-900 uppercase">Bronze</h4>
                   <p class="text-[11px] text-slate-500 font-medium">Avoir 2 membres directs actifs.</p>
                </div>
             </div>
             <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 bg-white shadow-sm">
                <div class="h-8 w-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center font-black text-xs">AG</div>
                <div>
                   <h4 class="text-sm font-black text-slate-900 uppercase">Argent</h4>
                   <p class="text-[11px] text-slate-500 font-medium">10 directs actifs OU 3 branches Bronze + 20 membres au total.</p>
                </div>
             </div>
             <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 bg-white shadow-sm">
                <div class="h-8 w-8 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center font-black text-xs">OR</div>
                <div>
                   <h4 class="text-sm font-black text-slate-900 uppercase">Or</h4>
                   <p class="text-[11px] text-slate-500 font-medium">30 directs actifs OU 3 branches Argent + 100 membres au total.</p>
                </div>
             </div>
          </div>
          
          <Button @click="showRankInfo = false" class="w-full py-4 font-black uppercase tracking-widest text-[10px]">J'ai compris</Button>
       </div>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { 
  Users as UsersIcon, 
  BarChart3 as BarChart3Icon, 
  Trophy as TrophyIcon, 
  TrendingUp as TrendingUpIcon,
  Copy as CopyIcon,
  Clock as ClockIcon,
  ChevronRight as ChevronRightIcon,
  Check as CheckIcon,
  Info as InfoIcon
} from 'lucide-vue-next';
import Card from '@/src/components/ui/Card.vue';
import Button from '@/src/components/ui/Button.vue';
import Modal from '@/src/components/ui/Modal.vue';

const profile = ref<any>(null);
const rank = ref<any>(null);
const earnings = ref<any>(null);
const globalStats = ref<any>(null);
const recentJoiners = ref<any[]>([]);
const loading = ref(true);
const copying = ref(false);
const showRankInfo = ref(false);

const copyLink = async () => {
    copying.value = true;
    try {
        const link = `https://livegood.com/${profile.value?.pseudo || 'yohann76'}`;
        await navigator.clipboard.writeText(link);
        setTimeout(() => copying.value = false, 2000);
    } catch (e) {
        copying.value = false;
    }
};

onMounted(async () => {
    try {
        const [profileRes, rankRes, earningsRes, recentRes, statsRes] = await Promise.all([
            axios.get('/api/profile/me'),
            axios.get('/api/rank/my-rank'),
            axios.get('/api/commissions/earnings'),
            axios.get('/api/statistics/recent-enrolments'),
            axios.get('/api/statistics/dashboard')
        ]);
        profile.value = profileRes.data;
        rank.value = rankRes.data;
        earnings.value = earningsRes.data;
        recentJoiners.value = recentRes.data;
        globalStats.value = statsRes.data;
    } catch (error) {
        console.error("Error loading dashboard data", error);
    } finally {
        loading.value = false;
    }
});

const nextRankData = computed(() => {
    const currentRank = rank.value?.currentRank;
    if (currentRank === 'BASIC') return { label: 'Rang Bronze', requirements: rank.value?.requirements.bronze };
    if (currentRank === 'BRONZE') return { label: 'Rang Argent', requirements: rank.value?.requirements.silver };
    if (currentRank === 'SILVER') return { label: 'Rang Or', requirements: rank.value?.requirements.gold };
    if (currentRank === 'GOLD') return { label: 'Rang Platine', requirements: rank.value?.requirements.platinum };
    if (currentRank === 'PLATINUM') return { label: 'Rang Diamant', requirements: rank.value?.requirements.diamond };
    return { label: 'Rang Maximum', requirements: null };
});

const activeDirects = computed(() => nextRankData.value.requirements?.activeDirects.current || 0);
const requiredDirects = computed(() => nextRankData.value.requirements?.activeDirects.required || 1);
const totalTeam = computed(() => nextRankData.value.requirements?.totalTeam.current || 0);
const requiredTeam = computed(() => nextRankData.value.requirements?.totalTeam.required || 1);

const progressionPercent = computed(() => {
    if (!nextRankData.value.requirements) return 100;
    const p1 = Math.min(100, (activeDirects.value / requiredDirects.value) * 100);
    const p2 = requiredTeam.value > 0 ? Math.min(100, (totalTeam.value / requiredTeam.value) * 100) : 100;
    return Math.round((p1 + p2) / 2);
});

const nextRankHint = computed(() => {
    if (!nextRankData.value.requirements) return "Félicitations, vous avez atteint le rang maximum !";
    const missing = requiredDirects.value - activeDirects.value;
    return missing > 0 ? `Plus que ${missing} directs pour le prochain rang` : "Prêt pour le prochain rang !";
});

const nextRankLabel = computed(() => nextRankData.value.label);

const stats = computed(() => [
  { label: "Gains de la Semaine", value: earnings.value ? `${earnings.value.weeklyTotal.toFixed(2)} $` : '0.00 $', icon: BarChart3Icon, color: "text-emerald-600", bg: "bg-emerald-100/50" },
  { label: "Taille de l'Équipe", value: rank.value ? rank.value.requirements.silver.totalTeam.current : '0', icon: UsersIcon, color: "text-blue-600", bg: "bg-blue-100/50" },
  { label: "Rang Actuel", value: rank.value ? rank.value.currentRank : 'BASIC', icon: TrophyIcon, color: "text-amber-600", bg: "bg-amber-100/50" },
  { label: "Membres Préinscrits", value: globalStats.value?.stats.preenrolments || "85", icon: TrendingUpIcon, color: "text-indigo-600", bg: "bg-indigo-100/50" },
]);
</script>
