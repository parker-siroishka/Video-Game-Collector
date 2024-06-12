<script>
import { onMounted, ref } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

export default {
    components: {
        DangerButton,
        PrimaryButton
    },
    props: {
        session: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const gameTitle = ref(props.session.game.title);
        const coverartUrl = ref(props.session.game.coverart);
        const playtime = ref(props.session.game.playtime);
        const estimatedPlaytime = ref(props.session.game.estimated_playtime);
        const console = ref(props.session.game.console);
        return {
            gameTitle,
            coverartUrl,
            playtime,
            estimatedPlaytime,
            console
        };
    }
}
</script>

<template>
    <div class="block p-6 mb-5 bg-white border animate-border-color sm:rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <div class="flex justify-between">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ gameTitle }}</h5>
            <span class="flex align-middle"><img class="h-5 w-5 mr-2" src="../../../../assets/images/live.png"/><strong class="text-red-500 hidden sm:block">Active Session</strong></span>
        </div>
        <div class="flex justify-between">
            <div class="flex flex-col justify-between">
                <div>
                    <p class="text-xs sm:text-base font-normal text-gray-700 dark:text-gray-400 mb-4">Current session <br class="sm:hidden"/><strong>1hr 47m 18s</strong></p>
                    <p class="opacity-50">Notes...</p>
                </div>
                <div class="inline-block">
                    <DangerButton class="mr-4"><img src="../../../../assets/images/stop-button.png" class="w-4 h-4"/></DangerButton>
                    <PrimaryButton onclick=""><img src="../../../../assets/images/pause.png" class="w-4 h-4"/></PrimaryButton>
                </div>
            </div>
            <div class="flex">
                <div class="text-right">
                    <p class="text-xs sm:text-base font-normal text-gray-700 dark:text-gray-400">Playtime <strong>{{ playtime }}h</strong></p>
                    <p v-if="estimatedPlaytime" class="text-xs sm:text-base font-normal text-gray-700 dark:text-gray-400">Projected time <strong>105h</strong></p>
                    <p v-if="console" class="text-xs sm:text-base font-normal text-gray-700 dark:text-gray-400">Playing on <strong>{{ console }}</strong></p>
                </div>
                <div class="w-50">
                    <img :src="coverartUrl" class="class=shadow-md shadow-gray-400 object-center object-cover h-28 w-auto rounded-lg ml-5 sm:h-36 sm:w-24"/>
                </div>
            </div>
        </div>
    </div>
</template>