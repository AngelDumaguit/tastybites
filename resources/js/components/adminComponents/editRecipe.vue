<template>
    <div class="edit-recipe-main-con">
        <div class="form">
            <form class="recipe-form" @submit.prevent="updateRecipe">
                <div class="form-header">
                    <div 
                        class="form-image-con" 
                        :style="{ backgroundImage: previewImage ? `url(${previewImage})` : 'none' }"
                        @click="triggerFileInput" 
                        >
                            <!-- Hidden Input -->
                            <input
                            type="file"
                            accept="image/*"
                            @change="handleImageUpload"
                            class="hidden-input"
                            id="file"
                            name="file"
                            ref="fileInput" 
                            style="display: none;"/>
                        </div>
                    <div class="header-input-con">
                        <div class="recipename-con">
                            <input
                                type="text"
                                placeholder="Recipe Name"
                                v-model="form.recipeName"
                            />
                        </div>
                        <div class="cuisine-con">
                            <input
                                type="text"
                                placeholder="Type of Cuisine"
                                v-model="form.cuisineType"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-content-con">
                    <div class="description-con">
                        <div class="description-label">
                            <img src="/public/images/descrept icon.png" alt="ingredients">
                            <h3>Description</h3>
                        </div>
                        <div class="description-input">
                            <textarea v-model="form.description" placeholder="Short description here..."></textarea>
                        </div>
                    </div>
                    <div class="form-ingredients-con">
                        <div class="ingredients-label">
                            <img src="/public/images/ingred icon.png" alt="ingredients">
                            <h3>Ingredients</h3>
                        </div>
                        <div v-for="(ingredient, index) in form.ingredients" :key="index" class="ingredient-input">
                            <input
                                type="text"
                                v-model="form.ingredients[index]"
                                placeholder="Enter ingredient"
                            />
                            <button type="button" @click="removeIngredient(index)">Remove</button>
                        </div>
                        <button class="add-ingredient-btn" type="button" @click="addIngredient">Add Ingredient</button>
                    </div>

                    <div class="form-procedure-con">
                        <div class="procedure-label">
                            <img src="/public/images/navbar-recipe.png" alt="Procedure icon" />
                            <h3>Procedure</h3>
                        </div>
                        <div v-for="(step, index) in form.procedure" :key="index" class="procedure-input">
                            <textarea
                                v-model="form.procedure[index]"
                                placeholder="Enter procedure step"
                            ></textarea>
                            <button type="button" @click="removeProcedureStep(index)">Remove</button>
                        </div>
                        <button type="button" @click="addProcedureStep">Add Step</button>
                    </div>

                    <div class="form-video-link-con">
                        <div class="video-label">
                            <img src="/public/images/video icon.png" alt="Video icon" />
                            <h3>Video Tutorial</h3>
                        </div>
                        <input
                            type="text"
                            placeholder="Enter video link"
                            v-model="form.videoLink"
                        />
                    </div>
                </div>

                <div class="form-submit-con">
                    <button class="save-btn" type="submit" :disabled="form.processing">Save</button>
                    <button class="cancel-btn" type="button" @click="cancelEdit">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    recipe: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['cancel-edit', 'recipe-updated']);

// Initialize the form using Inertia's useForm
const form = useForm({
    recipeName: props.recipe.recipeName,
    cuisineType: props.recipe.cuisineType,
    description: props.recipe.description,
    ingredients: [...props.recipe.ingredients],
    procedure: [...props.recipe.procedure],
    videoLink: props.recipe.videoLink,
    imagePath: null, // For file upload
});

const previewImage = computed(() =>
    form.imagePath instanceof File
        ? URL.createObjectURL(form.imagePath)
        : `/storage/${props.recipe.imagePath}`
);

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.imagePath = file;
    }
};

const addIngredient = () => {
    form.ingredients.push('');
};

const removeIngredient = (index) => {
    form.ingredients.splice(index, 1);
};

const addProcedureStep = () => {
    form.procedure.push('');
};

const removeProcedureStep = (index) => {
    form.procedure.splice(index, 1);
};

const cancelEdit = () => {
    emit('cancel-edit');
};

