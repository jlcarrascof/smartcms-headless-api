<script setup lang="ts">
import { FileText, Pencil, Trash2 } from 'lucide-vue-next'
import type { Post } from '@/types'

defineProps<{ post: Post }>()
defineEmits<{ edit: [id: number]; delete: [id: number] }>()
</script>

<template>
  <article class="card p-4 flex flex-col gap-3 hover:shadow-lg transition-shadow duration-200">
    <img
      v-if="post.featured_image"
      :src="post.featured_image"
      :alt="post.title"
      class="w-full h-36 object-cover rounded-lg"
      loading="lazy"
    />
    <div v-else class="w-full h-36 rounded-lg bg-muted flex items-center justify-center text-muted-foreground text-3xl">
      <FileText class="size-8" />
    </div>
    <div class="flex-1 space-y-2">
      <div class="flex items-start justify-between gap-2">
        <h3 class="text-sm font-semibold text-foreground leading-snug line-clamp-2">{{ post.title }}</h3>
        <span :class="`badge-${post.status} shrink-0`">{{ post.status }}</span>
      </div>
      <p v-if="post.excerpt" class="text-xs text-muted-foreground line-clamp-2">{{ post.excerpt }}</p>
    </div>
    <div class="flex items-center justify-between pt-2 border-t border-border">
      <span class="text-xs text-muted-foreground">{{ post.author.name }}</span>
      <div class="flex items-center gap-1">
        <button
          class="p-1.5 rounded-md text-muted-foreground hover:text-primary hover:bg-primary/10 transition-colors"
          @click="$emit('edit', post.id)"
          :title="'Edit post'"
        >
          <Pencil class="size-4" />
        </button>
        <button
          class="p-1.5 rounded-md text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors"
          @click="$emit('delete', post.id)"
          :title="'Delete post'"
        >
          <Trash2 class="size-4" />
        </button>
      </div>
    </div>
  </article>
</template>
