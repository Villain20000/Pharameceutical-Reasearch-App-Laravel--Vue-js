<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <router-link
                                to="/"
                                class="text-xl font-bold text-gray-800"
                            >
                                Pharma Research Management
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Display any global notifications -->
                <div v-if="notification" class="mb-4">
                    <div
                        :class="[
                            'rounded-md p-4',
                            notification.type === 'error'
                                ? 'bg-red-50 text-red-800'
                                : 'bg-green-50 text-green-800',
                        ]"
                    >
                        <p class="text-sm">{{ notification.message }}</p>
                    </div>
                </div>

                <!-- Main content -->
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component" @notify="showNotification" />
                    </transition>
                </router-view>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from "vue";

const notification = ref(null);

const showNotification = ({ message, type = "success", duration = 3000 }) => {
    notification.value = { message, type };
    setTimeout(() => {
        notification.value = null;
    }, duration);
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Add Tailwind form styles */
[type="text"],
[type="email"],
[type="url"],
[type="password"],
[type="number"],
[type="date"],
[type="datetime-local"],
[type="month"],
[type="search"],
[type="tel"],
[type="time"],
[type="week"],
[multiple],
textarea,
select {
    @apply w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
}

[type="checkbox"],
[type="radio"] {
    @apply rounded border-gray-300 text-blue-600 focus:ring-blue-500;
}
</style>
