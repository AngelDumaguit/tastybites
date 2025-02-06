<template>
  <a class="recipe-card" :key="recipe.id">
    <img 
      v-if="recipe.freePrem === 'Premium'" 
      class="crown-image" 
      src="/public/images/premium.png" 
      alt="Premium"
    />
    <div class="recipe-image-con" :style="{ backgroundImage: `url(${getImagePath(recipe.imagePath)})` }"></div>
    <div class="recipe-label-con">
      <div class="recipe-label">
        <h3>{{ recipe.recipeName }}</h3>    
      </div>
      <div class="recipe-cuisine">
        <h6>{{ recipe.cuisineType }}</h6>
      </div>
    </div>
    <div class="recipe-button-con">
      <button 
        class="recipe-button" 
        @click="viewRecipeDetails"
      >
        View Recipe
      </button>
      <div class="recipe-react-con">
        <button @click="toggleReaction">
          <font-awesome-icon
            :icon="liked ? 'fa-solid fa-heart' : 'fa-regular fa-heart'"
            class="react-icon"
            :style="{ color: liked ? 'red' : 'black' }"
          />
        </button>
        <p>{{ recipe.reacts_count }}</p>
      </div>
    </div>
  </a>
</template>

<script setup>
import { defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
  recipe: {
    type: Object,
    required: true,
  },
  currentUserId: {
    type: Number,
    required: true,
  },
  likedRecipes: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['toggleReaction', 'viewRecipeDetails']);

const liked = computed(() => {
  return props.likedRecipes[props.recipe.id] || false;
});

function toggleReaction() {
  emit('toggleReaction', props.recipe);
}

function viewRecipeDetails() {
  emit('viewRecipeDetails', props.recipe);
}

function getImagePath(path) {
  // Assuming your images are stored in the 'public/storage' directory
  return path ? `/storage/${path}` : '/images/default.png';
}
</script>



<style scoped>
.recipe-card {
    width: 170px;
    height: 190px;
    margin: 20px;
    border-radius: 20px;
    box-shadow: 2px 3px 5px black;
    background-color: #E0E7FF;
    text-decoration: none;
    padding: 20px;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.recipe-image-con {
    width: 70%;
    height: 40%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    border-radius: 50%;
}

.recipe-image-con img {
    width: 100%;
    height: auto;
}

.recipe-label-con {
    width: 100%;
    height: 40%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 5px;
}

.recipe-label {
    width: 100%;
    height: 30%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.recipe-cuisine {
    width: 100%;
    height: 30%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.recipe-label h3 {
    color: black;
    font-size: 1.2em;
    font-family: 'poppins', sans-serif;
    font-weight: 600;
}

.recipe-cuisine h6 {
    font-size: 0.8em;
    font-family: 'poppins', sans-serif;
    font-weight: 500;
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
    font-family: 'poppins', sans-serif;
    box-shadow: 3px 2px 7px black;
}
.recipe-button-con button:hover{
    background-color: #E0E7FF;
    color: #435F77;
    font-weight: bold;
    border-right: #435F77 2px solid;
    border-bottom: #435F77 2px solid;
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