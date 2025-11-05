<template>
    <Frontend>
        <div
            class="relative flex flex-col items-center justify-center min-h-screen px-8 py-12 mx-auto bg-center bg-cover md:flex-row"
            :style="{ backgroundImage: 'url(/photo/home_background.jpg)' }"
        >
            <!-- Overlay -->
            <div class="absolute inset-0 z-0 bg-black bg-opacity-60"></div>

            <div class="relative z-10 flex flex-row items-center justify-center w-full max-w-6xl gap-16 px-4">
                <!-- Headline -->
                <div
                    class="flex-shrink-0 font-extrabold tracking-widest text-white animate-fade-in-down"
                    style="min-width: 320px;"
                >
                    <p class="mb-2 text-3xl leading-tight text-left whitespace-normal md:text-5xl">
                    Welcome to
                    </p>
                    <p class="text-4xl leading-tight text-left text-transparent whitespace-normal md:text-6xl bg-gradient-to-r from-blue-400 to-purple-600 bg-clip-text">
                    the MPI Website
                    </p>
                </div>

                <!-- Form Container -->
                <div class="flex-shrink-0 w-full max-w-md form-container">
                    <div :class="['card', cardClass, { flipped: showRegister }]">
                        <!-- Front: Login -->
                        <div class="card-face front">
                            <!-- Login Form -->
                            <div
                                class="relative z-10 flex flex-col items-center w-full max-w-sm p-10 shadow-lg bg-white/20 backdrop-blur-lg rounded-xl animate-fade-in"
                                >
                                <form @submit.prevent="login" class="flex flex-col w-full gap-6">
                                    <div class="input-field">
                                    <input
                                        v-model="username"
                                        type="text"
                                        id="username"
                                        placeholder=""
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
                                        placeholder=""
                                        required
                                        autocomplete="current-password"
                                        class="input"
                                        @keydown="checkCapsLock"
                                        @keyup="checkCapsLock"
                                    />
                                    <label for="password" class="text-white">Password</label>
                                    <p v-if="capsLockOn" class="mt-1 text-sm text-yellow-400 select-none">
                                        ⚠️ Caps Lock is ON
                                    </p>
                                    </div>

                                    <button
                                    type="submit"
                                    class="w-full py-3 mt-10 font-semibold text-white transition bg-blue-600 rounded-md hover:bg-blue-700"
                                    >
                                    LOGIN
                                    </button>
                                    <!--p v-if="error" class="mt-2 font-medium text-center text-red-400">{{ error }}</p-->
                                    <p
                                      v-show="loginFailed"
                                      class="px-3 py-1 text-sm font-medium text-center text-red-700 border border-red-700 rounded-md select-none bg-white/10 backdrop-blur-md animate-fade-in-notif-login"
                                      style="max-width: 280px; margin: 0 auto 1rem auto; text-shadow: 0 0 6px rgba(255, 0, 0, 0.3);"
                                    >
                                      Username and Password do not match<br>Please try again.
                                    </p>
                                </form>

                                <p class="mt-6 text-sm text-center text-white select-none">
                                    Not registered yet?
                                    <button
                                        @click="switchToRegister"
                                        class="font-semibold text-blue-400 underline hover:text-blue-600 focus:outline-none"
                                        aria-label="Go to registration form"
                                    >
                                        Register here
                                    </button>
                                </p>
                            </div>
                        </div>

                        <!-- Back: Register -->
                        <div class="card-face back">
                            <!-- Registration modal or form toggle -->
                                <div
                                    class="relative w-full max-w-sm p-10 shadow-lg bg-white/20 backdrop-blur-lg rounded-xl"
                                    role="dialog"
                                    aria-modal="true"
                                >
                                    <form @submit.prevent="register" class="flex flex-col w-full gap-6 text-white">
                                    <div class="flex gap-4">
                                        <div class="flex-1 input-field">
                                            <input
                                            v-model="registerForm.firstName"
                                            type="text"
                                            id="reg-first-name"
                                            placeholder=" "
                                            required
                                            class="input"
                                            />
                                            <label for="reg-first-name" class="text-white">First Name</label>
                                            <p class="mt-1 ml-2 text-xs text-gray-300">e.g. Juan</p>
                                        </div>

                                        <div class="flex-1 input-field">
                                            <input
                                            v-model="registerForm.surname"
                                            type="text"
                                            id="reg-surname"
                                            placeholder=" "
                                            required
                                            class="input"
                                            />
                                            <label for="reg-surname" class="text-white">Surname</label>
                                            <p class="mt-1 ml-2 text-xs text-gray-300">e.g. Reyes</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-4">
                                        <div class="flex-1">
                                            <div class="input-field">
                                                <input
                                                v-model="registerForm.employee_id"
                                                type="text"
                                                id="reg-empId"
                                                placeholder=" "
                                                required
                                                class="input"
                                                />
                                                <label for="reg-empId" class="text-white">Employee ID</label>
                                            </div>
                                            <p class="mt-1 ml-2 text-xs text-gray-300">e.g. 006645</p>
                                        </div>

                                        <div class="flex-1">
                                            <div class="input-field">
                                                <input
                                                v-model="registerForm.plant"
                                                type="text"
                                                id="reg-plant"
                                                placeholder=" "
                                                required
                                                class="input"
                                                />
                                                <label for="reg-plant" class="text-white">Plant</label>
                                            </div>
                                            <p class="mt-1 ml-2 text-xs text-gray-300">e.g. Plant 8, Plant 7</p>
                                        </div>
                                    </div>

                                    <div class="input-field">
                                        <input
                                        v-model="registerForm.username"
                                        type="text"
                                        id="reg-username"
                                        placeholder=" "
                                        required
                                        class="input"
                                        />
                                        <label for="reg-username" class="text-white">Username</label>
                                    </div>

                                    <div class="input-field">
                                        <input
                                        v-model="registerForm.email"
                                        type="email"
                                        id="reg-email"
                                        placeholder=" "
                                        class="input"
                                        />
                                        <label for="reg-email" class="text-white">Email</label>
                                    </div>

                                    <div class="input-field">
                                        <input
                                        v-model="registerForm.password"
                                        type="password"
                                        id="reg-password"
                                        placeholder=" "
                                        required
                                        class="input"
                                        @keydown="checkCapsLock"
                                        @keyup="checkCapsLock"
                                        />
                                        <label for="reg-password" class="text-white">Password</label>
                                    </div>

                                    <div class="input-field">
                                        <input
                                        v-model="registerForm.password_confirmation"
                                        type="password"
                                        id="reg-password-confirm"
                                        placeholder=" "
                                        required
                                        class="input"
                                        @keydown="checkCapsLock"
                                        @keyup="checkCapsLock"
                                        />
                                        <label for="reg-password-confirm" class="text-white">Confirm Password</label>
                                    </div>

                                    <p v-if="capsLockOn" class="mt-1 text-sm text-yellow-400 select-none">
                                        ⚠️ Caps Lock is ON
                                    </p>
                                    <!-- v-if="registerSuccess" -->
                                    <p
                                    v-if="registerSuccess"
                                    class="px-3 py-1 text-sm font-medium text-center text-green-400 border border-green-400 rounded-md select-none bg-white/10 backdrop-blur-md animate-fade-in-notif"
                                    style="max-width: 280px; margin: 0 auto 1rem auto;"
                                    >
                                    Registration successful!<br>You can now log in.
                                    </p>

                                    <button
                                        type="submit"
                                        class="w-full py-3 font-semibold text-white transition bg-blue-600 rounded-md hover:bg-blue-700"
                                    >
                                        REGISTER
                                    </button>
                                    </form>

                                    <p v-if="registerError" class="mt-4 font-medium text-center text-red-400">{{ registerError }}</p>

                                    <p class="mt-4 text-sm text-center text-white">
                                        Already have an account?
                                        <button @click="switchToLogin" class="font-semibold text-blue-400 underline hover:text-blue-600 focus:outline-none">Login here</button>
                                    </p>
                                </div>
                        </div>

                        <!-- Third Face: Success -->
                        <div class="card-face success">
                          <div
                            class="relative z-10 flex flex-col items-center w-full max-w-sm p-10 mt-24 text-white shadow-lg select-none bg-green-700/30 backdrop-blur-lg rounded-xl animate-fade-in"
                            role="alert"
                            aria-live="assertive"
                          >
                            <h2 class="mb-4 text-3xl font-extrabold drop-shadow-md">
                              <span class="gradient-metallic" data-text="Login Successful!">Login Successful!</span>
                            </h2>
                            <p class="mb-6 text-base text-center drop-shadow-sm">
                              You can now access different sections via the navigation bar at the top.
                            </p>
                            <p v-if="state.user && state.user.access_type"
                                class="text-sm text-white/90 font-medium backdrop-blur-sm bg-white/10 px-4 py-2 rounded-lg border border-white/20 shadow-sm">
                                    Current Access Type:
                                <span class="font-semibold text-cyan-100">{{ state.user.access_type }}</span>
                            </p>
                          </div>
                        </div>

                    </div>
                </div> <!-- end of form container -->
            </div>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref, computed, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import DotsLoader from '@/Components/DotsLoader.vue';
