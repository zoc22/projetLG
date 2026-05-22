import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/dashboard'
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('@/modules/dashboard/DashboardView.vue')
    },
    {
      path: '/maison',
      name: 'maison',
      component: () => import('@/modules/maison/MaisonView.vue')
    },
    {
      path: '/adhesion',
      name: 'adhesion',
      component: () => import('@/modules/adhesion/AdhesionView.vue')
    },
    {
      path: '/boutique',
      name: 'boutique',
      component: () => import('@/modules/boutique/BoutiqueView.vue')
    },
    {
      path: '/boutique/panier',
      name: 'cart',
      component: () => import('@/modules/boutique/CartView.vue')
    },
    {
      path: '/boutique/favoris',
      name: 'favorites',
      component: () => import('@/modules/boutique/FavoritesView.vue')
    },
    {
      path: '/mesinfos',
      name: 'mesinfos',
      component: () => import('@/modules/mesinfos/MesInfosView.vue')
    },
    {
      path: '/commandes',
      name: 'commandes',
      component: () => import('@/modules/commandes/CommandesView.vue')
    },
    {
      path: '/equipe',
      name: 'equipe',
      component: () => import('@/modules/equipe/EquipeView.vue')
    },
    {
      path: '/recommandations',
      name: 'recommandations',
      component: () => import('@/modules/recommandations/RecommandationsView.vue')
    },
    {
      path: '/recruteur',
      name: 'recruteur',
      component: () => import('@/modules/recruteur/RecruteurView.vue')
    },
    {
      path: '/classement',
      name: 'classement',
      component: () => import('@/modules/classement/ClassementView.vue')
    },
    {
      path: '/sitesweb',
      name: 'sitesweb',
      component: () => import('@/modules/sitesweb/SitesWebView.vue')
    },
    {
      path: '/webinaires',
      name: 'webinaires',
      component: () => import('@/modules/webinaires/WebinairesView.vue')
    },
    {
      path: '/statistiques',
      name: 'statistiques',
      component: () => import('@/modules/statistiques/StatistiquesView.vue')
    },
    {
      path: '/planremuneration',
      name: 'planremuneration',
      component: () => import('@/modules/planremuneration/PlanRemunerationView.vue')
    },
    {
      path: '/gains',
      name: 'gains',
      component: () => import('@/modules/gains/GainsView.vue')
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('@/modules/contact/ContactView.vue')
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('@/modules/logout/LogoutView.vue')
    }
  ],
})

export default router
