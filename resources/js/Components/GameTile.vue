<script setup>
import { ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import { isValidUrl } from "@/Utils/urlUtils";
import { COVER_ART_PLACEHOLDER } from "@/Constants/urls";

const props = defineProps({
    coverArtUrl: {
        type: String,
        required: false,
    },
    title: {
        type: String,
        default: null,
    },
});

const url = ref(COVER_ART_PLACEHOLDER);
const showTitle = ref(true);
const gameTitle = ref(props.title);

const updateArt = async () => {
    const validUrl = await isValidUrl(props.coverArtUrl);
    url.value = validUrl ? props.coverArtUrl : COVER_ART_PLACEHOLDER;

    showTitle.value = !validUrl;
    if (showTitle.value && gameTitle.value.length > 40) {
        gameTitle.value = gameTitle.value.slice(0, 37) + "...";
    }
};

watch(() => props.coverArtUrl, updateArt);

updateArt();
</script>

<template>
    <Link href="#" class="relative h-[140px] w-[100px] group">
        <img
            :src="url"
            class="h-full w-full rounded-md object-cover z-10 shadow-md shadow-gray-700"
        />
        <p
            v-if="showTitle"
            class="absolute inset-0 flex items-center justify-center text-center text-white text-sm font-bold bg-black bg-opacity-50 p-2"
        >
            {{ gameTitle }}
        </p>
        <div
            class="absolute w-full bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 text-sm text-center font-medium text-white bg-gray-800 rounded-lg shadow-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
        >
            <p class="mb-1">{{ props.title }}</p>
            <div
                class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3 h-3 bg-gray-800 rotate-45"
            ></div>
        </div>
    </Link>
</template>
