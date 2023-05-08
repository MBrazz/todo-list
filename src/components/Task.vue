<script setup>
import ButtonRemove from "./ButtonRemove.vue"

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    removeTask: {
        type: Function,
        required: true,
    },

    notify: {
        type: Function,
        required: true,
    },
})
</script>

<template>
    <label>
        <input type="checkbox" v-model="task.done" />
        <span :class="`bubble`"></span>
    </label>

    <div class="task-content">
        <input type="text" v-model="task.content" />
    </div>
    <div class="actions">
        <ButtonRemove :task="task" :removeTask="removeTask" :notify="notify" />
    </div>
</template>
