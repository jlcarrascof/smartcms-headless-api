<script setup lang="ts">
interface Props {
  label?: string
  placeholder?: string
  type?: string
  error?: string
  required?: boolean
  modelValue: string
}

defineProps<Props>()
defineEmits<{ "update:modelValue": [value: string] }>()
</script>

<template>
  <div class="flex flex-col gap-1">
    <label v-if="label" class="form-label">
      {{ label }}
      <span v-if="required" class="text-red-500 ml-0.5">*</span>
    </label>
    <input
      :type="type ?? 'text'"
      :value="modelValue"
      :placeholder="placeholder"
      :class="['form-input', error && 'form-input-error']"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    />
    <span v-if="error" class="form-error-msg">{{ error }}</span>
  </div>
</template>
