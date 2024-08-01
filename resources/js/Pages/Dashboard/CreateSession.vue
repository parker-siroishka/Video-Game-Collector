<script setup>
import axios from "axios";
import { onUpdated, onMounted, ref } from "vue";

import AddNewGameForm from "@/Pages/Dashboard/Partials/AddNewGameForm.vue";
import PlaySessions from "@/Pages/Dashboard/Partials/PlaySessions.vue";

import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Toggle from "@/Components/Toggle.vue";
import Modal from "@/Components/Modal.vue";
import Header from "@/Components/Header.vue";

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

const getGames = async () => {
    const { data } = await axios.get(route("games.get"));
    games.value = data.sort((a, b) => a.title.localeCompare(b.title));
    hasGames.value = games.value.length > 0;
};

const getGroupedPlaySessions = async () => {
    try {
        const { data } = await axios.get(route("playSessionsGrouped.get"));
        for (const [date, sessionsArray] of Object.entries(data)) {
            sessionsArray.sort((a, b) => {
                if (a.is_active && !b.is_active) {
                    return -1;
                }
                if (!a.is_active && b.is_active) {
                    return 1;
                }
                // If `is_active` status is the same, compare `created_at`
                return new Date(b.created_at) - new Date(a.created_at);
            });
        }
        sessions.value = data;
    } catch (error) {
        console.error("Error fetching play sessions:", error);
    }
};

const onCancel = () => {
    showingCreateSessionModal.value = false;
};

const onSubmit = async () => {
    if (!showingAddNewGameForm.value) {
        const sessionGame = selectedGame.value;
        try {
            const response = await axios.post(route("playSessions.post"), {
                game_id: sessionGame.id,
                is_active: true,
                is_paused: false,
                notes: null,
                start_session: new Date().toISOString(),
            });
            showingCreateSessionModal.value = false;
            getGroupedPlaySessions();
        } catch (error) {
            console.error("Error starting session:", error);
        }
    } else {
        console.log("Add & Start");
        // todo: implement this
    }
};

const toggleAddNewGameForm = (newStatus) => {
    showingAddNewGameForm.value = newStatus;
    selectedGame.value = "";
};

onUpdated(() => getGames());
onMounted(() => getGroupedPlaySessions());
</script>

<template>
    <section class="space-y-6 mt-5">
        <div
            class="sm:w-fit ml-2 mr-2 flex items-center justify-between bg-white overflow-hidden shadow-sm rounded-lg"
        >
            <div class="p-6 text-gray-900">Start a new gaming session</div>
            <PrimaryButton @click="showCreateSessionModal" class="mr-6"
                >start</PrimaryButton
            >
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
                <h2 class="text-lg font-extrabold text-gray-900">
                    Start a new game session
                </h2>
                <div class="flex place-content-between">
                    <div class="sm:w-1/2">
                        <form class="max-w-sm mt-3">
                            <label
                                for="games"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Select an existing game from your
                                library</label
                            >
                            <select
                                v-model="selectedGame"
                                :disabled="!hasGames || showingAddNewGameForm"
                                id="games"
                                class="disabled:bg-slate-200 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
