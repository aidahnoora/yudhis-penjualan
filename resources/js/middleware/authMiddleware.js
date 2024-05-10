import { useRouter } from 'vue-router'

export default function authMiddleware() {
  const router = useRouter()

  router.beforeEach((to, from, next) => {
    const isLoggedIn = localStorage.getItem('loggedIn')
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

    if (requiresAuth && !isLoggedIn) {
      // Jika memerlukan autentikasi dan belum login, arahkan ke halaman login
      next('/login') // Ubah menjadi next('/login') agar benar-benar mengarahkan ke halaman login
    } else if (!requiresAuth && isLoggedIn && to.path === '/login') {
      // Jika pengguna sudah login dan mencoba mengakses halaman login, arahkan ke dashboard
      next('/dashboard') // Ubah menjadi next('/dashboard') agar benar-benar mengarahkan ke halaman dashboard
    } else {
      next() // Lanjutkan navigasi
    }
  })
}
