import { reactive, readonly } from 'vue'
import axios from 'axios'

const state = reactive({
    user: null,
    isAuthenticated: false,
    loading: false,
})

async function fetchUser() {
    state.loading = true;
    try {
        const { data } = await axios.get('/api/user', { withCredentials: true });
        state.user = data;
        state.isAuthenticated = true;
    } catch (error) {
        if (error.response && error.response.status === 401) {
            // Unauthorized means no user logged in, no need to show error
            state.user = null;
            state.isAuthenticated = false;
        } else {
            // Log or handle other errors if you want
            console.error('Fetch user failed:', error);
        }
    } finally {
        state.loading = false;
    }
}

async function login(credentials) {
    console.log('Fetching CSRF...');
    await axios.get('/sanctum/csrf-cookie', { withCredentials: true });
    console.log('Posting login...');
    const loginResponse = await axios.post('/api/login', credentials, {
        withCredentials: true
    });
    console.log('Login response', loginResponse);
    await fetchUser(); // refresh user state after login
}

async function logout() {
    await axios.post('/api/logout', {}, { withCredentials: true })
    state.user = null
    state.isAuthenticated = false
}

export function useAuth() {
    return {
        state: readonly(state),
        login,
        logout,
        fetchUser,
    }
}
