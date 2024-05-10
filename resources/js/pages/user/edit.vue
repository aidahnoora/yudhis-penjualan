<script setup>
import Swal from 'sweetalert2'
import { ref } from "vue"
import { useRoute, useRouter } from 'vue-router'
import api from "../../api"

const router = useRouter()
const route = useRoute()

const nama = ref("")
const username = ref("")
const email = ref("")
const no_hp = ref("")
const password = ref("")

const errors = ref([])

const isPasswordVisible = ref(false)

onMounted( async () => {
  await api.get(`/api/users/${route.params.id}`)
    .then(response => {
      nama.value = response.data.data.nama
      username.value = response.data.data.username
      email.value = response.data.data.email
      no_hp.value = response.data.data.no_hp
      password.value = response.data.data.password
    })
})

const updateUser = async () => {
  let formData = new FormData()

  formData.append("nama", nama.value)
  formData.append("username", username.value)
  formData.append("email", email.value)
  formData.append("no_hp", no_hp.value)
  formData.append("password", password.value)
  formData.append("_method", "PATCH")

  await api.post(`/api/users/${route.params.id}`, formData)
    .then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data berhasil disimpan!',
      }).then(() => {
        router.push({ path: '/users' })
      })
    })
    .catch(error => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Gagal menyimpan data. Ulangi lagi.',
      })
      errors.value = error.response.data
    })
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Edit Data User">
        <VCardText>
          <VForm @submit.prevent="updateUser">
            <VCol cols="12">
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label for="nama">Nama</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="nama"
                    v-model="nama"
                    placeholder="Masukkan nama"
                    persistent-placeholder
                    type="text"
                    density="compact"
                  />
                  <VCol v-if="errors.nama">
                    <span style="color: red;">*{{ errors.nama[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label for="username">Username</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="username"
                    v-model="username"
                    placeholder="Masukkan username"
                    persistent-placeholder
                    type="text"
                    density="compact"
                  />
                  <VCol v-if="errors.username">
                    <span style="color: red;">*{{ errors.username[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label for="email">Email</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="email"
                    v-model="email"
                    placeholder="Masukkan email"
                    persistent-placeholder
                    type="email"
                    density="compact"
                  />
                  <VCol v-if="errors.email">
                    <span style="color: red;">*{{ errors.email[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label for="no_hp">Nomor Hp</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="no_hp"
                    v-model="no_hp"
                    placeholder="Masukkan nomor hp"
                    persistent-placeholder
                    type="text"
                    density="compact"
                  />
                  <VCol v-if="errors.no_hp">
                    <span style="color: red;">*{{ errors.no_hp[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label for="password">Password</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="password"
                    v-model="password"
                    placeholder="············"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                    persistent-placeholder
                    density="compact"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  />
                  <span style="color: red;">*Kosongkan jika tidak mengubah password.</span>
                  <VCol v-if="errors.password">
                    <span style="color: red;">*{{ errors.password[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            <VCol class="text-center">
              <RouterLink to="/users">
                <VBtn
                  prepend-icon="bx-x"
                  color="warning"
                >
                  KEMBALI
                </VBtn>
              </RouterLink>
              <VBtn
                type="submit"
                color="primary"
                append-icon="bx-save"
                style="margin-left: 2px;"
              >
                UPDATE
              </VBtn>
            </VCol>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
