<script setup>
import { router } from '@inertiajs/vue3';
import { ref, defineEmits, onMounted, nextTick } from 'vue';
import FlatPickr from '@/Pages/Data-Santri/Components/FlatPickr.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    nis: String,
    formData: Object
})

// Declare Default Value
const fotoInput = ref(null);

const emit = defineEmits(['updatePreview']); // Define emit event
const maxSize = 400 * 1024; // 400KB
let fileFoto = ref(null);

const handleFileChange = (event) => {
    fileFoto = event.target.files[0];

    if (fileFoto) {
        // console.log("FormTambah: File masuk:", file.name); // Debugging

        if (fileFoto.size > maxSize) {
            Swal.fire({
                icon: "error",
                title: "Gagal...",
                text: "Ukuran gambar maksimal 400KB!",
            });

            // Reset input file
            fotoInput.value.value = "";
            // console.log("FormTambah: File terlalu besar, Reset input!");
            emit("updatePreview", null);
            // // console.log(emit);
            return;
        }

        // Emit to Parent
        const reader = new FileReader();
        reader.onload = (e) => {
            // console.log("FormTambah: File berhasil dibaca! -> ", e.target.result);
            emit("updatePreview", e.target.result); // Kirim URL preview ke parent
        };
        reader.readAsDataURL(fileFoto);
    } else {
        // console.log("FormTambah: Tidak ada file yang dipilih, reset preview ke blankProfile");
        emit("updatePreview", null);
    }
};

const pageReload = () => {
    window.notyf.open({
        type: "info",
        duration: 1000,
        backgroundColor: "#ff6f00",
        message: `<i data-feather="send"></i>  Resetting...`,
    })
    window.feather.replace();
    router.get('/data-santri/tambah');
    window.notyf.open({
        type: "success",
        message: 'Data Reset!',
    })
}

const submitFormTambah = async () => {
    // NIS
    props.formData.nis = props.nis

    let formData = new FormData();
    for (const key in props.formData) {
        formData.append(key, props.formData[key]);
    }
    // Foto
    if (fileFoto) {
        formData.append("foto", fileFoto);
    }

    // Start Sending
    const sendingNotyf = window.notyf.open({
        type: "info",
        backgroundColor: "#ff6f00",
        message: `<i data-feather="send"></i>  Sending data...`,
    });
    window.feather.replace();
    try {
        // console.log('FormTambah: Send data -> ', props.formData)
        const response = await axios.post('/data-santri/store', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'enctype': 'multipart/form-data',
            },
        });
        if (response.data.success) {
            window.notyf.dismiss(sendingNotyf);
            window.notyf.open({
                type: "success",
                ripple: true,
                dismissible: true,
                message: response.data.message
            });
            router.get('/data-santri')
        }
    } catch (error) {
        window.notyf.dismiss(sendingNotyf);
        let errorMessage = "Terjadi kesalahan!";

        if (error.response && error.response.data) {
            if (error.response.data.message) {
                errorMessage = "Gagal menambahkan data! " + error.response.data.message;
            } else if (error.response.data.errors) {
                // Ambil semua pesan error dari object errors
                errorMessage = Object.values(error.response.data.errors)
                    .flat()
                    .join(", ");
            }
        } else if (error.message) {
            errorMessage = "Gagal menambahkan data! " + error.message;
        }
        window.notyf?.open({
            type: "error",
            ripple: true,
            dismissible: true,
            message: errorMessage,
        });
    }
};

onMounted(async () => {
    await nextTick()
    if (window.notyf) {
        Object.assign(window.notyf.options, {
            duration: 5000,
            position: { x: "right", y: "top" },
            ripple: true,
            dismissible: true,
        });
    }
    if (window.feather) {
        window.feather.replace();
    }
});

