import authMiddleware from '@/middleware/authMiddleware'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: '/login' },
    {
      path: '/',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: 'dashboard',
          name: 'dashboard',
          component: () => import('../pages/dashboard.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'suppliers',
          name: 'suppliers.index',
          component: () => import('../pages/supplier/index.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'suppliers/create',
          name: 'suppliers.create',
          component: () => import('../pages/supplier/create.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'suppliers/edit/:id',
          name: 'suppliers.edit',
          component: () => import('../pages/supplier/edit.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'users',
          name: 'users.index',
          component: () => import('../pages/user/index.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'users',
          name: 'users.create',
          component: () => import('../pages/user/create.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'users/edit/:id',
          name: 'users.edit',
          component: () => import('../pages/user/edit.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'barangs',
          name: 'barangs.index',
          component: () => import('../pages/barang/index.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'barangs/create',
          name: 'barangs.create',
          component: () => import('../pages/barang/create.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'barangs/edit/:id',
          name: 'barangs.edit',
          component: () => import('../pages/barang/edit.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'customers',
          name: 'customers.index',
          component: () => import('../pages/customer/index.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'customers/create',
          name: 'customers.create',
          component: () => import('../pages/customer/create.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'customers/edit/:id',
          name: 'customers.edit',
          component: () => import('../pages/customer/edit.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'transaksis',
          name: 'transaksis.index',
          component: () => import('../pages/transaksi/index.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'transaksis/create',
          name: 'transaksis.create',
          component: () => import('../pages/transaksi/create.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'transaksis/edit/:id',
          name: 'transaksis.edit',
          component: () => import('../pages/transaksi/edit.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'transaksis/show/:id',
          name: 'transaksis.show',
          component: () => import('../pages/transaksi/show.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'pembelians',
          name: 'pembelians.index',
          component: () => import('../pages/pembelian/index.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'pembelians/create',
          name: 'pembelians.create',
          component: () => import('../pages/pembelian/create.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'pembelians/edit/:id',
          name: 'pembelians.edit',
          component: () => import('../pages/pembelian/edit.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'pembelians/show/:id',
          name: 'pembelians.show',
          component: () => import('../pages/pembelian/show.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'laporan-penjualan',
          name: 'laporan-penjualan',
          component: () => import('../pages/laporan/penjualan.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'laporan-pembelian',
          name: 'laporan-pembelian',
          component: () => import('../pages/laporan/pembelian.vue'),
          meta: { requiresAuth: true },
        },
        {
          path: 'logout',
          name: 'logout',
          beforeEnter: (to, from, next) => {
            next('login')
          },
          meta: { requiresAuth: true },
        },
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      children: [
        {
          path: 'login',
          name: 'login',
          component: () => import('../pages/login.vue'),
        },
        {
          path: 'register',
          component: () => import('../pages/register.vue'),
        },
        {
          path: '/:pathMatch(.*)*',
          component: () => import('../pages/[...all].vue'),
        },
      ],
    },
  ],
})

router.beforeEach(authMiddleware)

export default router
