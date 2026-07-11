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

onMounted(async () => {
  if (!auth.user) {
    await auth.fetchMe()
  }
  try {
    const { data } = await apiClient.get("/posts?per_page=1")
    stats.value.totalPosts = data.meta.total
    const published = await apiClient.get("/posts?status=published&per_page=1")
    stats.value.publishedPosts = published.data.meta.total
    const drafts = await apiClient.get("/posts?status=draft&per_page=1")
    stats.value.draftPosts = drafts.data.meta.total
  } catch {
    // stats stay at 0
  }
})
</script>

<template>
  <div class="p-6 space-y-6">
    <div>
      <h1 class="text-xl font-semibold text-slate-900">Dashboard</h1>
      <p class="text-sm text-slate-500 mt-1">Welcome back, {{ auth.user?.name }}</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="card p-5 space-y-2">
        <span class="text-2xl">📝</span>
        <p class="text-2xl font-bold text-slate-900">{{ stats.totalPosts }}</p>
        <p class="text-sm text-slate-500">Total Posts</p>
      </div>
      <div class="card p-5 space-y-2">
        <span class="text-2xl">✅</span>
        <p class="text-2xl font-bold text-green-600">{{ stats.publishedPosts }}</p>
        <p class="text-sm text-slate-500">Published</p>
      </div>
      <div class="card p-5 space-y-2">
        <span class="text-2xl">✏️</span>
        <p class="text-2xl font-bold text-amber-600">{{ stats.draftPosts }}</p>
        <p class="text-sm text-slate-500">Drafts</p>
      </div>
    </div>
  </div>
</template>
