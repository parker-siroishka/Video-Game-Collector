<script setup>
const back = () => {
    window.history.back();
};

const forward = () => {
    window.history.forward();
};
</script>

<template>
    <div class="mt-5 -mb-5">
        <button
            class="pl-4 inline-flex items-center text-blue-600 dark:text-orange-600 hover:underline"
            @click="back"
        >
            <svg
                class="w-4 h-4 ms-2 rotate-180 mr-1"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 14 10"
            >
                <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9"
                />
            </svg>
        </button>
        <button
            class="pl-4 inline-flex items-center text-blue-600 dark:text-orange-600 hover:underline"
            @click="forward"
        >
            <svg
                class="w-4 h-4 ms-2 rtl:rotate-180"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 14 10"
            >
                <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M1 5h12m0 0L9 1m4 4L9 9"
                />
            </svg>
        </button>
    </div>
</template>
