<script setup>
import Swal from 'sweetalert2'
import { computed, onMounted, ref } from "vue"
import { useRouter } from 'vue-router'
import api from "../../api"

const router = useRouter()

const suppliers = ref([])
const supplier_id = ref(null)
const tgl_transaksi = ref("")

const barangs = ref([])
const barang_id = ref(null)
const qty = ref("")
const errors = ref([])

const barangTerpilih = ref([])

const getSuppliers = async () => {
  try {
    const response = await api.get('/api/get-suppliers')

    suppliers.value = response.data.data.data
  } catch (error) {
    console.error('Error fetching suppliers:', error)
  }
}

const getBarangs = async () => {
  try {
    const response = await api.get('/api/get-barangs')

    barangs.value = response.data.data.data
  } catch (error) {
    console.error('Error fetching barangs:', error)
  }
}

const tambahBarang = () => {
  if (!barang_id.value || !qty.value) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Harap lengkapi data barang.',
    })
    
    return
  }

  const barangSudahDipilih = barangTerpilih.value.find(barang => barang.barang_id === barang_id.value)

  if (barangSudahDipilih) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Barang sudah ditambahkan.',
    })
    
    return
  }

  const barang = {
    barang_id: barang_id.value, 
    subtotal: qty.value * (barangOptions.value.find(option => option.value === barang_id.value)?.harga || 0), // Hitung subtotal
    qty: qty.value,
  }

  barangTerpilih.value.push(barang)

  // console.log(barang)

  barang_id.value = null
  qty.value = ""
}

const hapusBarang = index => {
  barangTerpilih.value.splice(index, 1)
}

const totalTransaksi = computed(() => {
  return barangTerpilih.value.reduce((total, barang) => total + barang.subtotal, 0)
})

function generateNota() {
  const hurufAwal = "TRB"
  const panjangAngka = 6 // Panjang angka yang diinginkan

  const angkaRandom = Math.floor(Math.random() * Math.pow(10, panjangAngka))

  return hurufAwal + angkaRandom.toString().padStart(panjangAngka, '0')
}

const nota = generateNota()

console.log(nota)

const storeTransaksi = async () => {
  const formData = new FormData()

  formData.append("supplier_id", supplier_id.value)
  formData.append("nota", nota)
  formData.append("tgl_transaksi", tgl_transaksi.value)
  formData.append("total", totalTransaksi.value)

  barangTerpilih.value.forEach((barang, index) => { // Gunakan barangTerpilih.value
    formData.append(`items[${index}][barang_id]`, barang.barang_id)
    formData.append(`items[${index}][subtotal]`, barang.subtotal)
    formData.append(`items[${index}][qty]`, barang.qty)
  })

  try {
    await api.post('/api/pembelians', formData)
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

onMounted(() => {
  getSuppliers(),
  getBarangs()
})

const supplierOptions = computed(() => {
  return suppliers.value.map(supplier => ({
    title: supplier.nama,
    value: supplier.id,
  }))
})

const barangOptions = computed(() => {
  return barangs.value.map(barang => ({
    title: barang.nama,
    harga: barang.harga,
    value: barang.id,
  }))
})
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Tambah Data Transaksi">
        <VCardText>
          <VForm @submit.prevent="storeTransaksi">
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
              <VRow no-gutters>
                <VCol cols="12">
                  <h4>Pilih Barang</h4>
                </VCol>
              </VRow>
            </VCol>
            <VCol cols="12">
              <VRow no-gutters>
                <VCol
                  cols="12"
                  md="3"
                >
                  <label for="barang_id">Barang</label>
                </VCol>
                <VCol
                  cols="12"
                  md="9"
                >
                  <VSelect
                    v-model="barang_id"
                    :items="barangOptions"
                    placeholder="Pilih Barang"
                    dense
                    density="compact"
                  />
                  <!-- Tampilkan pesan error jika ada -->
                  <VCol v-if="errors.barang_id">
                    <span style="color: red;">*{{ errors.barang_id[0] }}</span>
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
                  <label for="qty">Qty</label>
                </VCol>
                <VCol
                  cols="12"
                  md="9"
                >
                  <VTextField
                    id="qty"
                    v-model="qty"
                    placeholder="Masukkan qty"
                    dense
                    density="compact"
                    type="number"
                  />
                  <!-- Tampilkan pesan error jika ada -->
                  <VCol v-if="errors.qty">
                    <span style="color: red;">*{{ errors.qty[0] }}</span>
                  </VCol>
                </VCol>
              </VRow>
            </VCol>
            <VCol>
              <VBtn
                color="primary"
                @click="tambahBarang"
              >
                Tambah Barang
              </VBtn>
            </VCol>
            
            <VCol cols="12">
              <VTable>
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                    <th>Hapus</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(barang, index) in barangTerpilih"
                    :key="index"
                  >
                    <td>{{ barangOptions.find(option => option.value === barang.barang_id)?.title }}</td>
                    <td class="text-center">
                      {{ barang.qty }}
                    </td>
                    <td class="text-center">
                      {{ barangOptions.find(option => option.value === barang.barang_id)?.harga }}
                    </td>
                    <td class="text-center">
                      {{ barang.subtotal }}
                    </td>
                    <td class="text-center">
                      <VBtn
                        color="error"
                        prepend-icon="bx-trash"
                        size="small"
                        @click="hapusBarang(index)"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td
                      colspan="4"
                      class="text-right"
                    >
                      <strong>Total (Rp)</strong>
                    </td>
                    <td class="text-center">
                      Rp {{ totalTransaksi }}
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
                SIMPAN
              </VBtn>
            </VCol>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
