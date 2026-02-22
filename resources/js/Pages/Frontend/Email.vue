<template>
    <Frontend>
        <div class="max-w-xl p-6 mx-auto mt-12 space-y-6 bg-white rounded-lg shadow-lg">
            <div class="pb-6 border-b-2 border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-12 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">Email: Send Reports</h2>
                        <p class="mt-1 text-sm text-gray-600">Fill out all fields to send an email</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submitEmail" class="space-y-6">

                <div>
                    <label for="massProd" class="block mb-2 text-sm font-semibold text-gray-700">
                        Mass Production Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.massPro"
                        type="text"
                        @input="form.massPro = form.massPro.toUpperCase()"
                        id="massProd"
                        class="w-full px-4 py-3 text-sm font-medium text-gray-700 placeholder-gray-400 transition-all border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white"
                        required
                        placeholder="e.g., K40 541ST"
                    />
                </div>

                <div>
                    <label for="emails" class="block mb-2 text-sm font-semibold text-gray-700">
                        Recipients <span class="text-red-500">*</span>
                    </label>
                    <p class="mb-2 text-xs text-gray-500">Enter email addresses separated by commas</p>
                    <input
                        v-model="form.emails"
                        type="text"
                        id="emails"
                        class="w-full px-4 py-3 text-sm font-medium text-gray-700 placeholder-gray-400 transition-all border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white"
                        required
                        placeholder="automation3@smp.com.ph, automation2@smp.com.ph"
                    />
                </div>

                <div>
                    <label for="additionalFiles" class="block mb-2 text-sm font-semibold text-gray-700">
                        Additional Excel Attachments
                        <span class="text-xs font-normal text-gray-400">(Optional, multiple allowed)</span>
                    </label>

                    <!-- Drag & Click Area -->
                    <label
                        for="additionalFiles"
                        class="flex flex-col items-center justify-center w-full px-4 py-6 text-center border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-cyan-400 hover:bg-gray-50 transition-colors"
                    >
                        <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0-8l-4 4m4-4l4 4"/>
                        </svg>
                        <span class="text-sm text-gray-600">Click to upload Excel files</span>
                    </label>

                    <!-- Hidden Input -->
                    <input
                        type="file"
                        id="additionalFiles"
                        multiple
                        accept=".xls,.xlsx"
                        @change="handleFileChange"
                        class="hidden"
                    />

                    <!-- File List + Clear Button -->
                    <div v-if="form.additionalFiles.length" class="mt-2 flex flex-col gap-2">
                        <ul class="text-sm text-gray-700 list-disc list-inside">
                            <li v-for="(file, index) in form.additionalFiles" :key="index">
                                {{ file.name }}
                            </li>
                        </ul>
                        <button
                            type="button"
                            @click="form.additionalFiles = []"
                            class="self-start px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600 transition-colors"
                        >
                            Clear All
                        </button>
                    </div>
                </div>

                <div>
                    <label for="message" class="block mb-2 text-sm font-semibold text-gray-700">
                        Message <span class="text-xs font-normal text-gray-400">(Optional)</span>
                    </label>
                    <textarea
                        v-model="form.message"
                        id="message"
                        rows="5"
                        class="w-full px-4 py-3 text-sm font-medium text-gray-700 placeholder-gray-400 transition-all border border-gray-300 rounded-lg shadow-sm resize-none bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white"
                        placeholder="Add an optional message..."
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="w-full px-6 py-3.5 text-base font-semibold text-white bg-gradient-to-r from-teal-500 to-cyan-500 rounded-lg shadow-md hover:from-teal-600 hover:to-cyan-600 hover:shadow-lg transition-all duration-200 active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2"
                    :disabled="loading"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg v-if="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ loading ? 'Sending...' : 'Send Email' }}
                    </span>
                </button>
            </form>

            <div v-if="success" class="p-4 border-l-4 border-green-500 rounded-r-lg bg-green-50">
                <p class="flex items-center gap-2 text-sm font-semibold text-green-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Email Sent Successfully!
                </p>
            </div>

            <div v-if="loading" class="flex flex-col items-center justify-center py-6">
                <div class="relative w-20 h-20">
                    <div class="absolute inset-0 border-4 border-teal-200 rounded-full animate-spin border-t-teal-500"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-10 h-10 text-teal-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="absolute top-0 right-0 animate-ping">
                        <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 space-y-1 text-center">
                    <p class="text-base font-semibold text-gray-700">Sending your email</p>
                    <div class="flex items-center justify-center gap-1">
                        <span class="w-2 h-2 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-teal-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>
            </div>

        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { reactive, ref, onMounted, nextTick, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DotsLoader from '@/Components/DotsLoader.vue';
import { useAuth } from '@/Composables/useAuth.js'

const { state } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {

        const start = Date.now();
        const timeout = 500; // 5 seconds

        while (!state.user) {
            if (Date.now() - start > timeout) {
                console.error('Auth timeout: user data failed to load within 5 seconds.');
                Inertia.visit('/'); // Redirect if not authenticated
                return false;
            }
            await new Promise(resolve => setTimeout(resolve, 50)); // small delay
        }

        if (!state.isAuthenticated) {
            Inertia.visit('/'); // Redirect if not authenticated

            return false; // Indicate not authenticated
        }

        console.warn("USER AUTHENTICATED!");
        console.warn("Name: ", state.user.firstName + " " + state.user.surname);
        console.warn("Access: ", state.user.access_type);

        return true; // Indicate authenticated
    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/'); // Redirect on error
        return false; // Indicate not authenticated
    }
};


const userEmailLogging = async (logEvent) => {
    try{
        const responseEmailLogging = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: logEvent,
            section: 'Email',
        });

        //console.log('responseEmailLogging-data: ',responseEmailLogging.data);
    }catch(error){
        console.error('userEmailLogging post request failed: ',error);
    }
}

const handleFileChange = (event) => {
    form.additionalFiles = Array.from(event.target.files);
};

const form = reactive({
    massPro: '',
    emails: '',
    message: '',
    additionalFiles: [],
})

const success = ref(false);
const loading = ref(false);

const submitEmail = async () => {
    loading.value = true;

    try {
        // 1️⃣ Prepare FormData
        const formData = new FormData();
        formData.append('massPro', form.massPro);
        formData.append('emails', form.emails);
        formData.append('message', form.message);

        // 2️⃣ Append additional files if any
        form.additionalFiles.forEach((file, index) => {
            formData.append('additionalFiles[]', file);
        });

        // 3️⃣ Send to backend
        const response = await axios.post('/api/send-takefu-email', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                Accept: 'application/json',
            },
        });

        success.value = true;
        setTimeout(() => (success.value = false), 3000);

        await userEmailLogging(`has successfully sent an email for ${form.massPro}`);

        // Reset form
        form.massPro = '';
        form.emails = '';
        form.message = '';
        form.additionalFiles = [];

    } catch (error) {
        if (error.response) {
            console.error('Backend error status:', error.response.status);
            console.error('Backend error data:', error.response.data);
            alert('Server error: ' + JSON.stringify(error.response.data));
        } else {
            console.error('Request error:', error.message);
            alert('Failed to send email. Please check your inputs or try again later.');
        }
    } finally {
        loading.value = false;
    }
};

onMounted(async()=>{
    await checkAuthentication();
});

</script>
