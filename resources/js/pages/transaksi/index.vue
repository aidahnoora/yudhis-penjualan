<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from 'vue'
import api from '../../api'

const transaksis = ref([])

const fetchDataTransaksis = async () => {
  await api.get('/api/transaksis')
    .then(response => {
      transaksis.value = response.data.data.data
    })
}

onMounted(() => {
  fetchDataTransaksis()
})

const deleteTransaksi = async id => {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  }).then(async result => {
    if (result.isConfirmed) {
      await api.delete(`/api/transaksis/${id}`)
        .then(() => {
          fetchDataTransaksis()
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
                Data Transaksi Penjualan
              </VCardTitle>
            </template>
            <template #append>
              <RouterLink :to="{ name: 'transaksis.create' }">
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
                  Nota
                </th>
                <th scope="col">
                  Customer
                </th>
                <th scope="col">
                  Tanggal Transaksi
                </th>
                <th scope="col">
                  Total
                </th>
                <th scope="col">
                  Actions
                </th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="transaksis.length == 0">
                <td
                  colspan="6"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(transaksi, index) in transaksis"
                v-else
                :key="index"
              >
                <td>
                  {{ index + 1 }}.
                </td>
                <td class="text-center">
                  {{ transaksi.nota }}
                </td>
                <td class="text-center">
                  {{ transaksi.customer.nama }}
                </td>
                <td class="text-center">
                  {{ transaksi.tgl_transaksi }}
                </td>
                <td class="text-center">
                  {{ transaksi.total }}
                </td>
                <td class="text-center">
                  <RouterLink :to="{ name: 'transaksis.show', params:{id: transaksi.id} }">
                    <VBtn
                      color="info"
                      size="small"
                      append-icon="mdi-eye"
                    >
                      DETAIL
                    </VBtn>
                  </RouterLink>
                  <RouterLink :to="{ name: 'transaksis.edit', params:{id: transaksi.id} }">
                    <VBtn
                      color="warning"
                      style="margin: 2px;"
                      size="small"
                      append-icon="bx-edit"
                    >
                      EDIT
                    </VBtn>
                  </RouterLink>
                  <VBtn 
                    color="error"
                    size="small"
                    append-icon="bx-eraser"
                    @click.prevent="deleteTransaksi(transaksi.id)"
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
