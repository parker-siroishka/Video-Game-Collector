<script setup>
import { onUpdated, onMounted, ref } from "vue";

import AddNewGameForm from "@/Pages/Dashboard/Partials/AddNewGameForm.vue";
import PlaySessions from "@/Pages/Dashboard/Partials/PlaySessions.vue";

import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Toggle from "@/Components/Toggle.vue";
import Modal from "@/Components/Modal.vue";
import Header from "@/Components/Header.vue";

import getGames from "@/services/getGames";
import getGroupedPlaySessions from "@/services/getGroupedPlaySessions";
import postPlaySessions from "@/services/postPlaySessions";
import postGames from "@/services/postGames";

const showingCreateSessionModal = ref(false);
const showingAddNewGameForm = ref(false);
const hasGames = ref(false);
const games = ref([]);
const selectedGame = ref("");
const sessions = ref([]);

const showCreateSessionModal = () => {
    showingCreateSessionModal.value = true;
    showingAddNewGameForm.value = false;
};

const fetchGames = async () => {
    games.value = await getGames();
    hasGames.value = games.value.length > 0;
};

const fetchGroupedPlaySessions = async () => {
    sessions.value = await getGroupedPlaySessions();
};

const onCancel = () => {
    showingCreateSessionModal.value = false;
};

const onSubmit = async (form) => {
    if (!showingAddNewGameForm.value) {
        await postPlaySessions(selectedGame.value);
        showingCreateSessionModal.value = false;
        fetchGroupedPlaySessions();
    } else {
        const createdGame = await postGames(form);
        await postPlaySessions(createdGame.game);
        showingCreateSessionModal.value = false;
        fetchGroupedPlaySessions();
    }
};

const toggleAddNewGameForm = (newStatus) => {
    showingAddNewGameForm.value = newStatus;
    selectedGame.value = "";
};

onUpdated(() => {
    fetchGames();
});
onMounted(() => {
    fetchGroupedPlaySessions();
    fetchGames();
});
</script>

<template>
    <section class="space-y-6 mt-5">
        <div
            class="sm:w-fit ml-2 mr-2 flex items-center justify-between bg-gray-700 overflow-hidden shadow-sm rounded-lg"
        >
            <div class="p-6 text-white">Start a new gaming session</div>
            <PrimaryButton @click="showCreateSessionModal" class="mr-6"
                >start
            </PrimaryButton>
        </div>
        <div class="flex flex-col items-center">
            <Header>Sessions</Header>
            <h2 v-if="sessions.length == 0" class="block mt-20 text-gray-500">
                No sessions logged
            </h2>
        </div>
        <PlaySessions :sessions="sessions" />

        <Modal :show="showingCreateSessionModal" @close="onCancel">
            <div class="p-6">
                <h2 class="text-lg font-extrabold text-white">
                    Start a new game session
                </h2>
                <div class="flex place-content-between">
                    <div class="sm:w-1/2">
                        <form class="max-w-sm mt-3">
                            <label
                                for="games"
                                class="block mb-2 text-sm font-medium text-white"
                                >Select an existing game from your
                                library</label
                            >
                            <select
                                v-model="selectedGame"
                                :disabled="!hasGames || showingAddNewGameForm"
                                id="games"
                                class="disabled:bg-gray-400 disabled:text-gray-500 border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-700 border-gray-600 placeholder-gray-400 text-white"
                            >
                                <option selected value="">Select a game</option>
                                <option v-for="game in games" :value="game">
                                    {{ game.title }}
                                </option>
                            </select>
                        </form>
                    </div>
                    <div class="flex sm:w-1/2 place-content-center">
                        <img
                            v-if="selectedGame.coverart"
                            :src="selectedGame.coverart"
                            class="shadow-md shadow-gray-400 object-center object-cover h-28 w-24 rounded-lg ml-5 sm:h-36 sm:w-24"
                        />
                    </div>
                </div>
                <Toggle
                    class="mt-5"
                    label="Add New Game"
                    @updateStatus="toggleAddNewGameForm"
                />
                <div class="mt-5" v-show="showingAddNewGameForm">
                    <AddNewGameForm
                        :onSubmit="onSubmit"
                        :onCancel="onCancel"
                        submitLabel="Add & Start Session"
                    />
                </div>
                <!-- CTA buttons for starting session with pre-existing game -->
                <div
                    class="mt-6 flex justify-end"
                    v-show="!showingAddNewGameForm"
                >
                    <SecondaryButton @click="onCancel">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton
                        class="ms-3"
                        :disabled="!selectedGame"
                        @click="onSubmit"
                    >
                        Start Session
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
