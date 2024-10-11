<script setup>
import moment from "moment";
import { ref, onMounted } from "vue";
import { useStopwatch } from "vue-timer-hook";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    session: {
        type: Object,
        required: true,
    },
});

const notesOpen = ref(false);
const gameTitle = ref(props.session.game.title);
const coverartUrl = ref(props.session.game.coverart);
const playtime = ref(props.session.game.playtime);
const estimatedPlaytime = ref(props.session.game.estimated_playtime);
const gameConsole = ref(props.session.game.console);
const isActive = ref(props.session.is_active);
const paused = ref(props.session.is_paused);
const notes = ref(props.session.notes);
const notesInitial = ref(props.session.notes);
const sessionEndTime = ref(props.session.end_session);
const sessionPauseTime = ref(props.session.pause_session);
const sessionPlayTime = ref(props.session.play_session);
const sessionStartTime = ref(props.session.start_session);
const durationMilliseconds = ref(props.session.duration_milliseconds);
const durationHumanized = ref(props.session.duration_humanized);

let stopwatch = useStopwatch({ autoStart: true });

const pause = () => {
    paused.value = !paused.value;
    if (paused.value) {
        stopwatch.pause();
        pauseSession();
    } else {
        stopwatch.start();
        playSession();
    }
};

const getBorderColor = () => {
    if (paused.value && isActive.value) {
        return "animate-border-color-yellow";
    } else if (isActive.value && !paused.value) {
        return "animate-border-color-red";
    } else {
        return "";
    }
};

const updateActiveSession = () => {
    // todo fix janky persistent timing
    let start = moment.utc(sessionStartTime.value, "YYYY-MM-DD HH:mm:ss");
    let pausedAt = moment.utc(sessionPauseTime.value, "YYYY-MM-DD HH:mm:ss");
    let playedAt = moment.utc(sessionPlayTime.value, "YYYY-MM-DD HH:mm:ss");
    let now = moment.utc();
    let pauseOffset;
    let offsetDuration;

    if (pausedAt) {
        pauseOffset = playedAt.diff(pausedAt);
        pauseOffset = moment.duration(pauseOffset);

        let time = moment.utc();
        time.subtract(pauseOffset);
        time = time.diff(start);

        offsetDuration = moment.duration(time);
        stopwatch.reset(offsetDuration.asSeconds());
    } else {
        durationMilliseconds.value = now.diff(start);
        let duration = moment.duration(durationMilliseconds.value);

        stopwatch.reset(duration.asSeconds());
    }
};

const updatePausedSession = () => {
    // todo fix janky persistent timing
    let pausedAt = moment.utc(sessionPauseTime.value, "YYYY-MM-DD HH:mm:ss");
    let playedAt = moment.utc(sessionPlayTime.value, "YYYY-MM-DD HH:mm:ss");
    let start = moment.utc(sessionStartTime.value, "YYYY-MM-DD HH:mm:ss");
    let now = moment.utc();

    let pauseOffset = now.diff(pausedAt);

    durationMilliseconds.value = pausedAt.diff(start);
    let duration = moment.duration(durationMilliseconds.value);

    stopwatch.reset(duration.asSeconds());
    stopwatch.pause();
};

const pauseSession = async () => {
    try {
        await axios.patch(
            route("playSessions.patch", { id: props.session.id }),
            { is_paused: true, pause_session: new Date().toISOString() }
        );
    } catch (error) {
        console.error("Error patching session:", error);
    }
};

const playSession = async () => {
    try {
        await axios.patch(
            route("playSessions.patch", { id: props.session.id }),
            { is_paused: false, play_session: new Date().toISOString() }
        );
    } catch (error) {
        console.error("Error patching session:", error);
    }
};

const stopSession = async () => {
    const payload = {
        is_active: false,
        is_paused: false,
        start_session: sessionStartTime.value,
        end_session: sessionEndTime.value,
        duration_milliseconds: durationMilliseconds.value,
        duration_humanized: durationHumanized.value,
    };
    try {
        await axios.patch(
            route("playSessions.patch", { id: props.session.id }),
            payload
        );
    } catch (error) {
        console.error("Error patching session:", error);
    }
};

