<template>
  <Header 
    :notifications="notifications"
    @toggleProfile="toggleProfile"
  />
  <main class="chefMainDashboard" v-if="!viewAllMenu && !isMyRecipeVisible && !recipeDetailsVisible && !addRecipeVisible && !showProfile">
    <div class="tagline-con">
      <h1>Flavor Made Easy</h1>
      <img src="/public/images/the transparent.png" alt="Recipe Image">
    </div>

    <div class="view-button-con">
      <button @click="toggleViewMenu">View All Menu</button>
      <button @click="toggleMyRecipe">My Recipe</button>
    </div>
    <div class="recipe-Con">
      <RecipeCard
        v-for="(recipe, index) in latestRecipes"
        :key="index"
        :recipe="recipe"
        :currentUserId="currentUserId"
        :likedRecipes="likedRecipes"
        @toggleReaction="toggleReaction"
        @viewRecipeDetails="viewRecipeDetails"
      />
    </div>
  </main>
  <ChefProfile 
    v-if="showProfile && !viewAllMenu && !isMyRecipeVisible && !recipeDetailsVisible"
      :chef="chef" 
      :recipes="recipes"
      :reacts="reacts"
      :currentUserId="currentUserId"
      :likedRecipes="likedRecipes"
      @toggleProfile="toggleProfile"
      @toggleRecipeDetails="toggleRecipeDetails"
    />

  <viewAllRecipe 
    v-if="viewAllMenu"
    :recipes="recipes"
    :currentUserId="currentUserId"
    :likedRecipes="likedRecipes"
    :reacts="reacts"
    @toggleViewMenu="toggleViewMenu"
    @toggleMyRecipe="toggleMyRecipe"
    @toggleRecipeDetails="toggleRecipeDetails"
  />

  <myRecipe 
    v-if="isMyRecipeVisible"
    :recipes="recipes"
    :currentUserId="currentUserId"
    :reacts="reacts"
    @toggleViewMenu="toggleViewMenu"
    @toggleMyRecipe="toggleMyRecipe"
    @toggleRecipeDetails="toggleRecipeDetails"
    @showAddRecipe="showAddRecipe"
  />

  <RecipeDetails
    v-if="recipeDetailsVisible && !viewAllMenu && !isMyRecipeVisible && selectedRecipe"
    :recipe="selectedRecipe"
    :purchases="purchases"
    @close="closeRecipeDetails"
  />

  <addRecipe
    v-if="addRecipeVisible"
    @toggleMyRecipe="toggleMyRecipe"
  />
</template>

<script setup>
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Header from './ChefHeader.vue';
import viewAllRecipe from '@/components/viewAllRecipe.vue';
import myRecipe from '@/components/chefComponents/content/myRecipe.vue';
import RecipeCard from '@/components/RecipeCard.vue';
import addRecipe from '@/components/chefComponents/content/addRecipe.vue';
import RecipeDetails from '@/components/recipeDetails.vue';
import ChefProfile from '@/components/chefComponents/content/chefProfile.vue';

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
  notifications: {
    type: Array,
    required: true,
  },
  purchases: {
    type: Array,
    required: true,
  },
  chef: {
    type: Object,
    required: true,
  },
});

const likedRecipes = computed(() => {
  const likesMap = {};
  props.reacts.forEach((react) => {
    if (react.account_id === props.currentUserId && react.reaction === 'like') {
      likesMap[react.recipe_id] = true;
    }
  });
  return likesMap;
});

const latestRecipes = computed(() => {
  return props.recipes
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at)) 
    .slice(0, 5); 
});

const viewAllMenu = ref(false);
const isMyRecipeVisible = ref(false);
const recipeDetailsVisible = ref(false);
const addRecipeVisible = ref(false);
const selectedRecipe = ref(null);
const showProfile = ref(false);
const chefMain = ref(true);

function toggleViewMenu() {
  viewAllMenu.value = !viewAllMenu.value;
  isMyRecipeVisible.value = false;
  recipeDetailsVisible.value = false;
  addRecipeVisible.value = false;
  showProfile.value = false;
}

function toggleMyRecipe() {
  isMyRecipeVisible.value = !isMyRecipeVisible.value;
  viewAllMenu.value = false;
  recipeDetailsVisible.value = false;
  addRecipeVisible.value = false;
  showProfile.value = false;
}

function toggleRecipeDetails() {
  recipeDetailsVisible.value = !recipeDetailsVisible.value;
  viewAllMenu.value = false;
  isMyRecipeVisible.value = false;
  addRecipeVisible.value = false;
  showProfile.value = false;
}

function showAddRecipe() {
  addRecipeVisible.value = true;
  viewAllMenu.value = false;
  isMyRecipeVisible.value = false;
  recipeDetailsVisible.value = false;
  showProfile.value = false;
}

function toggleProfile(){
  showProfile.value = !showProfile.value;
  if (showProfile.value) {
    viewAllMenu.value = false;
    isMyRecipeVisible.value = false;
    recipeDetailsVisible.value = false;
    addRecipeVisible.value = false;
  }
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

function closeRecipeDetails() {
  recipeDetailsVisible.value = false;
  selectedRecipe.value = null;
}

function viewRecipeDetails(recipe) {
  selectedRecipe.value = recipe;
  toggleRecipeDetails();
}
</script>

  
  






<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rouge+Script&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
* {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}
body{
    margin: 0;
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
main{
    width: 100%;
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    overflow: hidden;
}
.tagline-con{
    width: 70%;
    height: 180px;
    margin-top: 90px;
    background-color: #E0E7FF;
    display: flex;
    flex-direction: row;
    position: relative;
    border-radius: 20px;
    align-items: center;
}
.tagline-con h1{
    font-size: 5.5em;
    font-family: 'Rouge Script', cursive;
    font-weight: 100;
    display: flex;
    align-self: center;
    margin-left: 70px;
}
.tagline-con img{
    width: 45%;
    height: auto;
    position: absolute;
    bottom: -5px;
    right: 0;
}
.view-button-con{
    width: 70%;
    height: 100px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.view-button-con button{
    background-color: #435F77;
    border: none;
    width: 130px;
    height: 40px;
    border-radius: 20px;
    color: white;
    font-weight: bold;
    box-shadow: 3px 2px 3px black;
}
.view-button-con button:hover{
    background-color: #E0E7FF;
    color: #435F77;
    border-right: #435F77 2px solid;
    border-bottom: #435F77 2px solid;
    cursor: pointer;
}
.recipe-Con{
    width: 100%;
    height: auto;
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    justify-content: center;
    margin-bottom: 20px;
}
</style>