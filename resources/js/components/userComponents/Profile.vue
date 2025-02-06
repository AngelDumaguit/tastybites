<template>
    <main class="profile-main" v-if="!recipeDetailsVisible && ProfileVisible">
      <div class="back-con">
        <a href="#" @click.prevent="goBackToUserDashboard"><img src="/public/images/back to me.png" alt="back"></a>
      </div>
      <div class="cover-photo">
        <div class="profile-photo">
          
        </div>
      </div>
      <div class="user-info-con">
        <div class="user-info-con-main">
          <h2>{{ user.fullname }} <a href="#" id="edit-profile-button" @click.prevent="toggleEditProfile()"><img src="/public/images/edit.png" alt="edit"></a></h2>
          <p>{{ user.account.email }}</p>
          <p><em>@{{ user.username }}</em></p>
        </div>
        <form class="edit-profile-box" @submit.prevent="updateProfile" id="edit-profile-box" v-if="editProfileVisible">
          <div class="close-button">
            <button @click.prevent="closeEditProfile" class="profile-close-button" id="close-profile-box">X</button>
          </div>
          <h2>Personal Information</h2>
          <div class="group-input">
            <p>Full Name:</p>
            <input type="text" placeholder="Full Name" v-model="form.fullname">
          </div>
          <div class="group-input">
            <p>Username:</p>
            <input type="text" placeholder="username" v-model="form.username">
          </div>
          <button type="submit">Save</button>
        </form>
      </div>
      
      <div class="liked-menus-con">
        <div class="liked-headers">
          <img src="/public/images/shaded-heart.png" alt="heart">
          <p>Liked Menus</p>
        </div>
        <div class="liked-list">
          <RecipeCard
            v-for="(recipe, index) in reactedRecipes"
            :key="index"
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
      v-if="recipeDetailsVisible && !viewAllMenu && !ProfileVisible"
      :recipe="selectedRecipe"
      :purchases="purchases"
      @close="closeRecipeDetails"
    />
  </template>
  
  <script setup>
  import { defineProps, computed, ref, defineEmits } from 'vue';
  import { Inertia } from '@inertiajs/inertia';
  import RecipeCard from '@/components/RecipeCard.vue';
  import RecipeDetails from '@/components/recipeDetails.vue';
  
  const props = defineProps({
    user: {
      type: Object,
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
    likedRecipes: {
      type: Object,
      required: true,
    },
  });
  
  const emit = defineEmits(['toggleProfile', 'toggleRecipeDetails']);
  
  const selectedRecipe = ref(null);
  const recipeDetailsVisible = ref(false);
  const ProfileVisible = ref(true);
  const editProfileVisible = ref(false);

  function viewRecipeDetails(recipe) {
    selectedRecipe.value = recipe;
    recipeDetailsVisible.value = true;
    ProfileVisible.value = false;
  }
  
  function closeRecipeDetails() {
    recipeDetailsVisible.value = false;
    selectedRecipe.value = null;
    ProfileVisible.value = true;
  }
  
  function goBackToUserDashboard() {
    emit('toggleProfile');
}

function toggleEditProfile() {
    editProfileVisible.value = !editProfileVisible.value;
}

function closeEditProfile() {
    editProfileVisible.value = false;
}

const reactedRecipes = computed(() => {
  return props.recipes.filter((recipe) => {
    return props.reacts.some(
      (react) => react.account_id === props.currentUserId && react.recipe_id === recipe.id && react.reaction === 'like'
    );
  });
});
  
function toggleReaction(recipe) {
  Inertia.post(route('recipe.toggleReaction', { recipe: recipe.id }), {}, {
    preserveState: true,
    onSuccess: ({ props }) => {
      const isLiked = props.likedRecipes[recipe.id];
      props.likedRecipes[recipe.id] = !isLiked;
      recipe.reacts_count = props.likeCount;
    },
    onError: (error) => {
      console.error('Error toggling reaction:', error);
    },
  });
}

const form = ref({
  fullname: props.user.fullname,
  username: props.user.username,
});
  
function updateProfile() {
  Inertia.post(route('profile.update'), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      editProfileVisible.value = false;
      alert('Profile updated successfully!');
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
}
  
</script>
  



<style scoped>
.profile-main{
    width: 100%;
    height: 88%;
    display: flex;
    flex-direction: column;
    overflow: auto;
}
.back-con{
    width: 100%;
    height: 80px;
    display: flex;
    align-items: center;
}
.back-con img{
    width: 40px;
    height: auto;
    margin-left: 20px;
}
.cover-photo{
    width: 80%;
    height: 100px;
    display: flex;
    align-self: center;
    border-radius: 30px;
    position: relative;
    background-color: #E0E7FF;
}
.user-info-con{
    width: 80%;
    height: auto;
    display: flex;
    flex-direction: column;
    align-self: center;
    position: relative;
    gap: -30px;
    margin: 0px;
}


.edit-profile-box{
    position: absolute;
    width: 350px;
    height:auto;
    left: 40%;
    top: 10px;
    background-color: #435F77;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    border-radius: 30px;
    gap: 10px;
}
.close-button{
    position: relative;
    width: 10%;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: end;
    margin-right: -90%;
}
.profile-close-button{
    width: 10px;
    height: 30px;
    border-radius: 100%;
    background-color: #E0E7FF;
    color: black;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
.edit-profile-box h2{
    margin-top: -10px;
    font-size: 3.5em;
    color: white;
}
.group-input p{
    font-size: .6em;
    color: white;
    font-weight: 700;
}
.edit-profile-box button{
    width: 100px;
    height: 30px;
    color: #435F77;
    border-radius: 10px;
    border: none;
    margin-top: 5px;
    background-color: white;
}
.group-input{
    width: 90%;
    height: 60px;
    display: flex;
    flex-direction: column;   
}
.group-input input{
    width: 100%;
    height: 30px;
    border: none;
    border-radius: 10px;
    font-size: .8em;
    padding-left: 5px;
}
.user-info-con h2{
    font-size: 1.3em;
    display: flex;
}
.user-info-con p{
    font-size: .8em;
    display: flex;
}
.user-info-con h2 img{
    width: 20px;
    height: 20px;
    margin-left: 10px;
    display: flex;
    align-self: center;
}
.user-info-con p img{
    width: 20px;
    height: 20px;
    margin-left: 10px;
    display: flex;
    align-self: center;
}
.liked-menus-con{
    width: 80%;
    height: 400px;
    display: flex;
    flex-direction: column;
    align-self: center;
    margin-top: 20px;
}
.liked-headers{
    width: 100%;
    height: 50px;
    display: flex;
    flex-direction: row;
    align-items: center;
}
.liked-headers img{
    width: 40px;
    height: 20px;
}
.liked-headers p{
    width: 100px;
    height: 30px;
    background-color: #435F77;
    border-radius: 20px;
    font-size: .7em;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.liked-list{
    width: 90%;
    height: auto;
    display: flex;
    padding: 10px;
    flex-direction: row;
}
.list-card{
    width: 200px;
    height: 200px;
    display: flex;
    flex-direction: column;
    background-color: #E0E7FF;
    border-radius: 20px;
    margin: 20px;    
}
.menu-image-con{
    width: 100%;
    height: 60%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.menu-image-con img{
    width: 170px;
    height: auto;
}
.list-card h2{
    display: flex;
    align-self: center;
    font-size: 1.5em;
}
.list-card p{
    display: flex;
    align-self: center;
    font-size: .8em;
}
</style>