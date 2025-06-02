<template>
  <Frontend>
    <div
      class="relative flex flex-col md:flex-row items-center justify-center min-h-screen px-8 py-12 mx-auto bg-center bg-cover"
      :style="{ backgroundImage: 'url(/photo/home_background.jpg)' }"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-60 z-0"></div>

        <!-- Headline -->
        <div
            class="relative z-10 flex flex-col items-start max-w-lg text-white font-extrabold tracking-widest mb-12 md:mb-0 md:mr-16 animate-fade-in-down"
        >
            <p class="text-3xl md:text-5xl mb-2 leading-tight">Welcome to</p>
            <p class="text-4xl md:text-6xl leading-tight bg-gradient-to-r from-blue-400 to-purple-600 bg-clip-text text-transparent">
            the MPI Website
            </p>
        </div>

        <!-- Login Form -->
        <div
            class="relative z-10 flex flex-col items-center opacity-0 w-full max-w-sm bg-white/20 backdrop-blur-lg rounded-xl p-10 shadow-lg animate-fade-in"
            >
            <p class="text-white font-bold text-xl mb-6 tracking-wide">LOGIN</p>
            <form @submit.prevent="login" class="w-full flex flex-col gap-6">
                <div class="input-field">
                <input
                    v-model="username"
                    type="text"
                    id="username"
                    placeholder=" "
                    required
                    autocomplete="username"
                    class="input"
                />
                <label for="username" class="text-white">Username</label>
                </div>

                <div class="input-field">
                <input
                    v-model="password"
                    type="password"
                    id="password"
                    placeholder=" "
                    required
                    autocomplete="current-password"
                    class="input"
                />
                <label for="password" class="text-white">Password</label>
                </div>

                <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition"
                >
                Submit
                </button>
                <p v-if="error" class="text-red-400 text-center mt-2 font-medium">{{ error }}</p>
            </form>

            <p class="mt-6 text-white text-center text-sm select-none">
                Not registered yet?
                <button
                    @click="showRegister = true"
                    class="text-blue-400 hover:text-blue-600 font-semibold underline focus:outline-none"
                    aria-label="Go to registration form"
                >
                    Register here
                </button>
            </p>
        </div>

        <!-- Registration modal or form toggle -->
        <div
          v-if="showRegister"
          class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-20 p-4"
        >
          <div
            class="bg-white rounded-xl p-8 max-w-md w-full shadow-lg relative"
            role="dialog"
            aria-modal="true"
          >
            <button
              @click="showRegister = false"
              class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 focus:outline-none"
              aria-label="Close registration form"
            >
              ✕
            </button>

            <h2 class="text-2xl font-bold mb-6 text-gray-800">Register</h2>

            <form @submit.prevent="register" class="flex flex-col gap-5">
              <input
                v-model="registerForm.name"
                type="text"
                placeholder="Full Name"
                required
                class="input-field input text-gray-900"
              />
              <input
                v-model="registerForm.username"
                type="text"
                placeholder="Username"
                required
                class="input-field input text-gray-900"
              />
              <input
                v-model="registerForm.email"
                type="email"
                placeholder="Email"
                required
                class="input-field input text-gray-900"
              />
              <input
                v-model="registerForm.password"
                type="password"
                placeholder="Password"
                required
                minlength="8"
                class="input-field input text-gray-900"
              />
              <input
                v-model="registerForm.password_confirmation"
                type="password"
                placeholder="Confirm Password"
                required
                minlength="8"
                class="input-field input text-gray-900"
              />
              <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition"
              >
                Register
              </button>
            </form>

            <p v-if="registerError" class="text-red-500 mt-3 text-center">{{ registerError }}</p>
          </div>
        </div>

    </div>
  </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
    import { ref, onMounted, nextTick, watch, computed, watchEffect } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import DotsLoader from '@/Components/DotsLoader.vue';
    import axios from 'axios'

    const email = ref('')
    const username = ref('')
    const password = ref('')
    const error = ref('')

    // CSRF token setup (only needed once globally, ideally)
    axios.defaults.withCredentials = true
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

    const login = async () => {
    try {
        // Make sure CSRF cookie is set (handled by Laravel)
        await axios.get('/sanctum/csrf-cookie') // Even for session-based login, this initializes CSRF

        await axios.post('/login', {
        username: username.value,
        password: password.value,
        })

        // Redirect or reload after login
        window.location.href = '/dashboard' // Or your desired route
    } catch (e) {
        error.value = e.response?.data?.message || 'Login failed'
    }
    }
</script>

<style scoped>
@keyframes fade-in-down {
  0% {
    opacity: 0;
    transform: translateY(-1.75rem);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-down {
  animation: fade-in-down 1s ease-out forwards;
}

@keyframes fade-in {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.animate-fade-in {
  animation: fade-in 1s ease-out 0.75s forwards;
}

/*
For Login form...
*/

.input-field {
  position: relative;
  width: 100%;
}

.input {
  width: 100%;
  padding: 12px 15px;
  font-size: 1rem;
  border-radius: 8px;
  border: 1px solid transparent;
  background-color: rgba(255, 255, 255, 0.15);
  color: #f0f0f0;
  letter-spacing: 1px;
  transition: border-color 0.3s ease, background-color 0.3s ease, color 0.3s ease;
}

.input::placeholder {
  color: transparent;
}

.input:focus {
  outline: none;
  border-color: #3b82f6; /* blue-500 */
  background-color: rgba(255, 255, 255, 0.4);
  color: #111;
}

.input-field label {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #d1d5db; /* Tailwind gray-300 */
  pointer-events: none;
  background-color: transparent;
  transition: all 0.3s ease;
  padding: 0 4px;
  font-weight: 500;
  user-select: none;
  font-size: 1rem;
}

.input:focus + label,
.input:not(:placeholder-shown) + label {
  top: -8px;
  left: 10px;
  font-size: 0.75rem;
  color: #3b82f6; /* blue-500 */
  font-weight: 600;
  background-color: rgba(255, 255, 255, 0.85);
  border-radius: 100px;
  padding: 0 6px;
}
</style>
