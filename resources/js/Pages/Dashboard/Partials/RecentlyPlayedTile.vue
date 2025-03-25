<script setup>
import { onMounted, ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import { isValidUrl } from "@/Utils/urlUtils";
import { COVER_ART_PLACEHOLDER } from "@/Constants/urls";

const props = defineProps({
    session: {
        type: Object,
        required: true,
    },
});

const url = ref(COVER_ART_PLACEHOLDER);
const showTitle = ref(false);
const gameTitle = ref(props.session.game.title);

const updateArt = async () => {
    const validUrl = await isValidUrl(props.session.game.coverart);
    url.value = validUrl ? props.session.game.coverart : COVER_ART_PLACEHOLDER;

    showTitle.value = !validUrl;
    if (showTitle.value && gameTitle.value.length > 40) {
        gameTitle.value = gameTitle.value.slice(0, 37) + "...";
    }
};

onMounted(() => updateArt());
</script>

<template>
    <div class="w-[135px] h-full">
        <div
            class="relative h-full rounded-lg overflow-hidden shadow-md shadow-gray-800 group"
        >
            <img
                :src="url"
                class="absolute top-0 left-0 h-full w-full object-cover"
            />
            <p
                v-if="showTitle"
                class="absolute inset-0 flex items-center justify-center text-center text-white text-sm font-bold bg-black bg-opacity-50 p-2"
            >
                {{ gameTitle }}
            </p>
            <div
                class="px-2 py-2 w-auto absolute bottom-0 mb-2 ml-2 flex space-x-3 bg-gray-700 bg-opacity-90 rounded-lg hidden group-hover:block"
            >
                <button
                    class="z-10 relative h-[34px] inline-flex items-center px-2 py-2 bg-orange-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase disabled:opacity-50 tracking-widest hover:bg-orange-600 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <img src="../../../../assets/images/play.png" class="w-4" />
                </button>
                <Link :href="'/games/' + props.session.game.id">
                    <button
                        class="z-10 relative h-[34px] w-[40px] inline-flex items-center px-2 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase disabled:opacity-50 tracking-widest hover:bg-blue-600 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <img src="../../../../assets/images/goto.png" />
                    </button>
                </Link>
            </div>
        </div>
    </div>
</template>
