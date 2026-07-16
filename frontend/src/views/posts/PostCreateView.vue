<script setup lang="ts">
import { useRouter } from 'vue-router'
import PostEditor from '@/components/posts/PostEditor.vue'

const router = useRouter()

function onSaved() {
  router.push('/posts')
}
</script>

<template>
  <PostEditor @saved="onSaved" @cancelled="router.push('/posts')" />
</template>
