<script setup>
import { router } from '@inertiajs/vue3';

defineProps({
    santri: Array
})
</script>

<template>
    <div class="card-header pb-0">
        <div class="d-flex flex-row">
            <button id="btnTambah" class="btn btn-primary me-2" @click="router.get('/data-santri/tambah')">
                <i class="fa-solid fa-plus"></i> Tambah Data
            </button>
            <button id="btnRefresh" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-left"></i> Refresh
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="tabelDataSantri" class="table table-striped hover compact" style="width:100%">
            <thead>
                <tr>
                    <th>NIS</th>
                    <th>Nama Santri</th>
                    <th>Pendidikan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in santri" :key="index">
                    <td>{{ item.nis }}</td>
                    <td>{{ item.nama }}</td>
                    <td>{{ item.pendidikan }}</td>
                    <td>
                        <span class="badge" :class="item.status === 'aktif' ? 'bg-success' : 'bg-danger'">{{ item.status
                        }}</span>
                    </td>
                    <td class="table-action">
                        <button><i class="align-middle" data-feather="edit-2"></i></button>
                        <button><i class="align-middle" data-feather="trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>