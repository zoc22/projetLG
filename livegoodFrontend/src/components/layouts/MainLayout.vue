<template>
  <div class="flex h-screen bg-[#F8FAFC]">
    <!-- Sidebar Desktop -->
    <aside class="hidden w-72 flex-col bg-[#1E293B] text-white lg:flex border-r border-slate-700/50">
      <div class="flex h-20 items-center px-6 border-b border-slate-700/50">
        <div class="flex items-center gap-2">
          <div class="bg-white p-1 rounded-lg text-[#1E293B]">
            <Globe2Icon :size="24" />
          </div>
          <span class="text-2xl font-bold tracking-tight">LIVE<span class="text-[#A3E635]">GOOD</span></span>
        </div>
      </div>
      
      <div class="flex-1 overflow-y-auto py-6 px-4 scrollbar-thin scrollbar-thumb-slate-700">
        <nav class="space-y-1">
          <router-link 
            v-for="item in navItems" 
            :key="item.path" 
            :to="item.path"
            custom
            v-slot="{ navigate, isActive }"
          >
            <button 
              @click="navigate"
              :class="[
                'w-full flex items-center px-4 py-3 text-sm font-medium transition-all rounded-xl gap-3',
                isActive 
                  ? 'bg-[#334155] text-[#A3E635] shadow-lg shadow-black/20' 
                  : 'text-slate-300 hover:bg-slate-700/50 hover:text-white'
              ]"
            >
              <component :is="item.icon" :size="20" :class="isActive ? 'text-[#A3E635]' : 'text-slate-400'" />
              {{ item.label }}
            </button>
          </router-link>
        </nav>
      </div>

      <div class="p-6 border-t border-slate-700/50">
        <button 
          @click="handleLogout"
          class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all rounded-xl gap-3"
        >
          <LogOutIcon :size="20" /> 
          Se déconnecter
        </button>
      </div>
    </aside>

    <!-- Mobile Sidebar -->
    <Teleport to="body">
      <div v-if="isMobileMenuOpen" class="fixed inset-0 z-50 lg:hidden">
        <!-- Backdrop -->
        <div 
          class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300"
          @click="isMobileMenuOpen = false"
        ></div>

        <!-- Sidebar Panel -->
        <div class="fixed inset-y-0 left-0 w-72 bg-[#1E293B] shadow-2xl flex flex-col animate-in slide-in-from-left duration-300">
          <div class="flex h-20 items-center justify-between px-6 border-b border-slate-700/50 text-white">
            <div class="flex items-center gap-2">
              <div class="bg-white p-1 rounded-lg text-[#1E293B]">
                <Globe2Icon :size="24" />
              </div>
              <span class="text-2xl font-bold tracking-tight">LIVE<span class="text-[#A3E635]">GOOD</span></span>
            </div>
          </div>
          
          <div class="flex-1 overflow-y-auto py-6 px-4">
            <nav class="space-y-1">
              <router-link 
                v-for="item in navItems" 
                :key="item.path" 
                :to="item.path"
                custom
                v-slot="{ navigate, isActive }"
              >
                <button 
                  @click="() => { navigate(); isMobileMenuOpen = false; }"
                  :class="[
                    'w-full flex items-center px-4 py-3 text-sm font-medium transition-all rounded-xl gap-3',
                    isActive 
                      ? 'bg-[#334155] text-[#A3E635]' 
                      : 'text-slate-300'
                  ]"
                >
                  <component :is="item.icon" :size="20" />
                  {{ item.label }}
                </button>
              </router-link>
            </nav>
          </div>

          <div class="p-6 border-t border-slate-700/50">
            <button 
              @click="handleLogout"
              class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-400 gap-3"
            >
              <LogOutIcon :size="20" /> Se déconnecter
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Main Content Area -->
    <div class="flex flex-1 flex-col overflow-hidden">
      <header class="flex h-20 items-center justify-between bg-white border-b border-slate-200 px-6 lg:px-10 shrink-0">
        <div class="flex items-center gap-4">
          <button 
            @click="isMobileMenuOpen = true"
            class="lg:hidden p-2 rounded-lg bg-slate-100 text-slate-600"
          >
            <MenuIcon :size="24" />
          </button>
          <h1 class="text-lg font-semibold text-slate-800 lg:text-xl capitalize">
            {{ currentLabel }}
          </h1>
        </div>

        <div class="flex items-center gap-6">
          <div class="hidden md:flex flex-col items-end">
            <span class="text-sm font-bold text-slate-800">Yohann76</span>
            <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold bg-slate-100 px-2 py-0.5 rounded cursor-default border border-slate-200">
              Rang Bronze
            </span>
          </div>
          <div class="h-10 w-10 rounded-full bg-gradient-to-br from-[#A3E635] to-green-600 flex items-center justify-center font-bold text-white shadow-lg shadow-green-200 cursor-pointer hover:scale-105 transition-transform overflow-hidden">
            <img v-if="currentUser?.avatar_url" :src="currentUser.avatar_url" class="h-full w-full object-cover" />
            <span v-else>Y</span>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-4 md:p-6 lg:p-10">
        <div class="max-w-7xl mx-auto">
          <router-view v-slot="{ Component }">
            <transition 
              name="fade" 
              mode="out-in"
              enter-active-class="animate-in fade-in slide-in-from-bottom-4 duration-500"
              leave-active-class="animate-out fade-out slide-out-to-top-4 duration-300"
            >
              <component :is="Component" />
            </transition>
          </router-view>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { 
  Home as HomeIcon,
  ShoppingBag as ShoppingBagIcon,
  CreditCard as CreditCardIcon,
  User as UserIcon,
  History as HistoryIcon,
  Users as UsersIcon,
  UserPlus as UserPlusIcon,
  Network as NetworkIcon,
  LayoutDashboard as LayoutDashboardIcon,
  Globe as GlobeIcon,
  Video as VideoIcon,
  BarChart3 as BarChart3Icon,
  Wallet as WalletIcon,
  Mail as MailIcon,
  LogOut as LogOutIcon,
  Menu as MenuIcon,
  Globe2 as Globe2Icon,
  Trophy as TrophyIcon,
  Star as StarIcon
} from 'lucide-vue-next';

