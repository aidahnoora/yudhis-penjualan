<script setup>
import { onMounted, ref } from 'vue'
import api from '../../api'

const bulanTahun = ref('')
const tahun = ref(new Date().getFullYear())
const transaksiPenjualan = ref([])

const fetchData = async () => {
  try {
    const response = await api.get(`/api/laporan-transaksi-penjualan?bulan_tahun=${bulanTahun.value}&tahun=${tahun.value}`)

    transaksiPenjualan.value = response.data.data
  } catch (error) {
    console.error('Error fetching data:', error)
  }
}

onMounted(() => {
  fetchData()
})

const downloadPDF = () => {
  const filteredData = transaksiPenjualan.value.filter(transaksi => {
    const [bulan, tahun] = bulanTahun.value.split('-')
    const transaksiBulan = parseInt(transaksi.bulan)
    const transaksiTahun = parseInt(transaksi.tahun)
    
    return transaksiBulan === parseInt(bulan) && transaksiTahun === parseInt(tahun)
  })

  if (filteredData.length === 0) {
    console.warn('Tidak ada data yang sesuai dengan filter.')
    
    return
  }

  console.log(filteredData)

  const newWindow = window.open('', '_blank')

  newWindow.document.write('<html><head><title>Laporan Transaksi Penjualan</title>')
  newWindow.document.write('<style>')
  newWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }')
  newWindow.document.write('table { width: 100%; border-collapse: collapse; }')
  newWindow.document.write('table, th, td { border: 1px solid black; }')
  newWindow.document.write('th, td { padding: 8px; text-align: left; }')
  newWindow.document.write('</style>')
  newWindow.document.write('</head><body>')

  newWindow.document.write('<h2>Data Transaksi Penjualan</h2>')
  newWindow.document.write('<table>')
  newWindow.document.write('<thead><tr><th>Nota</th><th>Total</th></tr></thead>')
  newWindow.document.write('<tbody>')
  filteredData.forEach(transaksi => {
    newWindow.document.write(`<tr><td>${transaksi.nota}</td><td>${transaksi.total}</td></tr>`)
  })
  newWindow.document.write('</tbody>')
  newWindow.document.write('</table>')

  newWindow.document.write('</body></html>')
  newWindow.document.close()

  newWindow.print()
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Laporan Transaksi Penjualan">
        <VCardText>    
          <VRow>
            <VCol
              cols="3"
              md="1"
            >
              <label for="bulan">Bulan</label>
            </VCol>
            <VCol
              cols="9"
              md="4"
            >
              <VTextField
                id="bulan"
                v-model="bulanTahun"
                type="month"
                density="compact"
                @change="fetchData"
              />
            </VCol>
            <VCol
              cols="3"
              md="1"
            >
              <label for="tahun">Tahun</label>
            </VCol>
            <VCol
              cols="9"
              md="4"
            >
              <VTextField
                id="tahun"
                v-model="tahun"
                type="number"
                density="compact"
                @change="fetchData"
              />
            </VCol>
          </VRow>

          <VRow>
            <VCol>
              <div v-if="transaksiPenjualan.length > 0">
                <h4>Data Transaksi Penjualan</h4>
                <VTable>
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Customer</th>
                      <th>Nota</th>
                      <th>Tanggal Transaksi</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(transaksi, index) in transaksiPenjualan"
                      :key="index"
                    >
                      <td>
                        {{ index + 1 }}.
                      </td>
                      <td class="text-center">
                        {{ transaksi.customer.nama }}
                      </td>
                      <td class="text-center">
                        {{ transaksi.nota }}
                      </td>
                      <td class="text-center">
                        {{ transaksi.tgl_transaksi }}
                      </td>
                      <td class="text-center">
                        Rp {{ transaksi.total }}
                      </td>
                    </tr>
                  </tbody>
                </VTable>
              </div>
              <div v-else>
                <p>Tidak ada data transaksi penjualan untuk bulan dan tahun yang dipilih.</p>
              </div>
            </VCol>
          </VRow>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>
