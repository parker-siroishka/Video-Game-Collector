<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateSession from './Partials/CreateSession.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <CreateSession />
            </div>
        </div>
        <div class="fixed bottom-4 right-4">
            <div class="flex items-center">
                <PrimaryButton>
                    <img src="../../../../resources/assets/images/plus-icon.png" 
                         class="block h-5 w-auto fill-current text-gray-800"
                    />
                    <h3 class="pl-3">Add New Game</h3>
                </PrimaryButton>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
