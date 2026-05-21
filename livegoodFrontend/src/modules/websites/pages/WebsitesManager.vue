<template>
  <div class="space-y-8">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight">Mes Sites Web</h1>
      <p class="mt-2 text-slate-500 font-medium leading-relaxed max-w-2xl">
        Utilisez ces liens pour promouvoir LiveGood. Chaque lien a un objectif spécifique pour maximiser vos conversions.
      </p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      <Card v-for="(site, i) in sites" :key="i" class="flex flex-col h-full border-t-4 shadow-xl shadow-slate-100" :style="{ borderColor: site.accent }">
        <div class="mb-6 flex items-center justify-between">
          <div :class="['rounded-2xl p-4', site.bg, site.color]">
            <component :is="site.icon" :size="28" />
          </div>
          <div class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-slate-400 border border-slate-200">
             Actif
          </div>
        </div>

        <div class="flex-1">
          <h3 class="text-xl font-black text-slate-900 tracking-tight uppercase text-sm mb-2">{{ site.title }}</h3>
          <p class="text-sm text-slate-500 font-medium leading-relaxed mb-6">
            {{ site.description }}
          </p>

          <div class="group relative rounded-xl bg-slate-900 p-4 font-mono text-[11px] text-[#A3E635] shadow-inner mb-4">
             <div class="flex items-center justify-between">
                <span class="truncate pr-4">{{ site.url }}</span>
                <button @click="copy(site.url)" class="shrink-0 hover:text-white transition-colors">
                  <CopyIcon :size="16" />
                </button>
             </div>
          </div>
        </div>

        <div class="mt-8 flex gap-3">
          <Button variant="outline" class="flex-1 py-3 font-bold text-xs uppercase tracking-widest" @click="visit(site.url)">
            <ExternalLinkIcon :size="14" class="mr-2" /> Visiter
          </Button>
          <Button class="flex-1 py-3 font-bold text-xs uppercase tracking-widest" @click="copy(site.url)">
            Partager
          </Button>
        </div>
      </Card>
    </div>

    <!-- Conseils Marketing -->
    <Card class="bg-[#1E293B] text-white border-none shadow-2xl shadow-blue-900/20 py-10">
      <div class="flex flex-col items-center text-center max-w-2xl mx-auto">
        <div class="mb-6 rounded-full bg-[#A3E635] p-4 text-[#1E293B]">
          <TrendingUpIcon :size="32" />
        </div>
        <h2 class="text-2xl font-black mb-4">Besoin d'aide pour parrainer ?</h2>
        <p class="text-slate-300 font-medium leading-relaxed mb-8">
          Consultez nos ressources marketing et apprenez comment utiliser efficacement le lien <strong>LiveGood Tour</strong> pour attirer de nouveaux membres.
        </p>
        <Button class="bg-[#A3E635] text-[#1E293B] px-10 py-4 font-black uppercase tracking-[0.2em] text-xs">
          Centre de Formation
        </Button>
      </div>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { 
  Globe as GlobeIcon, 
  ShoppingBag as ShoppingBagIcon, 
  UserPlus as UserPlusIcon,
  Copy as CopyIcon,
  ExternalLink as ExternalLinkIcon,
  TrendingUp as TrendingUpIcon
} from 'lucide-vue-next';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';
import api from '@/core/api/client';
import { useAuthStore } from '@/core/stores/authStore';

const authStore = useAuthStore();
const profile = computed(() => authStore.user);
const loading = ref(false);

onMounted(async () => {
    if (!authStore.user) {
        loading.value = true;
        await authStore.fetchCurrentUser();
        loading.value = false;
    }
});

const sites = computed(() => {
  const pseudo = profile.value?.pseudo || "user";
  return [
    {
      title: "Site Corporatif",
      description: "Affiche à la fois les prix publics et les prix membres. Idéal pour montrer la valeur de l'adhésion.",
      url: `https://livegood.com/${pseudo}`,
      icon: GlobeIcon,
      accent: "#3B82F6",
      color: "text-blue-600",
      bg: "bg-blue-50"
    },
    {
      title: "Site au Détail",
      description: "Affiche uniquement les prix publics. Utilisez ce lien si votre objectif est uniquement de vendre des produits.",
      url: `https://livegood.com/shop/${pseudo}`,
      icon: ShoppingBagIcon,
      accent: "#F59E0B",
      color: "text-amber-600",
      bg: "bg-amber-50"
    },
    {
      title: "Page de Destination",
      description: "Le fameux 'LiveGood Tour'. C'est le lien à utiliser pour inscrire de nouveaux membres affiliés.",
      url: `https://livegoodtour.com/${pseudo}`,
      icon: UserPlusIcon,
      accent: "#A3E635",
      color: "text-green-600",
      bg: "bg-green-50"
    }
  ];
});

const copy = (url: string) => {
  navigator.clipboard.writeText(url);
};

const visit = (url: string) => {
  window.open(url, '_blank');
};
</script>

