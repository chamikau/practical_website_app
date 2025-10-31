import axios from "axios";

const API = axios.create({
    baseURL: "http://localhost:8000/api", // Laravel backend
});

export default API;
