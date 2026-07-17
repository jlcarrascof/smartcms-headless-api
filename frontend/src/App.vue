<script setup lang="ts">
import { onMounted } from 'vue'
import { RouterView, useRoute } from 'vue-router'
import { computed } from 'vue'
import AppSidebar from '@/components/layout/AppSidebar.vue'
import ToastContainer from '@/components/common/ToastContainer.vue'
import { useTheme } from '@/composables/useTheme'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const isAuthPage = computed(() => route.name === 'login')
const auth = useAuthStore()
useTheme()

onMounted(() => {
  if (auth.token && !auth.user) {
    auth.fetchMe()
  }
})
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
  <ToastContainer />
</template>
