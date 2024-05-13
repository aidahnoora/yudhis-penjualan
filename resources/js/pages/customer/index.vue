<script setup>
import Swal from 'sweetalert2'
import { computed, onMounted, ref } from 'vue'
import api from '../../api'

const customers = ref([])
const search = ref('')

const fetchDataCustomers = async () => {
  await api.get('/api/customers')
    .then(response => {
      customers.value = response.data.data.data
    })
}

onMounted(() => {
  fetchDataCustomers()
})

// const searchCustomer = async () => {
//   await api.get('/api/customers/search', {
//     params: {
//       keyword: search.value,
//     },
//   })
//     .then(response => {
//       customers.value = response.data.items
//     })
//     .catch(error => {
//       console.error(error)
//     })
// }

const deleteCustomer = async id => {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  }).then(async result => {
    if (result.isConfirmed) {
      await api.delete(`/api/customers/${id}`)
        .then(() => {
          fetchDataCustomers()
          Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success')
        })
        .catch(error => {
          Swal.fire('Error!', 'Gagal menghapus data.', 'error')
        })
    }
  })
}

const filteredCustomers = computed(() => {
  return customers.value.filter(customer => {
    return customer.nama.toLowerCase().includes(search.value.toLowerCase())
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
                Data Customer
              </VCardTitle>
            </template>
            <template #append>
              <RouterLink :to="{ name: 'customers.create' }">
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
                  >Cari Customer</label>
                </VCol>
                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    v-model="search"
                    type="text"
                    placeholder="Cari customer berdasarkan nama ..."
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
              <tr v-if="filteredCustomers.length == 0">
                <td
                  colspan="5"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(customer, index) in filteredCustomers"
                v-else
                :key="index"
              >
                <td>
                  {{ index + 1 }}.
                </td>  
                <td>
                  {{ customer.nama }}
                </td>
                <td class="text-center">
                  {{ customer.no_hp }}
                </td>
                <td class="text-center">
                  {{ customer.alamat }}
                </td>
                <td class="text-center">
                  <RouterLink :to="{ name: 'customers.edit', params:{id: customer.id} }">
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
                    @click.prevent="deleteCustomer(customer.id)"
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
