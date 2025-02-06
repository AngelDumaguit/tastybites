<template>
  <div class="add-recipe-main-con">
    <div class="form">
      <form class="recipe-form" @submit.prevent="addRecipe">
        <div class="form-header">
          <div class="form-image-con">
            <input
              type="file"
              accept="image/*"
              @change="handleImageUpload"
              class="hidden-input"
              id="file"
              name="file"
            />
            <label for="file" class="the-image-bttn"><img :src="previewImage" alt="Recipe Image" class="preview-image" /></label>
          </div>
          <div class="header-input-con">
            <div class="recipename-con">
              <input type="text" placeholder="Recipe Name" name="recipeName" v-model="recipeName" />
            </div>
            <div class="cuisine-con">
              <input type="text" placeholder="Type of Cuisine" name="cuisineType" v-model="cuisineType" />
            </div>
          </div>
          <div class="header-back-con">
            <div class="back-to-recipes-con">
              <a href="javascript:void(0)" @click="emitSection('recipe')" class="back-to-recipes">
                <img src="/public/images/back to me.png" alt="Back to recipes" />
              </a>
            </div>
          </div>
        </div>

        <div class="form-content-con">
          <div class="form-content-input">
            <div class="form-content-navbar">
              <div class="form-navbar-bttn">
                <a
                  href="#"
                  class="nav-link"
                  :class="{ active: activeSection === 'description' }"
                  @click.prevent="setActiveSection('description')"
                >
                  <div class="form-navbar-img-con">
                    <img src="/public/images/descrept icon.png" alt="Description icon" />
                  </div>
                  <div class="form-navbar-label">
                    <p>Description</p>
                  </div>
                </a>
                <a
                  href="#"
                  class="nav-link"
                  :class="{ active: activeSection === 'ingredients' }"
                  @click.prevent="setActiveSection('ingredients')"
                >
                  <div class="form-navbar-img-con">
                    <img src="/public/images/ingred icon.png" alt="Ingredients icon" />
                  </div>
                  <div class="form-navbar-label">
                    <p>Ingredients</p>
                  </div>
                </a>
                <a
                  href="#"
                  class="nav-link"
                  :class="{ active: activeSection === 'procedure' }"
                  @click.prevent="setActiveSection('procedure')"
                >
                  <div class="form-navbar-img-con">
                    <img src="/public/images/navbar-recipe.png" alt="Procedure icon" />
                  </div>
                  <div class="form-navbar-label">
                    <p>Procedure</p>
                  </div>
                </a>
                <a
                  href="#"
                  class="nav-link"
                  :class="{ active: activeSection === 'video' }"
                  @click.prevent="setActiveSection('video')"
                >
                  <div class="form-navbar-img-con">
                    <img src="/public/images/video icon.png" alt="Video icon" />
                  </div>
                  <div class="form-navbar-label">
                    <p>Video</p>
                  </div>
                </a>
              </div>
            </div>

            <div v-show="activeSection === 'description'" class="form-description-inputs-con content">
              <textarea placeholder="Short description here..." name="description" v-model="description"></textarea>
            </div>
            <div v-show="activeSection === 'ingredients'" class="form-ingredients-inputs-con content">
              <div class="ingred-content">
                <div class="add-ingred-con">
                  <a href="#" id="add-ingred-btn" @click.prevent="addIngredient">Add new Ingredients</a>
                </div>
                <div class="ingredients-list">
                  <div v-for="(ingredient, index) in ingredients" :key="index" class="input-with-close">
                    <input type="text" placeholder="Ingredients" v-model="ingredients[index]" />
                    <button type="button" class="close-btn" @click="removeIngredient(index)">x</button>
                  </div>
                </div>
              </div>
            </div>
            <div v-show="activeSection === 'procedure'" class="form-procedure-inputs-con content">
              <div class="procedure-content">
                <div class="add-procedure-con">
                  <a href="#" id="add-procedure-btn" @click.prevent="addProcedure">Add new Procedure</a>
                </div>
                <div class="procedure-list">
                  <div v-for="(procedure, index) in procedures" :key="index" class="input-with-close">
                    <input type="text" placeholder="Procedure" v-model="procedures[index]" />
                    <button type="button" class="close-btn" @click="removeProcedure(index)">x</button>
                  </div>
                </div>
              </div>
            </div>
            <div v-show="activeSection === 'video'" class="form-video-inputs-con content">
              <div class="video-content">
                <button type="button" class="Video">Link</button>
                <input type="text" placeholder="Video Link" name="video" v-model="videoLink" />
              </div>
            </div>
          </div>

          <div class="form-submit-con">
            <div class="submit-con">
              <button type="submit">SAVE</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { defineEmits } from 'vue';

