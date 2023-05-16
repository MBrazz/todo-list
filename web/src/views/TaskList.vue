<script setup>
import { toast } from "vue3-toastify"
import "vue3-toastify/dist/index.css"
import { ref, onMounted } from "vue"

import FormAddTask from "../components/FormAddTask.vue"
import Task from "../components/Task.vue"

import api from "../services/api";

const list = ref({})

const user = localStorage.getItem('user');

if (!user) {
  window.location.href = '/'
}
const userJson = JSON.parse(user)
const access_token = userJson.access_token

const notify = (message, type) => {

  if (type === 'success') {
    toast.success(message, {
      autoClose: 1600,
    })
  }
  else if (type === 'error') {
    toast.error(message, {
      autoClose: 1600,
    })
  }
  return { notify }
}

const getList = () => {
  api.get("/task/my-tasks", {
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${access_token}`
    }
  }).then((response) => {
    list.value = response.data.data

  })
    .catch((error) => {
      console.error(error)
    })

}

onMounted(() => {
  getList();
})


const addTask = async (input_content) => {
  if (input_content === '') {
    return notify('Opss, o campo está vazio', 'error')
  }
  else {
    for (let index = 0; index < list.value.length; index++) {
      const input1 = input_content.toLowerCase()
      const input2 = list.value[index].content.toLowerCase()
      if (input1 === input2) {
        return notify('Opss, tarefa ja adicionada', 'error')
      }
    }
  }

  await api({
    method: 'POST',
    url: '/task',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${access_token}`
    },
    data: {
      content: input_content,
      is_done: false
    },
  }).then(function (response) {
    if (response.data) {
      console.log(response.data)
      list.value.push(response.data.data)
      notify('Tarefa adicionada com sucesso!!!', 'success')

    }
  })
    .catch(function (response) {
      console.log(response.data);
      return notify('Opss, tarefa ja adicionada', 'error')

    });
}

const removeTask = (task) => {

  api({
    method: 'DELETE',
    url: `/task/${task.id}`,
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${access_token}`
    },
  }).then(function (response) {
    console.log(response.data);
    getList();
  })
    .catch(function (response) {
      console.log(response.data);
    });
}

const Exit = () => {
  localStorage.removeItem('user');
  window.location.href = '/';
}
</script>

<template>
  <header id="app-header">
    <section class="create-task container">
      <h1>Tasks List</h1>
      <FormAddTask @btn-click="addTask" :notify="notify" />
      <button @click="Exit()" id="logout"><img src="../images/power.svg" alt="Botão de sair" srcset=""></button>
    </section>
  </header>

  <div class="container">
    <section class="task-list">
      <header>
        <h2>Lista de tarefas</h2>
        <div class="quant">
          <h2>{{ list.length }}</h2>
        </div>
      </header>
      <hr />
      <div class="list" id="task-list">
        <div v-for="(task) in list" :key="task.id" :class="`task-item ${task.done && 'done'}`">
          <Task :task="task" :notify="notify" :removeTask="removeTask" />
        </div>
      </div>

    </section>
  </div>
</template>