const stop = () => {
    const h = stopwatch.hours.value;
    const m = stopwatch.minutes.value;
    const s = stopwatch.seconds.value;
    stopwatch.reset();
    isActive.value = false;
    let startDateTime = moment(sessionStartTime.value, "YYYY-MM-DD HH:mm:ss");

    let endDateTime = startDateTime
        .clone()
        .add(h, "hours")
        .add(m, "minutes")
        .add(s, "seconds");

    sessionEndTime.value = endDateTime.format("YYYY-MM-DD HH:mm:ss");

    durationMilliseconds.value = endDateTime.diff(startDateTime);
    let duration = moment.duration(durationMilliseconds.value);
    let hours = duration.hours();
    let minutes = duration.minutes();
    let seconds = duration.seconds();
    let timeString = `${seconds}s`; // Seconds are always included

    if (hours > 0) {
        timeString = `${hours}h ${minutes}m` + timeString;
    } else if (minutes > 0) {
        timeString = `${minutes}m ` + timeString;
    }
    durationHumanized.value = timeString;

    stopSession();
};

const onClickNotes = () => {
    notesOpen.value = true;
};

const onClickNotesClose = () => {
    notesOpen.value = false;
    notes.value = notesInitial.value;
};

const onClickNotesSubmit = async () => {
    console.log(notes.value);
    const payload = {
        notes: notes.value,
    };
    try {
        await axios.patch(
            route("playSessions.patch", { id: props.session.id }),
            payload
        );
        notesOpen.value = false;
        notesInitial.value = notes.value;
    } catch (error) {
        console.error("Error patching session notes:", error);
    }
};

onMounted(() => {
    if (paused.value) updatePausedSession();
    else if (isActive.value && !paused.value) updateActiveSession();
    else if (!paused.value) stopwatch = useStopwatch(0, true);
});
</script>

