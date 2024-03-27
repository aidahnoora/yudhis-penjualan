// authMiddleware.js
import { useRouter } from 'vue-router'

export default function authMiddleware() {
  const router = useRouter()
  const isLoggedIn = localStorage.getItem('loggedIn')

  router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

    if (requiresAuth && !isLoggedIn) {
      // Jika memerlukan autentikasi dan belum login, arahkan ke halaman login
      next('/login')
    } else if (!requiresAuth && isLoggedIn && to.path === '/login') {
      // Jika pengguna sudah login dan mencoba mengakses halaman login, arahkan ke dashboard
      next('/dashboard')
    } else {
      next() // Lanjutkan navigasi
    }
  })
}
