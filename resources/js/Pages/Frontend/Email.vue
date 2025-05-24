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
                id="massProd"
                class="w-full px-4 py-2 placeholder-gray-500 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder-opacity-40"
                required
                placeholder="e.g. 541st"
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
                placeholder="e.g. automation3@smp.com.ph;automation2@smp.com.ph"
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
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { reactive, ref, onMounted, nextTick, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DotsLoader from '@/Components/DotsLoader.vue';

const form = reactive({
    massPro: '',
    emails: '',
    message: '',
})

const success = ref(false)

const submitEmail = async () => {
  try {
    const response = await axios.post('/api/send-takefu-email', form, {
        headers: {
            Accept: 'application/json',
        }
    });
    console.log(response.data);
    success.value = true

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
  }
}
</script>
