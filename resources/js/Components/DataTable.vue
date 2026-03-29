<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    columns: {
        type: Array,
        required: true,
    },
    rows: {
        type: Array,
        required: true,
    },
    pagination: {
        type: Object,
        default: null,
    },
    emptyMessage: {
        type: String,
        default: 'Tidak ada data.',
    },
})

defineEmits(['delete'])
</script>

<template>
    <div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                            No
                        </th>
                        <th
                            v-for="col in columns"
                            :key="col.key"
                            class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ col.label }}
                        </th>
                        <slot name="extra-header" />
                        <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="rows.length === 0">
                        <td
                            :colspan="columns.length + 2"
                            class="px-4 py-6 text-center text-gray-400"
                        >
                            {{ emptyMessage }}
                        </td>
                    </tr>

                    <tr
                        v-for="(row, index) in rows"
                        :key="row.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-4 py-3 text-gray-700">
                            {{ pagination
                                ? (pagination.current_page - 1) * pagination.per_page + index + 1
                                : index + 1
                            }}
                        </td>

                        <td
                            v-for="col in columns"
                            :key="col.key"
                            class="px-4 py-3 text-gray-700"
                        >
                            
                            <slot :name="`cell-${col.key}`" :row="row" :value="row[col.key]">
                                {{ row[col.key] ?? '-' }}
                            </slot>
                        </td>

                        <slot name="extra-cell" :row="row" />

                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <slot name="actions" :row="row">
                                    
                                </slot>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="pagination"
            class="flex items-center justify-between mt-4 text-sm text-gray-600"
        >
            <span>
                Menampilkan {{ pagination.from ?? 0 }} - {{ pagination.to ?? 0 }}
                dari {{ pagination.total }} data
            </span>
            <div class="flex gap-1">
                <Link
                    v-for="link in pagination.links"
                    :key="link.label"
                    :href="link.url ?? '#'"
                    v-html="link.label"
                    class="px-3 py-1 rounded border text-xs"
                    :class="{
                        'bg-indigo-600 text-white border-indigo-600': link.active,
                        'text-gray-500 hover:bg-gray-100': !link.active,
                        'pointer-events-none opacity-50': !link.url,
                    }"
                />
            </div>
        </div>
    </div>
</template>