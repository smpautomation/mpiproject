<template>
    <Frontend>
            <div class="max-w-xl p-6 mx-auto mt-12 space-y-6 bg-white rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-blue-900">Fill all the fields</h2>

            <form @submit.prevent="submitEmail" class="space-y-4">
            <!-- Mass Prod -->
            <div>
                <label for="massProd" class="block mb-1 font-medium text-gray-700">Mass Production Name:</label>
                <input
                v-model="form.massPro"
                type="text"
                @input="form.massPro = form.massPro.toUpperCase()"
                id="massProd"
                class="w-full px-4 py-2 placeholder-gray-500 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-opacity-40"
                required
                placeholder="e.g. K40 541ST"
                />
            </div>

            <div>
                <label for="emails" class="block mb-1 font-medium text-gray-700">Recipients (comma-separated)</label>
                <input
                v-model="form.emails"
                type="text"
                id="emails"
                class="w-full px-4 py-2 placeholder-gray-500 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-opacity-40"
                required
                placeholder="e.g. automation3@smp.com.ph,automation2@smp.com.ph"
                />
            </div>

            <!-- Message -->
            <div>
                <label for="message" class="block mb-1 font-medium text-gray-700">Message</label>
                <textarea
                v-model="form.message"
                id="message"
                rows="5"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                placeholder="Optional message..."
                ></textarea>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full px-4 py-2 font-bold text-white transition duration-200 bg-blue-700 rounded-md hover:bg-blue-800"
            >
                Send Email
            </button>
            </form>

            <!-- Success Message -->
            <p v-if="success" class="font-medium text-green-600">Test Email Sent!</p>
            <DotsLoader v-if="loading" class="mx-auto text-blue-600" />
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
})

const success = ref(false);
const loading = ref(false);

const submitEmail = async () => {
loading.value = true;
  try {
    const response = await axios.post('/api/send-takefu-email', form, {
        headers: {
            Accept: 'application/json',
        }
    });
    //console.log(response.data);
    success.value = true;
    setTimeout(() => {
        success.value = false;
    }, 3000); // 3000 milliseconds = 3 seconds

    await userEmailLogging(`has successfully sent an email for ${form.massPro}`);

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

onMounted(async()=>{
    await checkAuthentication();
});

</script>
