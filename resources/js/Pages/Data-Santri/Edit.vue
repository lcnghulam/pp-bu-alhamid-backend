<script setup>
import { ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import DataSantri from '@/Pages/Data-Santri/Index.vue';
import FormEdit from '@/Pages/Data-Santri/Partials/FormEdit.vue';
import FormPreview from '@/Pages/Data-Santri/Partials/FormPreview.vue';
import '@/Pages/Data-Santri/Components/FormSantriCSS.css';

const pageTitle = 'Edit Data Santri';

const props = defineProps({
    data: Object,
    fotoPreview: String
})
//Initialize with default Value
const fotoPreview = ref(null);

// Move watch after fotoPreview declaration.
watch(fotoPreview, (newVal) => {
    // console.log('Edit: Foto preview -> ', newVal);
});
</script>

<template>

    <Head :title="pageTitle" />
    <DataSantri :page-title="pageTitle">
        <div class="row">
            <div class="col-12 col-xl-6">
                <div class="card my-3">
                    <div class="card-header pb-0">
                        <h5 class="card-title"><i class="align-middle" data-feather="edit"></i> Form Data Edit Santri
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- {Form Tambah, NIS : {{ data }}} -->
                        <FormEdit :data="data" @updatePreview="fotoPreview = $event" />
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
                        <FormPreview :form-data="data" :nis="data.nis" :foto-preview="fotoPreview" />
                    </div>
                </div>
            </div>
        </div>
    </DataSantri>
</template>