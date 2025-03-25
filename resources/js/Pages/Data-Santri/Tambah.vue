<script setup>
import DataSantri from '../DataSantri.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue';
import FormTambah from './Partials/FormTambah.vue';
import FormPreview from './Partials/FormPreview.vue';

const pageTitle = 'Tambah Data Santri';

const props = defineProps ({
    nis: String,
})

const formDataTambah = reactive({
    nik: '',
    nama_lengkap: '',
    tempat_lahir: '',
    tgl_lahir: '',
    gender: '',
    email: '',
    no_hp: '',
    alamat: '',
    tgl_masuk: '',
    tgl_keluar: '',
})

//Initialize with default Value
const fotoPreview = ref(null);

// Move watch after fotoPreview declaration.
watch(fotoPreview, (newVal) => {
//   console.log("Tambah.vue: Foto Preview ter-update ->", newVal);
});
</script>

<template>
    <Head :title="pageTitle" />
    <DataSantri :pageTitle="pageTitle">
        <div class="row">
            <div class="col-12 col-xl-6">
                <div class="card my-3">
                    <div class="card-header pb-0">
                        <h5 class="card-title">Data Santri Baru</h5>
                    </div>
                    <div class="card-body">
                        <FormTambah :nis="nis" :form-data="formDataTambah" @updatePreview="fotoPreview = $event" />
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card my-3">
                    <div class="card-header pb-0">
                        <h5 class="card-title">Preview Data <i class="align-middle" data-feather="corner-right-down"></i></h5>
                    </div>
                    <div class="card-body">
                        <FormPreview :nis="nis" :form-data="formDataTambah" :fotoPreview="fotoPreview"/>
                    </div>
                </div>
            </div>
        </div>
    </DataSantri>
</template>