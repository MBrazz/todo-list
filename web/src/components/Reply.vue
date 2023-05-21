<script setup>
import { ref, onMounted, computed, watch, reactive } from "vue"

const props = defineProps({

  comment: {
    type: Object,
    required: true,
  },
  newReply: {
    type: Function,
    required: true,
  },
})



const added_reply = ref(false);
const input_reply = ref('');

// const removeReply = (reply) => {
//   listReply.value = listReply.value.filter((t) => t !== comment)
//   localStorage.removeItem('listReply');
// }

const newReply = (input_reply) => {
  added_reply = true;
}

</script>

<template>
  <section class="reply" v-for="(reply, index) in comment.all_comments">
    <div class="row">
      <img src="../images/curve-reply.svg" alt="" id="curve">
      <div class="column">
        <input type="text" name="reply" placeholder="" v-model="reply.text"/>
        <button id="new-reply" @click="" v-if="added_reply==false">Responder</button>
      </div>
    <button class="defaultButton" id="add" v-if="added_reply" @click="newReply(input_reply)">Enviar</button>
      <button @click="" class="defaultButton" id="update" v-if="added_reply==false"><img src="../images/edit.svg" alt=""/></button>
      <button @click="removeReply(reply)" class="defaultButton" id="delete" v-if="added_reply==false"><img src="../images/trash.svg" alt=""/></button>
    </div>
  </section>
</template>

<style scoped>

#add {
  background-color: var(--primary);
  color: #fff;
  white-space: nowrap;
  margin-left: 0.6rem;
  border: solid 2px #72c2ec;
  width: fit-content;
}
.row {
  display: flex;
  flex-direction: row;
  margin-bottom: 1rem;
}

.column {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
}

.comment input {
  border-radius: 4px;
  height: 2.4rem;
  width: 100%;
  font-family: 'Montserrat', sans-serif;
  padding: 0.4em;
  border: 2px solid #ccc;
  background-color: #f8f8f8;
  font-size: 15px;
  /* resize: none; */
  color: #444;
  font-family: 'Montserrat', sans-serif;
}


.defaultButton {
  padding: 8px;
  border-radius: 3px;
  height: 2.4rem;
  text-transform: uppercase;
  font-family: 'Arial', sans-serif;
  font-weight: 900;
  cursor: pointer;
  font-size: 11px;
}

.comment #update {
  background-color: #ff8c00;
  margin-left: 0.6rem;

}

.comment #delete {
  background-color: #e13737;
  margin-left: 0.6rem;
}

.comment h4 {
  font-weight: 500;
  font-size: 14px;
  margin-bottom: 0.4rem;
}

#new-reply {
  text-transform: uppercase;
  font-family: 'Arial', sans-serif;
  font-weight: 900;
  cursor: pointer;
  color: #777;
  margin: 0.6rem 0 0.8rem 2px;
  font-size: 11px;
}

#curve {
  margin: 0 0.6rem 0 1rem;
  align-self: flex-start;
}
</style>