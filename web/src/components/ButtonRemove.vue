<script setup>
import { ref } from "vue"
import Modal from "./Modal.vue"


let showModal = ref(false)

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
  <button class="delete" @click="showModal = true">Remover</button>
  <Modal @btn-click="showModal=false" :showModal="showModal" :notify="notify" :removeTask="removeTask" :task="task" />
</template>
