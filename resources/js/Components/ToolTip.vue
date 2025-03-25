<script setup>
defineProps({
    tooltipContent: {
        type: String,
    },
});
import { ref } from "vue";

const isTooltipVisible = ref(false);

function toggleTooltip() {
    isTooltipVisible.value = !isTooltipVisible.value;
}
</script>

<template>
    <div class="relative inline-block">
        <img
            src="../../assets/images/tooltip.png"
            class="ml-2 w-5 h-5 cursor-pointer"
            @click="toggleTooltip"
        />
        <div
            :class="{
                'opacity-100': isTooltipVisible,
                'opacity-0': !isTooltipVisible,
            }"
            class="w-64 max-h-40 bg-gray-700 dark:bg-dark-2 absolute bottom-full left-1/2 z-20 mb-3 -translate-x-1/2 whitespace-normal rounded-[5px] py-1.5 px-3.5 text-sm text-white transition-opacity duration-300"
        >
            <span
                class="bg-dark dark:bg-dark-2 absolute bottom-[-3px] left-1/2 -z-10 h-2 w-2 -translate-x-1/2 rotate-45"
            ></span>
            {{ tooltipContent }}
        </div>
    </div>
</template>
