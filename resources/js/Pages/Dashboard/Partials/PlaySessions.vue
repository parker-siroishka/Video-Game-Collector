<script>
import axios from "axios";
import { onMounted, ref } from 'vue';
import PlaySessionTile from '@/Pages/Dashboard/Partials/PlaySessionTile.vue';

export default {
    components: {
        PlaySessionTile
    },
    setup() {
        const sessions = ref([]);

        const getPlaySessions = async () => {
            const { sessionData } = await axios.get(route('playSessions.get'));
            sessions.value = sessionData;
        };
        onMounted(() => getPlaySessions());
        return {};
    }
}
</script>

<template>
    <PlaySessionTile />
</template>
