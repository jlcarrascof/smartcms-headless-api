<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { ArrowLeft, Save } from 'lucide-vue-next'
import type { Post, Category } from '@/types'
import { usePostsStore } from '@/stores/posts'
import { useCategoriesApi } from '@/api/categories'
import BaseButton from '@/components/common/BaseButton.vue'
import BaseInput from '@/components/common/BaseInput.vue'

interface Props {
  postId?: number
}

const props = defineProps<Props>()
const emit = defineEmits<{
  saved: [post: Post]
  cancelled: []
}>()

const postsStore = usePostsStore()
const { getCategories } = useCategoriesApi()

const categories = ref<Category[]>([])
const isSubmitting = ref(false)
const errors = ref<Record<string, string[]>>({})

const form = reactive({
  title: '',
  content: '',
  excerpt: '',
  status: 'draft' as Post['status'],
  category_id: null as number | null,
})

onMounted(async () => {
  categories.value = await getCategories()
  if (props.postId) {
    const post = await postsStore.fetchPost(props.postId)
    form.title = post.title
    form.content = post.content ?? ''
    form.excerpt = post.excerpt ?? ''
    form.status = post.status
    form.category_id = post.category?.id ?? null
  }
})

async function handleSubmit() {
  isSubmitting.value = true
  errors.value = {}
  try {
    let savedPost: Post
    if (props.postId) {
      savedPost = await postsStore.update(props.postId, { ...form })
    } else {
      savedPost = await postsStore.create({ ...form })
    }
    emit('saved', savedPost)
  } catch (err: any) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div class="p-6">
    <div class="mb-6">
      <h2 class="text-xl font-semibold text-foreground">
        {{ postId ? 'Edit post' : 'New post' }}
      </h2>
    </div>
    <form class="flex gap-6" @submit.prevent="handleSubmit">
      <div class="flex-1 space-y-5">
        <BaseInput
          v-model="form.title"
          label="Title"
          placeholder="Post title..."
          :error="errors.title?.[0]"
          required
        />
        <div>
          <label class="form-label">Content</label>
          <textarea
            v-model="form.content"
            rows="15"
            placeholder="Write your content here..."
            :class="['form-input resize-y', errors.content && 'form-input-error']"
          />
          <span v-if="errors.content" class="form-error-msg">{{ errors.content[0] }}</span>
        </div>
        <div>
          <label class="form-label">Excerpt</label>
          <textarea
            v-model="form.excerpt"
            rows="3"
            placeholder="Brief summary of the post..."
            maxlength="500"
            class="form-input resize-none"
          />
          <p class="mt-1 text-xs text-right text-muted-foreground">{{ form.excerpt.length }}/500</p>
        </div>
      </div>
      <div class="w-64 shrink-0 space-y-4">
        <div class="card p-4 space-y-4">
          <h3 class="text-sm font-semibold text-foreground">Publication</h3>
          <div>
            <label class="form-label">Status</label>
            <select v-model="form.status" class="form-input">
              <option value="draft">Draft</option>
              <option value="published">Published</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div>
            <label class="form-label">Category</label>
            <select v-model="form.category_id" class="form-input">
              <option :value="null">No category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>
          <div class="flex gap-2 pt-2 border-t border-border">
            <BaseButton type="button" variant="secondary" class="flex-1" @click="$emit('cancelled')">
              <ArrowLeft class="size-4" />
              Cancel
            </BaseButton>
            <BaseButton type="submit" class="flex-1" :loading="isSubmitting">
              <Save class="size-4" />
              {{ postId ? 'Update' : 'Save' }}
            </BaseButton>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