let tglKeluar = null;
const resetTGK = () => {
    tglKeluar = flatpickr("#tglKeluar", {
        altInput: true,
        altFormat: "j F Y",
        dateFormat: "Y-m-d",
        defaultDate: props.formData.tgl_keluar || "",
        onChange: (selectedDates, instance) => {
            props.formData.tgl_keluar = selectedDates.length
                ? flatpickr.formatDate(selectedDates[0], "Y-m-d")
                : "";
        },
    });
    tglKeluar.clear();
};

</script>

<template>
    <form id="formTambahSantri" ref="Tform" @submit.prevent="submitFormTambah">
        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label" for="nis">NIS (Nomor Induk Santri)<span class="label-required">*</span>
                    <strong class="fst-italic">(Auto)</strong></label>
                <input type="number" class="form-control" id="nis" name="nis" placeholder="25000000000001" :value="nis"
                    readonly required>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="nik">NIK<span class="label-required">*</span></label>
                <input type="number" class="form-control" id="nik" name="nik" placeholder="3505070000000001" required
                    @wheel.prevent @keydown.up.prevent @keydown.down.prevent v-model="formData.nik">
            </div>
            <div class="mb-3">
                <label class="form-label" for="namaLengkap">Nama Lengkap<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="namaLengkap" name="nama_lengkap" placeholder="Ahmad Sofyan"
                    required v-model="formData.nama_lengkap">
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="tempatLahir">Tempat Lahir<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="tempatLahir" name="tempat_lahir" placeholder="Malang"
                    required v-model="formData.tempat_lahir">
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label">Tgl Lahir<span class="label-required">*</span></label>
                <FlatPickr id="tglLahir" name="tgl_lahir" required v-model="formData.tgl_lahir"
                    preview-id="PVtgl_lahir" />
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="gender">Jenis Kelamin<span class="label-required">*</span></label>
                <select id="gender" name="gender" class="form-control" required v-model="formData.gender">
                    <option value="" disabled>Pilih...</option>
                    <option value="L">Laki-Laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="ahmadsofyan12@gmail.com"
                    v-model="formData.email">
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="noHP">No. HP<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="noHP" name="no_hp" placeholder="0812345678" required
                    v-model="formData.no_hp">
            </div>
            <div class="mb-3">
                <label class="form-label" for="alamat">Alamat<span class="label-required">*</span></label>
                <textarea class="form-control" id="alamat" name="alamat" rows="4"
                    placeholder="Jl. Sumber Agung, Ganjaran Selatan, Ganjaran, Kec. Gondanglegi, Kabupaten Malang, Jawa Timur 65174"
                    required v-model="formData.alamat"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="foto">Foto</label>
                <input class="form-control" type="file" id="foto" name="foto" accept="image/*"
                    @change="handleFileChange" ref="fotoInput" />
                <span class="text-muted fst-italic" style="font-size: 0.6rem; color: #ff5555 !important;">Perhatian:
                    Ukuran file maksimal 400 KB</span>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Tgl Masuk<span class="label-required">*</span></label>
                <FlatPickr id="tglMasuk" name="tgl_masuk" required v-model="formData.tgl_masuk"
                    preview-id="PVtgl_masuk" />
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Tgl Keluar</label>
                <div class="d-flex">
                    <FlatPickr id="tglKeluar" name="tgl_keluar" v-model="formData.tgl_keluar"
                        preview-id="PVtgl_keluar" />
                    <button type="button" id="btnResetTGK" class="btn btn-danger ms-1" @click="resetTGK"><i
                            class="align-middle" data-feather="rotate-ccw"></i></button>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <button type="submit" id="btnSimpan" class="btn btn-success me-2">Simpan</button>
            <button type="button" id="btnBatal" class="btn btn-danger"
                @click="router.get('/data-santri')">Batal</button>
            <button type="button" id="btnReset" class="btn btn-secondary ms-auto" @click="pageReload()">
                <i class="link-icon" data-feather="rotate-ccw"></i> Reset
            </button>
        </div>
    </form>
</template>
