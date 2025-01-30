<script setup>
import moment from "moment";
import { ref, onMounted } from "vue";
import { useStopwatch } from "vue-timer-hook";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import GameTile from "@/Components/GameTile.vue";

const props = defineProps({
    session: {
        type: Object,
        required: true,
    },
});
//TODO clean up unused code

const gameTitle = ref(props.session.game.title);
const gameId = ref(props.session.game.id);
const notes = ref(props.session.notes);
const sessionEndTime = ref(props.session.end_session);
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
                            <div class="hidden sm:block">
                                <div>
                                    <p
                                        v-if="notes"
                                        class="whitespace-pre-line text-sm font-medium text-white mt-6 mb-4"
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
                    <div class="hidden sm:block sm:w-auto mr-5"></div>
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
                </div>
            </div>
        </div>
    </div>
</template>
