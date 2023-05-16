<script setup>

const emit = defineEmits(['btn-click'])
emit('btn-click')

const props = defineProps({
  showModal: {
    type: Boolean,
    required: true
  },
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

<template >
  <Transition name="fade">
    <div class="modal" v-if="showModal">
      <div class="modal-content">

        <header>
          <h2>Você tem certeza?</h2>
          <span class="close" @click="$emit('btn-click', (showModal = false))">&times;</span>
        </header>

        <p>Deseja realmente apagar esta tarefa?</p>

        <footer>
          <button id="no" @click="$emit('btn-click', showModal = false)">Não</button>
          <button id="yes"
            @click="(removeTask(task), showModal = false, notify('Tarefa removida com sucesso!!!', 'success'))">
            Sim
          </button>
        </footer>

      </div>
    </div>
  </Transition>
</template>

<style lang="scss" scoped></style>
