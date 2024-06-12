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
            try {
                const response = await axios.get(route('playSessions.get'));
                sessions.value = response.data; // Assign the response data to sessions
                console.log(response.data); // Now this should log the correct data
            } catch (error) {
                console.error('Error fetching play sessions:', error);
            }
        };
        onMounted(() => getPlaySessions());
        return {
            PlaySessionTile,
            sessions
        };
    }
}
</script>

<template>
    <div>
        <!-- Use v-for to iterate over each session in the sessions array -->
        <PlaySessionTile
            v-for="session in sessions"
            :key="session.id"
            :session="session"
        />
    </div>
</template>

