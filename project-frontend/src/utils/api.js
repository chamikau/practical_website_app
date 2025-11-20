// src/utils/api.js
import axios from "axios";

const API = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: { "Content-Type": "application/json" },
});

/* ---------- AUTH ---------- */
export const registerUser = (userData) => API.post("/register-subscribe", userData);
export const loginUser = (userData) => API.post("/login", userData);

/* ---------- WEBSITES ---------- */
export const getWebsites = () => API.get("/websites");

/* Get subscribed websites by user */
export const getSubscribedWebsites = (userId) =>
    API.get(`/subscriber/${userId}/get-websites`);

/* ---------- POSTS ---------- */
/* Get posts for a website */
export const getPostsByWebsite = (websiteId) =>
    API.get(`/websites/${websiteId}/get-posts`);

/* Create a new post */
export const createPost = (websiteId, data) =>
    API.post(`/websites/${websiteId}/posts`, data);

export default API;
