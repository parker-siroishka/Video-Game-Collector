<script setup>
import "vue3-carousel/carousel.css";
import { ref, onMounted } from "vue";
import { Carousel, Slide, Navigation } from "vue3-carousel";

import getRecentPlaySessions from "@/services/getRecentPlaySessions";
import RecentlyPlayedTile from "./RecentlyPlayedTile.vue";

const hasSessions = ref(false);
const sessions = ref([]);

const fetchSessions = async () => {
    sessions.value = await getRecentPlaySessions();
    hasSessions.value = sessions.value.length > 0;
};

// Carousel configuration
const config = {
    height: 200,
    itemsToShow: "auto",
    gap: 10,
    breakpointMode: "carousel",
    breakpoints: {
        300: {
            itemsToShow: 2.5,
            snapAlign: "center",
        },
        400: {
            itemsToShow: 3,
            snapAlign: "start",
        },
        550: {
            itemsToShow: 4,
            snapAlign: "start",
        },
        700: {
            itemsToShow: 6,
            snapAlign: "start",
        },
    },
};

onMounted(() => {
    fetchSessions();
});
</script>

<template>
    <h1 class="text-2xl font-extrabold text-white mb-5 ml-2">
        Recently Played
    </h1>
    <div class="carousel__wrapper">
        <Carousel v-bind="config">
            <Slide v-for="session in sessions" :key="session.id">
                <RecentlyPlayedTile :session="session" />
            </Slide>

            <template #addons>
                <Navigation />
            </template>
        </Carousel>
    </div>
</template>

<style>
:root {
    background-color: #242424;
}

.carousel {
    --vc-nav-background: rgba(255, 255, 255, 0.7);
    --vc-nav-border-radius: 100%;
}

img {
    width: 137px;
    height: 100%;
    object-fit: cover;
}

.carousel__wrapper {
    overflow: auto;
}

.carousel__next,
.carousel__prev {
    background: #374151;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    color: white;
}
.carousel__next:hover,
.carousel__prev:hover {
    color: #c2410c;
}
</style>
