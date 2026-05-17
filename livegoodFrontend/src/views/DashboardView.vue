<template>
  <div class="space-y-6">
    <!-- Welcome Header -->
    <div>
      <h1 class="text-3xl font-bold text-slate-900">Bienvenue, {{ userFirstName }}</h1>
      <p class="text-slate-600 mt-1">Gérez votre affilié et vos gains</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-white rounded-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-600 text-sm">Solde disponible</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ formatCurrency(stats.balance) }}</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
            <WalletIcon class="text-green-600" :size="24" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-600 text-sm">Gains ce mois</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ formatCurrency(stats.monthlyEarnings) }}</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
            <TrendingUpIcon class="text-blue-600" :size="24" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-600 text-sm">Mon rang</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ stats.rank }}</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
            <Trophy2Icon class="text-purple-600" :size="24" />
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-slate-600 text-sm">Filleuls</p>
            <p class="text-2xl font-bold text-slate-900 mt-2">{{ stats.downline }}</p>
          </div>
          <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center">
            <UsersIcon class="text-orange-600" :size="24" />
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="text-center py-12">
      <p class="text-slate-600">Chargement...</p>
    </div>

    <!-- Error State -->
    <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">
      {{ error }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { WalletIcon, TrendingUpIcon, Trophy2Icon, UsersIcon } from "lucide-vue-next";
import { useAuthStore } from "../../core/stores/authStore";
import { affiliationService, commissionService, rankService, genealogyService } from "../../core/services";

const authStore = useAuthStore();
const isLoading = ref(true);
const error = ref("");

const stats = ref({
  balance: 0,
  monthlyEarnings: 0,
  rank: "N/A",
  downline: 0,
});

const userFirstName = computed(() => {
  return authStore.user?.name?.split(" ")[0] || "Utilisateur";
});

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat("fr-FR", {
    style: "currency",
    currency: "EUR",
  }).format(value);
};

onMounted(async () => {
  try {
    isLoading.value = true;
    const [earnings, rank, tree] = await Promise.all([
      commissionService.getEarnings(),
      rankService.getCurrentRank(),
      genealogyService.getUnilevelTree(),
    ]).catch((err) => {
      console.error("Error fetching dashboard data:", err);
      return [{}, {}, {}];
    });

    stats.value = {
      balance: earnings.data?.total_balance || 0,
      monthlyEarnings: earnings.data?.monthly_earnings || 0,
      rank: rank.data?.name || "N/A",
      downline: tree.data?.total_downline || 0,
    };
  } catch (err: any) {
    error.value = err.message || "Erreur lors du chargement des données";
  } finally {
    isLoading.value = false;
  }
});
</script>
