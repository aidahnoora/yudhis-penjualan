<script setup>
import AnalyticsCongratulations from '@/views/dashboard/AnalyticsCongratulations.vue'
import AnalyticsFinanceTabs from '@/views/dashboard/AnalyticsFinanceTab.vue'

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
      md="12"
    >
      <AnalyticsFinanceTabs />
    </VCol>
  </VRow>
</template>
