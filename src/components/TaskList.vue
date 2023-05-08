<script setup>
import { toast } from "vue3-toastify"
import "vue3-toastify/dist/index.css"
import { ref, onMounted, computed, watch } from "vue"

import FormAddTask from "./FormAddTask.vue"
import Task from "./Task.vue"

const list = ref({})

const addTask = (input_content) => {
  if (input_content === '') {
    return notify('Opss, o campo está vazio', 'error')
  }
  else {
    for (let index = 0; index < list.value.length; index++) {
      if (input_content === list.value[index].content) {
        return notify('Opss, tarefa ja adicionada', 'error')
      }
    }
    notify('Tarefa adicionada com sucesso!!!', 'success')
  }

  // console.log(list.value[0].content)

  list.value.push({
    content: input_content,
    done: false,
    id: Math.random(),
    createdAt: new Date().getTime(),
  })
}

// const list_asc = computed(() =>
//   list.sort((a, b) => {
//     return a.createdAt - b.createdAt
//   })
// )

const notify = (message, type) => {

  if (type === 'success') {
    toast.success(message, {
      autoClose: 1600,
    }) // ToastOptions
  }
  else if (type === 'error') {
    toast.error(message, {
      autoClose: 1600,
    })
  }
  return { notify }
}

watch(
  list,
  (newVal) => {
    localStorage.setItem("list", JSON.stringify(newVal))
  },
  {
    deep: true,
  }
)

const removeTask = (task) => {
  list.value = list.value.filter((t) => t !== task)
}

onMounted(() => {
  list.value = JSON.parse(localStorage.getItem("list")) || []
})
</script>

<template>
  <header id="app-header">
    <section class="create-task container">
      <h1>Tasks List</h1>

      <FormAddTask @btn-click="addTask" :notify="notify" />
    </section>
  </header>

  <div class="container">
    <section class="task-list">
      <h2>Lista de tarefas</h2>
      <!-- <div>
        <h2>{list.length}</h2>
      </div> -->
      <hr />
      <div class="list" id="task-list">
        <div v-for="task in list" :key="task.id" :class="`task-item ${task.done && 'done'}`">
          <Task :task="task" :notify="notify" :removeTask="removeTask" />
        </div>
      </div>
    </section>
  </div>
</template>
