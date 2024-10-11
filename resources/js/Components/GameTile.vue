<script setup>
import { onMounted, computed } from "vue";
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
// todo make coverart nullable
// const computedUrl = computed(() => {
//     return !isValidUrl(props.coverArtUrl)
//         ? COVER_ART_PLACEHOLDER
//         : props.coverArtUrl;
// });
</script>

<template>
    <!-- todo: link to individual game's page -->
    <Link>
        <img
            :src="coverArtUrl"
            class="h-[176px] w-[100px] rounded-md object-cover z-10 shadow-md shadow-gray-700"
        />
    </Link>
</template>
