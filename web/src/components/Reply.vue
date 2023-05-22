<script setup>
import { ref, onMounted, computed, watch, reactive } from "vue"
import api from "../services/api";

const props = defineProps({

  comment: {
    type: Object,
    required: true,
  },
  newReply: {
    type: Function,
    required: true,
  },

  replies: {
    type: Object,
    require: true
  },
    getList: {
    type: Function,
    require: true
  }
})



const added_reply = ref(false);
const edit_reply = ref(false);
const input_reply = ref('');
const user = localStorage.getItem('user');
const userJson = JSON.parse(user)
const access_token = userJson.access_token

// const removeReply = (reply) => {
//   listReply.value = listReply.value.filter((t) => t !== comment)
//   localStorage.removeItem('listReply');
// }

// const newReply = (reply) => {
//   added_reply = true;
// }

const newReply = async (input) => {
  added_reply.value = false
  await api({
    method: 'POST',
    url: '/comment',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${access_token}`
    },
    data: {
      task_id: props.comment.id,
      parent_id: props.replies.id,
      text: input
    },
  }).then(function (response) {
    if (response.data) {
      console.log(response.data)
      props.getList();
    }
  })
    .catch(function (response) {
      console.log(response.data);
    });
}

const removeReply = (reply) => {
  api({
    method: 'DELETE',
    url: `/comment/${reply.id}`,
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${access_token}`
    },
  }).then(function (response) {
    console.log(response.data);
    props.getList();
    props.notify('Comentário deletado com sucesso!!!', 'success')
  })
    .catch(function (response) {
      console.log(response.data);
    });
}

</script>

<template>
  <section class="reply" v-if="replies.all_comments.length > 0">
    <div class="row" v-for="(reply, index) in comment.all_comments">
      <img src="../images/curve-reply.svg" alt="" id="curve">
      <div class="column">
        <input type="text" name="reply" placeholder="" v-model="reply.id"/>
        <button id="new-reply" @click="added_reply = true" >Responder</button>
      </div>
    <button class="defaultButton" id="add" v-if="edit_reply">Enviar</button>
      <button @click="" class="defaultButton" id="update" v-if="edit_reply==false">
        <img src="../images/edit.svg" alt=""/>
      </button>
      <button @click="removeReply(reply)" class="defaultButton" id="delete" v-if="edit_reply==false">
        <img src="../images/trash.svg" alt=""/>
      </button>
    </div>
  </section>

  <section class="reply">
      <div class="row" v-for="(reply, index) in replies.all_comments">
        <img src="../images/curve-reply.svg" alt="" id="curve">
        <div class="column">
          <input type="text" name="reply" placeholder="" v-model="reply.id"/>
          <button id="new-reply" @click="added_reply=true" v-if="!added_reply">Responder</button>
        </div>
      <button class="defaultButton" id="add" v-if="edit_reply" @click="newReply({input_reply})">Enviar</button>
        <button @click="" class="defaultButton" id="update" v-if="!edit_reply">
          <img src="../images/edit.svg" alt=""/>
        </button>
        <button @click="removeReply(reply)" class="defaultButton" id="delete" v-if="!edit_reply">
          <img src="../images/trash.svg" alt=""/>
        </button>
      </div>
    </section>

    <section class="reply" v-if="added_reply">
        <div class="row">
          <img src="../images/curve-reply.svg" alt="" id="curve">
          <div class="column">
            <input v-model="input_reply" type="text" name="reply" placeholder="">
            <button id="new-reply" @click="added_reply">Responder</button>
          </div>
        <button class="defaultButton" id="add" v-if="added_reply" @click="newReply(input_reply)">Enviar</button>
          <button @click="" class="defaultButton" id="update" v-if="edit_reply"><img src="../images/edit.svg" alt=""/></button>
          <button @click="removeReply()" class="defaultButton" id="delete" v-if="edit_reply"><img src="../images/trash.svg" alt=""/></button>
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