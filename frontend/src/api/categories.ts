import apiClient from './client'
import type { Category } from '@/types'

export function useCategoriesApi() {
  function getCategories(): Promise<Category[]> {
    return apiClient.get('/categories').then(res => res.data)
  }

  return { getCategories }
}
