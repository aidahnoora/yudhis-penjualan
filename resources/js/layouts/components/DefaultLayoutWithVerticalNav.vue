<script setup>
import VerticalNavSectionTitle from '@/@layouts/components/VerticalNavSectionTitle.vue'
import upgradeBannerDark from '@images/pro/upgrade-banner-dark.png'
import upgradeBannerLight from '@images/pro/upgrade-banner-light.png'
import VerticalNavLayout from '@layouts/components/VerticalNavLayout.vue'
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue'
import { useTheme } from 'vuetify'

// Components
import Footer from '@/layouts/components/Footer.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'

const vuetifyTheme = useTheme()

const upgradeBanner = computed(() => {
  return vuetifyTheme.global.name.value === 'light' ? upgradeBannerLight : upgradeBannerDark
})
</script>

<template>
  <VerticalNavLayout>
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <!-- 👉 Vertical nav toggle in overlay mode -->
        <IconBtn
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon icon="bx-menu" />
        </IconBtn>

        <VSpacer />

        <NavbarThemeSwitcher class="me-2" />

        <UserProfile />
      </div>
    </template>

    <template #vertical-nav-content>
      <VerticalNavLink
        :item="{
          title: 'Dashboard',
          icon: 'bx-home',
          to: '/dashboard',
        }"
      />

      <!-- 👉 Pages -->
      <VerticalNavSectionTitle
        :item="{
          heading: 'Pages',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Data Supplier',
          icon: 'mdi-truck',
          to: '/suppliers',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Data User',
          icon: 'mdi-account-cog-outline',
          to: '/users',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Data Barang',
          icon: 'icon-park-outline:shopping',
          to: '/barangs',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Data Customer',
          icon: 'raphael:people',
          to: '/customers',
        }"
      />
      
      <VerticalNavLink
        :item="{
          title: 'Transaksi Pembelian',
          icon: 'bx-money',
          to: '/pembelians',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Transaksi Penjualan',
          icon: 'mdi-printer-point-of-sale-check',
          to: '/transaksis',
        }"
      />

      <!-- 👉 User Interface -->
      <VerticalNavSectionTitle
        :item="{
          heading: 'Laporan',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Laporan Penjualan',
          icon: 'mdi-report-bar',
          to: '/laporan-penjualan',
        }"
      />
      <VerticalNavLink
        :item="{
          title: 'Laporan Pembelian',
          icon: 'mdi-report-bar',
          to: '/laporan-pembelian',
        }"
      />
    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>
  </VerticalNavLayout>
</template>

<style lang="scss" scoped>
.meta-key {
  border: thin solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 6px;
  block-size: 1.5625rem;
  line-height: 1.3125rem;
  padding-block: 0.125rem;
  padding-inline: 0.25rem;
}
</style>
