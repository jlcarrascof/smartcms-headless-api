import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import apiClient from '@/api/client'
import type { User, LoginCredentials, AuthResponse } from '@/types'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<User | null>(null)

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  const apiBase = import.meta.env.VITE_API_URL || '/api/v1'

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
    const { data } = await axios.post<AuthResponse>(`${apiBase}/auth/refresh`, {}, {
      headers: { Authorization: `Bearer ${token.value}` },
    })
    token.value = data.access_token
    user.value = data.user
    localStorage.setItem('token', data.access_token)
  }

  function clearSession(): void {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
  }

  function logout(): void {
    const currentToken = token.value
    clearSession()
    if (currentToken) {
      axios.post(`${apiBase}/auth/logout`, {}, {
        headers: { Authorization: `Bearer ${currentToken}` },
      }).catch(() => {})
    }
  }

  return { token, user, isAuthenticated, isAdmin, login, fetchMe, refreshToken, logout, clearSession }
})
