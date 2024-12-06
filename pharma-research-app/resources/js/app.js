import "./bootstrap";
import { createApp } from "vue";
import { createRouter, createWebHistory } from "vue-router";

// Import components
import App from "./App.vue";
import ProductList from "./components/ProductList.vue";
import ProductForm from "./components/ProductForm.vue";

// Create router instance
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "products.index",
            component: ProductList,
        },
        {
            path: "/products/create",
            name: "products.create",
            component: ProductForm,
        },
        {
            path: "/products/:id/edit",
            name: "products.edit",
            component: ProductForm,
            props: true,
        },
    ],
});

// Create and mount the Vue application
const app = createApp(App);
app.use(router);

// Error handler
app.config.errorHandler = (err, vm, info) => {
    console.error("Vue Error:", err);
    console.error("Error Info:", info);
};

// Mount the app
app.mount("#app");
