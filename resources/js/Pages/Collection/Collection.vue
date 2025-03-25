<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";

import GameTile from "@/Components/GameTile.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import getGames from "@/services/getGames";

const hasGames = ref(false);
const games = ref([]);
const input = ref("");

const fetchGames = async () => {
    games.value = await getGames();
    hasGames.value = games.value.length > 0;
};

const filteredList = () => {
    return games.value.filter((game) =>
        game.title.toLowerCase().includes(input.value.toLowerCase())
    );
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
                <h1 class="text-2xl font-extrabold text-white mb-5 ml-2">
                    Collection
                </h1>
                <div class="flex items-center">
                    <img
                        src="../../../assets/images/magnifying-glass.png"
                        class="mr-3 w-9 h-9"
                    />
                    <TextInput
                        id="title"
                        class="mt-1 block w-full sm:w-3/4"
                        required
                        placeholder="Search your collection"
                        v-model="input"
                    />
                </div>
                <div
                    class="mt-10 grid md:grid-cols-8 sm:grid-cols-6 xs:grid-cols-5 grid-cols-3 gap-4"
                >
                    <GameTile
                        v-for="game in filteredList()"
                        :coverArtUrl="game.coverart"
                        :title="game.title"
                        :id="game.id"
                        :enableToolTip="true"
                        :needsTitle="true"
                    />
                </div>
                <div
                    class="max-w-4xl mx-auto mt-10 flex flex-col items-center justify-center w-1/2"
                    v-if="input && !filteredList().length"
                >
                    <img
                        class="h-10 w-10 mb-2"
                        src="../../../assets/images/no-results.png"
                    />
                    <p class="text-white text-xl">No games found</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
