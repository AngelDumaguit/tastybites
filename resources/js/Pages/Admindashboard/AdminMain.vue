<template>
    <div class="main-body">
        <Sidebar @change-section="navigateTo" :activeSection="activeSection" />

        <div v-if="activeSection === 'dashboard'" class="dashboard-content">
            <Dashboard 
                :weeklyRecipes="weeklyRecipes"
            />
        </div>
        <div v-else-if="activeSection === 'chef'" class="chef-content">
            <Chef :chefs="chefs" />
        </div>
        <div v-else-if="activeSection === 'recipe'" class="recipe-content">
            <Recipe 
                @change-section="navigateTo" 
                :activeSection="activeSection"
                :recipes="recipes"
                :reacts="reacts"
                :currentUserId="currentUserId"
            />
        </div>
        <div v-else-if="activeSection === 'users'" class="user-content">
            <User :users="users" :accounts="accounts" />
        </div>
        <div v-else-if="activeSection === 'addRecipe'" class="add-recipe-content">
            <AddRecipe 
                @change-section="navigateTo"
                :activeSection="activeSection"
            />
        </div>
        <div v-else-if="activeSection === 'recipeDetails'" class="recipe-details">
            <RecipeDetails 
                @change-section="navigateTo"
                :recipe="selectedRecipe" 
            />
        </div>
        <div v-else>
            <p>Invalid section. Please select a valid option from the sidebar.</p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import Sidebar from '../../components/adminComponents/sidebar.vue';
import Dashboard from '../../components/adminComponents/Main.vue';
import Chef from '../../components/adminComponents/Chef.vue';
import Recipe from '../../components/adminComponents/Recipe.vue';
import User from '../../components/adminComponents/User.vue';
import AddRecipe from '../../components/adminComponents/AddRecipe.vue';
import RecipeDetails from '../../components/adminComponents/RecipeDetails.vue';

const allowedSections = ['dashboard', 'chef', 'recipe', 'users', 'addRecipe', 'recipeDetails'];
const activeSection = ref(null); 
const selectedRecipe = ref(null);

// Fetching query parameters from the URL when the component is mounted
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const sectionFromQuery = urlParams.get('section');

    // Set the active section or default to 'dashboard' if not valid
    activeSection.value = allowedSections.includes(sectionFromQuery)
        ? sectionFromQuery
        : 'dashboard';
});

// Watch for changes in activeSection and update the URL
watch(activeSection, (newSection) => {
    const url = new URL(window.location.href);
    url.searchParams.set('section', newSection);
    window.history.pushState({}, '', url);
});

// Function to navigate between sections and pass recipe data if available
function navigateTo(section, recipe = null) {
    if (allowedSections.includes(section)) {
        activeSection.value = section;
        selectedRecipe.value = recipe; // Store the selected recipe (if any)
    }
}

// Props for receiving data from the parent component
const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    accounts: {
        type: Array,
        required: true,
    },
    chefs: {
        type: Array,
        required: true,
    },
    recipes: {
        type: Array,
        required: true,
    },
    reacts: {
        type: Array,
        required: true,
    },
    currentUserId: {
        type: Number,
        required: true,
    },
    weeklyRecipes: {
        type: Array,
        required: true,
    },
});
</script>






<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rouge+Script&display=swap');
.main-body{
    height: 100vh;
    width: 100%;
    display: flex;
    flex-direction: row;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
}
.dashboard-content,.chef-content,.recipe-content,.user-content, .recipe-details,.add-recipe-content{
    height: 100%;
    width: 82%;
}
</style>