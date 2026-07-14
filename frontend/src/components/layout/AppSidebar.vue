<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import { useAuthStore } from "@/stores/auth"
import { useTheme } from "@/composables/useTheme"
import { LayoutDashboard, FileText, Image, Settings, LogOut, Sun, Moon } from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const auth  = useAuthStore()
const { isDark, toggle } = useTheme()

function handleLogout() {
  auth.logout()
  router.push("/login")
}

const navItems = [
  { path: "/dashboard", label: "Dashboard", icon: LayoutDashboard },
  { path: "/posts",     label: "Posts",     icon: FileText },
  { path: "/media",     label: "Media",     icon: Image },
  { path: "/settings",  label: "Settings",  icon: Settings },
]
</script>

<template>
  <aside class="flex flex-col w-64 min-h-screen bg-sidebar text-sidebar-foreground">
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
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
          route.path.startsWith(item.path)
            ? 'bg-primary text-primary-foreground'
            : 'text-sidebar-foreground hover:bg-white/5 hover:text-white',
        ]"
      >
        <component :is="item.icon" class="size-4 shrink-0" />
        {{ item.label }}
      </RouterLink>
    </nav>
    <div class="px-3 pb-2">
      <button
        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 text-sidebar-foreground hover:bg-white/5 hover:text-white"
        @click="toggle"
      >
        <component :is="isDark ? Sun : Moon" class="size-4 shrink-0" />
        {{ isDark ? 'Light Mode' : 'Dark Mode' }}
      </button>
    </div>
    <div class="px-3 pb-4">
      <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-white/5">
        <div class="flex items-center justify-center size-7 rounded-full bg-brand-500 text-white text-xs font-bold shrink-0">
          {{ auth.user?.name?.[0]?.toUpperCase() }}
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-xs font-medium text-sidebar-foreground truncate">{{ auth.user?.name }}</p>
          <p class="text-xs text-muted-foreground truncate">{{ auth.user?.role }}</p>
        </div>
        <button class="text-muted-foreground hover:text-destructive transition-colors" @click="handleLogout" title="Sign Out">
          <LogOut class="size-4 shrink-0" />
        </button>
      </div>
    </div>
  </aside>
</template>
