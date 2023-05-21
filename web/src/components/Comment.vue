<script setup>

import { ref, onMounted, computed, watch, reactive } from "vue"
import Reply from '../components/Reply.vue';
import api from "../services/api";


const props = defineProps({
  task: {
    type: Object,
    required: true,
  },
  list: {
    type: Object,
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
const input_comment = ref('');
const input_reply = ref('');
const is_new_reply = ref(false)
const user = localStorage.getItem('user');
const userJson = JSON.parse(user)
const access_token = userJson.access_token


const newComment = async (input_comment) => {
  await api({
    method: 'POST',
    url: '/comment',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${access_token}`
    },
    data: {
      task_id: props.task.id,
      parent_id: props.task.comments.parent_id ? props.task.id : props.task.comments.parent_id,
      text: input_comment
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


const removeComment = (comment) => {

  api({
    method: 'DELETE',
    url: `/comment/${comment.id}`,
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


const editComment = (comment) => {
  // edit.value = true;
  api({
    method: 'PUT',
    url: `/comment/${comment.id}`,
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${access_token}`
    },
    data: {
      text: comment.text
    },
  }).then(function (response) {
    console.log(response.data);
    props.notify('Comentário atualizado com sucesso!!!', 'success')
  })
    .catch(function (response) {
      console.log(response.data);
    });
}



const newReply = () => {
  is_new_reply.value = true;
  // listReply.value.push({
  //   id: reply.id,
  //   text: input_reply,
  //   // task_id: task.id
  // })
  // localStorage.setItem("listReply", JSON.stringify(listReply.value))
}
// for (let index = 0; index < props.task.comments.length; index++) {
  // if() {

    // console.log(props.task.comments[0].all_comments[index].text)
  // }

  
// }
</script>

<template >
  <!-- <h4>Comentário</h4> -->
  <div class="top row">
    <input type="text" name="comment" id="input-fixed" v-model="input_comment"
      placeholder="Escreva aqui seu comentário" />
    <button class="defaultButton" id="add" @click="newComment(input_comment)">Adicionar comentário</button>
  </div>
  <section class="comment" v-for="(comment, index) in task.comments" :key="comment.id">

    <div class="row" v-if="comment.parent_id === null">

      <input type="text" name="comment" v-model="comment.text" />

      <button @click="editComment(comment)" class="defaultButton" id="update"><img src="../images/edit.svg" alt=""/></button>

      <button @click="removeComment(comment)" class="defaultButton" id="delete"><img src="../images/trash.svg" alt=""/></button>
    </div>

    <button id="new-reply" @click="newReply()" v-if="comment.parent_id === null">Responder</button>
    <div v-for="(reply, index) in comment.all_comments">
      <div v-if="reply.all_comments.length > 0">
        <Reply :newReply="newReply" :comment="comment" />
        {{ reply.all_comments }}
      </div>
    </div>
  </section>
</template>

<style scoped>
.top {
  margin: 2rem 0 0.2rem 0 !important;
}

.row {
  display: flex;
  flex-direction: row;
  margin-top: 1rem;
}

.column {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
}

#input-fixed {
  border-radius: 4px;
  height: 2.4rem;
  width: 100%;
  font-family: 'Montserrat', sans-serif;
  padding: 0.4em;
  margin-bottom: 0.8rem;
  border: 2px solid #1e6c96;
  background-color: #ffffff;
  font-size: 15px;
  color: #1e6c96;
  font-weight: 500;
}

::placeholder {
  color: #51a2ce;
}

.comment input {
  border-radius: 4px;
  height: 2.4rem;
  width: 100%;
  font-family: 'Montserrat', sans-serif;
  padding: 0.4em;
  border: 2px solid #8d8787;
  background-color: #ffffff;
  font-size: 15px;
  color: #777373;
  font-weight: 500;
  font-family: 'Montserrat', sans-serif;
}

#add {
  background-color: var(--primary);
  color: #fff;
  white-space: nowrap;
  margin-left: 0.6rem;
  border: solid 2px #72c2ec;
  width: fit-content;
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

.comment button:active {
  opacity: 0.3;
  transition: all ease-in .3s;
}

.comment button:hover {
  opacity: 0.65;
  transition: all ease-in .2s;
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
</style>