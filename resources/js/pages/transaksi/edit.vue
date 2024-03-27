<script setup>
import Swal from 'sweetalert2'
import { computed, onMounted, ref } from "vue"
import { useRoute, useRouter } from 'vue-router'
import api from "../../api"

const router = useRouter()
const route = useRoute()

const customers = ref([])
const customer_id = ref(null)
const nota = ref("")
const tgl_transaksi = ref("")
const total = ref("")

const itemTransaksis = ref([])
const errors = ref([])

const getCustomers = async () => {
  try {
    const response = await api.get('/api/get-customers')

    customers.value = response.data.data.data
  } catch (error) {
    console.error('Error fetching customers:', error)
  }
}

const getItemTransaksis = async () => {
  try {
    const response = await api.get(`api/transaksis/${route.params.id}/items`)

    // console.log('Item Transaksis:', response.data.data)
    itemTransaksis.value = response.data.data.data
  } catch (error) {
    console.error('Error fetching itemTransaksis:', error)
  }
}

onMounted( async () => {
  await api.get(`/api/transaksis/${route.params.id}`)
    .then(response => {
      customer_id.value = response.data.data.customer_id
      nota.value = response.data.data.nota
      tgl_transaksi.value = response.data.data.tgl_transaksi
      total.value = response.data.data.total
    }),
  getCustomers(),
  getItemTransaksis()
})

const updateTransaksi = async () => {
  const formData = new FormData()

  formData.append("customer_id", customer_id.value)
  formData.append("nota", nota.value)
  formData.append("tgl_transaksi", tgl_transaksi.value)
  formData.append("total", total.value)
  formData.append("_method", "PATCH")

  try {
    await api.post(`/api/transaksis/${route.params.id}`, formData)
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: 'Data berhasil diperbarui!',
    }).then(() => {
      router.push({ path: '/transaksis' })
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Gagal memperbarui data. Ulangi lagi.',
    })
    errors.value = error.response.data
  }
}

const customerOptions = computed(() => {
  return customers.value.map(customer => ({
    title: customer.nama,
    value: customer.id,
  }))
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Edit Data Transaksi">
        <VCardText>
          <VForm @submit.prevent="updateTransaksi">
            <VCol cols="12">
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label for="customer_id">Customer</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VSelect
                    v-model="customer_id"
                    :items="customerOptions"
                    dense
                    density="compact"
                  />

                  <VCol v-if="errors.customer_id">
                    <span style="color: red;">*{{ errors.customer_id[0] }}</span>
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
                  <label for="nota">Nomor Nota</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="nota"
                    v-model="nota"
                    prepend-inner-icon="bx-file"
                    type="text"
                    density="compact"
                  />
                  <VCol v-if="errors.nota">
                    <span style="color: red;">*{{ errors.nota[0] }}</span>
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
                  <label for="tgl_transaksi">Tanggal Transaksi</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="tgl_transaksi"
                    v-model="tgl_transaksi"
                    type="date"
                    density="compact"
                  />
                  <VCol v-if="errors.tgl_transaksi">
                    <span style="color: red;">*{{ errors.tgl_transaksi[0] }}</span>
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
                  <label for="total">Total</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="total"
                    v-model="total"
                    type="number"
                    density="compact"
                    disabled
                  />
                  <VCol v-if="errors.total">
                    <span style="color: red;">*{{ errors.total[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            
            <VCol cols="12">
              <VRow no-gutters>
                <VCol cols="12">
                  <h4>Data Barang</h4>
                </VCol>
              </VRow>
            </VCol>
            
            <VCol cols="12">
              <VTable>
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(item, index) in itemTransaksis"
                    :key="index"
                  >
                    <td>{{ item.barang.nama }}</td>
                    <td class="text-center">
                      {{ item.qty }}
                    </td>
                    <td class="text-center">
                      {{ item.barang.harga }}
                    </td>
                    <td class="text-center">
                      {{ item.subtotal }}
                    </td>
                  </tr>
                </tbody>
              </VTable>
            </VCol>
    
            <VCol class="text-center">
              <RouterLink to="/transaksis">
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