const updateRecipe = () => {
    // Create a FormData object to handle the form submission
    const formData = new FormData();

    // Append the other form data
    formData.append('recipeName', form.recipeName);
    formData.append('description', form.description);
    formData.append('cuisineType', form.cuisineType);
    formData.append('videoLink', form.videoLink);

    // Append ingredients and procedure steps
    form.ingredients.forEach((ingredient, index) => {
        formData.append(`ingredients[${index}]`, ingredient);
    });

    form.procedure.forEach((step, index) => {
        formData.append(`procedure[${index}]`, step);
    });

    // Only append imagePath if it's changed (i.e., not null or not the same as the original image)
    if (form.imagePath && form.imagePath !== props.recipe.imagePath) {
        formData.append('imagePath', form.imagePath);
    }

    // Make the request using Inertia's post method
    formData.append('_method', 'PUT'); // Ensure the method is PUT for update
    form.post(`/admin/recipes/${props.recipe.id}`, {
        data: formData,
        onSuccess: () => {
            alert('Recipe updated successfully!');
            emit('recipe-updated', form.data());
        },
        onError: (errors) => {
            alert('Error updating recipe.');
            console.error(errors);
        },
    });
};


const fileInput = ref(null);
const triggerFileInput = () => {
    fileInput.value.click(); // Programmatically click the file input
};

</script>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rouge+Script&display=swap');
.edit-recipe-main-con {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'poppins', sans-serif;
}

.form {
    width: 80%;
    height: 80%;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 20px;
    display: flex;
    flex-direction: column;
    scrollbar-width: none;
}

.form-header {
    width: 100%;
    height: 15%;
    display: flex;
    flex-direction: row;
    align-items: start;
    gap: 20px;
}

.form-image-con {
    width: 40%;
    height: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
    gap: 5px;
    background-position: center;
    background-repeat: no-repeat;   
    background-size: cover;
    border-radius: 50%;
}

.form-image-con input {
    width: 90;
    background-color: #f0f0f0;
    border-radius: 10px;
    padding: 5px;
    border: none;
}

.form-image-con label {
    font-size: .8em;
    color: black;
    font-weight: bold;
}

.form-image-con img {
    width: 70%;
    height: auto;
    border-radius: 50%;
}

.header-input-con {
    width: 50%;
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 50px;
}

.recipename-con input {
    width: 100%;
    height: 35px;
    padding: 5px 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    font-size: 1em;
    font-weight: bold;
    box-shadow: 1px 1px 2px #888;
    font-family: 'poppins', sans-serif;
}

.cuisine-con input {
    width: 80%;
    height: 30px;
    padding: 5px 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    font-size: .7em;
    font-style: italic;
    box-shadow: 1px 1px 2px #888;
}
.form-content-con {
    width: 100%;
    height: 81%;
    display: flex;
    flex-direction: column;
}
.form-content-con input, .form-content-con textarea {
    width: 80%;
    height: 30px;
    border: none;
    border-radius: 10px;
    font-size: .7em;
    padding-left: 5px;
    font-family: 'poppins', sans-serif;
}
.description-input textarea{
    width: 100%;
    height: 100px;
    resize: none;
}
.procedure-input textarea{
    width: 80%;
    height: 40px;
    resize: none;
}
.form-content-con input{
    margin-top: 10px;

}
.description-con, .form-ingredients-con, .form-procedure-con, .form-video-link-con {
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
button {
    padding: 5px 10px;
    border-radius: 20px;
    border: none;
    background-color: #435F77;
    color: white;
    cursor: pointer;
    font-size: .7em;
    margin-left: 10px;
    font-family: 'poppins', sans-serif;
}

.add-ingredient-btn {
    margin-top: 10px;
    padding: 5px 10px;
    border-radius: 20px;
    border: none;
    background-color: #435F77;
    color: white;
    cursor: pointer;
    font-size: 12px;
    margin-left: 10px;
    padding: 5px;
}

button:hover {
    background-color: white;
    color: #435F77;
    border: 2px solid #435F77;
    font-weight: bold;
}

.form-submit-con {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 60px;
}

.cancel-btn {
    width: 100px;
    height: 35px;
    font-size: .8em;
    box-shadow: 1px 1px 2px #888;
}
.cancel-btn:hover {
    background-color: white;
    color: red;
    border: 2px solid red;
    font-weight: bold;
}
.save-btn {
    width: 100px;
    height: 35px;
    font-size: .8em;
    box-shadow: 1px 1px 2px #888;
}
.save-btn:hover {
    background-color: white;
    color: green;
    border: 2px solid green;
    font-weight: bold;
}
</style>
