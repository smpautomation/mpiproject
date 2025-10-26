<template>
    <Frontend>
        <div class="max-w-xl p-8 mx-auto my-12 space-y-6 bg-white rounded-lg shadow-xl">
            <div>

            </div>
            <!-- Header Section - REPLACE -->
            <div class="pb-6 border-b-2 border-gray-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-1.5 h-12 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">Send Mass Production Files</h2>
                        <p class="text-sm text-gray-600 mt-1">Fill out the details below and select the files you wish to send.</p>
                    </div>
                </div>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-lg mt-4">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold text-blue-700">Tip:</span> Hold <kbd class="px-2 py-0.5 text-xs font-semibold bg-white border border-gray-300 rounded shadow-sm">Ctrl</kbd> (Windows) to select multiple files.
                    </p>
                </div>
            </div>

            <!-- Form - REPLACE class attribute -->
            <form @submit.prevent="submitEmail" class="space-y-6">

                <!-- Mass Production Name - REPLACE -->
                <div>
                    <label for="massProd" class="block mb-2 text-sm font-semibold text-gray-700">
                        Mass Production Name <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-2">Enter the Mass Production code (e.g., K40 541ST)</p>
                    <input
                        v-model="form.massPro"
                        type="text"
                        @input="form.massPro = form.massPro.toUpperCase()"
                        id="massProd"
                        class="w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white transition-all placeholder-gray-400"
                        required
                        placeholder="e.g., K40 541ST"
                    />
                </div>

                <!-- Recipients - REPLACE -->
                <div>
                    <label for="emails" class="block mb-2 text-sm font-semibold text-gray-700">
                        Recipients <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-2">Enter email addresses separated by commas</p>
                    <input
                        v-model="form.emails"
                        type="text"
                        id="emails"
                        class="w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white transition-all placeholder-gray-400"
                        required
                        placeholder="automation3@smp.com.ph, automation2@smp.com.ph"
                    />
                </div>

                <!-- Files - REPLACE -->
                <div>
                    <label for="files" class="block mb-2 text-sm font-semibold text-gray-700">
                        Select Files <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-2">Choose one or more files to attach</p>
                    <select
                        v-model="form.selectedFiles"
                        id="files"
                        multiple
                        class="w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white transition-all min-h-[150px] disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="availableFiles.length === 0"
                    >
                        <option v-if="availableFiles.length === 0" disabled class="text-gray-400">No files available for this Mass Production</option>
                        <option v-for="file in availableFiles" :key="file" :value="file" class="py-1">{{ file }}</option>
                    </select>
                    <p v-if="form.selectedFiles.length > 0" class="mt-2 text-xs font-medium text-teal-600">
                        {{ form.selectedFiles.length }} file(s) selected
                    </p>
                </div>

                <!-- Message - REPLACE -->
                <div>
                    <label for="message" class="block mb-2 text-sm font-semibold text-gray-700">
                        Message <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-2">Include any notes or instructions for recipients</p>
                    <textarea
                        v-model="form.message"
                        id="message"
                        rows="5"
                        class="w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-cyan-500 focus:bg-white transition-all placeholder-gray-400 resize-none"
                        placeholder="Add an optional message..."
                    ></textarea>
                </div>

                <!-- Submit Button - REPLACE -->
                <button
                    type="submit"
                    class="w-full px-6 py-3.5 text-base font-semibold text-white bg-gradient-to-r from-teal-500 to-cyan-500 rounded-lg shadow-md hover:from-teal-600 hover:to-cyan-600 hover:shadow-lg transition-all duration-200 active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md disabled:active:scale-100 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2"
                    :disabled="form.selectedFiles.length === 0 || loading"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg v-if="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ loading ? 'Sending...' : 'Send Email' }}
                    </span>
                </button>
            </form>

            <!-- Feedback - REPLACE -->
            <div v-if="success" class="p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                <p class="text-sm font-semibold text-green-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Email sent successfully!
                </p>
            </div>

            <div v-if="loading" class="flex flex-col items-center justify-center py-8">
                <div class="relative w-20 h-20">
                    <!-- Rotating circle background -->
                    <div class="absolute inset-0 border-4 border-teal-200 rounded-full animate-spin border-t-teal-500"></div>

                    <!-- Envelope in center -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-10 h-10 text-teal-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>

                    <!-- Flying paper trail -->
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

const form = reactive({
    massPro: '',
    emails: '',
    message: '',
    selectedFiles: [], // NEW: selected files array
});

const availableFiles = ref([]);
const success = ref(false);
const loading = ref(false);

const submitEmail = async () => {
loading.value = true;
  try {
    const response = await axios.post('/api/send-takefu-email-manual', form, {
        headers: {
            Accept: 'application/json',
        }
    });
    //console.log(response.data);
    success.value = true;
    setTimeout(() => {
        success.value = false;
    }, 3000); // 3000 milliseconds = 3 seconds

    await userEmailLogging(`has successfully sent an email for ${form.massPro}`); //user logs email

    // Reset form
    form.massPro = ''
    form.emails = ''
    form.message = ''
  } catch (error) {
    if (error.response) {
      console.error('Backend error status:', error.response.status);
      console.error('Backend error data:', error.response.data);
      alert('Server error: ' + JSON.stringify(error.response.data));
    } else {
      console.error('Request error:', error.message);
      alert('Failed to send email. Please check your inputs or try again later.');
    }
  }finally {
    loading.value = false;
  }
}

watch(() => form.massPro, async (newMassPro) => {
    if (!newMassPro) {
        availableFiles.value = [];
        form.selectedFiles = [];
        return;
    }

    try {
        const response = await axios.get(`/api/files/${encodeURIComponent(newMassPro)}`);
        availableFiles.value = response.data.files || [];
    } catch (error) {
        console.error('Failed to fetch files:', error);
        availableFiles.value = [];
    }
});

onMounted(async()=>{
    await checkAuthentication();
});

</script>
