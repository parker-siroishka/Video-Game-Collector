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
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="0"
        required
        v-model="model"
    />
</template>
