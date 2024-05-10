<script setup>
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'
import api from '../api'

const form = ref({
  email: '',
  password: '',
  remember: false,
})

const router = useRouter()
const errors = ref([])
const isPasswordVisible = ref(false)
const loggedIn = localStorage.getItem('loggedIn')

// const token = localStorage.getItem('token')
const user = []
let loginFailed = null

const login = async () => {
  if (form.value.email && form.value.password) {
    try {
      await api.get('/sanctum/csrf-cookie')

      const response = await api.post('/api/login', {
        email: form.value.email,
        password: form.value.password,
      })

      const token = response.data.data.access_token

      localStorage.setItem('loggedIn', true)
      localStorage.setItem('token', token)

      // console.log(token)

      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Login berhasil!',
      }).then(() => {
        router.push({ name: 'dashboard' })
      })
    } catch (error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Gagal login!',
      })
      errors.value = error.response.data
      loginFailed = true
    }
  } else {
    validation.email = !form.value.email
    validation.password = !form.value.password
  }
}
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <VCard
      class="auth-card pa-4 pt-7"
      max-width="448"
    >
      <VCardItem class="justify-center">
        <template #prepend>
          <div class="d-flex">
            <div class="d-flex text-primary">
              <img
                src="/yudhis-logo.jpeg"
                style="max-width: 100px; height: auto;"
              >
            </div>
          </div>
        </template>

        <VCardTitle class="text-2xl font-weight-bold">
          Yudhis Motor
        </VCardTitle>
      </VCardItem>

      <VCardText class="pt-2">
        <h5 class="text-h5 mb-1">
          Sistem Informasi Penjualan
        </h5>
        <p class="mb-0">
          Please sign-in to your account and start the adventure
        </p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="login">
          <VRow>
            <!-- email -->
            <VCol cols="12">
              <VTextField
                v-model="form.email"
                autofocus
                placeholder="wildan@email.com"
                label="Email"
                type="email"
              />
              <VCol v-if="errors.email">
                <span style="color: red;">*{{ errors.email[0] }}</span>
              </VCol>
            </VCol>

            <!-- password -->
            <VCol cols="12">
              <VTextField
                v-model="form.password"
                label="Password"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
              />
              <VCol v-if="errors.password">
                <span style="color: red;">*{{ errors.password[0] }}</span>
              </VCol>

              <!-- login button -->
              <VCol>
                <VBtn
                  block
                  type="submit"
                >
                  Login
                </VBtn>
              </VCol>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>
