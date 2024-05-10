<script setup>
import Swal from 'sweetalert2'
import { computed, onMounted, ref } from "vue"
import { useRoute, useRouter } from 'vue-router'
import api from "../../api"

const router = useRouter()
const route = useRoute()

const suppliers = ref([])
const supplier_id = ref(null)
const tgl_transaksi = ref("")
const nota = ref("")
const total = ref("")

const itemTransaksis = ref([])
const errors = ref([])

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

const updateTransaksi = async () => {
  const formData = new FormData()

  formData.append("supplier_id", supplier_id.value)
  formData.append("nota", nota)
  formData.append("tgl_transaksi", tgl_transaksi.value)
  formData.append("total", total.value)
  formData.append("_method", "PATCH")

  try {
    await api.post(`/api/pembelians/${route.params.id}`, formData)
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: 'Data berhasil disimpan!',
    }).then(() => {
      router.push({ path: '/pembelians' })
    })
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Gagal menyimpan data. Ulangi lagi.',
    })
    errors.value = error.response.data
  }
}

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
      <VCard title="Tambah Data Transaksi">
        <VCardText>
          <VForm @submit.prevent="updateTransaksi">
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
                    placeholder="Pilih Supplier"
                    dense
                    density="compact"
                  />

                  <VCol v-if="errors.supplier_id">
                    <span style="color: red;">*{{ errors.supplier_id[0] }}</span>
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
                    readonly=""
                    persistent-placeholder
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
                    placeholder="0"
                    persistent-placeholder
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
                  <tr>
                    <td
                      colspan="3"
                      class="text-right"
                    >
                      <strong>Total (Rp)</strong>
                    </td>
                    <td class="text-center">
                      Rp. {{ total }}
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
