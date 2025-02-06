<template>
    <div class="free-details-con">
        <div v-if="recipe" class="free-card">
            <div v-if="!isEditMode">
                <!-- View Mode -->
                <div class="free-header">
                    <div 
                        class="free-image-con" 
                        :style="{ backgroundImage: `url(${getImageUrl(props.recipe.imagePath)})` }">
                    </div>
                    <div class="free-header-main-con">
                        <div class="free-label-con">
                            <h2>{{ recipe.recipeName || 'No Recipe Name' }}</h2>
                            <p><em>{{ recipe.cuisineType || 'No Cuisine Type' }}</em></p>
                        </div>
                        <div class="free-header-button-con">
                            <button @click="enableEditMode">
                                <img src="/public/images/edit.png" alt="Edit">
                                Edit
                            </button>
                            <button @click="deleteRecipe">
                                <img src="/public/images/delete.png" alt="Delete">
                                Delete
                            </button>
                        </div>
                    </div>
                    <div class="header-back-con">
                        <a href="javascript:void(0)" @click="$emit('change-section', 'recipe')">
                            <img src="/public/images/back to me.png" alt="Back">
                        </a>
                    </div>
                </div>
                <div class="free-card-main">
                    <div class="description">
                        <div class="description-label">
                            <img src="/public/images/descrept icon copy.png" alt="ingredients">
                            <h3>Description</h3>
                        </div>
                        <p>{{ recipe.description }}</p>
                    </div>
                    <div class="ingredients-con">
                        <div class="ingredients-label">
                            <img src="/public/images/ingred icon.png" alt="ingredients">
                            <h3>Ingredients</h3>
                        </div>
                        <div class="ingredients-list">
                            <ul>
                                <li v-for="(ingredient, index) in recipe.ingredients" :key="index">{{ ingredient }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="procedure-con">
                        <div class="procedure-label">
                            <img src="/public/images/navbar-recipe.png" alt="Procedure icon" />
                            <h3>Procedure</h3>
                        </div>
                        <div class="procedure-list">
                            <ul>
                                <li v-for="(step, index) in recipe.procedure" :key="index">{{ step }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="video-con">
                        <div class="video-label">
                            <img src="/public/images/video icon.png" alt="Video icon" />
                            <h3>Video</h3>
                        </div>
                        <div class="video-main">
                            <a :href="recipe.videoLink" target="_blank">Click to Watch Tutorial</a>
                        </div>
                    </div>
                </div>
            </div>
            <editRecipe v-else :recipe="recipe" @cancel-edit="isEditMode = false" @recipe-updated="handleRecipeUpdated" @recipe-deleted="handleRecipeDeleted" />
        </div>
        <div v-else>
            <p>No recipe details available. Please select a recipe.</p>
        </div>
    </div>
</template>


<script setup>
import { ref, reactive } from 'vue';
import axios from 'axios';
import editRecipe from '../../components/adminComponents/editRecipe.vue';

const props = defineProps({
    recipe: {
        type: Object,
        required: false,
        default: null,
    },
});

const isEditMode = ref(false);
const editData = reactive({ ...props.recipe });

function enableEditMode() {
    isEditMode.value = true;
}

function cancelEdit() {
    isEditMode.value = false;
}

function updateImage(event) {
    const file = event.target.files[0];
    if (file) {
        editData.imagePath = file; 
    }
}
function getImageUrl(imagePath) {
    if (!imagePath) {
        return '/public/images/the transparent.png'; // Default placeholder image
    }
    return `/storage/${imagePath}`; // Generate the public URL
}
function handleRecipeUpdated(updatedRecipe) {
    Object.assign(props.recipe, updatedRecipe); // Update the recipe data in the parent
    isEditMode.value = false;
}


async function deleteRecipe() {
    if (!confirm('Are you sure you want to delete this recipe?')) return;

    try {
        const response = await axios.delete(`/admin/recipes/${props.recipe.id}`);
        console.log('Recipe deleted:', response.data);
        $emit('recipe-deleted', props.recipe.id); // Emit recipe ID to notify the parent
    } catch (error) {
        console.error('Error deleting recipe:', error);
    }
}

function handleRecipeDeleted(recipeId) {
    // Remove the recipe from the list or update the UI
    if (props.recipe.id === recipeId) {
        props.recipe = null; // or handle this accordingly
    }
}



</script>


<style scoped>
.free-details-con {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.free-card {
    width: 60%;
    height: 80%;
    background-color: #E0E7FF;
    border-radius: 50px;
    box-shadow: 2px 2px 2px black;
}
.free-header {
    width: 100%;
    height: 40%;
    display: flex;
    flex-direction: row;
    margin-top: 20px;
    margin-left: 20px;
}
.free-image-con {
    width: 150px;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    border-radius: 50%;
}
.free-header-main-con {
    width: 60%;
    height: 100%;
    display: flex;
    flex-direction: row;
    margin-left: 20px;
    margin-top: 35px;
}
.free-label-con {
    width: auto;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.free-label-con h2 {
    font-size: 2em;
    margin: 0;
}
.free-label-con p {
    font-size: 1em;
    margin: 0;
}
.free-header-button-con {
    width: auto;
    height: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-top: 13px;
}

.free-header-button-con button {
    width: 70px;
    height: 25px;
    display: flex;
    flex-direction: row;
    margin-left: 10px;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    border: none;
    background-color: #435F77;
    color: white;
    cursor: pointer;
    font-size: 12px;
    margin-bottom: 15px;
}
.free-header-button-con button:hover {
    background-color: white;
    color: #435F77;
    border: #435F77 2px solid;
    font-weight: bold;
}   
.free-header-button-con button img {
    width: 20%;
    height: auto;
    margin-right: 2px;
    filter: invert(100%);
}
.header-back-con {
    width: 10%;
    height: 100%;
}
.header-back-con a img {
    width: 70%;
    height: auto;
    margin-top: 10px;
    margin-left: 10px;
}
.free-card-main {
    width: 100%;
    height: 380px;
    overflow-y: auto;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
    scrollbar-width: none;
}
.description, .ingredients-con, .procedure-con, .video-con {
    width: 90%;
    height: auto;
    margin-left: 30px;
    margin-top: 20px;
    flex-direction: column;
}
.description-label, .ingredients-label, .procedure-label, .video-label {
    width: 100%;
    height: 50px;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 30px;
}
.description-label h3, .ingredients-label h3, .procedure-label h3, .video-label h3 {
    font-size: 1.2em;
}
.description p{
    text-align: justify;
}
.description-label img, .ingredients-label img, .procedure-label img, .video-label img {
    width: 30px;
    height: auto;
}
.ingredients-list, .procedure-list {
    width: 100%;
    height: auto;
    display: flex;
    flex-direction: column;
}
.ingredients-list ul li, .procedure-list ul li {
    font-size: 1em;
    margin: 0;
    margin-left: 20%;
}
.video-con {
    width: 100%;
    height: auto;
}
.video-main {
    width: 100%;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
