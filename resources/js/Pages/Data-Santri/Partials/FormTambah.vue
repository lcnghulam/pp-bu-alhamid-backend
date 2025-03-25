<script setup>
import { router } from '@inertiajs/vue3';
import { ref, defineEmits, onMounted } from 'vue';
import FlatPickr from '@/Pages/Data-Santri/Components/FlatPickr.vue';

const props = defineProps({
    nis: String,
    formData: Object
})

// Declare Default Value
const fotoInput = ref(null);

const emit = defineEmits(['updatePreview']); // Define emit event
const maxSize = 400 * 1024; // 400KB

const handleFileChange = (event) => {
    const file = event.target.files[0];

    if (file) {
        // console.log("FormTambah: File masuk:", file.name); // Debugging

        if (file.size > maxSize) {
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

        // Baca gambar dan emit ke parent
        const reader = new FileReader();
        reader.onload = (e) => {
            // console.log("FormTambah: File berhasil dibaca! -> ", e.target.result);
            emit("updatePreview", e.target.result); // Kirim URL preview ke parent
        };
        reader.readAsDataURL(file);
    } else {
        // console.log("FormTambah: Tidak ada file yang dipilih, reset preview ke blankProfile");
        emit("updatePreview", null);
    }
};

const resetTGK = () => {
    formData.value.tgl_keluar = null; // Kosongkan tanggal
};

</script>

<style lang="css" scoped>
input:read-only {
    font-style: italic;
}

/* Untuk Chrome, Safari, Edge */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Untuk Firefox */
input[type="number"] {
    -moz-appearance: textfield;
}

input:read-only:not([type="file"]) {
    pointer-events: none;
}

input:read-only[type="file"] {
    background-color: #222e3c;
}

span.label-required {
    color: red !important;
}
</style>

<template>
    <form id="formTambahSantri" @submit.prevent="submit">
        <div class="row">
            <div class="mb-3">
                <label class="form-label" for="nis">NIS (Nomor Induk Santri)<span class="label-required">*</span>
                    <strong class="fst-italic">(Auto)</strong></label>
                <input type="number" class="form-control" id="nis" name="nis" placeholder="25000000000001" :value="nis"
                    readonly required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="nik">NIK<span class="label-required">*</span></label>
                <input type="number" class="form-control" id="nik" name="nik" placeholder="3505070000000001" required
                    @wheel.prevent @keydown.up.prevent @keydown.down.prevent v-model="props.formData.nik">
            </div>
            <div class="mb-3">
                <label class="form-label" for="nama_lengkap">Nama Lengkap<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="namaLengkap" name="nama_lengkap" placeholder="Ahmad Sofyan"
                    required v-model="formData.nama_lengkap">
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="tempat_lahir">Tempat Lahir<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="tempatLahir" name="tempat_lahir" placeholder="Malang"
                    required v-model="formData.tempat_lahir">
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="tgl_lahir">Tgl Lahir<span class="label-required">*</span></label>
                <FlatPickr class="form-control flatpickr-human" id="tglLahir" name="tgl_lahir"
                    placeholder="Pilih tanggal.." required v-model="formData.tgl_lahir" preview-id="PVtgl_lahir" />
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
                <label class="form-label" for="no_hp">No. HP<span class="label-required">*</span></label>
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
                <label class="form-label" for="tgl_masuk">Tgl Masuk<span class="label-required">*</span></label>
                <FlatPickr class="form-control flatpickr-human" id="tglMasuk" name="tgl_masuk"
                    placeholder="Pilih tanggal.." required v-model="formData.tgl_masuk" preview-id="PVtgl_masuk" />
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="tgl_keluar">Tgl Keluar</label>
                <div class="d-flex">
                    <FlatPickr class="form-control flatpickr-human" id="tglKeluar" name="tgl_keluar"
                        placeholder="Pilih tanggal.." v-model="formData.tgl_keluar" preview-id="PVtgl_keluar"/>
                    <button type="button" id="btnResetTGK" class="btn btn-danger ms-1" @click="formData.tgl_keluar = null"><i class="align-middle"
                            data-feather="rotate-ccw"></i></button>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <button type="submit" id="btnSimpan" class="btn btn-success me-2">Simpan</button>
            <button type="button" id="btnBatal" class="btn btn-danger"
                @click="router.get('/data-santri')">Batal</button>
            <button type="button" id="btnReset" class="btn btn-secondary ms-auto"
                @click="router.get('/data-santri/tambah')">
                <i class="link-icon" data-feather="rotate-ccw"></i> Reset
            </button>
        </div>
    </form>
</template>
