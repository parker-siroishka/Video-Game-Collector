<script setup>
import { useForm } from '@inertiajs/vue3';
import { onMounted, watch, ref, computed } from 'vue';
import axios from "axios";

import { isValidUrl } from '../../../Utils/urlUtils';
import { COVER_ART_PLACEHOLDER } from '@/Constants/urls';

import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Toggle from '@/Components/Toggle.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import NumberInput from '@/Components/NumberInput.vue';

const showingCreateSessionModal = ref(false);
const showingAddNewGameForm = ref(false);
const passwordInput = ref(null);
const hasGames = ref(false);
const games = ref(['test']);
const selectedGame = ref('');
const newGameCoverArt = ref(null);

const form = useForm({
    title: '',
    thumbnailUrl: '',
    playtime: 0,
});

const thumbnailUrl = computed({
  get: () => form.thumbnailUrl,
  set: (value) => form.thumbnailUrl = value,
});

const showCreateSessionModal = () => {
    showingCreateSessionModal.value = true;
    showingAddNewGameForm.value = false;
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

// GET and sort current games alphabetically
const getGames = async () => {
    const { data } = await axios.get('/games');
    games.value = data.sort((a, b) => a.title.localeCompare(b.title));;
    hasGames.value = games.value.length > 0;
};

const onStartClick = async () => {

};

const onAddAndStartClick = async () => {

};

const toggleAddNewGameForm = (newStatus) => {
    showingAddNewGameForm.value = newStatus;
    selectedGame.value = '';
};

const closeModal = () => {
    showingCreateSessionModal.value = false;
    form.reset();
};

onMounted(() => getGames());

watch(thumbnailUrl, async (newUrl, oldUrl) => {
    if (newUrl) {
            if (await isValidUrl(newUrl)) {
                newGameCoverArt.value.src = newUrl;
            } else {
                newGameCoverArt.value.src = COVER_ART_PLACEHOLDER;
        }
    }
}, { immediate: true });


</script>

<template>
    <section class="space-y-6 mt-5">
        <div class="w-full sm:w-fit flex items-center justify-content-around bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">Start a new gaming session</div>
            <PrimaryButton @click="showCreateSessionModal" class="mr-6">start</PrimaryButton>
        </div>

        <Modal :show="showingCreateSessionModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-extrabold text-gray-900">
                    Start a new game session
                </h2>
                <div class="flex place-content-between">
                    <div class="sm:w-1/2">
                        <form class="max-w-sm mt-3">
                            <label for="games" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select an existing game from your library</label>
                            <select v-model="selectedGame" :disabled="!hasGames || showingAddNewGameForm" id="games" class="disabled:bg-slate-200 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="" >Choose a game</option>
                                <option v-for="game in games" :value="game">{{ game.title }}</option>
                            </select>
                        </form>
                    </div>
                    <div class="flex sm:w-1/2 place-content-center">
                        <img v-if="selectedGame.coverart" :src="selectedGame.coverart" onerror="this.onerror=null;this.src='https://t3.ftcdn.net/jpg/02/68/55/60/360_F_268556012_c1WBaKFN5rjRxR2eyV33znK4qnYeKZjm.jpg';" class="shadow-md shadow-gray-400 object-center object-cover h-28 w-24 rounded-lg ml-5 sm:h-36 sm:w-24" />
                    </div> 
                </div>
                <Toggle class="mt-5" label="Add New Game" @updateStatus="toggleAddNewGameForm"/>
                <div class="mt-5" v-show="showingAddNewGameForm">
                    <InputLabel for="title" value="Title" />
                    <TextInput
                        id="title"
                        class="mt-1 block w-3/4"
                        required
                        placeholder="Enter game title..."
                        v-model="form.title"
                    />
                    <InputLabel for="coverArt" value="Cover Art URL" class="mt-5"/>
                    <TextInput
                        id="coverArt"
                        class="mt-1 block w-3/4"
                        required
                        placeholder="Enter cover art URL"
                        v-model="form.thumbnailUrl"
                    />
                    <img v-if="thumbnailUrl" ref="newGameCoverArt" class="shadow-md shadow-gray-400 object-center object-cover h-28 w-24 rounded-lg ml-5 mt-5 sm:h-36 sm:w-24" />
                    <InputLabel for="playtime" value="Playtime (hrs)" class="mt-5"/>
                    <NumberInput
                        id="playtime"
                        class="mt-1 block w-3/4"
                        required
                        placeholder=""
                        v-model="form.playtime"
                    />
                </div>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <PrimaryButton
                    v-if="!showingAddNewGameForm"
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing || !selectedGame"
                        @click="onStartClick"
                    >
                        Start
                    </PrimaryButton>
                    <PrimaryButton
                        v-if="showingAddNewGameForm"
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="onAddAndStartClick"
                    >
                        Add & Start
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
