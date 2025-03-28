<script setup>
import { ref, onMounted, watch, nextTick } from "vue";
import flatpickr from "flatpickr";

const props = defineProps({
    modelValue: String, // Untuk binding v-model
});

const emit = defineEmits(["update:modelValue"]);

const inputRef = ref(null);
let fpInstance = null;

onMounted(async () => {
    await nextTick()
    fpInstance = flatpickr(inputRef.value, {
        altInput: true,
        altFormat: "j F Y",
        dateFormat: "Y-m-d",
        defaultDate: props.modelValue || null,
        onChange: (selectedDates, dateStr) => {
            emit("update:modelValue", dateStr);
        }
    });

});

watch(async () => props.modelValue, (newValue) => {
    if (!newValue && fpInstance) {
        fpInstance.clear(); // Reset tampilan input Flatpickr
    }
});

</script>

<template>
    <input ref="inputRef" type="text" class="form-control flatpickr-human" placeholder="Pilih tanggal..">
</template>
