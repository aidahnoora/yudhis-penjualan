<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from 'vue'
import api from '../../api'

const pembelians = ref([])

const fetchDataPembelians = async () => {
  await api.get('/api/pembelians')
    .then(response => {
      pembelians.value = response.data.data.data
    })
}

onMounted(() => {
  fetchDataPembelians()
})

const deletePembelian = async id => {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  }).then(async result => {
    if (result.isConfirmed) {
      await api.delete(`/api/pembelians/${id}`)
        .then(() => {
          fetchDataPembelians()
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
  <VCol
    cols="12"
    md="12"
  >
    <AnalyticsFinanceTab />
  </VCol>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VSheet>
          <VCardItem>
            <template #prepend>
              <VCardTitle>
                Data Transaksi Pembelian
              </VCardTitle>
            </template>
            <template #append>
              <RouterLink :to="{ name: 'pembelians.create' }">
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
                  Supplier
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
              <tr v-if="pembelians.length == 0">
                <td
                  colspan="6"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(pembelian, index) in pembelians"
                v-else
                :key="index"
              >
                <td>
                  {{ index + 1 }}.
                </td>
                <td class="text-center">
                  {{ pembelian.nota }}
                </td>
                <td class="text-center">
                  {{ pembelian.supplier.nama }}
                </td>
                <td class="text-center">
                  {{ pembelian.tgl_transaksi }}
                </td>
                <td class="text-center">
                  Rp. {{ pembelian.total }}
                </td>
                <td class="text-center">
                  <RouterLink :to="{ name: 'pembelians.show', params:{id: pembelian.id} }">
                    <VBtn
                      color="info"
                      size="small"
                      append-icon="mdi-eye"
                    >
                      DETAIL
                    </VBtn>
                  </RouterLink>
                  <RouterLink :to="{ name: 'pembelians.edit', params:{id: pembelian.id} }">
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
                    @click.prevent="deletePembelian(pembelian.id)"
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
