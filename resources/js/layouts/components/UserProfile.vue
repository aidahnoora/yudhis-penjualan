<script setup>
import logo from '@images/avatars/yudhis-logo.jpeg'
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'
import api from '../../api'

const router = useRouter()

const handleLogout = async () => {
  const token = localStorage.getItem('token') // Ambil token dari localStorage

  try {
    await api.post('/api/logout', null, { // Mengirim token sebagai bagian dari permintaan
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    
    localStorage.removeItem('loggedIn')
    localStorage.removeItem('token')

    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: 'Logout berhasil!',
    }).then(() => {
      router.push({ name: 'login' })

      console.log('Redirecting to login page...')
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Gagal logout!',
    })
    console.error('Logout error:', error)
  }
}
</script>


<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    color="success"
    bordered
  >
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
    >
      <VImg :src="logo" />

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
      >
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="logo" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              Web Area
            </VListItemTitle>
            <VListItemSubtitle>Admin</VListItemSubtitle>
          </VListItem>
          <VDivider class="my-2" />

          <!-- 👉 Profile -->
          <!--
            <VListItem
            link
            to="/profile"
            >
            <template #prepend>
            <VIcon
            class="me-2"
            icon="bx-user"
            size="22"
            />
            </template>

            <VListItemTitle>Profile</VListItemTitle> 
            </VListItem> 
          -->

          <!-- 👉 Settings -->
          <!--
            <VListItem
            link
            to="/settings"
            >
            <template #prepend>
            <VIcon
            class="me-2"
            icon="bx-cog"
            size="22"
            />
            </template>

            <VListItemTitle>Settings</VListItemTitle>
            </VListItem> 
          -->

          <!-- Divider -->

          <!-- 👉 Logout -->
          <VListItem @click="handleLogout">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="bx-log-out"
                size="22"
              />
            </template>
            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
