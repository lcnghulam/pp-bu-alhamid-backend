<script setup>
import DataSantri from '@/Pages/Data-Santri/Index.vue';
import { Head } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, reactive, ref, watch } from 'vue';
import FormTambah from '@/Pages/Data-Santri/Partials/FormTambah.vue';
import FormPreview from '@/Pages/Data-Santri/Partials/FormPreview.vue';
import '@/Pages/Data-Santri/Components/FormSantriCSS.css';

const pageTitle = 'Tambah Data Santri';

const props = defineProps({
    nis: String,
    // formData: Object,
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

// const formDataTambah = computed(() => props.formData);

//Initialize with default Value
const fotoPreview = ref(null);

onMounted(async () => {
    await nextTick()
    if (window.feather) {
        window.feather.replace();
    }
})

// Move watch after fotoPreview declaration.
watch(fotoPreview, (newVal) => {
    // console.log('Tambah: Foto preview -> ', newVal);
});
</script>

<template>

    <Head :title="pageTitle" />
    <DataSantri :page-title="pageTitle">
        <div class="row">
            <div class="col-12 col-xl-6">
                <div class="card my-3">
                    <div class="card-header pb-0">
                        <h5 class="card-title"><i class="align-middle" data-feather="user-plus"></i> Form Data Santri
                            Baru</h5>
                    </div>
                    <div class="card-body">
                        <FormTambah :nis="nis" :form-data="formDataTambah" @updatePreview="fotoPreview = $event" />
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card my-3">
                    <div class="card-header pb-0">
                        <h5 class="card-title">Preview Data <i class="align-middle"
                                data-feather="corner-right-down"></i></h5>
                    </div>
                    <div class="card-body">
                        <FormPreview :nis="nis" :form-data="formDataTambah" :foto-preview="fotoPreview" />
                    </div>
                </div>
            </div>
        </div>
    </DataSantri>
</template>