const emit = defineEmits();

function emitSection(section) {
  emit('change-section', section);
}

const activeSection = ref('description');
const recipeName = ref('');
const cuisineType = ref('');
const description = ref('');
const videoLink = ref('');
const ingredients = ref([]);
const procedures = ref([]);
const imagePath = ref(null);

const setActiveSection = (section) => {
  activeSection.value = section;
};

const addIngredient = () => {
  ingredients.value.push('');
};

const addProcedure = () => {
  procedures.value.push('');
};

const removeIngredient = (index) => {
  ingredients.value.splice(index, 1);
};

const removeProcedure = (index) => {
  procedures.value.splice(index, 1);
};

// Image Preview Computed Property
const previewImage = computed(() => {
  return imagePath.value instanceof File
    ? URL.createObjectURL(imagePath.value)  // Show the uploaded image preview
    : '/storage/default-image.png';  // Default image if no file selected
});

// Handle Image Upload
const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
  if (!validTypes.includes(file.type)) {
    alert('Invalid file type. Please upload an image file.');
    return;
  }

  if (file.size > 2 * 1024 * 1024) { // 2MB limit
    alert('File size exceeds 2MB.');
    return;
  }

  imagePath.value = file;
  console.log('Selected file:', file.name);
};

// Add Recipe
const addRecipe = async () => {
  try {
    const formData = new FormData();
    formData.append('recipeName', recipeName.value);
    formData.append('cuisineType', cuisineType.value);
    formData.append('description', description.value);

    ingredients.value.forEach((ingredient, index) => {
      formData.append(`ingredients[${index}]`, ingredient);
    });

    procedures.value.forEach((procedure, index) => {
      formData.append(`procedure[${index}]`, procedure);
    });

    if (imagePath.value) {
      formData.append('imagePath', imagePath.value);
    }

    formData.append('videoLink', videoLink.value);

    const response = await axios.post('/admin/addRecipe', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    console.log('Recipe added successfully:', response.data);
    alert('Recipe added successfully!');
  } catch (error) {
    if (error.response) {
      console.error('Validation errors:', error.response.data);
      alert('Error: ' + error.response.data.message);
    } else {
      console.error('Unexpected error:', error);
      alert('An unexpected error occurred.');
    }
  }
};
</script>
  
  

<style scoped>
.add-recipe-main-con {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}   
.form{
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.recipe-form{
    width: 70%;
    height: 90%;
    border-radius: 30px;
    background-color: #E0E7FF;
    box-shadow: 3px 3px 5px black;    
}
.form-header{
    width: 100%;
    height: 40%;
    display: flex;
    flex-direction: row;
}
.form-image-con{
    height: 100%;
    width: 30%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.the-image-bttn{
    width: 80%;
    height: 80%;
    border-radius: 50%;
    border: 1px solid black;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.the-image-bttn img{
    width: 100%;
    height: auto;
    border-radius: 50%;
}
.header-input-con{
    height: 100%;
    width: 60%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.recipename-con{
    width: 100%;
    height: 20%;
}
.cuisine-con{
    width: 100%;
    height: 15%;
}
.recipename-con input{
    width: 80%;
    height: 80%;
    padding: 5px 20px 5px 20px;
    border-radius: 30px;
    border: none;
    
}
.cuisine-con input{
    width: 50%;
    height: 60%;
    padding: 5px;
    padding-left: 20px;
    border-radius: 30px;
    border: none;
    font-style: italic;
}
.header-back-con{
    height: 100%;
    width: 10%;
    display: flex;
    flex-direction: column;
}
.back-to-recipes-con{
    width: 100%;
    height: 25%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.back-to-recipes-con a{
    height: 70%;
    width: 70%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.back-to-recipes-con a img{
    width: 100%;
    height: auto;
}
.form-content-con{
    width: 100%;
    height: 60%;
    display: flex;
    flex-direction: column;
}
.form-content-input{
    width: 100%;
    height: 80%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}
.form-content-navbar{
    width: 35%;
    height: 80%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 30px;
    border-radius: 20px;
    background-color: #B9D5D8;
    box-shadow: 3px 3px 3px black;
}
.form-navbar-bttn{
    width: 100%;
    height: 80%;
    display: flex;
    justify-content: space-between;
    align-items: end;
    flex-direction: column;
}
.form-navbar-bttn a{
    width: 80%;
    height: 40px;
    border-radius: 20px 0 0 20px;
    border: none;
    display: flex;
    text-decoration: none;
    color: black;
    flex-direction: row;
}
.nav-link.active{ 
    background-color: #f0f0f0;
}

.nav-link {
    gap: 20px;
}

.form-navbar-img-con{
    width: 20%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.form-navbar-img-con img{
    width: 80%;
    height: auto;
}
.form-navbar-label{
    width: 80%;
    height: 100%;
    display: flex;
    align-items: center;
}
.form-navbar-label p{
    font-size: .8em;
}
.content {
    display: flex;
}
.form-description-inputs-con,.form-ingredients-inputs-con ,.form-procedure-inputs-con,.form-video-inputs-con{
    width: 45%;
    height: 65%;
    background-color: #B9D5D8;
    border-radius: 30px;
    margin-left: 10px;
    box-shadow: 2px 2px 2px black;
    padding: 30px;
}
.form-description-inputs-con textarea{
    resize: none;
    width: 100%;
    height: 100%;
    border: none;
    background-color: transparent;
}
.ingred-content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.add-ingred-con {
    width: 100%;
    height: 25%;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.add-ingred-con a {
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    background-color: #435F77;
    font-size: 12px;
    font-weight: 700;
}

.ingredients-list {
    margin-top: 20px;
    width: 100%;
    height: 100%;
    overflow-y: auto;
    scrollbar-width: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-right: 10px;
}

.ingredients-list::-webkit-scrollbar {
    display: none;
}

.ingredients-list input {
    width: 100%;
    height: 40px; 
    min-height: 40px; 
    flex-shrink: 0;
    border: none;
    border-radius: 20px;
    box-sizing: border-box;
    background-color: white;
    padding: 10px 20px 10px 20px;
    margin-right: 10px;
    position: relative;
}

.procedure-content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.add-procedure-con {
    width: 100%;
    height: 25%;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.add-procedure-con a {
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    background-color: #435F77;
    font-size: 12px;
    font-weight: 700;
}

.procedure-list {
    margin-top: 20px;
    width: 100%;
    height: 100%;
    overflow-y: auto;
    scrollbar-width: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-right: 10px;
}

.procedure-list::-webkit-scrollbar {
    display: none;
}

.procedure-list input {
    width: 100%;
    height: 40px; 
    min-height: 40px; 
    flex-shrink: 0;
    border: none;
    border-radius: 20px;
    box-sizing: border-box;
    background-color: white;
    padding: 10px 20px 10px 20px;
    margin-right: 10px;
    position: relative;
}
.video-content{
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.Video {
    padding: 10px;
    background-color: #435F77;
    color: white;
    font-weight: 600;
    border-radius: 20px 0px 0px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.video-content input{
    width: 80%;
    height: 20%;
    border-radius: 0px 20px 20px 0px;
    border: none;
    background-color: white;
    padding-left: 10px;
}
.form-submit-con{
    width: 100%;
    height: 20%;
    display: flex;
    align-items: end;
    justify-content: end;
}
.submit-con{
    width: 20%;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}
.submit-con button{
    width: 80%;
    height: 50%;
    background-color: #435F77;
    border: none;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    font-weight: 600;
    margin-right: 40px;
    cursor: pointer;
}
.input-with-close {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 10px;
  position: relative;
}

.close-btn {
  background-color: white;
  color: black;
  border: none;
  border-radius: 50%;
  width: 25px;
  height: 25px;
  cursor: pointer;
  font-size: 16px;
  display: flex;
  justify-content: center;
  align-items: center;
  right: 5px;
  position: absolute;
}

.close-btn:hover {
  background-color: #ff1a1a;
}
.hidden-input {
  display: none;
}
</style>