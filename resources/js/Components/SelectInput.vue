<script setup>
import { onMounted, ref } from 'vue'

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    placeholder: {
        type: String,
        default: '-- Pilih --',
    },
    optionValue: {
        type: String,
        default: 'value',
    },
    optionLabel: {
        type: String,
        default: 'label',
    },
})

const model = defineModel({
    type: [String, Number],
    required: true,
})

const input = ref(null)

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus()
    }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
    <select
        ref="input"
        v-model="model"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option value="" disabled>{{ placeholder }}</option>
        <option
            v-for="opt in options"
            :key="opt[optionValue]"
            :value="opt[optionValue]"
        >
            {{ opt[optionLabel] }}
        </option>
    </select>
</template>