<template>
    <Frontend>
        <div class="flex flex-col items-center justify-center mt-12 font-bold">
            <label>Lists of reports are located here</label>

        </div>
        <div :class="vueClass">
            <p v-for="item in items" :key="item">
                {{ item }}
            </p>
        </div>
    </Frontend>
</template>

<script setup>
import Frontend from '@/Layouts/FrontendLayout.vue';
import { ref } from 'vue';


const date = "Feb 3, 2025";

const vueClass = ref('pinkBG');
const inpText = ref(date);
const items = ref(['Apple', 'Banana', 'Cherry']);

</script>

<style scoped>
.pinkBG {
    background-color: lightpink;
}
</style>
