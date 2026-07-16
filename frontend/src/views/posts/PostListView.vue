<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { usePostsStore } from '@/stores/posts'
import { useCategoriesApi } from '@/api/categories'
import { Plus, Search, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import type { Category } from '@/types'
import PostCard from '@/components/posts/PostCard.vue'

const router = useRouter()
const postsStore = usePostsStore()
const { getCategories } = useCategoriesApi()

const categories = ref<Category[]>([])
const searchInput = ref('')
let searchTimeout: ReturnType<typeof setTimeout> | null = null

onMounted(async () => {
  categories.value = await getCategories()
  postsStore.fetchPosts()
})

function onSearchInput(value: string) {
  if (searchTimeout) clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    postsStore.setFilter('search', value)
  }, 400)
}

function confirmDelete(id: number) {
  if (confirm('Are you sure you want to delete this post?')) {
    postsStore.remove(id).then(() => postsStore.fetchPosts())
  }
}
</script>

<template>
  <div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-semibold text-foreground">Posts</h1>
      <button class="btn-primary" @click="router.push('/posts/new')">
        <Plus class="size-4" />
        New post
      </button>
    </div>

    <div class="flex flex-wrap items-center gap-3">
      <div class="relative flex-1 min-w-[200px] max-w-sm">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
        <input
          v-model="searchInput"
          type="text"
          placeholder="Search posts..."
          class="form-input pl-9"
          @input="onSearchInput(searchInput)"
        />
      </div>
      <select
        class="form-input w-auto"
        @change="postsStore.setFilter('status', ($event.target as HTMLSelectElement).value)"
      >
        <option value="">All statuses</option>
        <option value="draft">Draft</option>
        <option value="published">Published</option>
        <option value="archived">Archived</option>
      </select>
      <select
        class="form-input w-auto"
        @change="postsStore.setFilter('category_id', ($event.target as HTMLSelectElement).value ? Number(($event.target as HTMLSelectElement).value) : null)"
      >
        <option value="">All categories</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>
    </div>

    <div v-if="postsStore.loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <div v-for="i in 6" :key="i" class="card p-4 space-y-3 animate-pulse">
        <div class="w-full h-36 rounded-lg bg-muted" />
        <div class="space-y-2">
          <div class="h-4 bg-muted rounded w-3/4" />
          <div class="h-3 bg-muted rounded w-1/2" />
        </div>
        <div class="flex justify-between pt-2 border-t border-border">
          <div class="h-3 bg-muted rounded w-20" />
          <div class="flex gap-1">
            <div class="size-7 bg-muted rounded" />
            <div class="size-7 bg-muted rounded" />
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="postsStore.posts.length === 0" class="text-center py-16">
      <p class="text-muted-foreground">No posts found.</p>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <PostCard
        v-for="post in postsStore.posts"
        :key="post.id"
        :post="post"
        @edit="router.push(`/posts/${post.id}/edit`)"
        @delete="confirmDelete(post.id)"
      />
    </div>

    <div
      v-if="postsStore.pagination.last_page > 1"
      class="flex items-center justify-between pt-4 border-t border-border"
    >
      <button
        class="btn-secondary"
        :disabled="postsStore.pagination.current_page <= 1"
        @click="postsStore.goToPage(postsStore.pagination.current_page - 1)"
      >
        <ChevronLeft class="size-4" />
        Previous
      </button>
      <span class="text-sm text-muted-foreground">
        Page {{ postsStore.pagination.current_page }} of {{ postsStore.pagination.last_page }}
      </span>
      <button
        class="btn-secondary"
        :disabled="postsStore.pagination.current_page >= postsStore.pagination.last_page"
        @click="postsStore.goToPage(postsStore.pagination.current_page + 1)"
      >
        Next
        <ChevronRight class="size-4" />
      </button>
    </div>
  </div>
</template>
