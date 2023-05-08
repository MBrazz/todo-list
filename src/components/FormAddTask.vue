<script setup>
import { ref } from "vue"
import Input from "./Input.vue";

const input_content = ref("")

const props = defineProps ({
  input_content: {
    type: String,
  },

  notify: {
    type: Function,
  }
})
</script>

<template>
  <form id="new-task-form" @submit.prevent="$emit('btn-click', input_content)">
    <Input v-model="input_content" />
    <input type="submit" value="Adicionar tarefa" />
  </form>
</template>
