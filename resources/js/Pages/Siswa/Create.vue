<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import MapPicker from '@/Components/MapPicker.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const form = useForm({
    nama: '',
    kelas: '',
    alamat: '',
    coordinate: '',
})

const submit = () => {
    form.post(route('siswa.store'))
}
</script>

<template>
    <Head title="Tambah Siswa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Tambah Siswa
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <InputLabel for="nama" value="Nama" />
                                <TextInput
                                    id="nama"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.nama"
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.nama" />
                            </div>

                            <div>
                                <InputLabel for="kelas" value="Kelas" />
                                <TextInput
                                    id="kelas"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.kelas"
                                />
                                <InputError class="mt-2" :message="form.errors.kelas" />
                            </div>

                            <div>
                                <InputLabel for="alamat" value="Alamat" />
                                <TextInput
                                    id="alamat"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.alamat"
                                />
                                <InputError class="mt-2" :message="form.errors.alamat" />
                            </div>
                            
                            <div>
                                <InputLabel value="Lokasi Rumah (Klik pada peta)" />
                                <MapPicker
                                    v-model="form.coordinate"
                                    @update:alamat="(val) => form.alamat = val"
                                    class="mt-1"
                                />
                                <InputError class="mt-2" :message="form.errors.coordinate" />
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <Link
                                    :href="route('siswa.index')"
                                    class="text-sm text-gray-500 hover:text-gray-700"
                                >
                                    Batal
                                </Link>
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Simpan
                                </PrimaryButton>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>