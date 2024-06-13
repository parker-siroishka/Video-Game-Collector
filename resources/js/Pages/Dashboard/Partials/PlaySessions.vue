<script>
import axios from "axios";
import { onMounted, ref, watchEffect } from 'vue';
import PlaySessionTile from '@/Pages/Dashboard/Partials/PlaySessionTile.vue';

export default {
    components: {
        PlaySessionTile
    },
    props: {
        sessions: {
            type: Array,
            required: true
        }
    },
    setup() {
        return {
            PlaySessionTile,
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

