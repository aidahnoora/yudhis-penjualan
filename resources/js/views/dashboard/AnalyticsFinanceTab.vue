<script>
import Chart from "chart.js/auto"
import { VCard } from "vuetify/lib/components/index.mjs"
import api from '../../api'


export default {
  name: "GradientLineChart",

  props: {
    title: {
      type: String,
      default: "Grafik Penjualan",
    },
    detail1: {
      type: String,
      default: " Penjualan Sparepart",
    },
    detail2: {
      type: String,
      default: "Tahun 2024",
    },
  },

  mounted() {
    var ctx1 = document.getElementById("chart-line").getContext("2d")

    api.get("/api/get-transaction")
      .then(response => {
        const responseData = response.data // Data dari respons API

        if (responseData.success && Array.isArray(responseData.data)) {
          const data = responseData.data // Ambil array data transaksi Penjualan

          const labels = data.map(item => item.bulan)
          const values = data.map(item => item.jumlah_transaksi)

          var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50)
          gradientStroke1.addColorStop(1, "rgba(94, 114, 228, 0.2)")
          gradientStroke1.addColorStop(0.2, "rgba(94, 114, 228, 0.0)")
          gradientStroke1.addColorStop(0, "rgba(94, 114, 228, 0)")

          new Chart(ctx1, {
            type: "line",
            data: {
              labels: labels,
              datasets: [
                {
                  label: "Transaksi",
                  tension: 0.4,
                  pointRadius: 0,
                  borderColor: "#4BB543 ",
                  backgroundColor: gradientStroke1,
                  borderWidth: 3,
                  fill: true,
                  data: values,
                  maxBarThickness: 6,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: false,
                },
              },
              interaction: {
                intersect: false,
                mode: "index",
              },
              scales: {
                y: {
                  grid: {
                    drawBorder: false,
                    display: true,
                    drawOnChartArea: true,
                    drawTicks: false,
                    borderDash: [5, 5],
                  },
                  ticks: {
                    display: false,
                    padding: 10,
                    color: "#fbfbfb",
                    font: {
                      size: 14,
                      family: "Public Sans",
                      style: "normal",
                      lineHeight: 2,
                    },
                  },
                },
                x: {
                  grid: {
                    drawBorder: false,
                    display: false,
                    drawOnChartArea: false,
                    drawTicks: false,
                    borderDash: [5, 5],
                  },
                  ticks: {
                    display: true,
                    color: "#black",
                    padding: 20,
                    font: {
                      size: 14,
                      family: 'Public Sans',
                      style: "normal",
                      lineHeight: 2,
                    },
                  },
                },
              },
            },
          })
        } else {
          console.error("Data from API is not in the expected format:", responseData)
        }
      })
      .catch(error => {
        console.error("Error fetching data from API:", error)
      })
  },
}
</script>

<template>
  <VCard>
    <VCardText class="d-flex align-center gap-3">
      <div>
        <p class="mb-0">
          {{ title }}
        </p>
      </div>
    </VCardText>
    <VCardText>
      <div class="chart">
        <canvas
          id="chart-line"
          class="chart-canvas"
          height="300"
        />
      </div>
    </VCardText>
  </VCard>
</template>
