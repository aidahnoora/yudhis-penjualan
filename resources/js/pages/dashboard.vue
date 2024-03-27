<script setup>
import AnalyticsCongratulations from '@/views/dashboard/AnalyticsCongratulations.vue'
import AnalyticsFinanceTabs from '@/views/dashboard/AnalyticsFinanceTab.vue'
import AnalyticsTransactions from '@/views/dashboard/AnalyticsTransactions.vue'

// 👉 Images
</script>

<template>
  <VRow>
    <!-- 👉 Congratulations -->
    <VCol
      cols="12"
      md="12"
    >
      <AnalyticsCongratulations />
    </VCol>

    <!-- 👉 Tabs chart -->
    <VCol
      cols="12"
      md="6"
    >
      <AnalyticsFinanceTabs />
    </VCol>

    <!-- 👉 Transactions -->
    <VCol
      cols="12"
      md="6"
    >
      <AnalyticsTransactions />
    </VCol>
  </VRow>
</template>
