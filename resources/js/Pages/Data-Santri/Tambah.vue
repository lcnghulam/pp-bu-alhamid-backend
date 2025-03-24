<script setup>
import DataSantri from '../DataSantri.vue';
import { Head } from '@inertiajs/vue3';
import FormTambah from './Partials/FormTambah.vue';
import FormPreview from './Partials/FormPreview.vue';

const pageTitle = 'Tambah Data Santri';
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
                        <FormTambah />
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="card my-3">
                    <div class="card-header pb-0">
                        <h5 class="card-title">Preview Data <i class="align-middle" data-feather="corner-right-down"></i></h5>
                    </div>
                    <div class="card-body">
                        <FormPreview />
                    </div>
                </div>
            </div>
        </div>
    </DataSantri>
</template>