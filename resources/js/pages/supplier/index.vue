<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from 'vue'
import api from '../../api'

const suppliers = ref([])
const search = ref('')

const fetchDataSuppliers = async () => {
  await api.get('/api/suppliers')
    .then(response => {
      suppliers.value = response.data.data.data
    })
}

onMounted(() => {
  fetchDataSuppliers()
})

const deleteSupplier = async id => {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  }).then(async result => {
    if (result.isConfirmed) {
      await api.delete(`/api/suppliers/${id}`)
        .then(() => {
          fetchDataSuppliers()
          Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success')
        })
        .catch(error => {
          Swal.fire('Error!', 'Gagal menghapus data.', 'error')
        })
    }
  })
}

const filteredSuppliers = computed(() => {
  return suppliers.value.filter(supplier => {
    return supplier.nama.toLowerCase().includes(search.value.toLowerCase())
  })
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VSheet>
          <VCardItem>
            <template #prepend>
              <VCardTitle>
                Data Supplier
              </VCardTitle>
            </template>
            <template #append>
              <RouterLink :to="{ name: 'suppliers.create' }">
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
                  >Cari Supplier</label>
                </VCol>
                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    v-model="search"
                    type="text"
                    placeholder="Cari supplier berdasarkan nama ..."
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
                  Nama
                </th>
                <th scope="col">
                  Nomor Hp
                </th>
                <th scope="col">
                  Alamat
                </th>
                <th scope="col">
                  Actions
                </th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="filteredSuppliers.length == 0">
                <td
                  colspan="5"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(supplier, index) in filteredSuppliers"
                v-else
                :key="index"
              >
                <td>
                  {{ index + 1 }}.
                </td>  
                <td>
                  {{ supplier.nama }}
                </td>
                <td class="text-center">
                  {{ supplier.no_hp }}
                </td>
                <td class="text-center">
                  {{ supplier.alamat }}
                </td>
                <td class="text-center">
                  <RouterLink :to="{ name: 'suppliers.edit', params:{id: supplier.id} }">
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
                    @click.prevent="deleteSupplier(supplier.id)"
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
