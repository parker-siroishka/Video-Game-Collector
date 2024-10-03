<script setup>
import { onMounted, ref } from "vue";

const model = defineModel({
    type: Number,
    required: true,
});
</script>

<template>
    <input
        type="number"
        id="number-input"
        aria-describedby="helper-text-explanation"
        class="border text-sm rounded-lg block w-1/4 p-2.5 bg-gray-800 border-gray-600 placeholder-gray-400 text-white focus:ring-blue-500 focus:border-blue-500"
        placeholder="0"
        required
        v-model="model"
    />
</template>
