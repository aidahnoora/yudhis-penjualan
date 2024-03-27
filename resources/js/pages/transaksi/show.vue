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
      <VCard title="Detail Data Transaksi">
        <VCardText>
          <VForm>
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
                    disabled
                  />
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
                    type="text"
                    density="compact"
                    disabled
                  />
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
                    disabled
                  />
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
            </VCol>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
