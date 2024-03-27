<script setup>
import Swal from 'sweetalert2'
import { ref } from "vue"
import { useRouter } from 'vue-router'
import api from "../../api"

const router = useRouter()

const nama = ref("")
const no_hp = ref("")
const alamat = ref("")
const errors = ref([])

const storeCustomer = async () => {
  let formData = new FormData()

  formData.append("nama", nama.value)
  formData.append("no_hp", no_hp.value)
  formData.append("alamat", alamat.value)

  await api.post('/api/customers', formData)
    .then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data berhasil disimpan!',
      }).then(() => {
        router.push({ path: '/customers' })
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
      <VCard title="Tambah Data Customer">
        <VCardText>
          <VForm @submit.prevent="storeCustomer">
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
                    prepend-inner-icon="bx-user"
                    placeholder="John"
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
                  <label for="no_hp">Nomor Hp</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="no_hp"
                    v-model="no_hp"
                    prepend-inner-icon="bx-phone"
                    placeholder="081234567890"
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
                  <label for="alamat">Alamat</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="alamat"
                    v-model="alamat"
                    prepend-inner-icon="bx-map"
                    placeholder="Jln. Hasanudin No. 12"
                    persistent-placeholder
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
                SIMPAN
              </VBtn>
            </VCol>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
