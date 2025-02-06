<template>
    <div class="recipe-main-con">
        <div class="recipe-main">
            <a class="recipe-add-card" href="javascript:void(0)" @click="emitSection('addRecipe')">
                <div class="recipe-add-image-con">
                    <img src="/public/images/add.png" alt="images">
                </div>
                <div class="recipe-label-con">
                    <div class="recipe-label">
                        <h3>Add Recipe</h3>
                    </div>
                </div>
            </a>
            <a class="recipe-card" v-for="(recipe, index) in recipes" :key="recipe.id">
                <img v-if="recipe.freePrem === 'Premium'" class="crown-image" src="/public/images/premium.png" alt="Premium">
                <div class="recipe-image-con" :style="{ backgroundImage: `url(${getImagePath(recipe.imagePath)})` }">
                    
                </div>
                <div class="recipe-label-con">
                    <div class="recipe-label">
                        <h2>{{ recipe.recipeName }}</h2>
                        <h6><em>{{ recipe.cuisineType }}</em></h6>
                        <p v-if="recipe.account && recipe.account.chef">
                            Chef: {{ recipe.account.chef.username }}
                        </p>
                        <p v-else>
                            Posted by admin
                        </p>
                    </div>
                </div>
                <div class="recipe-button-con">
                    <button class="recipe-button" @click="viewRecipeDetails(recipe)">View Recipe</button>
                </div>
            </a>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

// Props
const props = defineProps({
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
});

// Emits
const emit = defineEmits(['change-section']);

const likedRecipes = computed(() => {
    const likesMap = {};
    props.reacts.forEach((react) => {
        if (react.account_id === props.currentUserId && react.reaction === 'like') {
            likesMap[react.recipe_id] = true;
        }
    });
    return likesMap;
});


function emitSection(section, recipe = null) {
    emit('change-section', section, recipe);
}

function viewRecipeDetails(recipe) {
    emit('change-section', 'recipeDetails', recipe);
}
function getImagePath(path) {
    // Assuming your images are stored in the 'public/storage' directory
    return path ? `/storage/${path}` : '/images/default.png';
}

function toggleReaction(recipe) {
    Inertia.post(route('recipe.toggleReaction', { recipe: recipe.id }), {}, {
        preserveState: true,
        onSuccess: ({ props }) => {
            // Update like state
            const isLiked = likedRecipes.value[recipe.id];
            likedRecipes.value[recipe.id] = !isLiked;

            // Update like count from server response
            recipe.reacts_count = props.likeCount;
        },
        onError: (error) => {
            console.error('Error toggling reaction:', error);
        },
    });
}

</script>

<style scoped>
.recipe-main-con {
    height: 100%;
    width: 100%;
}

.recipe-main {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    overflow: auto;
}

.recipe-main a {
    color: black;
}

.recipe-add-card {
    width: 170px;
    height: 200px;
    margin: 20px;
    border-radius: 20px;
    box-shadow: 2px 3px 5px black;
    background-color: #E0E7FF;
    text-decoration: none;
    cursor: pointer;
    padding: 20px;
}

.recipe-add-image-con {
    width: 100%;
    height: 60%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.recipe-add-image-con img {
    width: auto;
    height: 100%;
}

.recipe-label-con {
    width: 100%;
    height: 30%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.recipe-label {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.recipe-label h2 {
    color: black;
    font-size: 1em;
    font-weight: bold;
    margin: 0
}
.recipe-label h6 {
    color: black;
    font-size: 0.8em;
    margin: 0;
}

.recipe-label p {
    color: black;
    font-size: .7em;
    margin: 0;
    font-family: 400;
}

.recipe-card {
    width: 170px;
    height: 200px;
    margin: 20px;
    border-radius: 20px;
    box-shadow: 2px 3px 5px black;
    background-color: #E0E7FF;
    text-decoration: none;
    padding: 20px;
    position: relative;
    display: flex;
    align-items: center;
    flex-direction: column;
}

.recipe-image-con {
    width: 70%;
    height: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    border-radius: 50%;
}


.recipe-cuisine h6 {
    font-size: 0.6em;
}

.recipe-button-con {
    width: 100%;
    height: 20%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}

.recipe-button-con button {
    width: 90px;
    height: 80%;
    margin: 5px;
    border-radius: 20px;
    border: none;
    background-color: #435F77;
    color: white;
    font-size: .7em;
    cursor: pointer;
}

.recipe-button-con button:hover {
    background-color: white;
    border: #435F77 2px solid;
    color: #435F77;
}

.recipe-react-con {
    display: flex;
    align-items: center;
    gap: 10px;
}

.recipe-react-con button {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6em;
    background-color: transparent;
    border: none;
}
.crown-image{
    position: absolute;
    height: 30px;
    width: auto;
    right:10px;
    top: 10px;
}
</style>
