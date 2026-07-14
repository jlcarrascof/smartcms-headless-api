import { ref, watch } from 'vue'

const STORAGE_KEY = 'theme-preference'

function getStoredTheme(): 'dark' | 'light' {
  if (typeof localStorage !== 'undefined') {
    const stored = localStorage.getItem(STORAGE_KEY)
    if (stored === 'dark' || stored === 'light') return stored
  }
  return 'light'
}

function applyTheme(theme: 'dark' | 'light') {
  if (theme === 'dark') {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

const isDark = ref(getStoredTheme() === 'dark')

applyTheme(isDark.value ? 'dark' : 'light')

watch(isDark, (val) => {
  const theme = val ? 'dark' : 'light'
  applyTheme(theme)
  localStorage.setItem(STORAGE_KEY, theme)
})

export function useTheme() {
  function toggle() {
    isDark.value = !isDark.value
  }

  return { isDark, toggle }
}
