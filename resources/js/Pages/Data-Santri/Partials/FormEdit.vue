<script setup>
import { router } from "@inertiajs/vue3";
import { ref, defineEmits, onMounted, nextTick, watch, toRaw } from "vue";
import Swal from "sweetalert2";
import FlatPickr from "@/Pages/Data-Santri/Components/FlatPickr.vue";
import flatpickr from "flatpickr";
import axios from "axios";

const props = defineProps({
    data: Object,
});

const formE = ref(null);

const pageReload = async () => {
    const reloadNotyf = window.notyf.open({
        type: "info",
        duration: 1000,
        backgroundColor: "#ff6f00",
        message: `<i data-feather="send"></i>  Reload data...`,
    })
    window.feather.replace()
    router.get(`/data-santri/edit?nis=${props.data.nis}`);
    await nextTick();
    window.notyf.open({
        type: "success",
        message: 'Data Reloaded!',
    })
}

const tgl_lahir = ref(props.data.tgl_lahir);
const tgl_masuk = ref(props.data.tgl_masuk);
const tgl_keluar = ref(props.data.tgl_keluar);

const emit = defineEmits(['updatePreview']); // Define emit event

const fotoDefault = ref(null);

const fetchFoto = async () => {
    if (!props.data.foto) return;

    try {
        const response = await fetch(`/storage/data-santri/${props.data.foto}`);
        const blob = await response.blob();
        const reader = new FileReader();

        reader.onload = () => {
            fotoDefault.value = reader.result;
            // console.log("FotoEdit: Hasil fetch & render foto ->", fotoDefault.value);
            emit("updatePreview", fotoDefault.value);
        };

        reader.readAsDataURL(blob);
    } catch (error) {
        console.error('Gagal mengambil foto:', error);
    }
};

const fotoInput = ref(null);

const maxSize = 400 * 1024; // 400KB
let fileFoto = ref(null);

const handleFileChange = (event) => {
    fileFoto = event.target.files[0];

    if (fileFoto) {
        // console.log("FormEdit: File masuk:", fileFoto.name); // Debugging

        if (fileFoto.size > maxSize) {
            Swal.fire({
                icon: "error",
                title: "Gagal...",
                text: "Ukuran gambar maksimal 400KB!",
            });

            // Reset input file
            fotoInput.value.value = "";
            // console.log("FormEdit: File terlalu besar, Reset input!");
            emit("updatePreview", fotoDefault.value);
            // // console.log(emit);
            return;
        }

        // Emit to Parrent
        const reader = new FileReader();
        reader.onload = (e) => {
            // console.log("FormEdit: File berhasil dibaca! -> ", e.target.result);
            emit("updatePreview", e.target.result); // Kirim URL preview ke parent
        };
        reader.readAsDataURL(fileFoto);
    } else {
        // console.log("FormEdit: Tidak ada file yang dipilih, reset preview ke null");
        emit("updatePreview", fotoDefault.value);
    }
};

