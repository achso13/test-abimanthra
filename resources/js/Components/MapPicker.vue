<script setup>
import { ref, onMounted, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
})

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    initialCoordinate: {
        type: String,
        default: '',
    },
    height: {
        type: String,
        default: 'h-72',
    },
    defaultCenter: {
        type: Array,
        default: () => [-6.2088, 106.8456],
    },
    defaultZoom: {
        type: Number,
        default: 12,
    },
    readonly: {
        type: Boolean,
        default: false,
    }
})

const emit = defineEmits(['update:modelValue', 'update:alamat'])

const mapContainer = ref(null)
const selectedLatLng = ref(null)
const isLoadingAlamat = ref(false)
let map = null
let marker = null

const parseCoordinate = (coordinateString) => {
    if (!coordinateString) return null
    const [lat, lng] = coordinateString.split(',').map(Number)
    if (isNaN(lat) || isNaN(lng)) return null
    return { lat, lng }
}

const placeMarker = (lat, lng) => {
    if (marker) marker.remove()
    marker = L.marker([lat, lng]).addTo(map)
    selectedLatLng.value = {
        lat: lat.toFixed(6),
        lng: lng.toFixed(6),
    }
}

const reverseGeocode = async (lat, lng) => {
    isLoadingAlamat.value = true
    try {
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`,
            { headers: { 'Accept-Language': 'id' } }
        )
        const data = await response.json()
        emit('update:alamat', data.display_name ?? '')
    } catch (e) {
        console.error('Gagal mengambil alamat:', e)
    } finally {
        isLoadingAlamat.value = false
    }
}

const resetMarker = () => {
    if (marker) marker.remove()
    marker = null
    selectedLatLng.value = null
    emit('update:modelValue', '')
    emit('update:alamat', '')
}

onMounted(() => {
    const initial = parseCoordinate(props.initialCoordinate)
    const center = initial
        ? [initial.lat, initial.lng]
        : props.defaultCenter

    map = L.map(mapContainer.value).setView(center, props.defaultZoom)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
    }).addTo(map)

    if (initial) {
        placeMarker(initial.lat, initial.lng)
    }

    if (props.readonly) return

    map.on('click', async (e) => {
        const { lat, lng } = e.latlng
        placeMarker(lat, lng)
        emit('update:modelValue', `${lat.toFixed(6)},${lng.toFixed(6)}`)
        await reverseGeocode(lat, lng)
    })
})

watch(() => props.modelValue, (val) => {
    const parsed = parseCoordinate(val)
    if (!parsed && marker) {
        marker.remove()
        marker = null
        selectedLatLng.value = null
    }
})
</script>

<template>
    <div class="space-y-2">
        <div
            ref="mapContainer"
            :class="['w-full rounded-md border border-gray-300 z-0', height]"
        />

        <div class="flex items-center gap-3">
            <div class="flex-1 text-sm bg-gray-50 border border-gray-200 rounded px-3 py-2">
                <span v-if="selectedLatLng" class="text-gray-700">
                    📍 {{ selectedLatLng.lat }}, {{ selectedLatLng.lng }}
                </span>
                <span v-else class="text-gray-400">
                    {{ readonly ? 'Tidak ada koordinat.' : 'Belum ada titik dipilih — klik pada peta' }}
                </span>
            </div>
            <button
                v-if="selectedLatLng && !readonly"
                type="button"
                @click="resetMarker"
                class="text-xs text-red-500 hover:text-red-700 whitespace-nowrap"
            >
                Reset
            </button>
        </div>

        <p v-if="isLoadingAlamat" class="text-xs text-gray-400 animate-pulse">
            ⏳ Mengambil alamat...
        </p>
    </div>
</template>