<script setup>
import blankProfile from '@/../assets/img/blank-profile.png';
import { ref, onMounted, watchEffect, nextTick, computed } from 'vue';
import flatpickr from 'flatpickr';

const props = defineProps({
    nis: String,
    formData: Object,
    fotoPreview: String
})

const getGenderText = (value) => {
    const genderMap = {
        "L": "Laki-Laki",
        "P": "Perempuan"
    };
    return genderMap[value];
};

const fotoPV = ref(null); // Untuk menyimpan foto preview

const dateFields = ["tgl_lahir", "tgl_masuk", "tgl_keluar"];
const formattedDates = ref({});

const formatAllDates = () => {
    let newFormattedDates = {};
    dateFields.forEach(field => {
        if (props.formData[field]) {
            newFormattedDates[field] = flatpickr.formatDate(new Date(props.formData[field]), "j F Y");
        } else {
            newFormattedDates[field] = "";
        }
    });
    formattedDates.value = newFormattedDates;
};

onMounted(async () => {
    // await nextTick();
    formatAllDates();
});

watchEffect(() => {
    formatAllDates();
    fotoPV.value = props.fotoPreview;
    // console.log("FotoPreview: Foto masukkk ->", fotoPV.value)
});

</script>

<template>
    <form id="formPreviewDataSantri">
        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label" for="PVnis">NIS (Nomor Induk Santri)<span
                        class="label-required">*</span></label>
                <input type="text" class="form-control fst-italic" id="PVnis" :value="nis" disabled>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="PVnik">NIK<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="PVnik" :value="formData.nik" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label" for="PVnamaLengkap">Nama Lengkap<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="PVnamaLengkap" :value="formData.nama_lengkap" disabled>
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="PVtempatLahir">Tempat Lahir<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="PVtempatLahir" :value="formData.tempat_lahir" disabled>
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label">Tgl Lahir<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="PVtgl_lahir" :value="formattedDates.tgl_lahir" disabled>
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="PVgender">Jenis Kelamin<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="PVgender" :value="getGenderText(formData.gender)" disabled>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="PVemail">Email</label>
                <input type="text" class="form-control" id="PVemail" :value="formData.email" disabled>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label" for="PVnoHP">No. HP<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="PVnoHP" :value="formData.no_hp" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label" for="PValamat">Alamat<span class="label-required">*</span></label>
                <textarea class="form-control" id="PValamat" rows="4" disabled>{{ formData.alamat }}</textarea>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Tgl Masuk<span class="label-required">*</span></label>
                <input type="text" class="form-control" id="PVtgl_masuk" :value="formattedDates.tgl_masuk" disabled>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Tgl Keluar</label>
                <input type="text" class="form-control" id="PVtgl_keluar" :value="formattedDates.tgl_keluar" disabled>
            </div>
            <div class="text-center py-3 mb-3">
                <img :src="fotoPV || blankProfile"
                    :alt="'blank-profile' || formData.foto" id="PVfoto"
                    style="width: auto; max-width: 100%; height: auto; max-height: 250px; object-fit: contain; display: block; margin: auto;">
            </div>
        </div>
    </form>
</template>