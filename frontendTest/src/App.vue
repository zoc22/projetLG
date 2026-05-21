<template>
  <div :class="['flex h-screen overflow-hidden', darkMode ? 'dark' : '']">
    <!-- Overlay pour mobile -->
    <div 
      v-if="isSidebarOpen" 
      @click="setSidebarOpen(false)" 
      class="fixed inset-0 bg-black/50 z-30 lg:hidden transition-opacity"
    ></div>

    <div class="flex h-screen w-full">
      <AppSidebar />

      <!-- ========== MAIN CONTENT ========== -->
      <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <AppHeader />

        <main class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-custom bg-gray-50">
          <DashboardView v-show="activeSection === 'dashboard'" />
          <MaisonView v-show="activeSection === 'maison'" />
          <AdhesionView v-show="activeSection === 'adhesion'" />
          <BoutiqueView v-show="activeSection === 'boutique'" />
          <MesInfosView v-show="activeSection === 'mesinfos'" />
          <CommandesView v-show="activeSection === 'commandes'" />
          <EquipeView v-show="activeSection === 'equipe'" />
          <RecommandationsView v-show="activeSection === 'recommandations'" />
          <RecruteurView v-show="activeSection === 'recruteur'" />
          <ClassementView v-show="activeSection === 'classement'" />
          <SitesWebView v-show="activeSection === 'sitesweb'" />
          <WebinairesView v-show="activeSection === 'webinaires'" />
          <StatistiquesView v-show="activeSection === 'statistiques'" />
          <GainsView v-show="activeSection === 'gains'" />
          <ContactView v-show="activeSection === 'contact'" />
          <LogoutView v-show="activeSection === 'logout'" />
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useUIStore } from '@/stores/uiStore'
import { useSidebar } from '@/composables/useSidebar'
import { useTheme } from '@/composables/useTheme'

// Layouts
import AppSidebar from '@/layouts/AppSidebar.vue'
import AppHeader from '@/layouts/AppHeader.vue'

// Views
import DashboardView from '@/modules/dashboard/DashboardView.vue'
import MaisonView from '@/modules/maison/MaisonView.vue'
import AdhesionView from '@/modules/adhesion/AdhesionView.vue'
import BoutiqueView from '@/modules/boutique/BoutiqueView.vue'
import MesInfosView from '@/modules/mesinfos/MesInfosView.vue'
import CommandesView from '@/modules/commandes/CommandesView.vue'
import EquipeView from '@/modules/equipe/EquipeView.vue'
import RecommandationsView from '@/modules/recommandations/RecommandationsView.vue'
import RecruteurView from '@/modules/recruteur/RecruteurView.vue'
import ClassementView from '@/modules/classement/ClassementView.vue'
import SitesWebView from '@/modules/sitesweb/SitesWebView.vue'
import WebinairesView from '@/modules/webinaires/WebinairesView.vue'
import StatistiquesView from '@/modules/statistiques/StatistiquesView.vue'
import GainsView from '@/modules/gains/GainsView.vue'
import ContactView from '@/modules/contact/ContactView.vue'
import LogoutView from '@/modules/logout/LogoutView.vue'

const uiStore = useUIStore()
const { activeSection } = storeToRefs(uiStore)
const { isSidebarOpen, setSidebarOpen } = useSidebar()
const { darkMode } = useTheme()
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css');

* { font-family: 'Inter', sans-serif; }

.sidebar-item {
  transition: all 0.2s ease;
  border-left: 3px solid transparent;
}
.sidebar-item:hover { background-color: #f3f4f6; border-left-color: #3b82f6; }
.sidebar-item.active {
  background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
  border-left-color: #3b82f6;
  color: #1d4ed8;
  font-weight: 600;
}

.scrollbar-custom::-webkit-scrollbar { width: 5px; }
.scrollbar-custom::-webkit-scrollbar-track { background: transparent; }
.scrollbar-custom::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

.section-content { animation: fadeIn 0.3s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

/* MODE SOMBRE */
.dark .bg-white { background-color: #1f2937 !important; }
.dark .bg-gray-50 { background-color: #111827 !important; }
.dark .bg-gray-100 { background-color: #374151 !important; }
.dark .text-gray-800 { color: #f3f4f6 !important; }
.dark .text-gray-900 { color: #ffffff !important; }
.dark .text-gray-500, .dark .text-gray-600, .dark .text-gray-700 { color: #9ca3af !important; }
.dark .border-gray-100, .dark .border-gray-200 { border-color: #374151 !important; }
.dark .bg-gradient-to-br.from-purple-50.to-indigo-50 { background: linear-gradient(135deg, #1e3a5f 0%, #1f2937 100%) !important; }
.dark .bg-blue-50 { background-color: #1e3a5f !important; }
.dark .bg-amber-50 { background-color: #451a03 !important; }
.dark .bg-green-100 { background-color: #064e3b !important; }
.dark .bg-purple-100 { background-color: #4c1d95 !important; }
.dark .bg-red-50 { background-color: #7f1d1d !important; }
.dark .bg-indigo-100 { background-color: #3730a3 !important; }
.dark .bg-yellow-50 { background-color: #713f12 !important; }
.dark .sidebar-item.active { background: linear-gradient(135deg, #1e3a5f 0%, #1f2937 100%) !important; color: #60a5fa !important; }
.dark .sidebar-item:hover { background-color: #374151 !important; }
.dark .hover\:bg-gray-50:hover { background-color: #374151 !important; }
.dark .hover\:bg-gray-100:hover { background-color: #374151 !important; }
.dark .hover\:bg-gray-200:hover { background-color: #4b5563 !important; }
</style>
