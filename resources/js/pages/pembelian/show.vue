<script setup>
import { computed, onMounted, ref } from "vue"
import { useRoute } from 'vue-router'
import api from "../../api"

const route = useRoute()

const suppliers = ref([])
const supplier_id = ref(null)
const nota = ref("")
const tgl_transaksi = ref("")
const total = ref("")

const itemTransaksis = ref([])

const getSuppliers = async () => {
  try {
    const response = await api.get('/api/get-suppliers')

    suppliers.value = response.data.data.data
  } catch (error) {
    console.error('Error fetching suppliers:', error)
  }
}

const getItemTransaksis = async () => {
  try {
    const response = await api.get(`api/pembelians/${route.params.id}/items`)

    // console.log('Item Transaksis:', response.data.data)
    itemTransaksis.value = response.data.data.data
  } catch (error) {
    console.error('Error fetching itemTransaksis:', error)
  }
}

onMounted( async () => {
  await api.get(`/api/pembelians/${route.params.id}`)
    .then(response => {
      supplier_id.value = response.data.data.supplier_id
      nota.value = response.data.data.nota
      tgl_transaksi.value = response.data.data.tgl_transaksi
      total.value = response.data.data.total
    }),
  getSuppliers(),
  getItemTransaksis()
})

const supplierOptions = computed(() => {
  return suppliers.value.map(supplier => ({
    title: supplier.nama,
    value: supplier.id,
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
                  <label for="supplier_id">Supplier</label>
                </VCol>

                <VCol
                  cols="12"
                  md="9"
                >
                  <VSelect
                    v-model="supplier_id"
                    :items="supplierOptions"
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
                      Rp. {{ item.barang.harga }}
                    </td>
                    <td class="text-center">
                      Rp. {{ item.subtotal }}
                    </td>
                  </tr>
                </tbody>
              </VTable>
            </VCol>
    
            <VCol class="text-center">
              <RouterLink to="/pembelians">
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
