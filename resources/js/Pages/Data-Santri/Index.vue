<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Head } from '@inertiajs/vue3'
import { nextTick, onMounted, ref } from 'vue'
import TabelSantri from '@/Pages/Data-Santri/Partials/Tabel.vue'
import axios from 'axios';

const props = defineProps({
    pageTitle: {
        type: String,
        default: 'Data Santri'
    }
});

</script>

<template>
    <Head :title="props.pageTitle" />
    <DashboardLayout>
        <h1 class="d-inline align-middle">{{ pageTitle }}</h1>
        <slot>
            <div class="row">
                <div class="col-12">
                    <div class="card my-3">
                        <TabelSantri />
                    </div>
                </div>
            </div>
        </slot>
    </DashboardLayout>
</template>