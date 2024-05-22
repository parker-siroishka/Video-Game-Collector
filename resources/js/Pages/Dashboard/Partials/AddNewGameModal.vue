<script>
import { ref, watch } from 'vue';

import AddNewGameForm from '@/Pages/Dashboard/Partials/AddNewGameForm.vue';
import Modal from '@/Components/Modal.vue';

export default {
    emits: ['update:showAddNewGameModal'],
    components: {
        AddNewGameForm,
        Modal
    },
    props: {
        showAddNewGameModal: {
            type: Boolean
        }
    },
    setup(props, { emit }) {

        const show = ref(false);

        const onCancel = () => {
            show.value = false;
            emit('update:showAddNewGameModal', false);
        };

        const onSubmit = async () => {
            console.log('submitted');
        };

        watch(() => props.showAddNewGameModal, newVal => {
        show.value = newVal;
        });

        return {
            onCancel,
            onSubmit,
            show
        }
    }
}
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
                :onCancel="onCancel"/>
            </div>
        </Modal>
    </div>
  </template>
