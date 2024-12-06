<template>
    <!-- 
    Form component using Tailwind CSS for styling
    Uses Vue's v-model for two-way data binding
    Implements form validation and error handling
  -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                {{ isEditing ? "Edit Product" : "Add New Product" }}
            </h2>

            <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Name Field -->
                <div class="form-group">
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Product Name
                        <span class="text-xs text-gray-500 ml-1"
                            >(Required)</span
                        >
                    </label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        :class="{ 'border-red-300': errors.name }"
                    />
                    <p v-if="errors.name" class="mt-1 text-sm text-red-600">
                        {{ errors.name[0] }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Enter the official name of the pharmaceutical product
                    </p>
                </div>

                <!-- Category Field -->
                <div class="form-group">
                    <label
                        for="category"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Category
                        <span class="text-xs text-gray-500 ml-1"
                            >(Required)</span
                        >
                    </label>
                    <select
                        id="category"
                        v-model="form.category"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        :class="{ 'border-red-300': errors.category }"
                    >
                        <option value="" disabled>Select a category</option>
                        <option value="tablet">Tablet</option>
                        <option value="capsule">Capsule</option>
                        <option value="injection">Injection</option>
                    </select>
                    <p v-if="errors.category" class="mt-1 text-sm text-red-600">
                        {{ errors.category[0] }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Choose the pharmaceutical form of the product
                    </p>
                </div>

                <!-- Active Ingredients Field -->
                <div class="form-group">
                    <label
                        for="active_ingredients"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Active Ingredients
                        <span class="text-xs text-gray-500 ml-1"
                            >(Required)</span
                        >
                    </label>
                    <textarea
                        id="active_ingredients"
                        v-model="form.active_ingredients"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        :class="{ 'border-red-300': errors.active_ingredients }"
                    ></textarea>
                    <p
                        v-if="errors.active_ingredients"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.active_ingredients[0] }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        List all active ingredients with their concentrations
                    </p>
                </div>

                <!-- Batch Number Field -->
                <div class="form-group">
                    <label
                        for="batch_number"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Batch Number
                        <span class="text-xs text-gray-500 ml-1"
                            >(Required, Unique)</span
                        >
                    </label>
                    <input
                        type="text"
                        id="batch_number"
                        v-model="form.batch_number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        :class="{ 'border-red-300': errors.batch_number }"
                    />
                    <p
                        v-if="errors.batch_number"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.batch_number[0] }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Enter a unique identifier for this batch
                    </p>
                </div>

                <!-- Research Status Field -->
                <div class="form-group">
                    <label
                        for="research_status"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Research Status
                        <span class="text-xs text-gray-500 ml-1"
                            >(Required)</span
                        >
                    </label>
                    <select
                        id="research_status"
                        v-model="form.research_status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        :class="{ 'border-red-300': errors.research_status }"
                    >
                        <option value="" disabled>Select a status</option>
                        <option value="under development">
                            Under Development
                        </option>
                        <option value="in clinical trials">
                            In Clinical Trials
                        </option>
                        <option value="completed">Completed</option>
                    </select>
                    <p
                        v-if="errors.research_status"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.research_status[0] }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Current stage in the research process
                    </p>
                </div>

                <!-- Manufacturing Date Field -->
                <div class="form-group">
                    <label
                        for="manufacturing_date"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Manufacturing Date
                        <span class="text-xs text-gray-500 ml-1"
                            >(Required)</span
                        >
                    </label>
                    <input
                        type="date"
                        id="manufacturing_date"
                        v-model="form.manufacturing_date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        :class="{ 'border-red-300': errors.manufacturing_date }"
                    />
                    <p
                        v-if="errors.manufacturing_date"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.manufacturing_date[0] }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Date when the product was manufactured
                    </p>
                </div>

                <!-- Expiration Date Field -->
                <div class="form-group">
                    <label
                        for="expiration_date"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Expiration Date
                        <span class="text-xs text-gray-500 ml-1"
                            >(Required, Must be after manufacturing date)</span
                        >
                    </label>
                    <input
                        type="date"
                        id="expiration_date"
                        v-model="form.expiration_date"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                        :class="{ 'border-red-300': errors.expiration_date }"
                    />
                    <p
                        v-if="errors.expiration_date"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.expiration_date[0] }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Date when the product expires
                    </p>
                </div>

                <!-- Form Actions -->
                <div
                    class="flex justify-end space-x-3 sm:flex-col sm:space-y-3 sm:space-x-0"
                >
                    <router-link
                        to="/"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 sm:w-full"
                        >Cancel</router-link
                    >
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 sm:w-full"
                        :disabled="isSubmitting"
                    >
                        <span v-if="isSubmitting">
                            <svg
                                class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            Processing...
                        </span>
                        <span v-else
                            >{{ isEditing ? "Update" : "Create" }} Product</span
                        >
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

// Define the form interface
interface ProductForm {
    name: string;
    category: string;
    active_ingredients: string;
    batch_number: string;
    research_status: string;
    manufacturing_date: string;
    expiration_date: string;
}

const router = useRouter();
const route = useRoute();

const form = ref<ProductForm>({
    name: "",
    category: "",
    active_ingredients: "",
    batch_number: "",
    research_status: "",
    manufacturing_date: "",
    expiration_date: "",
});

const errors = ref({});
const globalError = ref("");
const isSubmitting = ref(false);

const isEditing = computed(() => route.params.id !== undefined);

const validateForm = () => {
    errors.value = {};
    globalError.value = "";

    if (!form.value.name?.trim()) errors.value.name = ["Name is required"];
    if (!form.value.category) errors.value.category = ["Category is required"];
    if (!form.value.active_ingredients?.trim())
        errors.value.active_ingredients = ["Active ingredients are required"];
    if (!form.value.batch_number?.trim())
        errors.value.batch_number = ["Batch number is required"];
    if (!form.value.research_status)
        errors.value.research_status = ["Research status is required"];
    if (!form.value.manufacturing_date)
        errors.value.manufacturing_date = ["Manufacturing date is required"];
    if (!form.value.expiration_date)
        errors.value.expiration_date = ["Expiration date is required"];

    if (form.value.expiration_date && form.value.manufacturing_date) {
        if (
            new Date(form.value.expiration_date) <=
            new Date(form.value.manufacturing_date)
        ) {
            errors.value.expiration_date = [
                "Expiration date must be after manufacturing date",
            ];
        }
    }

    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) return;

    isSubmitting.value = true;
    errors.value = {};
    globalError.value = "";

    try {
        const endpoint = isEditing.value
            ? `/api/products/${route.params.id}`
            : "/api/products";
        const method = isEditing.value ? "put" : "post";

        const response = await axios[method](endpoint, form.value);
        router.push("/");
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
        } else {
            globalError.value =
                error.response?.data?.message ||
                "An error occurred while saving the product";
        }
    } finally {
        isSubmitting.value = false;
    }
};

const fetchProduct = async () => {
    if (!isEditing.value) return;

    try {
        const response = await axios.get(`/api/products/${route.params.id}`);
        form.value = {
            ...response.data,
            manufacturing_date: response.data.manufacturing_date.split("T")[0],
            expiration_date: response.data.expiration_date.split("T")[0],
        };
    } catch (error) {
        globalError.value = "Failed to load product details";
        router.push("/");
    }
};

onMounted(fetchProduct);
</script>
``` Please manually update the `ProductForm.vue` file with the above content.
After updating, verify if the TypeScript errors are resolved. If you encounter
any further issues, let me know, and we can address them accordingly.
