<script setup lang="ts">
import { onMounted, ref } from "vue"
import { useAuthStore } from "@/stores/auth"
import apiClient from "@/api/client"

const auth = useAuthStore()

const stats = ref({
  totalPosts: 0,
  publishedPosts: 0,
  draftPosts: 0,
})

async function fetchStat(url: string): Promise<number> {
  try {
    const { data } = await apiClient.get(url)
    return data.meta?.total ?? 0
  } catch (err) {
    console.error("Dashboard stat fetch failed:", url, err)
    return 0
  }
}

onMounted(async () => {
  if (!auth.user) {
    try {
      await auth.fetchMe()
    } catch {
      // user will be redirected by router guard
      return
    }
  }
  const [total, published, drafts] = await Promise.all([
    fetchStat("/posts?per_page=1"),
    fetchStat("/posts?status=published&per_page=1"),
    fetchStat("/posts?status=draft&per_page=1"),
  ])
  stats.value = { totalPosts: total, publishedPosts: published, draftPosts: drafts }
})
</script>

<template>
  <div class="p-6 space-y-6">
    <div>
      <h1 class="text-xl font-semibold text-foreground">Dashboard</h1>
      <p class="text-sm text-muted-foreground font-semibold mt-1">Welcome back, {{ auth.user?.name }}</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="card p-5 space-y-2">
        <span class="text-2xl">📝</span>
        <p class="text-2xl font-bold text-foreground">{{ stats.totalPosts }}</p>
        <p class="text-sm text-muted-foreground">Total Posts</p>
      </div>
      <div class="card p-5 space-y-2">
        <span class="text-2xl">✅</span>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ stats.publishedPosts }}</p>
        <p class="text-sm text-muted-foreground">Published</p>
      </div>
      <div class="card p-5 space-y-2">
        <span class="text-2xl">✏️</span>
        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ stats.draftPosts }}</p>
        <p class="text-sm text-muted-foreground">Drafts</p>
      </div>
    </div>
  </div>
</template>
