import axios from "axios";

// Configure axios
window.axios = axios;

// Set default headers
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["Accept"] = "application/json";
window.axios.defaults.withCredentials = true;

// Set base URL for API requests
window.axios.defaults.baseURL = "/";

// Add request interceptor for CSRF token
window.axios.interceptors.request.use(
    (config) => {
        // Get CSRF token from meta tag
        const token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            config.headers["X-CSRF-TOKEN"] = token.content;
        } else {
            console.error("CSRF token not found");
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor for error handling
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        console.error("API Error:", {
            status: error.response?.status,
            data: error.response?.data,
            message: error.message,
        });
        return Promise.reject(error);
    }
);

// Log configuration for debugging
console.log("Axios configured with:", {
    baseURL: window.axios.defaults.baseURL,
    withCredentials: window.axios.defaults.withCredentials,
    headers: window.axios.defaults.headers.common,
});
