<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
import InputError from '@/Components/InputError.vue'
import MapPicker from '@/Components/MapPicker.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
    siswa: Object,
    nilai: Object,
})

const form = useForm({
    siswa_id: props.nilai.siswa_id,
    kelas: props.nilai.kelas,
    mapel: props.nilai.mapel,
    nilai: props.nilai.nilai,
})

const submit = () => {
    form.put(route('nilai.update', props.nilai.id))
}
</script>

<template>
    <Head title="Edit Nilai" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Nilai
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <InputLabel :required="true" for="nama" value="Nama Siswa" />
                                <SelectInput
                                    id="siswa_id"
                                    class="mt-1 block w-full"
                                    v-model="form.siswa_id"
                                    :options="siswa"
                                    option-value="id"
                                    option-label="nama"
                                    placeholder="-- Pilih Siswa --"
                                />
                                <InputError class="mt-2" :message="form.errors.nama" />
                            </div>

                            <div>
                                <InputLabel :required="true" for="kelas" value="Kelas" />
                                <TextInput
                                    id="kelas"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.kelas"
                                />
                                <InputError class="mt-2" :message="form.errors.kelas" />
                            </div>

                            <div>
                                <InputLabel :required="true" for="mapel" value="Mata Pelajaran" />
                                <TextInput
                                    id="mapel"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.mapel"
                                />
                                <InputError class="mt-2" :message="form.errors.mapel" />
                            </div>

                            <div>
                                <InputLabel :required="true" for="nilai" value="Nilai" />
                                <TextInput
                                    id="nilai"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.nilai"
                                />
                                <InputError class="mt-2" :message="form.errors.nilai" />
                            </div>

                            <div class="flex items-center justify-end gap-3 pt-2">
                                <Link
                                    :href="route('nilai.index')"
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