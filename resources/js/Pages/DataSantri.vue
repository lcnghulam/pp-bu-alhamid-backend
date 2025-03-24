<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import TabelSantri from './Data-Santri/Partials/Tabel.vue'

const props = defineProps({
    pageTitle: {
        type: String,
        default: 'Data Santri'
    }
});

// Data Santri (Contoh)
const santriList = ref([
    { nis: '1001', nama: 'Ahmad', pendidikan: 'XII IPA', status: 'aktif' },
    { nis: '1002', nama: 'Aisyah', pendidikan: 'XI IPS', status: 'aktif' },
    { nis: '1003', nama: 'Budi', pendidikan: 'X MIPA', status: 'non-aktif' }
])
</script>

<template>
    <Head :title="props.pageTitle" />
    <DashboardLayout>
        <h1 class="d-inline align-middle">{{ props.pageTitle }}</h1>
        <slot>
            <div class="row">
                <div class="col-12">
                    <div class="card my-3">
                        <TabelSantri :santri="santriList" />
                    </div>
                </div>
            </div>
        </slot>
    </DashboardLayout>
</template>