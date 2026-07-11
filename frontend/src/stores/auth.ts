import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import apiClient from '@/api/client'
import type { User, LoginCredentials, AuthResponse } from '@/types'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<User | null>(null)

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  async function login(credentials: LoginCredentials): Promise<void> {
    const { data } = await apiClient.post<AuthResponse>('/auth/login', credentials)
    token.value = data.access_token
    user.value = data.user
    localStorage.setItem('token', data.access_token)
  }

  async function fetchMe(): Promise<void> {
    const { data } = await apiClient.get<User>('/auth/me')
    user.value = data
  }

  async function refreshToken(): Promise<void> {
    const { data } = await apiClient.post<AuthResponse>('/auth/refresh')
    token.value = data.access_token
    localStorage.setItem('token', data.access_token)
  }

  function logout(): void {
    apiClient.post('/auth/logout').catch(() => {})
    token.value = null
    user.value = null
    localStorage.removeItem('token')
  }

  return { token, user, isAuthenticated, isAdmin, login, fetchMe, refreshToken, logout }
})
