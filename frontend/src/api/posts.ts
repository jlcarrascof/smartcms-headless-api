import apiClient from './client'
import type { Post, PaginatedResponse } from '@/types'

export function usePostsApi() {
  function getPosts(params?: Record<string, any>): Promise<PaginatedResponse<Post>> {
    return apiClient.get('/posts', { params }).then(res => res.data)
  }

  function getPost(id: number): Promise<Post> {
    return apiClient.get(`/posts/${id}`).then(res => res.data)
  }

  function createPost(data: FormData | Record<string, any>): Promise<Post> {
    return apiClient.post('/posts', data).then(res => res.data)
  }

  function updatePost(id: number, data: FormData | Record<string, any>): Promise<Post> {
    return apiClient.put(`/posts/${id}`, data).then(res => res.data)
  }

  function deletePost(id: number): Promise<void> {
    return apiClient.delete(`/posts/${id}`)
  }

  return { getPosts, getPost, createPost, updatePost, deletePost }
}
