<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    message: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: 'success', // 'success' | 'error'
    },
    autoDismiss: {
        type: Number,
        default: 3000,
    },
})

const visible = ref(!!props.message)

let timer = null

watch(() => props.message, (val) => {
    if (timer) clearTimeout(timer)

    if (val) {
        visible.value = true

        if (props.autoDismiss > 0) {
            timer = setTimeout(() => {
                visible.value = false
            }, props.autoDismiss)
        }
    } else {
        visible.value = false
    }
}, { immediate: true })

const styles = {
    success: 'bg-green-50 border-green-400 text-green-800',
    error:   'bg-red-50 border-red-400 text-red-800',
}
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div
            v-if="visible && message"
            :class="['flex items-center justify-between border rounded-md px-4 py-3 mb-4 text-sm', styles[type]]"
        >
            <span>{{ message }}</span>
            <button
                @click="visible = false"
                class="ml-4 text-lg leading-none opacity-50 hover:opacity-100"
            >
                &times;
            </button>
        </div>
    </Transition>
</template>