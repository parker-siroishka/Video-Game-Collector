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
                    <hr class="w-2/5 h-px my-8 border-1 border-orange-300" />
                    <span
                        class="absolute px-3 text-xl font-medium -translate-x-1/2 left-1/2 text-white bg-gray-900"
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
