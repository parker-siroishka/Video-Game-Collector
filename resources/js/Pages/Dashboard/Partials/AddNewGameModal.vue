<script setup>
import axios from "axios";
import { ref, watch, defineProps } from 'vue';

import AddNewGameForm from '@/Pages/Dashboard/Partials/AddNewGameForm.vue';
import Modal from '@/Components/Modal.vue';

const emit = defineEmits(['update:showAddNewGameModal']);

const props = defineProps({
    showAddNewGameModal: {
        type: Boolean
    }
});

const show = ref(false);

const onCancel = () => {
    show.value = false;
    emit('update:showAddNewGameModal', false);
};

const onSubmit = async (form) => {
    try {
        const response = await axios.post(route('games.post'), {
            title: form.title,
            coverart: form.thumbnailUrl,
            playtime: form.playtime,
            estimatedPlaytime: form.estimatedPlaytime,
            console: form.console.console
        });
        // Close modal on success
        show.value = false;
        emit('update:showAddNewGameModal', false);
        
    } catch (error) {
        // Handle error
        console.error('Error submitting game:', error);
    }
};

watch(() => props.showAddNewGameModal, newVal => {
    show.value = newVal;
});
</script>

<template>
    <div>
        <Modal :show="show" @close="onCancel">
            <div class="p-6">
                <h2 class="text-lg font-extrabold text-gray-900 pb-3">
                    Add a new game to your collection
                </h2>
                <AddNewGameForm 
                :onSubmit="onSubmit"
                :onCancel="onCancel"
                submitLabel="Add Game"/>
            </div>
        </Modal>
    </div>
</template>