<template>
    <div
        :class="getBorderColor()"
        class="block border-2 border-gray-700 mb-5 rounded-md shadow bg-gradient-to-t from-transparent to-gray-800 hover:from-transparent hover:to-gray-700"
    >
        <div
            class="relative flex justify-center sm:hidden block w-full max-h-54 overflow-hidden"
        >
            <img
                class="h-[176px] w-auto object-contain z-10 shadow-lg shadow-gray-700"
                :src="coverartUrl"
            />
            <img
                class="w-full absolute max-h-44 object-cover blur-md opacity-90"
                :src="coverartUrl"
            />
        </div>
        <div
            class="pt-3 pb-4 px-4 rounded-lg bg-gradient-to-t from-transparent to-gray-800"
        >
            <div class="flex justify-between">
                <h5
                    class="mb-2 text-4xl font-extrabold drop-shadow-sm tracking-tight text-white"
                >
                    {{ gameTitle }}
                </h5>
                <span v-if="isActive" class="flex align-middle">
                    <img
                        v-if="!paused && isActive"
                        class="h-5 w-5 mr-2"
                        src="../../../../assets/images/live.png"
                    />
                    <strong
                        :class="paused ? 'text-yellow-500' : 'text-red-500'"
                        class="hidden sm:block"
                    >
                        <span v-if="paused">Paused</span
                        ><span v-if="!paused">Active</span> Session</strong
                    ></span
                >
            </div>
            <div class="flex justify-between w-100%">
                <div class="w-full sm:w-3/4">
                    <div class="flex flex-col justify-between sm:w-auto">
                        <div class="w-100">
                            <p
                                v-if="!sessionEndTime"
                                class="text-s sm:text-lg font-normal text-gray-200 mb-4"
                            >
                                Current session <br class="sm:hidden" />
                                <strong>
                                    <span v-if="stopwatch.hours !== '0'">
                                        {{ stopwatch.hours }}h
                                    </span>
                                    <span v-if="stopwatch.minutes !== '0'">
                                        {{ stopwatch.minutes }}m
                                    </span>
                                    <span> {{ stopwatch.seconds }}s </span>
                                </strong>
                            </p>
                            <p
                                v-if="sessionEndTime"
                                class="text-xs sm:text-lg font-bold text-gray-200 mb-3"
                            >
                                Duration
                                <strong>{{ durationHumanized }}</strong>
                            </p>
                            <div
                                class="flex justify-normal sm:block items-baseline"
                            >
                                <p
                                    class="text-xs sm:p-0 p-1 sm:text-lg font-normal text-gray-200 sm:bg-transparent bg-gray-600 rounded-md mr-2"
                                >
                                    Playtime
                                    <strong class="text-orange-300"
                                        >{{ playtime }}h</strong
                                    >
                                </p>
                                <p
                                    v-if="estimatedPlaytime"
                                    class="text-xs sm:p-0 p-1 sm:text-lg font-normal text-gray-200 sm:bg-transparent bg-gray-600 rounded-md mr-2"
                                >
                                    Projected time
                                    <strong class="text-orange-300"
                                        >{{
                                            Math.round(estimatedPlaytime)
                                        }}h</strong
                                    >
                                </p>
                                <p
                                    v-if="gameConsole"
                                    class="text-xs sm:p-0 p-1 sm:text-lg font-normal text-gray-200 sm:bg-transparent bg-gray-600 rounded-md"
                                >
                                    Playing on
                                    <strong class="text-orange-300">{{
                                        gameConsole
                                    }}</strong>
                                </p>
                            </div>
                            <div class="hidden sm:block">
                                <label
                                    v-if="notes == null"
                                    @click="onClickNotes"
                                    for="message"
                                    class="block mb-2 mt-3 text-sm font-medium text-gray-400"
                                    >Notes</label
                                >
                                <div>
                                    <div v-if="notesOpen">
                                        <textarea
                                            v-model="notes"
                                            id="message"
                                            rows="4"
                                            class="block p-2 w-full text-sm rounded-lg border focus:ring-blue-500 focus:border-blue-500 bg-gray-700 border-gray-600 placeholder-gray-400 text-white"
                                            placeholder="Write your thoughts here..."
                                        >
                                            {{ notes }}
                                        </textarea>
                                        <div class="flex items-center mt-3">
                                            <button
                                                class="w-5 h-5 mr-5"
                                                @click="onClickNotesSubmit"
                                            >
                                                <img
                                                    src="../../../../assets//images/check.png"
                                                />
                                            </button>
                                            <button
                                                class="w-3.5 h-3.5"
                                                @click="onClickNotesClose"
                                            >
                                                <img
                                                    src="../../../../assets//images/close.png"
                                                />
                                            </button>
                                        </div>
                                    </div>
                                    <p
                                        v-if="!notesOpen"
                                        class="whitespace-pre-line text-sm font-medium text-white line-clamp-3 mt-6 mb-4"
                                        @click="onClickNotes"
                                    >
                                        {{ notes }}
                                    </p>
                                    <div
                                        v-if="!sessionEndTime"
                                        class="inline-block mt-5"
                                    >
                                        <DangerButton @click="stop" class="mr-4"
                                            ><img
                                                src="../../../../assets/images/stop-button.png"
                                                class="w-4 h-4"
                                        /></DangerButton>
                                        <PrimaryButton @click="pause">
                                            <img
                                                src="../../../../assets/images/pause.png"
                                                v-if="!paused"
                                                class="w-4 h-4"
                                            />
                                            <img
                                                src="../../../../assets/images/play.png"
                                                v-if="paused"
                                                class="w-4 h-4"
                                            />
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex">
                    <div class="hidden sm:block sm:w-auto">
                        <img
                            :src="coverartUrl"
                            class="class=shadow-lg shadow-gray-400 object-center object-cover h-28 w-auto rounded-sm sm:h-36 sm:w-24"
                        />
                    </div>
                </div>
            </div>
            <div class="sm:hidden">
                <label
                    v-if="notes == null"
                    @click="onClickNotes"
                    for="message"
                    class="opacity-50 block mb-2 mt-3 text-sm font-medium text-white"
                    >Notes</label
                >
                <div>
                    <div v-if="notesOpen">
                        <textarea
                            v-model="notes"
                            id="message"
                            rows="4"
                            class="block p-2.5 w-full mt-3 text-sm rounded-lg border focus:ring-blue-500 focus:border-blue-500 bg-gray-700 border-gray-600 placeholder-gray-400 text-white"
                            placeholder="Write your thoughts here..."
                            >{{ notes }}</textarea
                        >
                        <div class="flex items-center pt-3">
                            <button
                                class="w-5 h-5 mr-5"
                                @click="onClickNotesSubmit"
                            >
                                <img
                                    src="../../../../assets//images/check.png"
                                />
                            </button>
                            <button
                                class="w-3.5 h-3.5"
                                @click="onClickNotesClose"
                            >
                                <img
                                    src="../../../../assets//images/close.png"
                                />
                            </button>
                        </div>
                    </div>
                    <p
                        v-if="!notesOpen"
                        class="mt-4 whitespace-pre-line text-sm font-medium text-white line-clamp-3"
                        @click="onClickNotes"
                    >
                        {{ notes }}
                    </p>
                    <div v-if="!sessionEndTime" class="inline-block mt-5">
                        <DangerButton @click="stop" class="mr-4"
                            ><img
                                src="../../../../assets/images/stop-button.png"
                                class="w-4 h-4"
                        /></DangerButton>
                        <PrimaryButton @click="pause">
                            <img
                                src="../../../../assets/images/pause.png"
                                v-if="!paused"
                                class="w-4 h-4"
                            />
                            <img
                                src="../../../../assets/images/play.png"
                                v-if="paused"
                                class="w-4 h-4"
                            />
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
