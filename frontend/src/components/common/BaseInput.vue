<script setup lang="ts">
interface Props {
  label?: string
  placeholder?: string
  type?: string
  error?: string
  required?: boolean
  modelValue: string
  id?: string
}

const props = defineProps<Props>()
const inputId = props.id || `input-${Math.random().toString(36).slice(2, 9)}`
defineEmits<{ "update:modelValue": [value: string] }>()
</script>

<template>
  <div class="flex flex-col gap-1">
    <label v-if="label" :for="inputId" class="form-label">
      {{ label }}
      <span v-if="required" class="text-red-500 ml-0.5">*</span>
    </label>
    <input
      :id="inputId"
      :name="inputId"
      :type="type ?? 'text'"
      :value="modelValue"
      :placeholder="placeholder"
      :class="['form-input', error && 'form-input-error']"
      @input="$emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    />
    <span v-if="error" class="form-error-msg">{{ error }}</span>
  </div>
</template>
