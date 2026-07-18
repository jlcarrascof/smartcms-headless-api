import { defineStore } from 'pinia'
import { ref, reactive } from 'vue'
import { usePostsApi } from '@/api/posts'
import type { Post } from '@/types'

export const usePostsStore = defineStore('posts', () => {
  const posts = ref<Post[]>([])
  const currentPost = ref<Post | null>(null)
  const loading = ref(false)

  const filters = reactive({
    status: '',
    search: '',
    category_id: null as number | null,
    page: 1,
  })

  const pagination = reactive({
    current_page: 1,
    last_page: 1,
    per_page: 9,
    total: 0,
  })

  const { getPosts, getPost, createPost, updatePost, deletePost } = usePostsApi()

  async function fetchPosts() {
    loading.value = true
    try {
      const params: Record<string, any> = { page: filters.page, per_page: 9 }
      if (filters.status) params.status = filters.status
      if (filters.search) params.search = filters.search
      if (filters.category_id) params.category_id = filters.category_id

      const response = await getPosts(params)
      posts.value = response.data
      pagination.current_page = response.meta.current_page
      pagination.last_page = response.meta.last_page
      pagination.per_page = response.meta.per_page
      pagination.total = response.meta.total
    } finally {
      loading.value = false
    }
  }

  async function fetchPost(id: number): Promise<Post> {
    const post = await getPost(id)
    currentPost.value = post
    return post
  }

  async function create(data: FormData | Record<string, any>): Promise<Post> {
    const post = await createPost(data)
    return post
  }

  async function update(id: number, data: FormData | Record<string, any>): Promise<Post> {
    const post = await updatePost(id, data)
    return post
  }

  async function remove(id: number): Promise<void> {
    await deletePost(id)
  }

  function setFilter(key: keyof typeof filters, value: any) {
    (filters as any)[key] = value
    filters.page = 1
    fetchPosts()
  }

  function goToPage(page: number) {
    filters.page = page
    fetchPosts()
  }

  return {
    posts, currentPost, loading, filters, pagination,
    fetchPosts, fetchPost, create, update, remove, setFilter, goToPage,
  }
})
