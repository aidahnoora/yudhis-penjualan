<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from "vue"
import { useRoute, useRouter } from 'vue-router'
import api from "../../api"

const router = useRouter()
const route = useRoute()

const image = ref("")
const nama = ref("")
const stok = ref("")
const harga = ref("")
const kode = ref("")
const tgl_expired = ref("")
const errors = ref([])

onMounted( async () => {
  await api.get(`/api/barangs/${route.params.id}`)
    .then(response => {
      nama.value = response.data.data.nama
      stok.value = response.data.data.stok
      harga.value = response.data.data.harga
      kode.value = response.data.data.kode
      tgl_expired.value = response.data.data.tgl_expired
    })
})

const handleFileChange = e => {
  image.value = e.target.files[0]
}
    
const updateBarang = async () => {
  let formData = new FormData()
    
  formData.append("image", image.value)
  formData.append("nama", nama.value)
  formData.append("stok", stok.value)
  formData.append("harga", harga.value)
  formData.append("kode", kode.value)
  formData.append("tgl_expired", tgl_expired.value)
  formData.append("_method", "PATCH")
    
  await api.post(`/api/barangs/${route.params.id}`, formData)
    .then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data berhasil diperbarui!',
      }).then(() => {
        router.push({ path: '/barangs' })
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
      <VCard title="Edit Data Barang">
        <VCardText>
          <VForm @submit.prevent="updateBarang">
            <VCol cols="12">
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label for="image">Image</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="image"
                    type="file"
                    density="compact"
                    @change="handleFileChange($event)"
                  />
                  <VCol v-if="errors.image">
                    <span style="color: red;">*{{ errors.image[0] }}</span>
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
                  <label for="kode">Kode Barang</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="kode"
                    v-model="kode"
                    type="text"
                    density="compact"
                  />
                  <VCol v-if="errors.kode">
                    <span style="color: red;">*{{ errors.kode[0] }}</span>
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
                  <label for="nama">Nama Barang</label>
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
                  <label for="tgl_expired">Tanggal Expired</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="tgl_expired"
                    v-model="tgl_expired"
                    type="date"
                    density="compact"
                  />
                  <VCol v-if="errors.tgl_expired">
                    <span style="color: red;">*{{ errors.tgl_expired[0] }}</span>
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
                  <label for="stok">Stok</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="stok"
                    v-model="stok"
                    type="number"
                    density="compact"
                  />
                  <VCol v-if="errors.stok">
                    <span style="color: red;">*{{ errors.stok[0] }}</span>
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
                  <label for="harga">Harga Satuan (Rp)</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="harga"
                    v-model="harga"
                    type="number"
                    density="compact"
                  />
                  <VCol v-if="errors.harga">
                    <span style="color: red;">*{{ errors.harga[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            <VCol class="text-center">
              <RouterLink to="/barangs">
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
