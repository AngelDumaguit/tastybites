<template>
    <body>
      <Header 
        :notifications="notifications"
        @toggleProfile="toggleProfile"
      />
      <main v-if="!viewAllMenu && !recipeDetailsVisible && !ProfileVisible && userMain">
        <div class="tagline-con">
          <h1>Flavor Made Easy</h1>
          <img src="/public/images/the transparent.png" alt="Recipe Image">
        </div>
        <div class="view-button-con">
          <button @click="toggleViewMenu">View All Menu</button>
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
      <Profile v-if="ProfileVisible && !viewAllMenu && !recipeDetailsVisible"
        :user="user"
        :recipes="recipes"
        :reacts="reacts"
        :currentUserId="currentUserId"
        :likedRecipes="likedRecipes"
        @toggleProfile="toggleProfile"
        @toggleRecipeDetails="toggleRecipeDetails"
      />
      <viewAllRecipe 
        v-if="viewAllMenu && !ProfileVisible && !recipeDetailsVisible"
        :recipes="recipes"
        :currentUserId="currentUserId"
        :likedRecipes="likedRecipes"
        :reacts="reacts"
        @toggleViewMenu="toggleViewMenu"
        @toggleRecipeDetails="toggleRecipeDetails"
      />
  
      <RecipeDetails
        v-if="recipeDetailsVisible && !viewAllMenu && !ProfileVisible"
        :recipe="selectedRecipe"
        :purchases  = "purchases"
        :currentUserId="currentUserId"
        @close="closeRecipeDetails"
      />
    </body>
  </template>
  
  <script setup>
  import Header from './UserHeader.vue';
  import { ref, computed } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import viewAllRecipe from '@/components/AllUserRecipe.vue';
  import RecipeCard from '@/components/RecipeCard.vue';
  import RecipeDetails from '@/components/recipeDetails.vue';
  import Profile from '@/components/userComponents/Profile.vue';
  
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
    user: {
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
  const recipeDetailsVisible = ref(false);
  const selectedRecipe = ref(null);
  const ProfileVisible = ref(false);
  const userMain = ref(true);


  function toggleProfile() {
  ProfileVisible.value = !ProfileVisible.value;
  if (ProfileVisible.value) {
    viewAllMenu.value = false;
    recipeDetailsVisible.value = false;
  }
}
  
  function toggleViewMenu() {
    viewAllMenu.value = !viewAllMenu.value;
    recipeDetailsVisible.value = false;
    ProfileVisible.value = false;
    userMain.value = false;

  }
  
  function toggleRecipeDetails() {
    recipeDetailsVisible.value = !recipeDetailsVisible.value;
    viewAllMenu.value = false;
    ProfileVisible.value = false;
    userMain.value = false;
  }
  function viewRecipeDetails(recipe) {
    selectedRecipe.value = recipe;
    recipeDetailsVisible.value = true;
    ProfileVisible.value = false;
    viewAllMenu.value = false;

  }
  
  function closeRecipeDetails() {
    recipeDetailsVisible.value = false;
    selectedRecipe.value = null;
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
    width: 70%;
    height: auto;
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    justify-content: center;
    margin-bottom: 20px;
}

.menu-card{
    width: 220px;
    height: 220px;
    display: flex;
    flex-direction: column;
    border-radius: 20px;
    background-color: #E0E7FF;
    box-shadow: 3px 2px 3px black;
    align-items: center;
    margin: 20px;
}
.menu-img{
    margin-top: 10px;
    width: 100%;
    height: 40%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.menu-img img{
    width: auto;
    height: 100%;
}
.menu-card h2{
    font-size: 1.3em;
}
.menu-card p{
    font-size: 1em;
}
.menu-button{
    width: 100%;
    height: 50px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}
.menu-button button{
    width: 100px;
    height: 30px;
    background-color: #435F77;
    border: none;
    border-radius: 20px;
    color: white;
}
.menu-button img{
    width: 50px;
    height: auto;
}
.menu-button p{
    font-size: 1.1em;
}
</style>