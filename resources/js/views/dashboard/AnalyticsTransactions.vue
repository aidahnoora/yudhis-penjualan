<script setup>
import axios from "axios"
import { onMounted, ref } from 'vue'

import chartInfo from '@images/cards/chart-info.png'
import creditCardSuccess from '@images/cards/credit-card-success.png'
import creditCardWarning from '@images/cards/credit-card-warning.png'
import walletPrimary from '@images/cards/wallet-primary.png'

const transactions = ref([])

onMounted(async () => {
  try {
    const response = await axios.get('/api/get-categories')
    const { suppliers, barangs, customers, users } = response.data.data

    transactions.value = [
      {
        amount: suppliers,
        paymentMethod: 'Supplier',
        description: 'Total Supplier',
        icon: chartInfo,
        color: 'info',
      },
      {
        paymentMethod: 'Barang',
        amount: barangs,
        description: 'Total Barang',
        icon: creditCardSuccess,
        color: 'success',
      },
      {
        paymentMethod: 'Customer',
        amount: customers,
        description: 'Banyaknya Customer',
        icon: walletPrimary,
        color: 'primary',
      },
      {
        paymentMethod: 'User',
        amount: users,
        description: 'Banyaknya User',
        icon: creditCardWarning,
        color: 'warning',
      },
    ]
  } catch (error) {
    console.error('Failed to fetch categories:', error)
  }
})
</script>

<template>
  <VCard title="Sistem Informasi Penjualan Sparepart Yudhis Motor">
    <VCardText>
      <VList class="card-list">
        <VListItem
          v-for="item in transactions"
          :key="item.paymentMethod"
        >
          <template #prepend>
            <VAvatar
              rounded
              variant="tonal"
              :color="item.color"
              :image="item.icon"
              class="me-3"
            />
          </template>

          <VListItemSubtitle class="text-disabled mb-1">
            {{ item.paymentMethod }}
          </VListItemSubtitle>
          <VListItemTitle>
            {{ item.description }}
          </VListItemTitle>

          <template #append>
            <VListItemAction>
              <span class="me-1">{{ item.amount > 0 ? item.amount : item.amount }}</span>
            </VListItemAction>
          </template>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
  .card-list {
    --v-card-list-gap: 1.6rem;
  }
</style>