const route = useRoute();
const router = useRouter();
const isMobileMenuOpen = ref(false);
const currentUser = ref<any>(null);

const fetchUser = async () => {
    try {
        const response = await axios.get('/api/profile/me');
        currentUser.value = response.data;
    } catch (e) {
        console.error("Layout: Error fetching user", e);
    }
};

// Initialize theme on mount
onMounted(() => {
  fetchUser();
});

const navItems = [
  { label: "Maison", path: "/dashboard", icon: HomeIcon },
  { label: "Boutique", path: "/dashboard/shop", icon: ShoppingBagIcon },
  { label: "Mon adhésion", path: "/dashboard/membership", icon: CreditCardIcon },
  { label: "Ma Qualification", path: "/dashboard/rank", icon: TrophyIcon },
  { label: "Mes informations", path: "/dashboard/info", icon: UserIcon },
  { label: "Historique commandes", path: "/dashboard/orders", icon: HistoryIcon },
  { label: "Mon équipe", path: "/dashboard/team", icon: NetworkIcon },
  { label: "Généalogie", path: "/dashboard/genealogy", icon: LayoutDashboardIcon },
  { label: "Mes recommandations", path: "/dashboard/referrals", icon: UserPlusIcon },
  { label: "Mon recruteur", path: "/dashboard/enroller", icon: UsersIcon },
  { label: "Le classement", path: "/dashboard/leaderboard", icon: StarIcon },
  { label: "Mes sites web", path: "/dashboard/websites", icon: GlobeIcon },
  { label: "Webinaires", path: "/dashboard/webinars", icon: VideoIcon },
  { label: "Statistiques", path: "/dashboard/statistics", icon: BarChart3Icon },
  { label: "Mes gains", path: "/dashboard/earnings", icon: WalletIcon },
  { label: "Contactez-nous", path: "/dashboard/contact", icon: MailIcon },
];

const currentLabel = computed(() => {
  return navItems.find(i => i.path === route.path)?.label || "Espace Membre";
});

const handleLogout = () => {
  router.push('/auth/login');
};
</script>
