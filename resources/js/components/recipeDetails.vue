<template>
  <div v-if="recipe">
    <div v-if="recipe.freePrem === 'Free'" class="free-details-con">
      <div class="free-card">
        <div class="free-header">
          
          <div class="header-back-con">
            <a href="javascript:void(0)" @click="closeDetails">
              <img src="/public/images/back to me.png" alt="Back">
            </a>
          </div>
          <div class="free-image-con" :style="{ backgroundImage: `url(${getImagePath(recipe.imagePath)})` }"></div>
          <div class="free-header-main-con">
            <div class="free-label-con">
              <h2>{{ recipe.recipeName }}</h2>
              <p>{{ recipe.cuisineType }}</p>
            </div>
          </div>
        </div>
        <div class="free-card-main">
          <div class="description">
            <div class="description-label">
              <img src="/public/images/descrept icon copy.png" alt="Description Icon">
              <h3>Description</h3>
            </div>
            <p>{{ recipe.description }}</p>
            <div class="ingredients-label">
              <img src="/public/images/ingred icon.png" alt="Ingredients Icon">
              <h3>Ingredients</h3>
            </div>
            <ul>
              <li v-for="(ingredient, index) in recipe.ingredients" :key="index">
                {{ ingredient }}
              </li>
            </ul>
            <div class="procedure-label">
              <img src="/public/images/navbar-recipe.png" alt="Procedure Icon">
              <h3>Procedure</h3>
            </div>
            <ol>
              <li v-for="(step, index) in recipe.procedure" :key="index">
                {{ step }}
              </li>
            </ol>
            <div class="video-con">
              <div class="video-label">
                <img src="/public/images/video icon.png" alt="Video Icon">
                <h3>Video</h3>
              </div>
                <a :href="recipe.videoLink" target="_blank">Click to Watch Tutorial</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="recipe.freePrem === 'Premium'" class="premium-recipe-details-con">
      <main v-if="!showBuyForm">
        <div class="free-card">
          <div class="free-header">
            <div class="free-image-con" :style="{ backgroundImage: `url(${getImagePath(recipe.imagePath)})` }"></div>
            <div class="free-header-main-con">
              <div class="free-label-con">
                <h2>{{ recipe.recipeName }} (Premium)</h2>
                <p>{{ recipe.cuisineType }}</p>
              </div>
              <div class="description-label">
                  <img src="/public/images/descrept icon copy.png" alt="Description Icon">
                  <h3>Description</h3>
                </div>
                <p>{{ recipe.description }}</p>
                <p ><strong>Price:</strong> ${{ recipe.price }}</p>
              <div class="free-header-button-con">
                <button v-if="!isPurchasePending && !isPurchaseOwned" @click="toggleBuyForm" class="buy-button">Buy</button>
                <p v-else class="purchase-status-message"><strong>Status:</strong> {{ getPurchaseStatus }}</p>
                <button @click="closeDetails" class="close-button">Close</button>
              </div>
            </div>
          </div>
          <div class="free-card-main" :style="{ width: '90%' }">
            <div v-if="isPurchaseOwned" class="description">
              <div class="description-label">
                <img src="/public/images/ingred icon.png" alt="Ingredients Icon">
                <h3>Ingredients</h3>
              </div>
              <ul>
                <li v-for="(ingredient, index) in recipe.ingredients" :key="index">
                  {{ ingredient }}
                </li>
              </ul>
              <div class="description-label">
                <img src="/public/images/navbar-recipe.png" alt="Procedure Icon">
                <h3>Procedure</h3>
              </div>
              <ol>
                <li v-for="(step, index) in recipe.procedure" :key="index">
                  {{ step }}
                </li>
              </ol>
              <div class="video-con">
              <div class="video-label">
                <img src="/public/images/video icon.png" alt="Video Icon">
                <h3>Video</h3>
              </div>
                <a :href="recipe.videoLink" target="_blank">Click to Watch Tutorial</a>
            </div>
            </div>
          </div>
        </div>
      </main>
      <buyForm :recipe-id="recipe.id" v-else @cancel="toggleBuyForm" />
    </div>
  </div>  
</template>

<script setup>
import { defineProps, defineEmits, ref, computed, h } from 'vue';
import buyForm from '@/components/buyForm.vue';

