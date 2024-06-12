<script>

export default {
    components: {},
    props: {
        session: Object,
        required: true
    },
    setup() {
        return {};
    }
}
</script>

<template>
    <div class="block p-6 bg-white border-4 animate-border-color rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
        <div class="flex justify-between">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tetris</h5>
            <span class="flex align-middle"><img class="h-5 w-5 mr-2" src="../../../../assets/images/live.png"/><strong class="text-red-500">Active Session</strong></span>
        </div>
        <div class="flex justify-between">
            <div>
                <p class="font-normal text-gray-700 dark:text-gray-400">Current session <strong>1hr 47m 18s</strong></p>
                <br/>
                <p>Notes...</p>
            </div>
            <div class="flex">
                <div class="text-right">
                    <p class="font-normal text-gray-700 dark:text-gray-400">Playtime <strong>12h</strong></p>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Estimated completion time <strong>105h</strong></p>
                    <p class="font-normal text-gray-700 dark:text-gray-400">Playing on <strong>NES</strong></p>
                </div>
                <div>
                    <img src="https://i.pinimg.com/736x/a0/37/00/a037000daeae56905fd5c3685687b361.jpg" class="class=shadow-md shadow-gray-400 object-center object-cover h-28 w-24 rounded-lg ml-5 sm:h-36 sm:w-24"/>
                </div>
            </div>
        </div>
    </div>
</template>