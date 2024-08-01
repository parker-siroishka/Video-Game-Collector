<script setup>
import Multiselect from "vue-multiselect";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";
import { watch, ref, computed, onMounted, defineProps } from "vue";

import { COVER_ART_PLACEHOLDER } from "@/Constants/urls";

import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import { isValidUrl } from "@/Utils/urlUtils";

const props = defineProps({
    onSubmit: {
        type: Function,
        required: true,
    },
    onCancel: {
        type: Function,
        required: true,
    },
    submitLabel: {
        type: String,
        required: false,
    },
});

const newGameCoverArt = ref(null);
const consoles = ref([]);
const typed = ref("");

const addNewGameForm = useForm({
    title: "",
    thumbnailUrl: "",
    playtime: 0,
    estimatedPlaytime: 0,
    console: "",
});

const thumbnailUrl = computed({
    get: () => addNewGameForm.thumbnailUrl,
    set: (value) => (addNewGameForm.thumbnailUrl = value),
});

const onSubmitClick = () => {
    props.onSubmit(addNewGameForm);
};

const onCancelClick = () => {
    props.onCancel();
};

const getUniqueConsoles = async () => {
    const { data } = await axios.get(route("games.getUniqueConsoles"));
    consoles.value = data.sort((a, b) => a.console.localeCompare(b.console));
};

const addConsole = () => {
    if (!consoles.value.includes(typed.value))
        consoles.value.push({ console: typed.value });
    addNewGameForm.console = typed.value;
};

watch(
    thumbnailUrl,
    async (newUrl, oldUrl) => {
        if (newUrl) {
            if (await isValidUrl(newUrl)) {
                newGameCoverArt.value.src = newUrl;
            } else {
                newGameCoverArt.value.src = COVER_ART_PLACEHOLDER;
            }
        }
    },
    { immediate: true }
);

onMounted(() => getUniqueConsoles());
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<template>
    <form>
        <InputLabel for="title" value="Title" />
        <TextInput
            id="title"
            class="mt-1 block w-full sm:w-3/4"
            required
            placeholder="Enter game title"
            v-model="addNewGameForm.title"
        />
        <InputLabel for="coverArt" value="Cover Art URL" class="mt-5" />
        <TextInput
            id="coverArt"
            class="mt-1 block w-full sm:w-3/4"
            required
            placeholder="Enter cover art URL"
            v-model="addNewGameForm.thumbnailUrl"
        />
        <img
            v-if="thumbnailUrl"
            ref="newGameCoverArt"
            class="shadow-md shadow-gray-400 object-center object-cover h-28 w-24 rounded-lg ml-5 mt-5 sm:h-36 sm:w-24"
        />
        <InputLabel
            for="console"
            value="Add or Select a Console"
            class="mt-5"
        />
        <div @keyup.enter="addConsole($event)" class="sm:w-3/4">
            <Multiselect
                class="border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="addNewGameForm.console"
                :options="consoles"
                placeholder="Select a console"
                label="console"
                track-by="console"
                id="console"
                @search-change="typed = $event"
            >
                <template #noResult>
                    <span
                        class="block hover:bg-gray-100 w-100"
                        onclick="addToConsoles"
                        >Press 'Enter' to add new console</span
                    >
                </template>
            </Multiselect>
        </div>
        <InputLabel for="playtime" value="Playtime (hrs)" class="mt-5" />
        <NumberInput
            id="playtime"
            class="mt-1 block w-full sm:w-2/5"
            required
            placeholder=""
            v-model="addNewGameForm.playtime"
        />

        <InputLabel
            for="estimatedPlaytime"
            value="Estimated Playtime to Complete (hrs)"
            class="mt-5"
        />
        <NumberInput
            id="estimatedPlaytime"
            class="mt-1 block w-full sm:w-2/5"
            required
            placeholder=""
            v-model="addNewGameForm.estimatedPlaytime"
        />
    </form>
    <div class="mt-6 flex justify-end">
        <SecondaryButton @click="onCancelClick"> Cancel </SecondaryButton>
        <PrimaryButton
            class="ms-3"
            :class="{ 'opacity-25': addNewGameForm.processing }"
            :disabled="addNewGameForm.processing"
            @click="onSubmitClick"
        >
            {{ submitLabel }}
        </PrimaryButton>
    </div>
</template>
