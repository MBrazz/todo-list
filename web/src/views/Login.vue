<script setup>
import { toast } from "vue3-toastify"
import "vue3-toastify/dist/index.css"
import { ref } from "vue"

import api from "../services/api";

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

const data = ref({ email: '', password: '' });

const submit = async () => {
  api({
    method: 'POST',
    url: '/login',
    data: data.value,
    config: {
      headers: {
        'Content-Type': 'application/json',
      }
    }
  })
    .then((response) => {
      console.log(response)
      localStorage.setItem('user', JSON.stringify(response.data));
      window.location.href = '/tasks';
    })
}
</script>

<template>
  <div class="content-login">
    <h1>Login</h1>
    <section class="input-box">
      <form action="javascript:void(0)" method="post">
        
        <div class="form-group">
          <input type="email" class="form-control" v-model="data.email" name="email" placeholder="E-mail">
        </div>
        
        <div class="form-group">
          <input type="password" class="form-control" v-model="data.password" name="password" placeholder="Senha">
        </div>

        <div class="form-group">
          <button @click="submit">Entrar</button>
        </div>

        <p>Não tem conta? <a href="/register">clique aqui!</a></p>
      </form>
    </section>
  </div>
</template>

<style scoped>
.content-login {
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  padding-bottom: 10rem;

}

.content-login h1 {
  margin-bottom: 2.6rem;
}

.form-group:nth-child(2) {
  margin-bottom: 2.2rem;
}

input,
button {
  font-size: 15px;
  font-family: "Montserrat", sans-serif;
  font-weight: 500;
  color: #1c587e;
  padding: 0.6rem;
}

input::placeholder {
  color: #1c587e;
}

.form-group {
  border: 2px solid #1d9ae2;
  border-radius: 5px;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 0.7rem;
  background-color: rgb(238, 245, 255);
}

form p {
  font-weight: 600;
  font-size: 13px;
  text-transform: uppercase;
  color: rgb(21, 171, 216);
  margin-top: 1rem;
}

button {
  cursor: pointer;
  width: 100%;
}
</style>
