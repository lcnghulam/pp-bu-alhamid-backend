<script setup>
import { router } from '@inertiajs/vue3';
import { nextTick, onMounted } from 'vue';
import $ from 'jquery';
import 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import axios from 'axios';
import Swal from 'sweetalert2';

const destroySantri = async (nis) => {
    Swal.fire({
        title: "Hapus Data Santri?",
        html: '<span class="mb-2">Data Santri dengan : </span><br>NIS : "<span class="fst-italic fw-bolder mb-2">' + nis + '"</span><br>akan dihapus!',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const softDel = await axios.delete(`/data-santri/softdel?nis=${nis}`);
                if (softDel.data.success) {
                    Swal.fire({
                        title: "Telah Dihapus!",
                        html: softDel.data.message,
                        icon: "success"
                    })
                    initializeDataTable()
                }
            } catch (error) {
                Swal.fire({
                    title: "Gagal!",
                    text: 'Terjadi sebuah kesalahan pada Server',
                    icon: "error"
                })
                console.error("❌ Catch error ->", error);
                console.log("🔴 Response error ->", error.softDel?.data?.error);
            }
        }
    })
}

const editSantri = async (nis) => {
    router.get(`/data-santri/edit?nis=${nis}`);
}

$(document).on("click", "#btnEdit", function () {
    let nis = $(this).data("id");
    editSantri(nis);
});

$(document).on("click", "#btnDestroy", function () {
    let nis = $(this).data("id");
    destroySantri(nis);
});

const initializeDataTable = async () => {
    // await nextTick()
    // console.log('Tabel.vue: Masuk DataTables ni bosss!!!')
    $("#tabelDataSantri").DataTable({
        ajax: '/data-santri/data',
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        columnDefs: [{ width: '10%', targets: 0 }],
        columns: [
            {
                data: "DT_RowIndex",
                className: 'text-center',
                orderable: false,
                searchable: false
            },
            { data: "nis", className: 'text-center' },
            {
                data: "nama_lengkap",
                className: "fw-bold",
                render: function (data, type, row) {
                    return `<div class="d-flex justify-content-between align-items-center">
                    <span class="text-label">${row.nama_lengkap}</span>
                    <button class="btn btn-primary btn-sm ms-2 me-1"><i class="align-middle fa-solid fa-eye my-1"></i></button>
                    </div>`
                }
            },
            {
                data: "status",
                className: 'text-center',
                render: function (data) {
                    return `<span class="badge ${data === "aktif" ? "bg-success" : "bg-danger"}">${data}</span>`;
                },
                searchable: false
            },
            {
                data: "aksi",
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                    <button id="btnEdit" data-id="${row.nis}"><i class="align-middle" data-feather="edit-2"></i></button>
                    <button id="btnDestroy" data-id="${row.nis}"><i class="align-middle" data-feather="trash"></i></button>`
                },
                orderable: false,
                searchable: false
            },
        ],
        order: [2, 'asc'],
        language: {
            emptyTable: "Belum ada data santri.",
            search: "Cari 🔍 : ",
            lengthMenu: "Tampilkan _MENU_ / halaman",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Data tidak ditemukan...",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: {
                first: "« Awal",
                last: "Akhir »",
                next: "Next ›",
                previous: "‹ Prev"
            }
        },
        drawCallback: function () {
            $("#tabelDataSantri tbody td:nth-child(5)").addClass("table-action");
            $('#tabelDataSantri').addClass('table table-striped hover')
            window.feather.replace();
        },
        destroy: true,
        error: function (xhr) {
            console.log(errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Terjadi kesalahan pada server.")
            window.notyf?.open({
                type: "warning",
                backgroundColor: "#fcb62c",
                ripple: true,
                dismissible: true,
                icon: {
                    className: "notyf__icon--error",
                    tagName: "i",
                },
                message: "Gagal Load Data!",
            });
        }

    });

};

onMounted(() => {
    if (window.notyf) {
        Object.assign(window.notyf.options, {
            duration: 5000,
            position: { x: "right", y: "top" },
        });
    }
    initializeDataTable()
});

</script>

<template>
    <div class="card-header pb-0">
        <div class="d-flex flex-row">
            <button id="btnTambah" class="btn btn-primary me-2" @click="router.get('/data-santri/tambah')">
                <i class="fa-solid fa-plus"></i> Tambah Data
            </button>
            <button id="btnRefresh" class="btn btn-secondary" @click="router.get('/data-santri')">
                <i class="fa-solid fa-rotate-left"></i> Refresh
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="tabelDataSantri" style="width:100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NIS</th>
                    <th>Nama Santri</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</template>

<style lang="css" scoped>
.dtr-control::before {
    content: '\f067';
    font-family: FontAwesome;
    margin-right: 10px;
}

.dtr-control[aria-expanded="true"]::before {
    content: '\f068';
}
</style>