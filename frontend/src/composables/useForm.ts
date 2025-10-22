import { ref } from 'vue'
import { toast } from 'vue3-toastify'

export function useForm<T extends Record<string, any>>(initial: T) {
  const form = ref({ ...initial })
  const errors = ref<Record<string, string[]>>({})
  const loading = ref(false)

  const reset = () => {
    form.value = { ...initial }
    errors.value = {}
  }

  const submit = async (handler: (data: T) => Promise<void>) => {
    loading.value = true
    try {
      await handler(form.value)
      toast.success('Saved successfully')
    } catch (err: any) {
      toast.error(err?.message || 'Something went wrong')
    } finally {
      loading.value = false
    }
  }

  return { form, errors, loading, submit, reset }
}
