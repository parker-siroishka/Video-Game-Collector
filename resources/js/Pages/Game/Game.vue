<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import getGame from "@/services/getGame";
import { COVER_ART_PLACEHOLDER } from "@/Constants/urls";

const props = defineProps({
    gameId: Number,
});

const game = ref({});

const fetchGameInfo = async () => {
    const gameData = await getGame(props.gameId);
    game.value = gameData[0];
};

onMounted(() => {
    fetchGameInfo();
});
</script>

<template>
    <Head title="Collection" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto mt-10 p-4">
            <div
                class="pt-3 pb-4 px-4 rounded-lg bg-gradient-to-t from-gray-800 to-transparent border-2 border-gray-700"
            >
                <div
                    class="relative flex justify-center block w-full overflow-hidden"
                >
                    <img
                        class="h-96 w-auto object-contain z-10 shadow-lg shadow-gray-700"
                        :src="
                            game.coverart
                                ? game.coverart
                                : COVER_ART_PLACEHOLDER
                        "
                    />
                    <img
                        class="w-full absolute h-96 object-cover blur-md opacity-90"
                        :src="
                            game.coverart
                                ? game.coverart
                                : COVER_ART_PLACEHOLDER
                        "
                    />
                </div>
                <div class="p-6">
                    <h5
                        class="mb-8 text-4xl font-extrabold drop-shadow-sm tracking-tight text-white"
                    >
                        {{ game.title }}
                    </h5>
                    <div class="flex flex-wrap">
                        <div class="mb-4 md:mr-5">
                            <p
                                class="text-xs sm:p-0 p-1 sm:text-lg font-normal text-gray-200 sm:bg-transparent bg-gray-600 rounded-md mr-2"
                            >
                                Playtime
                                <strong class="text-orange-300"
                                    >{{ game.playtime }}h</strong
                                >
                            </p>
                        </div>
                        <div class="mb-4 md:mr-5">
                            <p
                                v-if="
                                    game.estimated_playtime &&
                                    game.estimated_playtime > 0
                                "
                                class="text-xs sm:p-0 p-1 sm:text-lg font-normal text-gray-200 sm:bg-transparent bg-gray-600 rounded-md mr-2"
                            >
                                Projected time
                                <strong class="text-orange-300"
                                    >{{
                                        Math.round(game.estimated_playtime)
                                    }}h</strong
                                >
                            </p>
                        </div>
                        <div class="mb-4 md:mr-5">
                            <p
                                v-if="game.console"
                                class="text-xs sm:p-0 p-1 sm:text-lg font-normal text-gray-200 sm:bg-transparent bg-gray-600 rounded-md"
                            >
                                Playing on
                                <strong class="text-orange-300">{{
                                    game.console
                                }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
