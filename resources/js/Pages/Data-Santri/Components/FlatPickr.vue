<script setup>
import { ref, onMounted, watch } from "vue";

const props = defineProps({
    modelValue: String, // Untuk binding v-model
});

const emit = defineEmits(["update:modelValue"]);

const inputRef = ref(null);
let fpInstance = null;

onMounted(() => {
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

watch(() => props.modelValue, (newValue) => {
    if (!newValue && fpInstance) {
        fpInstance.clear(); // Reset tampilan input Flatpickr
    }
});

</script>

<template>
    <input ref="inputRef" type="text">
</template>
