<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import { useAuthStore } from "@/stores/auth"

const route = useRoute()
const router = useRouter()
const auth  = useAuthStore()

function handleLogout() {
  auth.logout()
  router.push("/login")
}

const navItems = [
  { path: "/dashboard", label: "Dashboard", icon: "📊" },
  { path: "/posts",     label: "Posts",     icon: "📝" },
  { path: "/media",     label: "Media",     icon: "🖼️" },
  { path: "/settings",  label: "Settings",  icon: "⚙️" },
]
</script>

<template>
  <aside class="flex flex-col w-64 min-h-screen bg-slate-sidebar text-slate-100">
    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-700">
      <span class="flex items-center justify-center size-8 rounded-lg bg-brand-500 text-white font-bold text-sm">S</span>
      <span class="font-semibold tracking-tight">SmartCMS</span>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-0.5">
      <RouterLink
        v-for="item in navItems"
        :key="item.path"
        :to="item.path"
        :class="[
          'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150',
          route.path.startsWith(item.path) ? 'bg-brand-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white',
        ]"
      >
        <span class="text-base leading-none">{{ item.icon }}</span>
        {{ item.label }}
      </RouterLink>
    </nav>
    <div class="px-3 pb-4">
      <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-slate-700/50">
        <div class="flex items-center justify-center size-7 rounded-full bg-brand-500 text-white text-xs font-bold shrink-0">
          {{ auth.user?.name?.[0]?.toUpperCase() }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-medium text-slate-100 truncate">{{ auth.user?.name }}</p>
          <p class="text-xs text-slate-400 truncate">{{ auth.user?.role }}</p>
        </div>
        <button class="text-slate-400 hover:text-red-400 transition-colors" @click="handleLogout" title="Sign Out">
          <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h6a2 2 0 012 2v1" />
          </svg>
        </button>
      </div>
    </div>
  </aside>
</template>
