<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from "vue"
import { useRouter } from 'vue-router'
import api from "../../api"

const router = useRouter()
const route = useRoute()

const nama = ref("")
const no_hp = ref("")
const alamat = ref("")
const errors = ref([])

onMounted( async () => {
  await api.get(`/api/customers/${route.params.id}`)
    .then(response => {
      nama.value = response.data.data.nama
      no_hp.value = response.data.data.no_hp
      alamat.value = response.data.data.alamat
    })
})

const updateCustomer = async () => {
  let formData = new FormData()

  formData.append("nama", nama.value)
  formData.append("no_hp", no_hp.value)
  formData.append("alamat", alamat.value)
  formData.append("_method", "PATCH")

  await api.post(`/api/customers/${route.params.id}`, formData)
    .then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data berhasil diperbarui!',
      }).then(() => {
        router.push({ path: '/customers' })
      })
    })
    .catch(error => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Gagal memperbarui data. Ulangi lagi.',
      })
      errors.value = error.response.data
    })
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Edit Data Supplier">
        <VCardText>
          <VForm @submit.prevent="updateCustomer">
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
                  <label for="no_hp">Nomor Hp</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="no_hp"
                    v-model="no_hp"
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
                  <label for="alamat">Alamat</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="alamat"
                    v-model="alamat"
                    type="text"
                    density="compact"
                  />
                  <VCol v-if="errors.alamat">
                    <span style="color: red;">*{{ errors.alamat[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            <VCol class="text-center">
              <RouterLink to="/customers">
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
