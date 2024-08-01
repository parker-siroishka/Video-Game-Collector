<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link :href="route('dashboard')">
        <img
            src="../../../resources/assets/images/logo.jpg"
            class="block h-12 w-auto fill-current text-gray-800 pr-5"
        />
    </Link>
</template>
