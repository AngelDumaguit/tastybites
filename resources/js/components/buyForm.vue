<template>
  <div class="buy-form-container">
    <h2>Submit Purchase Request</h2>
    <form @submit.prevent="submitPurchaseRequest">
      <div>
        <label for="receipt">Upload Receipt:</label>
        <input type="file" id="receipt" @change="handleFileChange" accept="image/*" />
        <p v-if="form.errors.receipt" class="error">{{ form.errors.receipt }}</p>
      </div>
      <div class="buttons-con">
        <button type="submit" :disabled="form.processing">Submit</button>
        <button type="button" @click="$emit('cancel')">Cancel</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
  recipeId: {
    type: Number,
    required: true,
  },
});

const file = ref(null);
const form = useForm({
  receipt: null, 
});

function handleFileChange(event) {
  file.value = event.target.files[0];
  form.receipt = file.value;
}

async function submitPurchaseRequest() {
  if (!file.value) {
    alert('Please upload a receipt.');
    return;
  }

  try {
    form.post(`/purchases/${props.recipeId}/buy`, {
      preserveScroll: true,
      onSuccess: () => {
        alert('Purchase request sent successfully!');
        $emit('cancel');
      },
      onError: (errors) => {
        console.error(errors);
      },
    });
  } catch (error) {
    console.error('Unexpected error occurred:', error);
  }
}
</script>

<style scoped>
/* Buy Form Container */
.buy-form-container {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 20px;
  width: 400px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  position: absolute;
  top: 35%;
  left: 35%;
}

/* Form Heading */
.buy-form-container h2 {
  font-size: 1.5em;
  margin-bottom: 20px;
  text-align: center;
  color: #333;
}

/* Form Group */
.buy-form-container div {
  margin-bottom: 15px;
}

/* Labels */
.buy-form-container label {
  font-weight: bold;
  color: #555;
  display: block;
  margin-bottom: 5px;
}

/* Input Field */
.buy-form-container input[type="file"] {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 0.9em;
  box-sizing: border-box;
}

/* Buttons */
.buttons-con {
  align-items: center;
  justify-content: center;
  flex-direction: row;
  display: flex;
  gap: 10px;
}
.buy-form-container button {
  background-color: #4CAF50; /* Green for Submit */
  color: white;
  padding: 10px 15px;
  font-size: 1em;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-right: 10px;
  transition: background-color 0.3s ease;
}

.buy-form-container button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.buy-form-container button:hover:not(:disabled) {
  background-color: #45a049;
}

.buy-form-container button[type="button"] {
  background-color: #f44336; /* Red for Cancel */
}

.buy-form-container button[type="button"]:hover {
  background-color: #e53935;
}

/* Error Message */
.error {
  color: #e53935;
  font-size: 0.9em;
  margin-top: 5px;
  font-style: italic;
}
</style>