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
              <label for="bulan">Bulan:</label>
            </VCol>
            <VCol
              cols="9"
              md="4"
            >
              <select
                v-model="bulan"
                @change="filterData"
              >
                <option value="">
                  Pilih Bulan
                </option>
                <option
                  v-for="bulan in bulanOptions"
                  :key="bulan.value"
                  :value="bulan.value"
                >
                  {{ bulan.label }}
                </option>
              </select>
            </VCol>
            <VCol
              cols="3"
              md="1"
            >
              <label for="bulan">Tahun:</label>
            </VCol>
            <VCol
              cols="9"
              md="4"
            >
              <select
                v-model="tahun"
                @change="filterData"
              >
                <option value="">
                  Pilih Tahun
                </option>
                <option
                  v-for="tahun in tahunOptions"
                  :key="tahun"
                  :value="tahun"
                >
                  {{ tahun }}
                </option>
              </select>
            </VCol>
            <VCol>
              <VBtn
                color="success"
                @click="exportPDF"
              >
                Export PDF
              </VBtn>
            </VCol>
          </VRow>
        </VCardText>
        <VCardText>
          <VTable>
            <thead>
              <tr>
                <th scope="col">
                  No.
                </th>
                <th scope="col">
                  Nota
                </th>
                <th scope="col">
                  Customer
                </th>
                <th scope="col">
                  Tanggal Transaksi
                </th>
                <th scope="col">
                  Total
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="transaksis.length == 0">
                <td
                  colspan="6"
                  class="text-center"
                >
                  Data Belum Tersedia!
                </td>
              </tr>
              <tr
                v-for="(transaksi, index) in transaksis"
                :key="transaksi.id"
              >
                <td>
                  {{ index + 1 }}.
                </td>
                <td class="text-center">
                  {{ transaksi.nota }}
                </td>
                <td class="text-center">
                  {{ transaksi.customer.nama }}
                </td>
                <td class="text-center">
                  {{ transaksi.tgl_transaksi }}
                </td>
                <td class="text-center">
                  Rp. {{ transaksi.total }}
                </td>
              </tr>
            </tbody>
          </VTable>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<script>
import axios from "axios"

export default {
  data() {
    return {
      bulan: '',
      tahun: '',
      bulanOptions: [
        { value: 1, label: 'Januari' },
        { value: 2, label: 'Februari' },
        { value: 3, label: 'Maret' },
        { value: 4, label: 'April' },
        { value: 5, label: 'Mei' },
        { value: 6, label: 'Juni' },
        { value: 7, label: 'Juli' },
        { value: 8, label: 'Agustus' },
        { value: 9, label: 'September' },
        { value: 10, label: 'Oktober' },
        { value: 11, label: 'November' },
        { value: 12, label: 'Desember' },
      ],
      tahunOptions: [2022, 2023, 2024], // Ganti dengan tahun-tahun yang sesuai
      transaksis: [],
    }
  },
  methods: {
    async filterData() {
      if (this.bulan && this.tahun) {
        try {
          const response = await axios.get(`/api/transaksi-penjualan/filter?bulan=${this.bulan}&tahun=${this.tahun}`)

          this.transaksis = response.data.transaksis

          console.log(this.transaksis)
        } catch (error) {
          console.error('Error filtering data:', error)
        }
      }
    },
    async exportPDF() {
      if (this.bulan && this.tahun) {
        try {
          window.open(`/api/transaksi-penjualan/export-pdf?bulan=${this.bulan}&tahun=${this.tahun}`, '_blank')
        } catch (error) {
          console.error('Error exporting PDF:', error)
        }
      }
    },
  },
}
</script>

<style>
  select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    inline-size: 100%;
    outline: none;
    transition: border-color 0.3s ease;
  }

  option {
    font-size: 14px;
  }

  select:hover {
    border-color: #999;
  }

  select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 25%);
  }
</style>
