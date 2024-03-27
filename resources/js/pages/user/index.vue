<script setup>
import { onMounted, ref } from 'vue'
import api from '../../api'

const users = ref([])

const fetchDataUsers = async () => {
  await api.get('/api/users')
    .then(response => {
      users.value = response.data.data.data
    })
}

onMounted(() => {
  fetchDataUsers()
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
                  Nama
                </th>
                <th scope="col">
                  Email
                </th>
              </tr>
            </thead>

            <tbody>
              <tr v-if="users.length == 0">
                <td
                  colspan="4"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(user, index) in users"
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
                  {{ user.email }}
                </td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
