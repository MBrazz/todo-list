<script setup>
import ButtonRemove from "./ButtonRemove.vue"
import Comment from "./Comment.vue"
import api from "../services/api";
import { ref, onMounted } from "vue"

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
    list: {
        type: Object,
        required: true,
    },
    removeTask: {
        type: Function,
        required: true,
    },

    getList: {
        type: Function,
        required: true,
    },

    notify: {
        type: Function,
        required: true,
    },
})

onMounted(() => {
    props.getList();
})


const user = localStorage.getItem('user');
const userJson = JSON.parse(user)
const access_token = userJson.access_token

const bubble = () => {
    api({
        method: 'PUT',
        url: `/task/${props.task.id}`,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${access_token}`
        },
        data: {
            content: props.task.content,
            is_done: props.task.is_done ? false : true
        },
    }).then(function (response) {
        console.log(response.data);
    })
        .catch(function (response) {
            console.log(response.data);
        });
}
</script>

<template>
    <section class="task">
        <div class="row">
            <label @click="bubble()">
                <input type="checkbox" :checked="task.is_done == 1" />
                <span :class="`bubble`"></span>
            </label>

            <div class="task-content">
                <input type="text" v-model="task.content" />
            </div>

            <div class="actions">
                <ButtonRemove :task="task" :removeTask="removeTask" :notify="notify" />
            </div>
        </div>
        <Comment :list="list" :task="task" :notify="notify" :getList="getList" />
    </section>
</template>


<style scoped>
.row {
    display: flex;
    flex-direction: row;
    align-items: center;
}

.task {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin-bottom: 1.8rem;
    padding: 2rem;
    border-radius: 3px;
    background-color: #f5f5f5;
}

</style>
