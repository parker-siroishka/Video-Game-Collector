<script setup>
const props = defineProps({
    label: {
        type: String,
    },
});

const emit = defineEmits(["updateStatus"]);
</script>

<template>
    <label class="inline-flex items-center cursor-pointer">
        <input
            type="checkbox"
            value=""
            class="sr-only peer"
            @change="emit('updateStatus', $event.target.checked)"
        />
        <div
            class="relative w-11 h-6 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-800 rounded-full peer bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all border-gray-600 peer-checked:bg-blue-600"
        ></div>
        <span class="ms-3 text-sm font-medium text-gray-300">{{ label }}</span>
    </label>
</template>
