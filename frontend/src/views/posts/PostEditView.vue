<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import PostEditor from '@/components/posts/PostEditor.vue'

const route = useRoute()
const router = useRouter()
const postId = Number(route.params.id)

function onSaved() {
  router.push('/posts')
}
</script>

<template>
  <PostEditor :post-id="postId" @saved="onSaved" @cancelled="router.push('/posts')" />
</template>
