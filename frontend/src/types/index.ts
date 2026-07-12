export interface User {
  id: number
  name: string
  email: string
  role: 'admin' | 'editor' | 'viewer'
  created_at: string
}

export interface Post {
  id: number
  title: string
  slug: string
  excerpt: string | null
  content?: string
  status: 'draft' | 'published' | 'archived'
  featured_image: string | null
  published_at: string | null
  created_at: string
  author: { id: number; name: string }
  category: Category | null
}

export interface Category {
  id: number
  name: string
  slug: string
  description: string | null
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface AuthResponse {
  access_token: string
  token_type: string
  expires_in: number
  user: User
}
