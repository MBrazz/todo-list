import axios from "axios";

const api = axios.create({
    baseURL: "http://local.lo/api"
});

export default api;


