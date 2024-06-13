<script>
import { ref } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useStopwatch } from 'vue-timer-hook';
import moment from 'moment';

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
        const autoStart = true;
        const stopwatch = useStopwatch(autoStart);

        const gameTitle = ref(props.session.game.title);
        const coverartUrl = ref(props.session.game.coverart);
        const playtime = ref(props.session.game.playtime);
        const estimatedPlaytime = ref(props.session.game.estimated_playtime);
        const gameConsole = ref(props.session.game.console);
        const isActive = ref(props.session.is_active);
        const paused = ref(props.session.is_paused);
        const sessionEndTime = ref(props.session.end_session);
        const sessionStartTime = ref(props.session.start_session);
        const durationMilliseconds = ref(props.session.duration_milliseconds);
        const durationHumanized = ref(props.session.duration_humanized);

        const pause = () => {
            paused.value = !paused.value;
            if (paused.value){
                stopwatch.pause();
                pauseSession();
            } else { 
                stopwatch.start();
            };
        };

        const getBorderColor = () => {
            if (paused.value){
                return 'animate-border-color-yellow';
            } else if (isActive.value && !paused.value) {
                return 'animate-border-color-red';
            } else {
                return '';
            }
        };

        const pauseSession = async () => {
            try {
                const response = await axios.patch(route('playSessions.patch', { id: props.session.id }), { is_paused: true });
            } catch (error) {
                // Handle error
                console.error('Error patching session:', error);
            }
        };

        const playSession = async () => {
            try {
                const response = await axios.patch(route('playSessions.patch', { id: props.session.id }), { is_paused: false });
            } catch (error) {
                // Handle error
                console.error('Error patching session:', error);
            }
        };

        const stopSession = async () => {
            const payload = {
                is_active: false,
                is_paused: false,
                start_session: sessionStartTime.value,
                end_session: sessionEndTime.value,
                duration_milliseconds: durationMilliseconds.value,
                duration_humanized: durationHumanized.value
            };
            try {
                const response = await axios.patch(route('playSessions.patch', { id: props.session.id }), payload);
            } catch (error) {
                // Handle error
                console.error('Error patching session:', error);
            }
        };

        const stop = () => {
            const h = stopwatch.hours.value;
            const m = stopwatch.minutes.value;
            const s = stopwatch.seconds.value;
            stopwatch.reset()
            isActive.value = false;
            // Parse the start date using Moment.js
            let startDateTime = moment(sessionStartTime.value, 'YYYY-MM-DD HH:mm:ss');
            
            // Add hours, minutes, and seconds to the start date
            let endDateTime = startDateTime.clone().add(h, 'hours')
                                .add(m, 'minutes')
                                .add(s, 'seconds');
            
            // Format the end date to match the desired format
            sessionEndTime.value = endDateTime.format('YYYY-MM-DD HH:mm:ss');

            durationMilliseconds.value = endDateTime.diff(startDateTime);
            durationHumanized.value = moment.duration(durationMilliseconds.value).humanize();

            stopSession();
        };

        return {
            gameTitle,
            coverartUrl,
            playtime,
            estimatedPlaytime,
            gameConsole,
            isActive,
            paused,
            sessionEndTime,
            stopwatch,
            durationHumanized,
            pause,
            stop,
            getBorderColor
        };
    }
}
</script>

<template>
    <div :class="getBorderColor()" class="block p-3 sm:p-6 mb-5 bg-white border-2 sm:rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <div class="flex justify-between">
            <h5 class="mb-2 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ gameTitle }}</h5>
            <span v-if="isActive" class="flex align-middle">
                <img v-if="!paused && isActive" class="h-5 w-5 mr-2" src="../../../../assets/images/live.png"/>
                <strong :class="paused ? 'text-yellow-500' : 'text-red-500'" class="hidden sm:block">
                    <span v-if="paused">Paused</span><span v-if="!paused">Active</span> Session</strong></span>
        </div>
        <div class="flex justify-between">
            <div class="flex flex-col justify-between w-[177px] sm:w-auto">
                <div>
                    <p v-if="!sessionEndTime" class="text-xs sm:text-lg font-normal text-gray-700 dark:text-gray-400 mb-4">Current session <br class="sm:hidden"/>
                        <strong>
                            <span >
                                {{stopwatch.hours}}h
                            </span>
                            <span >
                                {{stopwatch.minutes}}m
                            </span>
                            <span>
                                {{stopwatch.seconds}}s
                            </span>
                        </strong>
                    </p>
                    <p v-if="sessionEndTime" class="text-xs sm:text-lg font-normal text-gray-700 dark:text-gray-400 mb-4">Duration <strong>{{ durationHumanized }}</strong></p>
                    <p class="opacity-50">Notes...</p>
                </div>
                <div v-if="!sessionEndTime" class="inline-block">
                    <DangerButton @click="stop" class="mr-4"><img src="../../../../assets/images/stop-button.png" class="w-4 h-4"/></DangerButton>
                    <PrimaryButton @click="pause">
                        <img src="../../../../assets/images/pause.png" v-if="!paused" class="w-4 h-4"/>
                        <img src="../../../../assets/images/play.png" v-if="paused" class="w-4 h-4"/>
                    </PrimaryButton>
                </div>
            </div>
            <div class="flex">
                <div class="text-right pr-3 sm:pr-10">
                    <p class="text-xs sm:text-lg font-normal text-gray-700 dark:text-gray-400">Playtime <strong>{{ playtime }}h</strong></p>
                    <p v-if="estimatedPlaytime" class="text-xs sm:text-lg font-normal text-gray-700 dark:text-gray-400">Projected time <strong>105h</strong></p>
                    <p v-if="gameConsole" class="text-xs sm:text-lg font-normal text-gray-700 dark:text-gray-400">Playing on <strong>{{ gameConsole }}</strong></p>
                </div>
                <div class="w-1/2 sm:w-auto">
                    <img :src="coverartUrl" class="class=shadow-lg shadow-gray-400 object-center object-cover h-28 w-auto rounded-sm sm:h-36 sm:w-24"/>
                </div>
            </div>
        </div>
    </div>
</template>