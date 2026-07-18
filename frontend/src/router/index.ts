import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import LoginView from '@/views/Auth/LoginView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView,
      meta: { requiresAuth: false },
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      meta: { requiresAuth: true },
      component: () => import('@/views/DashboardView.vue'),
    },
    {
      path: '/posts',
      name: 'posts',
      meta: { requiresAuth: true },
      component: () => import('@/views/posts/PostListView.vue'),
    },
    {
      path: '/posts/new',
      name: 'posts-create',
      meta: { requiresAuth: true },
      component: () => import('@/views/posts/PostCreateView.vue'),
    },
    {
      path: '/posts/:id/edit',
      name: 'posts-edit',
      meta: { requiresAuth: true },
      component: () => import('@/views/posts/PostEditView.vue'),
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/login',
    },
  ],
})

router.beforeEach((to, _from) => {
  const auth = useAuthStore()
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'login' }
  }
  if (to.name === 'login' && auth.isAuthenticated) {
    return { name: 'dashboard' }
  }
})

export default router
