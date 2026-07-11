<script setup lang="ts">
import { reactive, ref } from "vue"
import { useRouter } from "vue-router"
import { useAuthStore } from "@/stores/auth"
import BaseButton from "@/components/common/BaseButton.vue"
import BaseInput from "@/components/common/BaseInput.vue"

const router = useRouter()
const auth   = useAuthStore()

const form = reactive({ email: "", password: "" })
const error = ref("")
const loading = ref(false)

async function handleLogin() {
  error.value = ""
  loading.value = true
  try {
    await auth.login(form)
    router.push("/dashboard")
  } catch {
    error.value = "Invalid credentials. Please try again."
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center size-12 rounded-xl bg-brand-600 text-white text-xl font-bold mb-4">S</div>
        <h1 class="text-2xl font-bold text-slate-900">SmartCMS</h1>
        <p class="text-sm text-slate-500 mt-1">Admin Panel</p>
      </div>
      <div class="card p-6 space-y-5">
        <div v-if="error" class="flex items-center gap-2 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">{{ error }}</div>
        <form class="space-y-4" @submit.prevent="handleLogin">
          <BaseInput v-model="form.email" label="Email" type="email" placeholder="you@email.com" required />
          <BaseInput v-model="form.password" label="Password" type="password" placeholder="••••••••" required />
          <BaseButton type="submit" class="w-full" :loading="loading">Sign In</BaseButton>
        </form>
      </div>
    </div>
  </div>
</template>
