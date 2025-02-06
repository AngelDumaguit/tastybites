<template>
<main v-if="!recipeDetailsVisible && AllMyRecipe">
    <div class="back-con">
    <a href="#" @click.prevent="goBackToChefDashboard">
        <img src="/public/images/back to me.png" alt="Back to me">
    </a>
    <div class="back-con-button-con">
        <a href="#" @click="viewAllMenu">View All Menu</a>
        <a href="#">My Recipes</a>
    </div>
    </div>
    <div class="all-tastybites-recipe">
    <div class="menu-list-con">
        <button @click="showAddRecipe">
            <div class="recipe-add-image-con">
                <img src="/public/images/add.png" alt="images">
            </div>
            <div class="recipe-label-con">
                <div class="recipe-label">
                    <h3>Add Recipe</h3>
                </div>
            </div>
        </button>
        <RecipeCard
            v-for="recipe in userRecipes" 
            :key="recipe.id" 
            :recipe="recipe"
            :currentUserId="currentUserId"
            :likedRecipes="likedRecipes"
            @toggleReaction="toggleReaction"
            @viewRecipeDetails="viewRecipeDetails"
        />
    </div>
    </div>
</main>
<RecipeDetails
  v-if="recipeDetailsVisible"
  :recipe="selectedRecipe"
  :currentUserId="currentUserId"
  @close="closeRecipeDetails"
/>
</template>
  
<script setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import RecipeCard from '@/components/RecipeCard.vue';
import RecipeDetails from '@/components/recipeDetails.vue';

const props = defineProps({
    recipes: {
        type: Array,
        required: true,
    },
    currentUserId: {
        type: Number,
        required: true,
    },
    reacts: {
        type: Array,
        required: true,
    },


});

const selectedRecipe = ref(null);
const recipeDetailsVisible = ref(false);
const AllMyRecipe = ref(true)

const emit = defineEmits(['toggleViewMenu', 'toggleMyRecipe', 'toggleRecipeDetails']);

function goBackToChefDashboard() {
    emit('toggleMyRecipe');
}

function viewAllMenu() {
emit('toggleViewMenu');
}

const likedRecipes = computed(() => {
const likesMap = {};
props.reacts.forEach((react) => {
    if (react.account_id === props.currentUserId && react.reaction === 'like') {
    likesMap[react.recipe_id] = true;
    }
});
return likesMap;
});

function toggleRecipeDetails() {
recipeDetailsVisible.value = !recipeDetailsVisible.value;
viewAllMenu.value = false;
isMyRecipeVisible.value = false;
}

function toggleReaction(recipe) {
    Inertia.post(route('recipe.toggleReaction', { recipe: recipe.id }), {}, {
        preserveState: true,
        onSuccess: ({ props }) => {
        const isLiked = likedRecipes.value[recipe.id];
        likedRecipes.value[recipe.id] = !isLiked;
        recipe.reacts_count = props.likeCount;
        },
        onError: (error) => {
        console.error('Error toggling reaction:', error);
        },
    });
}

function showAddRecipe() {
    emit('showAddRecipe'); 
}
const userRecipes = computed(() => {
    return props.recipes.filter(recipe => recipe.account_id === props.currentUserId);
});

function closeRecipeDetails() {
    recipeDetailsVisible.value = false;
    selectedRecipe.value = null;
    
}

function viewRecipeDetails(recipe) {
    selectedRecipe.value = recipe;
    recipeDetailsVisible.value = true;
}


</script>
  
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rouge+Script&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
main{
    width: 100%;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
.back-con{
    width: 100%;
    height: 70px;
    display: flex;
    align-items: center;
    margin-top: 20px;
}
.back-con img{
    width: 50px;
    height: auto;
    margin-left: 30px;
}
.back-con-button-con{
    position: absolute;
    width: 300px;
    height: 100%;
    left: 43%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}
.back-con-button-con a{
    width: 45%;
    height: 5%;
    background-color: #435F77;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    margin: 10px;
    font-weight: bold;
    font-size: .8em;
}
.all-tastybites-recipe{
    width: 100%;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
.menu-list-con{
    width: 85%;
    height: 550px;
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    justify-content: start;
    overflow: auto;
    position: relative;
    gap: 10px;
}
.menu-list-con button{
    width: 170px;
    height: 190px;
    margin: 20px;
    border-radius: 20px;
    box-shadow: 2px 3px 5px black;
    background-color: #E0E7FF;
    text-decoration: none;
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
    height: 40%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.recipe-label {
    width: 100%;
    height: 30%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.recipe-label h3 {
    color: black;
    font-size: 1em;
}
</style>