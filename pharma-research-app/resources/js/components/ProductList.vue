<template>
    <div class="bg-white shadow rounded-lg mx-auto max-w-7xl px-2 sm:px-0">
        <div class="p-3 sm:p-4 md:p-6">
            <!-- Header -->
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6"
            >
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                    Research Products
                </h2>
                <button
                    @click="$router.push('/products/create')"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                >
                    Add New Product
                </button>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="text-center py-4">
                <div class="inline-flex items-center">
                    <svg
                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500"
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
                    <span class="text-gray-500">Loading products...</span>
                </div>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-4">
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg
                                class="h-5 w-5 text-red-400"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ error }}</p>
                            <button
                                @click="fetchProducts"
                                class="mt-2 text-sm text-red-600 hover:text-red-800"
                            >
                                Try Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else-if="products.length === 0" class="text-center py-8">
                <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    No products
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Get started by creating a new product.
                </p>
                <div class="mt-6">
                    <button
                        @click="$router.push('/products/create')"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                    >
                        Add New Product
                    </button>
                </div>
            </div>

            <!-- Products Table -->
            <div
                v-else
                class="overflow-x-auto -mx-3 sm:-mx-4 md:mx-0 sm:rounded-lg"
            >
                <div class="inline-block min-w-full align-middle">
                    <table
                        class="min-w-full divide-y divide-gray-200 table-fixed sm:table-auto"
                    >
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="w-1/3 sm:w-auto px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell"
                                >
                                    Category
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell"
                                >
                                    Batch Number
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Status
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell"
                                >
                                    Manufacturing Date
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell"
                                >
                                    Expiration Date
                                </th>
                                <th
                                    scope="col"
                                    class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-3 sm:px-6 py-4 whitespace-normal">
                                    <div
                                        class="text-sm font-medium text-gray-900 truncate max-w-[200px] sm:max-w-xs"
                                    >
                                        {{ product.name }}
                                    </div>
                                    <div
                                        class="sm:hidden text-xs text-gray-500 mt-1 space-y-1"
                                    >
                                        <div class="flex flex-col gap-0.5">
                                            <div>
                                                <span class="font-medium"
                                                    >Category:</span
                                                >
                                                {{ product.category }}
                                            </div>
                                            <div>
                                                <span class="font-medium"
                                                    >Batch:</span
                                                >
                                                {{ product.batch_number }}
                                            </div>
                                            <div>
                                                <span class="font-medium"
                                                    >Mfg Date:</span
                                                >
                                                {{
                                                    formatDate(
                                                        product.manufacturing_date
                                                    )
                                                }}
                                            </div>
                                            <div>
                                                <span class="font-medium"
                                                    >Exp Date:</span
                                                >
                                                {{
                                                    formatDate(
                                                        product.expiration_date
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-3 sm:px-6 py-4 hidden sm:table-cell"
                                >
                                    <div
                                        class="text-sm text-gray-500 truncate max-w-xs"
                                    >
                                        {{ product.category }}
                                    </div>
                                </td>
                                <td
                                    class="px-3 sm:px-6 py-4 hidden sm:table-cell"
                                >
                                    <div class="text-sm text-gray-500">
                                        {{ product.batch_number }}
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-4">
                                    <span
                                        :class="
                                            getStatusClass(
                                                product.research_status
                                            )
                                        "
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    >
                                        {{ product.research_status }}
                                    </span>
                                </td>
                                <td
                                    class="px-3 sm:px-6 py-4 hidden lg:table-cell"
                                >
                                    <div class="text-sm text-gray-500">
                                        {{
                                            formatDate(
                                                product.manufacturing_date
                                            )
                                        }}
                                    </div>
                                </td>
                                <td
                                    class="px-3 sm:px-6 py-4 hidden lg:table-cell"
                                >
                                    <div class="text-sm text-gray-500">
                                        {{
                                            formatDate(product.expiration_date)
                                        }}
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-4">
                                    <div
                                        class="flex flex-col sm:flex-row justify-between items-center gap-1.5 sm:gap-2"
                                    >
                                        <button
                                            @click="
                                                $router.push(
                                                    '/products/' +
                                                        product.id +
                                                        '/edit'
                                                )
                                            "
                                            class="inline-flex items-center justify-center w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm transition-colors"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="confirmDelete(product)"
                                            class="inline-flex items-center justify-center w-full sm:w-auto px-2.5 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md shadow-sm transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const products = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchProducts = async () => {
    loading.value = true;
    error.value = null;

    try {
        console.log("Fetching products...");
        const response = await axios.get("/api/products");
        console.log("Products response:", response.data);

        products.value = response.data.filter(
            (product) =>
                product &&
                product.name &&
                product.category &&
                product.batch_number &&
                product.research_status
        );
    } catch (e) {
        console.error("Error fetching products:", e);
        error.value = "Failed to load products. Please try again.";
    } finally {
        loading.value = false;
    }
};

const confirmDelete = async (product) => {
    const message = `Are you sure you want to delete ${product.name}?\n\nThis action cannot be undone.`;

    if (!confirm(message)) return;

    try {
        await axios.delete(`/api/products/${product.id}`);
        await fetchProducts();
    } catch (e) {
        console.error("Error deleting product:", e);
        alert("Failed to delete product. Please try again.");
    }
};

const formatDate = (date) => {
    if (!date) return "N/A";
    return new Date(date).toLocaleDateString(undefined, {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const getStatusClass = (status) => {
    const classes = {
        "under development": "bg-yellow-100 text-yellow-800",
        "in clinical trials": "bg-blue-100 text-blue-800",
        completed: "bg-green-100 text-green-800",
    };
    return classes[status] || "bg-gray-100 text-gray-800";
};

onMounted(fetchProducts);
</script>
