<script setup>
import moment from "moment";
import PlaySessionTile from "@/Pages/Dashboard/Partials/PlaySessionTile.vue";

const props = defineProps({
    sessions: {
        type: Object,
        required: true,
    },
});

const formatDate = (date) => {
    return moment(date).format("MMMM Do, YYYY");
};
</script>

<template>
    <div class="pr-3 pl-3">
        <div>
            <div v-for="(daySessions, date) in sessions" :key="date">
                <div
                    class="inline-flex items-center justify-center w-full mb-5"
                >
                    <hr class="w-1/2 h-px my-8 border-0 bg-gray-700" />
                    <span
                        class="absolute px-3 font-medium -translate-x-1/2 left-1/2 text-white bg-gray-900"
                        >{{ formatDate(date) }}</span
                    >
                </div>
                <PlaySessionTile
                    v-for="session in daySessions"
                    :key="session.id"
                    :session="session"
                />
            </div>
        </div>
    </div>
</template>
