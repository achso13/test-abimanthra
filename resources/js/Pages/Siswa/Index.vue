<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import Alert from '@/Components/Alert.vue' 

const page = usePage()

const props = defineProps({
    siswa: Object,
    filters: Object,
})

const searchQuery = ref(props.filters?.search ?? '')

const search = () => {
    router.get(route('siswa.index'), { search: searchQuery.value }, {
        preserveState: true,
        replace: true,
    })
}

const destroy = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('siswa.destroy', id))
    }
}

const columns = [
    { key: 'nama', label: 'Nama' },
    { key: 'kelas', label: 'Kelas' },
    { key: 'alamat', label: 'Alamat' },
]

const flash = computed(() => page.props.flash)

</script>

<template>
    <Head title="Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Siswa
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <Alert :message="flash.success" type="success" />
                        <Alert :message="flash.error"   type="error" />

                        <div class="flex items-center justify-between mb-4">
                            <form @submit.prevent="search" class="flex gap-2">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Cari nama siswa..."
                                    class="border border-gray-300 rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                />
                                <button
                                    type="submit"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700"
                                >
                                    Cari
                                </button>
                            </form>

                            <Link
                                :href="route('siswa.create')"
                                class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700"
                            >
                                + Tambah Siswa
                            </Link>
                        </div>

                        <DataTable
                            :columns="columns"
                            :rows="siswa.data"
                            :pagination="siswa"
                            empty-message="Tidak ada data siswa."
                        >
                            <template #cell-alamat="{ value }">
                                <span class="line-clamp-1 max-w-xs" :title="value">
                                    {{ value ?? '-' }}
                                </span>
                            </template>

                            <template #actions="{ row }">
                                <Link
                                    :href="route('siswa.edit', row.id)"
                                    class="bg-yellow-400 text-white px-3 py-1 rounded text-xs hover:bg-yellow-500"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="destroy(row.id)"
                                    class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600"
                                >
                                    Hapus
                                </button>
                            </template>
                        </DataTable>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
