<script setup lang="ts">
import { AlertTriangle } from 'lucide-vue-next'

interface Props {
  show: boolean
  title?: string
  message?: string
  confirmText?: string
  cancelText?: string
  variant?: 'danger' | 'primary'
}

const props = withDefaults(defineProps<Props>(), {
  show: false,
  title: 'Confirm action',
  message: 'Are you sure?',
  confirmText: 'Confirm',
  cancelText: 'Cancel',
  variant: 'danger',
})

const emit = defineEmits<{
  confirm: []
  cancel: []
}>()
</script>

<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="emit('cancel')" />
        <div class="relative bg-card text-card-foreground rounded-lg border border-border shadow-xl p-6 w-full max-w-sm mx-4 space-y-4">
          <div class="flex items-center gap-3">
            <div
              :class="[
                'flex items-center justify-center size-10 rounded-full shrink-0',
                variant === 'danger' ? 'bg-destructive/10 text-destructive' : 'bg-primary/10 text-primary',
              ]"
            >
              <AlertTriangle class="size-5" />
            </div>
            <div>
              <h3 class="text-sm font-semibold text-foreground">{{ title }}</h3>
              <p class="text-sm text-muted-foreground mt-0.5">{{ message }}</p>
            </div>
          </div>
          <div class="flex justify-end gap-2 pt-2">
            <button class="btn-secondary text-sm" @click="emit('cancel')">{{ cancelText }}</button>
            <button
              :class="[
                'btn text-sm transition-colors duration-150',
                variant === 'danger'
                  ? 'bg-white text-slate-700 border border-slate-200 hover:bg-destructive hover:text-destructive-foreground hover:border-destructive dark:bg-slate-800 dark:text-slate-200 dark:border-slate-700 dark:hover:bg-destructive dark:hover:text-destructive-foreground'
                  : 'btn-primary',
              ]"
              @click="emit('confirm')"
            >
              {{ confirmText }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
