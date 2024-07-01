<script setup>
import Swal from 'sweetalert2'
import { ref } from "vue"
import { useRouter } from 'vue-router'
import api from "../../api"

const router = useRouter()

const image = ref("")
const nama = ref("")
const stok = ref("")
const harga = ref("")
const kode = ref("")
const tgl_expired = ref("")
const errors = ref([])

const handleFileChange = e => {
  image.value = e.target.files[0]
}
    
const storeBarang = async () => {
  let formData = new FormData()

  formData.append("image", image.value)
  formData.append("nama", nama.value)
  formData.append("stok", stok.value)
  formData.append("harga", harga.value)
  formData.append("kode", kode.value)
  formData.append("tgl_expired", tgl_expired.value)

  await api.post('/api/barangs', formData)
    .then(() => {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: 'Data berhasil disimpan!',
      }).then(() => {
        router.push({ path: '/barangs' })
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
      <VCard title="Tambah Data Barang">
        <VCardText>
          <VForm @submit.prevent="storeBarang">
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
                    prepend-inner-icon="bx-image"
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
                    prepend-inner-icon="bx-barcode"
                    placeholder="Masukkan kode barang"
                    persistent-placeholder
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
                    prepend-inner-icon="bx-cart"
                    placeholder="Masukkan nama barang"
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
                  <label for="tgl_expired">Tanggal Expired</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="tgl_expired"
                    v-model="tgl_expired"
                    prepend-inner-icon="bx-time"
                    persistent-placeholder
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
                    prepend-inner-icon="bx-add-to-queue"
                    placeholder="0"
                    persistent-placeholder
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
                    prepend-inner-icon="bx-money"
                    placeholder="0"
                    persistent-placeholder
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
                SIMPAN
              </VBtn>
            </VCol>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
