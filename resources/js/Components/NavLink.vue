<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? "inline-flex items-center px-1 pt-1 pr-2 pl-2 border-b-2 border-indigo-600 text-sm font-medium leading-5 text-orange-300 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out bg-gray-700"
        : "inline-flex items-center px-1 pt-1 pr-2 pl-2 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-orange-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
