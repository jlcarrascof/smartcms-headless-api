<script setup lang="ts">
interface Props {
  variant?: "primary" | "secondary" | "danger"
  type?: "button" | "submit" | "reset"
  loading?: boolean
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: "primary",
  type: "button",
  loading: false,
  disabled: false,
})
</script>

<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[
      variant === 'primary'   && 'btn-primary',
      variant === 'secondary' && 'btn-secondary',
      variant === 'danger'    && 'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-150 cursor-pointer focus-visible:outline-2 focus-visible:outline-offset-2 bg-destructive text-destructive-foreground hover:bg-destructive/90 focus-visible:outline-destructive disabled:opacity-50',
    ]"
  >
    <svg
      v-if="loading"
      class="animate-spin size-4 shrink-0"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
    </svg>
    <slot />
  </button>
</template>
