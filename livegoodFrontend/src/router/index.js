import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../core/stores/authStore'

// Layout
import MainLayout from '../components/layouts/MainLayout.vue'

// Auth Views
import LoginView from '../views/LoginView.vue'

// Dashboard Views
import DashboardView from '../views/DashboardView.vue'

const routes = [
  // Redirect root to dashboard
  {
    path: '/',
    redirect: '/dashboard'
  },

  // Auth Routes (no layout)
  {
    path: '/auth',
    children: [
      {
        path: 'login',
        name: 'login',
        component: LoginView,
        meta: { requiresAuth: false }
      },
      {
        path: 'register',
        name: 'register',
        component: () => import('../views/RegisterView.vue'),
        meta: { requiresAuth: false }
      },
      {
        path: 'forgot-password',
        name: 'forgot-password',
        component: () => import('../views/ForgotPasswordView.vue'),
        meta: { requiresAuth: false }
      },
    ]
  },

  // Protected Routes (with layout)
  {
    path: '/dashboard',
    component: MainLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'dashboard',
        component: DashboardView,
      },
      {
        path: 'shop',
        name: 'shop',
        component: () => import('../modules/ecommerce/views/ShopView.vue'),
      },
      {
        path: 'membership',
        name: 'membership',
        component: () => import('../modules/membership/views/MembershipView.vue'),
      },
      {
        path: 'rank',
        name: 'rank',
        component: () => import('../modules/rank/views/RankView.vue'),
      },
      {
        path: 'info',
        name: 'profile-info',
        component: () => import('../modules/profile/views/ProfileView.vue'),
      },
      {
        path: 'orders',
        name: 'orders',
        component: () => import('../modules/orders/views/OrdersView.vue'),
      },
      {
        path: 'team',
        name: 'team',
        component: () => import('../modules/team/views/TeamView.vue'),
      },
      {
        path: 'genealogy',
        name: 'genealogy',
        component: () => import('../modules/genealogy/views/GenealogyView.vue'),
      },
      {
        path: 'referrals',
        name: 'referrals',
        component: () => import('../modules/team/views/ReferralsView.vue'),
      },
      {
        path: 'enroller',
        name: 'enroller',
        component: () => import('../modules/team/views/EnrollerView.vue'),
      },
      {
        path: 'leaderboard',
        name: 'leaderboard',
        component: () => import('../modules/rank/views/LeaderboardView.vue'),
      },
      {
        path: 'websites',
        name: 'websites',
        component: () => import('../modules/marketing/views/WebsitesView.vue'),
      },
      {
        path: 'webinars',
        name: 'webinars',
        component: () => import('../modules/training/views/WebinarsView.vue'),
      },
      {
        path: 'statistics',
        name: 'statistics',
        component: () => import('../modules/statistics/views/StatisticsView.vue'),
      },
      {
        path: 'earnings',
        name: 'earnings',
        component: () => import('../modules/commissions/views/EarningsView.vue'),
      },
      {
        path: 'contact',
        name: 'contact',
        component: () => import('../modules/support/views/ContactView.vue'),
      },
    ]
  },

  // Catch all - 404
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../views/NotFoundView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Navigation Guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  // Check if user is authenticated on first load
  if (!authStore.isAuthenticated && !authStore.user && !to.meta.skipAuthCheck) {
    await authStore.checkAuth()
  }

  // Check if route requires authentication
  if (to.meta.requiresAuth === true && !authStore.isAuthenticated) {
    // Redirect to login but remember where they wanted to go
    next({
      name: 'login',
      query: { redirect: to.fullPath }
    })
  } 
  // Redirect to dashboard if already logged in and trying to access auth pages
  else if (to.meta.requiresAuth === false && authStore.isAuthenticated) {
    next('/dashboard')
  } 
  else {
    next()
  }
})

export default router

