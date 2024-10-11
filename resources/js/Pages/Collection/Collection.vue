<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";

import GameTile from "@/Components/GameTile.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import getGames from "@/services/getGames";

const hasGames = ref(false);
const games = ref([]);

const fetchGames = async () => {
    games.value = await getGames();
    hasGames.value = games.value.length > 0;
};

onMounted(() => {
    fetchGames();
});
</script>

<template>
    <Head title="Collection" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto mt-10">
            <div class="pl-4 pr-4">
                <h1 class="text-2xl font-extrabold text-white mb-5">
                    Collection
                </h1>
                <div class="flex items-center">
                    <img
                        src="../../../assets/images/magnifying-glass.png"
                        class="mr-3"
                    />
                    <TextInput
                        id="title"
                        class="mt-1 block w-full sm:w-3/4"
                        required
                        placeholder="Search your collection"
                    />
                </div>
                <div
                    class="mt-10 grid md:grid-cols-8 sm:grid-cols-6 xs:grid-cols-5 grid-cols-3 gap-4"
                >
                    <GameTile v-for="game in games" :coverArtUrl="game.coverart"
                        >test</GameTile
                    >
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
