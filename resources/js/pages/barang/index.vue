<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from 'vue'
import api from '../../api'

const barangs = ref([])

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
</script>

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
                  Nama
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
              <tr v-if="barangs.length == 0">
                <td
                  colspan="6"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(barang, index) in barangs"
                v-else
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
                  {{ barang.nama }}
                </td>
                <td class="text-center">
                  {{ barang.stok }}
                </td>
                <td class="text-center">
                  {{ barang.harga }}
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
