<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VSheet>
          <VCardItem>
            <template #prepend>
              <VCardTitle>
                Data Barang
              </VCardTitle>
            </template>
            <template #append>
              <RouterLink :to="{ name: 'barangs.create' }">
                <VBtn
                  color="primary"
                  prepend-icon="bx-plus"
                >
                  TAMBAH
                </VBtn>
              </RouterLink>
            </template>
          </VCardItem>
        </VSheet>
        <VCardText>
          <VCol cols="12">
            <VForm>
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label
                    for="search"
                    style="font-weight: bold;"
                  >Cari Barang</label>
                </VCol>
                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    v-model="search"
                    type="text"
                    placeholder="Cari barang berdasarkan nama barang ..."
                    density="compact"
                  />
                </VCol>
              </VRow>
            </VForm>
          </VCol>
          <VTable>
            <thead>
              <tr>
                <th scope="col">
                  No.
                </th>
                <th scope="col">
                  Image
                </th>
                <th scope="col">
                  Kode
                </th>
                <th scope="col">
                  Nama
                </th>
                <th scope="col">
                  Tanggal Expired
                </th>
                <th scope="col">
                  Stok
                </th>
                <th scope="col">
                  Harga
                </th>
                <th scope="col">
                  Actions
                </th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="filteredBarangs.length === 0">
                <td
                  colspan="8"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(barang, index) in filteredBarangs"
                :key="index"
              >
                <td>
                  {{ index + 1 }}.
                </td>
                <td>
                  <img
                    :src="barang.image"
                    width="100"
                    class="rounded-3"
                  >
                </td>
                <td>
                  {{ barang.kode }}
                </td>
                <td>
                  {{ barang.nama }}
                </td>
                <td>
                  {{ barang.tgl_expired }}
                </td>
                <td class="text-center">
                  {{ barang.stok }}
                </td>
                <td class="text-center">
                  Rp. {{ barang.harga }}
                </td>
                <td class="text-center">
                  <RouterLink :to="{ name: 'barangs.edit', params:{id: barang.id} }">
                    <VBtn
                      color="warning"
                      append-icon="bx-edit"
                    >
                      EDIT
                    </VBtn>
                  </RouterLink>
                  <VBtn 
                    color="error" 
                    style="margin: 2px;"
                    append-icon="bx-eraser"
                    @click.prevent="deleteBarang(barang.id)"
                  >
                    HAPUS
                  </VBtn>
                </td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<script setup>
import Swal from 'sweetalert2'
import { computed, onMounted, ref } from 'vue'
import api from '../../api'

const barangs = ref([])
const search = ref('')

const fetchDataBarangs = async () => {
  await api.get('/api/barangs')
    .then(response => {
      barangs.value = response.data.data.data
    })
}

onMounted(() => {
  fetchDataBarangs()
})

const deleteBarang = async id => {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  }).then(async result => {
    if (result.isConfirmed) {
      await api.delete(`/api/barangs/${id}`)
        .then(() => {
          fetchDataBarangs()
          Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success')
        })
        .catch(error => {
          Swal.fire('Error!', 'Gagal menghapus data.', 'error')
        })
    }
  })
}

const filteredBarangs = computed(() => {
  return barangs.value.filter(barang => {
    return barang.nama.toLowerCase().includes(search.value.toLowerCase())
  })
})
</script>

<style scoped>
/* Styling sesuai kebutuhan Anda */
</style>
