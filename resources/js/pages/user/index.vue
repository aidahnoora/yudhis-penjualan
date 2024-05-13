<script setup>
import Swal from 'sweetalert2'
import { onMounted, ref } from 'vue'
import api from '../../api'

const users = ref([])
const search = ref('')

const fetchDataUsers = async () => {
  await api.get('/api/users')
    .then(response => {
      users.value = response.data.data.data
    })
}

onMounted(() => {
  fetchDataUsers()
})

const deleteUser = async id => {
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data yang telah dihapus tidak dapat dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
  }).then(async result => {
    if (result.isConfirmed) {
      await api.delete(`/api/users/${id}`)
        .then(() => {
          fetchDataUsers()
          Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success')
        })
        .catch(error => {
          Swal.fire('Error!', 'Gagal menghapus data.', 'error')
        })
    }
  })
}

const filteredUsers = computed(() => {
  return users.value.filter(user => {
    return user.nama.toLowerCase().includes(search.value.toLowerCase())
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
                Data User
              </VCardTitle>
            </template>
            <template #append>
              <RouterLink :to="{ name: 'users.create' }">
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
                  >Cari User</label>
                </VCol>
                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    v-model="search"
                    type="text"
                    placeholder="Cari user berdasarkan nama ..."
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
                  Email
                </th>
                <th scope="col">
                  Actions
                </th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="filteredUsers.length == 0">
                <td
                  colspan="6"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(user, index) in filteredUsers"
                v-else
                :key="index"
              >
                <td>
                  {{ index + 1 }}.
                </td>  
                <td class="text-center">
                  {{ user.nama }}
                </td>
                <td class="text-center">
                  {{ user.no_hp }}
                </td>
                <td class="text-center">
                  {{ user.email }}
                </td>
                <td class="text-center">
                  <RouterLink :to="{ name: 'users.edit', params:{id: user.id} }">
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
                    @click.prevent="deleteUser(user.id)"
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