const submitFormEdit = async () => {
    const formData = new FormData();
    formData.append('_method', 'PATCH');
    for (const key in props.data) {
        formData.append(key, props.data[key]);
    }
    if (props.data.foto && typeof props.data.foto === "string") {
        // console.log("Foto Sama");
        formData.delete("foto");
    } 
    if (fileFoto) {
        console.log("Foto Baru");
        formData.delete("foto");
        formData.append("foto", fileFoto); // Tambahkan file baru jika tidak ada foto
    }

    // Start Sending
    const sendingNotyf = window.notyf.open({
        type: "info",
        backgroundColor: "#ff6f00",
        message: `<i data-feather="send"></i>  Updating data...`,
    });
    window.feather.replace();

    try {
        const response = await axios.post(`/data-santri/patch?nis=${props.data.nis}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'enctype': 'multipart/form-data',
            }
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
}

onMounted(async () => {
    await nextTick();
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
    fetchFoto();
});

watch([tgl_lahir, tgl_masuk, tgl_keluar], ([newLahir, newMasuk, newKeluar]) => {
    props.data.tgl_lahir = newLahir;
    props.data.tgl_masuk = newMasuk;
    props.data.tgl_keluar = newKeluar;
});

let tglKeluar = null;
const resetTGK = () => {
    tglKeluar = flatpickr("#tglKeluar", {
        altInput: true,
        altFormat: "j F Y",
        dateFormat: "Y-m-d",
        defaultDate: tgl_keluar.value || "",
        onChange: (selectedDates, instance) => {
            tgl_keluar.value = selectedDates.length
                ? flatpickr.formatDate(selectedDates[0], "Y-m-d")
                : "";
        },
    });
    tglKeluar.clear();
};

</script>

<template>
    <form id="formEditSantri" ref="formE" @submit.prevent="submitFormEdit">
        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label" for="nis">NIS (Nomor Induk Santri)<span class="label-required">*</span>
                    <strong class="fst-italic">(Auto)</strong></label>
                <input type="number" class="form-control" id="nis" name="nis" placeholder="25000000000001"
                    v-model="props.data.nis" readonly required />
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="nik">NIK<span class="label-required">*</span></label>
                <input type="number" class="form-control" id="nik" name="nik" placeholder="3505070000000001" required
                    @wheel.prevent @keydown.up.prevent @keydown.down.prevent v-model="data.nik" />
            </div>
            <div class="mb-3">
                <label class="form-label" for="namaLengkap">Nama Lengkap<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="namaLengkap" name="nama_lengkap" placeholder="Ahmad Sofyan"
                    v-model="data.nama_lengkap" required />
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="tempatLahir">Tempat Lahir<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="tempatLahir" name="tempat_lahir" placeholder="Malang"
                    required v-model="data.tempat_lahir">
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label">Tgl Lahir<span class="label-required">*</span></label>
                <FlatPickr id="tglLahir" name="tgl_lahir" v-model="tgl_lahir" preview-id="PVtgl_lahir" required />
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="gender">Jenis Kelamin<span class="label-required">*</span></label>
                <select id="gender" name="gender" class="form-control" required v-model="data.gender">
                    <option value="" disabled>Pilih...</option>
                    <option value="L">Laki-Laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="ahmadsofyan12@gmail.com"
                    v-model="data.email">
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="noHP">No. HP<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="noHP" name="no_hp" placeholder="0812345678" required
                    v-model="data.no_hp">
            </div>
            <div class="mb-3">
                <label class="form-label" for="alamat">Alamat<span class="label-required">*</span></label>
                <textarea class="form-control" id="alamat" name="alamat" rows="4"
                    placeholder="Jl. Sumber Agung, Ganjaran Selatan, Ganjaran, Kec. Gondanglegi, Kabupaten Malang, Jawa Timur 65174"
                    required v-model="data.alamat"></textarea>
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
                <FlatPickr id="tglMasuk" name="tgl_masuk" required v-model="tgl_masuk" preview-id="PVtgl_masuk" />
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Tgl Keluar</label>
                <div class="d-flex">
                    <FlatPickr id="tglKeluar" name="tgl_keluar" v-model="tgl_keluar" preview-id="PVtgl_keluar" />
                    <button type="button" id="btnResetTGK" class="btn btn-danger ms-1" @click="resetTGK">
                        <i class="align-middle" data-feather="rotate-ccw"></i></button>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <button type="submit" id="btnSimpan" class="btn btn-primary me-2">
                <i class="link-icon" data-feather="save"></i> Update
            </button>
            <button type="button" id="btnBatal" class="btn btn-danger" @click="router.get('/data-santri')">
                Batal
            </button>
            <button type="button" id="btnReload" class="btn btn-secondary ms-auto" @click="pageReload">
                <i class="link-icon" data-feather="rotate-ccw"></i> Reload
            </button>
        </div>
    </form>
</template>
