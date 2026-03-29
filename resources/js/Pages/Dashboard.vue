<script setup>
import { onMounted, ref, nextTick } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import VueApexCharts from 'vue3-apexcharts'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl:       'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl:     'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
})

const props = defineProps({
    stats:        Object,
    rata_per_kelas: Array,
    lokasi_siswa: Array,
})

const cards = [
    {
        label: 'Jumlah Siswa',
        value: props.stats.jumlah_siswa,
        icon: '🎓',
        bg: 'bg-blue-50',
        border: 'border-blue-200',
        text: 'text-blue-700',
    },
    {
        label: 'Jumlah Kelas',
        value: props.stats.jumlah_kelas,
        icon: '🏫',
        bg: 'bg-green-50',
        border: 'border-green-200',
        text: 'text-green-700',
    },
    {
        label: 'Rata-rata Nilai',
        value: props.stats.rata_rata_nilai ?? '-',
        icon: '📊',
        bg: 'bg-yellow-50',
        border: 'border-yellow-200',
        text: 'text-yellow-700',
    },
    {
        label: 'Jumlah Mata Pelajaran',
        value: props.stats.jumlah_mapel,
        icon: '📚',
        bg: 'bg-purple-50',
        border: 'border-purple-200',
        text: 'text-purple-700',
    },
]

const chartOptions = {
    chart: {
        type: 'bar',
        toolbar: { show: false },
    },
    plotOptions: {
        bar: {
            borderRadius: 6,
            columnWidth: '50%',
        },
    },
    dataLabels: { enabled: true },
    xaxis: {
        categories: props.rata_per_kelas.map(r => r.kelas),
        title: { text: 'Kelas' },
    },
    yaxis: {
        min: 0,
        max: 100,
        title: { text: 'Rata-rata Nilai' },
    },
    colors: ['#6366f1'],
    tooltip: {
        y: { formatter: val => `${val}` },
    },
}

const chartSeries = [
    {
        name: 'Rata-rata Nilai',
        data: props.rata_per_kelas.map(r => parseFloat(r.rata_rata)),
    },
]

const mapContainer = ref(null)

onMounted(async () => {
    await nextTick()

    if (!mapContainer.value) return

    const defaultCenter = [-2.5489, 118.0149]
    const center = props.lokasi_siswa.length > 0
        ? [props.lokasi_siswa[0].lat, props.lokasi_siswa[0].lng]
        : defaultCenter

    const map = L.map(mapContainer.value).setView(center, 5)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
    }).addTo(map)

    const markers = []
    props.lokasi_siswa.forEach(siswa => {
        const marker = L.marker([siswa.lat, siswa.lng])
            .addTo(map)
            .bindPopup(`
                <div class="text-sm">
                    <p class="font-semibold">${siswa.nama}</p>
                    <p class="text-gray-500">Kelas ${siswa.kelas}</p>
                    <p class="text-gray-500 text-xs mt-1">${siswa.alamat}</p>
                </div>
            `)
        markers.push(marker)
    })

    if (markers.length > 1) {
        const group = L.featureGroup(markers)
        map.fitBounds(group.getBounds().pad(0.1))
    }
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

                <!-- Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div
                        v-for="card in cards"
                        :key="card.label"
                        :class="['rounded-lg border p-5 flex items-center gap-4', card.bg, card.border]"
                    >
                        <div class="text-3xl">{{ card.icon }}</div>
                        <div>
                            <p class="text-sm text-gray-500">{{ card.label }}</p>
                            <p :class="['text-2xl font-bold', card.text]">{{ card.value }}</p>
                        </div>
                    </div>
                </div>

                <!-- Chart & Map -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Chart -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-semibold text-gray-700 mb-4">
                            Rata-rata Nilai per Kelas
                        </h3>
                        <div v-if="rata_per_kelas.length > 0">
                            <VueApexCharts
                                type="bar"
                                height="300"
                                :options="chartOptions"
                                :series="chartSeries"
                            />
                        </div>
                        <div v-else class="h-48 flex items-center justify-center text-gray-400 text-sm">
                            Belum ada data nilai.
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-base font-semibold text-gray-700 mb-4">
                            Lokasi Rumah Siswa
                            <span class="text-xs font-normal text-gray-400 ml-1">
                                ({{ lokasi_siswa.length }} titik)
                            </span>
                        </h3>
                        <div
                            v-if="lokasi_siswa.length > 0"
                            ref="mapContainer"
                            class="w-full h-72 rounded-md border border-gray-200 z-0"
                        />
                        <div v-else class="h-72 flex items-center justify-center text-gray-400 text-sm">
                            Belum ada data lokasi siswa.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>