const props = defineProps({
  recipe: {
    type: Object,
    required: true,
  },
  purchases: {
    type: Array,
    required: true,
  },
  currentUserId: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(['close']);
const showBuyForm = ref(false);

const isPurchasePending = computed(() => {
  if (!props.purchases) {
    return false;
  }
  const purchase = props.purchases.find(
    (purchase) => purchase.recipe_id === props.recipe.id && purchase.status === 'pending'
  );
  return !!purchase;
});


const getPurchaseStatus = computed(() => {
  if (!props.purchases) return 'No purchase found';

  const purchase = props.purchases.find(
    (purchase) => purchase.recipe_id === props.recipe.id
  );

  return purchase ? purchase.status : 'No purchase found';
});

const isPurchaseOwned = computed(() => {
  if (!props.purchases){
    return false;
  } 
  
  const purchase = props.purchases.find(
    (purchase) => purchase.recipe_id === props.recipe.id && purchase.status === 'owned'
  );

  return purchase ? true : false;
});

function closeDetails() {
  emit('close');
}

function toggleBuyForm() {
  showBuyForm.value = !showBuyForm.value;
}
function getImagePath(path) {
  // Assuming your images are stored in the 'public/storage' directory
  return path ? `/storage/${path}` : '/images/default.png';
}

</script>

<style scoped>
.free-details-con {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

/* Free Card */
.free-card {
  width: 60%;
  height: 550px;
  background-color: #E0E7FF;
  border-radius: 50px;
  box-shadow: 2px 2px 2px black;
  display: flex;
  flex-direction: column;
  margin-top: 45px;
  scrollbar-width: none;
}

/* Header */
.free-header {
  display: flex;
  flex-direction: row;
  margin-top: 20px;
  margin-left: 20px;
}

.free-image-con {
  width: 170px;
  height: 150px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  border-radius: 50%;
}

.free-header-main-con {
  width: 80%;
  display: flex;
  flex-direction: column;
  margin-left: 20px;
}

.free-label-con {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: start;
  justify-content: center;
  gap: -50px;
}

.free-label-con h2 {
  font-size: 2em;
  margin: 0;
}

.free-label-con p {
  font-size: 1em;
  margin: 0;
  font-style: italic;
}

/* Card Content */
.free-card-main {
  display: flex;
  flex-direction: column;
  margin-top: 20px;
  overflow-y: auto;
  padding: 20px;
  scrollbar-width: none;
}

.description, .ingredients-con, .procedure-con {
  margin-left: 30px;
  width: 90%;
}

.description-label, .ingredients-label, .procedure-label, .video-label {
  display: flex;
  align-items: center;
  gap: 30px;
}

.description p{
  text-align: justify;
}

.description-label h3, .ingredients-label h3, .procedure-label h3, .video-label h3 {
  font-size: 1.2em;
}

.ingredients-list, .procedure-list {
  margin-left: 20%;
}

.ingredients-list ul li, .procedure-list ul li {
  font-size: 1em;
}

/* Purchase Status */
.purchase-status-message {
  font-size: 1.1em;
  color: green;
}

.header-back-con {
    width: 10%;
    height: 100%;
}
.header-back-con a img {
    width: 5%;
    height: auto;
    position: absolute;
    right: 20%;
}
.description-label img, .ingredients-label img, .procedure-label img, .video-label img {
    width: 30px;
    height: auto;
}
.video-con a {
  width: 100%;
  height: auto;
  display: flex;
  align-items: center;
  justify-content: center;
}
.premium-recipe-details-con {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  margin-left: 18%;
}
.free-header-button-con{
  width: 90%;
  height: 100%;
  display: flex;
  justify-content: flex-end;
}
.buy-button, .close-button{
  width: 20%;
  height: 30px;
  background-color: #435F77;
  border: none;
  border-radius: 20px;
  color: white;
  font-weight: bold;
  cursor: pointer;
  font-size: .8em;
  box-shadow: 3px 2px 3px black;
}
.close-button{
  margin-left: 5%;
}

.buy-button:hover{
  background-color: white;
  color: #435F77;
  border-right: #435F77 2px solid;
  border-bottom: #435F77 2px solid;
}
.close-button:hover{
  background-color: white;
  color: red;
  border-right: red 2px solid;
  border-bottom: red 2px solid;
}
</style>
