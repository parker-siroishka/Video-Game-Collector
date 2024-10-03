<script setup>
import { ref, watch } from "vue";

import AddNewGameForm from "@/Pages/Dashboard/Partials/AddNewGameForm.vue";
import Modal from "@/Components/Modal.vue";

import postGames from "@/services/postGames";

const emit = defineEmits(["update:showAddNewGameModal"]);

const props = defineProps({
    showAddNewGameModal: {
        type: Boolean,
    },
});

const show = ref(false);

const onCancel = () => {
    show.value = false;
    emit("update:showAddNewGameModal", false);
};

const onSubmit = async (form) => {
    try {
        postGames(form);
        show.value = false;
        emit("update:showAddNewGameModal", false);
    } catch (error) {
        console.error("Error submitting game:", error);
    }
};

watch(
    () => props.showAddNewGameModal,
    (newVal) => {
        show.value = newVal;
    }
);
</script>

<template>
    <div>
        <Modal :show="show" @close="onCancel">
            <div class="p-6">
                <h2 class="text-lg font-extrabold text-white pb-3">
                    Add a new game to your collection
                </h2>
                <AddNewGameForm
                    :onSubmit="onSubmit"
                    :onCancel="onCancel"
                    submitLabel="Add Game"
                />
            </div>
        </Modal>
    </div>
</template>
