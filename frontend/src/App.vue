<script setup lang="ts">
import { RouterView, useRoute } from 'vue-router'
import { computed } from 'vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import { useTheme } from '@/composables/useTheme'

const route = useRoute()
const isAuthPage = computed(() => route.name === 'login')
useTheme()
</script>

<template>
  <div v-if="isAuthPage" class="min-h-screen">
    <RouterView />
  </div>
  <div v-else class="flex min-h-screen">
    <AppSidebar />
    <main class="flex-1 overflow-auto">
      <RouterView />
    </main>
  </div>
</template>
