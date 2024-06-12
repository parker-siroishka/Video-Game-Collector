<script>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateSession from './CreateSession.vue'
import AddNewGameModal from './Partials/AddNewGameModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout,
        CreateSession,
        PrimaryButton,
        AddNewGameModal,
        Head
    },
    setup() {
        const showAddNewGameModal = ref(false);

        const addNewGameClicked = () => {
            showAddNewGameModal.value = true;
        };

        return {
            addNewGameClicked,
            showAddNewGameModal
        }
    }
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <CreateSession />
            </div>
        </div>
        <div class="fixed bottom-8 right-8">
            <div class="flex items-center">
                <PrimaryButton @click="addNewGameClicked">
                    <img src="../../../../resources/assets/images/plus-icon.png" 
                         class="block h-5 w-auto fill-current text-gray-800"
                    />
                    <h3 class="pl-3">Add New Game</h3>
                </PrimaryButton>
                <AddNewGameModal 
                    :showAddNewGameModal="showAddNewGameModal"
                    @update:showAddNewGameModal="showAddNewGameModal = $event"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
