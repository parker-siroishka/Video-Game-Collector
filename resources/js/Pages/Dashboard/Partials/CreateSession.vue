<script>
import { onUpdated, ref } from 'vue';
import axios from "axios";

import AddNewGameForm from '@/Pages/Dashboard/Partials/AddNewGameForm.vue';

import PrimaryButton from '@/Components/PrimaryButton.vue'
import Toggle from '@/Components/Toggle.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

export default {
    components: {
        PrimaryButton,
        SecondaryButton,
        Modal,
        Toggle,
        AddNewGameForm
    },
    setup() {
        const showingCreateSessionModal = ref(false);
        const showingAddNewGameForm = ref(false);
        const hasGames = ref(false);
        const games = ref([]);
        const selectedGame = ref('');

        const showCreateSessionModal = () => {
            showingCreateSessionModal.value = true;
            showingAddNewGameForm.value = false;
        };

        // GET and sort current games alphabetically
        const getGames = async () => {
            const { data } = await axios.get(route('games.get'));
            games.value = data.sort((a, b) => a.title.localeCompare(b.title));;
            hasGames.value = games.value.length > 0;
        };

        const onCancel = () => {
            showingCreateSessionModal.value = false;
        };

        const onSubmit = async () => {
            if(!showingAddNewGameForm.value) {
                console.log('Start');
                // TODO: create session in db
            } else {
                console.log('Add & Start');
            }
        };

        const toggleAddNewGameForm = (newStatus) => {
            showingAddNewGameForm.value = newStatus;
            selectedGame.value = '';
        };

        onUpdated(() => getGames());

        return {
            showCreateSessionModal,
            onCancel,
            onSubmit,
            toggleAddNewGameForm,
            showingCreateSessionModal,
            showingAddNewGameForm,
            hasGames,
            games,
            selectedGame
        };
    }
}
</script>

<template>
    <section class="space-y-6 mt-5">
        <div class="w-full sm:w-fit flex items-center justify-content-around bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">Start a new gaming session</div>
            <PrimaryButton @click="showCreateSessionModal" class="mr-6">start</PrimaryButton>
        </div>

        <Modal :show="showingCreateSessionModal" @close="onCancel">
            <div class="p-6">
                <h2 class="text-lg font-extrabold text-gray-900">
                    Start a new game session
                </h2>
                <!-- 
                    TODO: implement these components Components
                    <PlaySessionsContainer>
                        <PlaySession />
                    </PlaySessionsContainer> 
                -->
                <div class="flex place-content-between">
                    <div class="sm:w-1/2">
                        <form class="max-w-sm mt-3">
                            <label for="games" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an existing game from your library</label>
                            <select v-model="selectedGame" :disabled="!hasGames || showingAddNewGameForm" id="games" class="disabled:bg-slate-200 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="" >Select a game</option>
                                <option v-for="game in games" :value="game">{{ game.title }}</option>
                            </select>
                        </form>
                    </div>
                    <div class="flex sm:w-1/2 place-content-center">
                        <img v-if="selectedGame.coverart" :src="selectedGame.coverart" class="shadow-md shadow-gray-400 object-center object-cover h-28 w-24 rounded-lg ml-5 sm:h-36 sm:w-24" />
                    </div> 
                </div>
                <Toggle class="mt-5" label="Add New Game" @updateStatus="toggleAddNewGameForm"/>
                <div class="mt-5" v-show="showingAddNewGameForm">
                    <AddNewGameForm 
                        :onSubmit="onSubmit"
                        :onCancel="onCancel"
                        submitLabel='Add & Start Session'/>
                </div>
                <!-- CTA buttons for starting session with pre-existing game -->
                <div class="mt-6 flex justify-end" v-show="!showingAddNewGameForm">
                    <SecondaryButton @click="onCancel"> Cancel </SecondaryButton>
                    <PrimaryButton
                        class="ms-3"
                        :disabled="!selectedGame"
                        @click="onSubmit">
                        Start Session
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
