import { createRouter, createWebHistory } from "vue-router"

import TaskList from "../views/TaskList.vue"
import Login from "../views/Login.vue"
import Register from "../views/Register.vue"

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/register",
      name: "Register",
      component: Register,
    },
    {
      path: "/",
      name: "Login",
      component: Login,
    },
    {
      path: "/tasks",
      name: "TaskList",
      component: TaskList,
    },
  ],
})

export default router
