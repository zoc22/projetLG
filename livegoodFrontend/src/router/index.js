import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../core/stores/authStore'

// Layout
import MainLayout from '../components/layouts/MainLayout.vue'

// Auth Views
import LoginView from '../views/LoginView.vue'

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
      // {
      //   path: 'forgot-password',
      //   name: 'forgot-password',
      //   component: () => import('../views/ForgotPasswordView.vue'),
      //   meta: { requiresAuth: false }
      // },
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
        component: () => import('../modules/dashboard/pages/Dashboard.vue'),
      },
      {
        path: 'shop',
        name: 'shop',
        component: () => import('../modules/ecommerce/pages/ProductList.vue'),
      },
      {
        path: 'membership',
        name: 'membership',
        component: () => import('../modules/membership/pages/MyMembership.vue'),
      },
      {
        path: 'rank',
        name: 'rank',
        component: () => import('../modules/rank/pages/RankRequirements.vue'),
      },
      {
        path: 'info',
        name: 'profile-info',
        component: () => import('../modules/profile/pages/MyInfo.vue'),
      },
      {
        path: 'orders',
        name: 'orders',
        component: () => import('../modules/orders/pages/OrderHistory.vue'),
      },
      {
        path: 'team',
        name: 'team',
        component: () => import('../modules/team/pages/MyTeam.vue'),
      },
      {
        path: 'genealogy',
        name: 'genealogy',
        component: () => import('../modules/genealogy/pages/MatrixView.vue'),
      },
      {
        path: 'referrals',
        name: 'referrals',
        component: () => import('../modules/team/pages/MyReferrals.vue'),
      },
      {
        path: 'enroller',
        name: 'enroller',
        component: () => import('../modules/team/pages/MyEnroller.vue'),
      },
      {
        path: 'websites',
        name: 'websites',
        component: () => import('../modules/websites/pages/WebsitesManager.vue'),
      },
      {
        path: 'webinars',
        name: 'webinars',
        component: () => import('../modules/training/pages/Webinars.vue'),
      },
      {
        path: 'statistics',
        name: 'statistics',
        component: () => import('../modules/statistics/pages/StatsDashboard.vue'),
      },
      {
        path: 'earnings',
        name: 'earnings',
        component: () => import('../modules/commissions/pages/Earnings.vue'),
      },
      {
        path: 'contact',
        name: 'contact',
        component: () => import('../modules/support/pages/OpenTicket.vue'),
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
router.beforeEach(async (to, from) => {
  const authStore = useAuthStore()
  
  // Check if user is authenticated on first load
  if (!authStore.isAuthenticated && !authStore.user && !to.meta.skipAuthCheck) {
    await authStore.checkAuth()
  }

  // Check if route requires authentication
  if (to.meta.requiresAuth === true && !authStore.isAuthenticated) {
    // Redirect to login but remember where they wanted to go
    return {
      name: 'login',
      query: { redirect: to.fullPath }
    }
  } 
  // Redirect to login if trying to access auth pages while not authenticated
  else if (to.meta.requiresAuth === false && !authStore.isAuthenticated && to.path.startsWith('/auth')) {
    return true
  }
  // Redirect to dashboard if already logged in and trying to access auth pages
  else if (to.meta.requiresAuth === false && authStore.isAuthenticated && to.path.startsWith('/auth')) {
    return '/dashboard'
  }
})

export default router

