<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import Alert from '@/Components/Alert.vue' 
import Modal from '@/Components/Modal.vue'

const page = usePage()

const props = defineProps({
    nilai: Object,
    filters: Object,
})

const searchQuery = ref(props.filters?.search ?? '')

const search = () => {
    router.get(route('nilai.index'), { search: searchQuery.value }, {
        preserveState: true,
        replace: true,
    })
}

const destroy = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(route('nilai.destroy', id))
    }
}

const columns = [
    { key: 'siswa.nama', label: 'Nama' },
    { key: 'kelas', label: 'Kelas' },
    { key: 'mapel', label: 'Mata Pelajaran' },
    { key: 'nilai', label: 'Nilai' },
]

const flash = computed(() => page.props.flash)

const showImportModal = ref(false)
const importForm = useForm({ file: null })

const handleFileChange = (e) => {
    importForm.file = e.target.files[0]
}

const submitImport = () => {
    importForm.post(route('nilai.import'), {
        preserveScroll: true,
        onSuccess: () => {
            showImportModal.value = false
            importForm.reset()
        },
    })
}

const closeImportModal = () => {
    showImportModal.value = false
    importForm.reset()
}
</script>

<template>
    <Head title="Nilai" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Nilai
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

                            <div class="flex gap-2">
                                <button
                                    @click="showImportModal = true"
                                    type="button"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700"
                                >
                                    Import
                                </button>
                                <a
                                    :href="route('nilai.export', { search: filters.search })"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700"
                                >
                                    Export
                                </a>
                                <Link
                                    :href="route('nilai.create')"
                                    class="bg-green-600 text-white px-4 py-2 rounded-md text-sm hover:bg-green-700"
                                >
                                    + Tambah Nilai
                                </Link>
                            </div>
                        </div>

                        <DataTable
                            :columns="columns"
                            :rows="nilai.data"
                            :pagination="nilai"
                            empty-message="Tidak ada data nilai."
                        >
                            <template #actions="{ row }">
                                <Link
                                    :href="route('nilai.edit', row.id)"
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

        <Modal :show="showImportModal" max-width="md" @close="closeImportModal">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Import Nilai</h3>
                    <button @click="closeImportModal" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-md p-3 text-xs text-blue-700 mb-4">
                    <p class="font-semibold mb-1">Format kolom Excel:</p>
                    <p>No | <strong>nama</strong> | <strong>kelas</strong> | <strong>mapel</strong> | <strong>nilai</strong></p>
                    <p class="mt-1 text-blue-500">* Kolom <strong>No</strong> akan diabaikan saat import</p>
                    <p class="text-blue-500">* Nama siswa harus sesuai data yang ada di sistem</p>
                </div>

                <div class="mb-4">
                    <a
                        :href="route('nilai.sample')"
                        class="inline-flex items-center gap-1 text-xs text-green-600 hover:text-green-800 underline"
                    >
                        Download sample Excel
                    </a>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih File <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="file"
                        accept=".xlsx,.xls,.csv"
                        @change="handleFileChange"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    />
                    <p v-if="importForm.errors.file" class="mt-1 text-xs text-red-500">
                        {{ importForm.errors.file }}
                    </p>
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        @click="closeImportModal"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        @click="submitImport"
                        :disabled="importForm.processing || !importForm.file"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700 disabled:opacity-50"
                    >
                        {{ importForm.processing ? 'Mengimport...' : 'Import' }}
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
