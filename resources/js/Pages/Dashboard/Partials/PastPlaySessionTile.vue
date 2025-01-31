<script setup>
import { ref } from "vue";

const props = defineProps({
    session: {
        type: Object,
        required: true,
    },
});

const gameTitle = ref(props.session.game.title);
const notes = ref(props.session.notes);
const durationHumanized = ref(props.session.duration_humanized);
</script>

<template>
    <div
        class="block border-2 border-gray-700 mb-5 rounded-md shadow bg-gradient-to-t from-transparent to-gray-800 hover:from-transparent hover:to-gray-700"
    >
        <div
            class="relative flex justify-center sm:hidden block w-full max-h-54 overflow-hidden"
        ></div>
        <div
            class="pt-3 pb-4 px-4 rounded-lg bg-gradient-to-t from-transparent to-gray-800"
        >
            <div class="flex justify-between">
                <h5
                    class="mb-2 text-4xl font-extrabold drop-shadow-sm tracking-tight text-white"
                >
                    {{ gameTitle }}
                </h5>
            </div>
            <div class="flex justify-between w-100%">
                <div class="w-full sm:w-3/4">
                    <div class="flex flex-col justify-between sm:w-auto">
                        <div class="w-100">
                            <p
                                class="text-sm sm:text-lg font-bold text-gray-200 mb-3"
                            >
                                Duration
                                <strong>{{ durationHumanized }}</strong>
                            </p>
                            <div class="hidden sm:block">
                                <div>
                                    <p
                                        v-if="notes"
                                        class="whitespace-pre-line text-sm font-medium text-white mt-6 mb-4"
                                    >
                                        {{ notes }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="hidden sm:block sm:w-auto mr-5"></div>
                </div>
            </div>
            <div class="sm:hidden">
                <div>
                    <p
                        class="mt-4 whitespace-pre-line text-sm font-medium text-white line-clamp-3"
                    >
                        {{ notes }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