import axios from 'axios';
import { useAuth } from '@/Composables/useAuth.js';
import { post } from 'jquery';

const { state, fetchUser } = useAuth();

// Function to check authentication
const checkAuthentication = async () => {
    try {

        const start = Date.now();
        const timeout = 5000; // 5 seconds

        while (!state.user) {
            if (Date.now() - start > timeout) {
                console.error('Auth timeout: user data failed to load within 5 seconds.');
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

        await logUserLogin();

        return true; // Indicate authenticated
    } catch (error) {
        console.error('Error checking authentication:', error);
        Inertia.visit('/'); // Redirect on error
        return false; // Indicate not authenticated
    }
};

const logUserLogin = async () => {
    try{
        const responseUserLogin = await axios.post('/api/userlogs', {
            user: state.user.firstName + " " + state.user.surname,
            event: 'has logged in',
            section: 'System',
        });

        //console.log('responseUserLogin-data: ',responseUserLogin.data);
    }catch(error){
        console.error('responseUserLogin post request failed: ',error);
    }
}

const username = ref('');
const password = ref('');
const error = ref('');
const showRegister = ref(false);
const registerError = ref('');
const capsLockOn = ref(false);
const registerSuccess = ref(false);
const loginFailed = ref(false);
const loginSuccess = ref(false);

// Computed property for the card's class
const cardClass = computed(() => {
  if (state.isAuthenticated) return 'face-success';
  return showRegister.value ? 'face-back flipped' : 'face-front';
});

function checkCapsLock(e) {
    capsLockOn.value = e.getModifierState && e.getModifierState('CapsLock');
}

const switchToLogin = () => {
  loginSuccess.value = false;  // Reset success when switching forms
  showRegister.value = false;
  resetRegisterForm();
  username.value = '';
  password.value = '';
};

const switchToRegister = () => {
  loginSuccess.value = false;  // Reset success here too
  showRegister.value = true;
  username.value = '';
  password.value = '';
};

// Registration form fields with updated keys
const registerForm = ref({
    firstName: '',       // Updated from name
    surname: '',         // Added to match backend changes
    employee_id: '',     // Fixed typo from employeed_id
    plant: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const resetRegisterForm = () => {
    registerForm.value = {
        firstName: '',
        surname: '',
        employee_id: '',
        plant: '',
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
    };
};

// Log the registration form state for debugging
console.log(JSON.stringify(registerForm.value, null, 2));

// CSRF token setup (only needed once globally, ideally)
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const login = async () => {
  try {
    await axios.get('/sanctum/csrf-cookie');

    const response = await axios.post('/api/login', {
      username: username.value,
      password: password.value,
    });

    localStorage.setItem('access_token', response.data.access_token);
    localStorage.setItem('user', JSON.stringify(response.data.user));
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

    loginSuccess.value = true;    // ✅ Set here on success
    showRegister.value = false;   // Hide register form

    // Optionally reset errors
    loginFailed.value = false;
    error.value = '';

    // Optionally fetchUser or redirect here if needed
    await fetchUser();

    await checkAuthentication();

  } catch (e) {
    loginSuccess.value = false;   // Reset here on failure
    loginFailed.value = true;
    password.value = '';
    setTimeout(() => {
      loginFailed.value = false;
      error.value = '';
    }, 3000);
  }
};

const register = async () => {
    registerError.value = '';
    registerSuccess.value = false;

    if (registerForm.value.password !== registerForm.value.password_confirmation) {
        registerError.value = 'Passwords do not match.';
        return;
    }

    try {
        // CSRF for security
        await axios.get('/sanctum/csrf-cookie');

        // Submit registration form
        await axios.post('/api/register', { ...registerForm.value });

        registerSuccess.value = true;

        setTimeout(() => {
            showRegister.value = false;
            registerSuccess.value = false;
            // Reset the form
            Object.keys(registerForm.value).forEach((key) => {
                registerForm.value[key] = '';
            });
        }, 1500);
    } catch (e) {
        console.error(e.response?.data);
        registerError.value = e.response?.data?.message || 'Registration failed';
        registerSuccess.value = false;
    }
};
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

@keyframes fade-in-out {
  0% {
    opacity: 0;
  }
  20% {
    opacity: 1;
  }
  70% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}

.animate-fade-in-notif-login {
  animation: fade-in-out 3s ease forwards;
}

.animate-fade-in-notif {
    animation: fade-in 0.5s ease forwards;
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
  color: #fff;
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

/*Flipping effect -- */

.form-container {
  perspective: 1200px;
  width: 100%;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
  margin-top: -25.5rem; /* pull it up */
}

.card {
  position: relative;
  width: 100%;
  height: auto; /* Adapt to content */
  transition: transform 0.8s cubic-bezier(0.4, 0.2, 0.2, 1);
  transform-style: preserve-3d;
}

.card.flipped {
  transform: rotateY(180deg);
}

.card-face {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  transition: opacity 0.3s ease, z-index 0.3s ease;
  opacity: 0;
  pointer-events: none;
}

.card.face-front .card-face.front,
.card.face-back .card-face.back,
.card.face-success .card-face.success {
  opacity: 1;
  pointer-events: auto;
  z-index: 2;
}

.card-face.back {
  transform: rotateY(180deg);
}

.card-face.success {
  transform: rotateY(360deg); /* Logical position for the third face */
}

.gradient-metallic {
  position: relative;
  display: inline-block;
  font-weight: 700;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  color: transparent;
  background: linear-gradient(135deg, #d1fae5, #6ee7b7, #10b981, #059669);
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;

  text-shadow:
    0 0 1px rgba(16, 185, 129, 0.3),
    0 0 3px rgba(5, 150, 105, 0.2);

  overflow: hidden;
}

.gradient-metallic::before {
  content: attr(data-text);
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;

  background: linear-gradient(
    120deg,
    rgba(255, 255, 255, 0) 40%,
    rgba(255, 255, 255, 0.6) 50%,
    rgba(255, 255, 255, 0) 60%
  );

  background-size: 200% auto;
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;

  animation: inner-shine 2s linear infinite;
  pointer-events: none;
}

@keyframes inner-shine {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

.god-tier-shine-effect {
  position: relative;
  overflow: hidden;
}

.god-tier-shine-effect::after {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 200%;
  height: 100%;

  background: linear-gradient(
    120deg,
    rgba(255, 255, 255, 0) 40%,
    rgba(255, 255, 255, 0.6) 50%,
    rgba(255, 255, 255, 0) 60%
  );

  animation: god-tier-shine 2.5s ease-in-out infinite;
  pointer-events: none;

  /* Key trick: only show inside text */
  mix-blend-mode: lighten;
  filter: brightness(1.5);
}

</style>
