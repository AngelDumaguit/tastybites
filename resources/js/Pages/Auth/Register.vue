<template>
    <div class="register-body">
        <div class="leftside">
            <div class="logo-cont">
                <img src="/public/images/TastyBites.png" alt="Tastybites Logo">
            </div>
        </div>
        <div class="rightside">
            <div class="register-container">
                <div class="register-header">
                    <p>SIGNUP</p>
                </div>
                <form class="register-form" @submit.prevent="register">
                    <div class="input-container">
                        <input type="text" v-model="form.fullname" placeholder="Full Name" required>
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="input-container">
                        <input type="text" v-model="form.username" placeholder="Username" required>
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="input-container">
                        <input type="email" v-model="form.email" placeholder="Email" required>
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="input-container">
                        <input type="password" v-model="form.password" placeholder="Password" required>
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <div class="role-con">
                        <button type="button" :class="{ active: role === 'User' }" @click="setRole('User')">
                            <i class="fa-solid fa-user"></i>User
                        </button>
                        <button type="button" :class="{ active: role === 'Chef' }" @click="setRole('Chef')">
                            <i class="fa-solid fa-utensils"></i>Chef
                        </button>
                    </div>

                    <div v-if="role === 'Chef'" class="additional-inputs" id="additionalInputs">
                        <input class="experience" type="text" v-model="form.experience" placeholder="Experience" required>
                        <input class="file-upload" type="file" @change="handleFileUpload" ref="credentials">
                    </div>
                    <button type="submit">Register</button>
                </form>
                <div class="already-have-account">
                    <p>Already have an account? <Link href="/login">Login</Link></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

const form = ref({
    fullname: '',
    username: '',
    email: '',
    password: '',
    role: '',
    experience: '',
    credentials: null,
});

const role = ref('');

function setRole(selectedRole) {
    role.value = selectedRole;
    form.value.role = selectedRole;
}

function handleFileUpload(event) {
    form.value.credentials = event.target.files[0];
}

const register = () => {
    const formData = new FormData();
    formData.append('fullname', form.value.fullname);
    formData.append('username', form.value.username);
    formData.append('email', form.value.email);
    formData.append('password', form.value.password);
    formData.append('role', form.value.role);
    formData.append('status', form.value.status || 'pending');  // Ensure this is set

    if (form.value.role === 'Chef') {
        formData.append('experience', form.value.experience);
        formData.append('credentials', form.value.credentials);
    }

    Inertia.post('/register', formData, {
        onSuccess: () => {
            Inertia.visit('/login');
        },
        onError: (error) => {
            console.error(error);  // Inspect error for details
            if (error && error.response && error.response.data) {
                alert('Registration failed: ' + JSON.stringify(error.response.data.errors));
            }
        }
    });
};

</script>

<style scoped>
.register-body{
    background-color: white;
    font-family: 'Poppins', sans-serif;
    overflow: hidden;
}
.leftside {
    width: 47%;
    height: 93vh;
    float: left;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;

}
.logo-cont {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    margin: auto;
}

.leftside img {
    width: 90%;
    height: auto;
    margin-bottom: 20px;
    animation: moveImage 4s infinite; 
}

@keyframes moveImage {
    0% { transform: translateX(0); }
    50% { transform: translateX(30px); }
    100% { transform: translateX(0); }
}

.rightside {
    width: 47%;
    height: 93vh;
    float: right;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.register-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 70%;
    margin: auto;
    background-color: #E0E7FF;
    border-radius: 30px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: height 0.3s ease; 
}

.register-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 5%;
}

.register-header p {
    color: black;
    font-weight: 550;
    font-size: 30px;
}

form {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: auto;
}

.input-container {
    position: relative; 
    margin-bottom: 20px;
    width: 85%;
}

.input-container input {
    width: 100%;
    height: 40px; 
    padding:5px;
    border-radius: 10px;
    border: 1px solid #ccc;
}

.input-container i {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
}

button {
    margin: 10px;
    border-radius: 5px;
    border: none;
    outline: none;
    background-color: #435f77;
    color: white;  
    width: 85%;
    height: 45px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 14px;
}

button:hover {
    background-color: White;
    color: Black;
    border-bottom: 2px solid #435f77;
    border-right: 2px solid #435f77;
}     

.already-have-account {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: auto;
}

.already-have-account p{
    font-size: 14px; 
    color: black;     
    margin: 10px 0; 
}

.already-have-account a {
    color: #435f77;  
    text-decoration: none; 
    font-style: italic;
}

.already-have-account a:hover {
    text-decoration: underline; 
}
.role-con{
    width: 85%;
    height: 50px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
}
.role-con button {
    width: 48%; 
    height: 90%;
    background-color: #435f77; 
    color: white;
    border: none; 
    cursor: pointer; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
}

.role-con button.active {
    background-color: white; 
    color: black;
    border-right: 2px solid #435f77;
    border-bottom: 2px solid #435f77;
}

.role-con button:hover {
    color: black;
    background-color: white;
    border-right: 3px solid #435f77;
    border-bottom: 3px solid #435f77;
}

.role-con i {
    margin-right: 20px; 
    font-size: 20px;
    color: white;
}

.role-con button i.active {
    background-color: white; 
    color: black;
}

.role-con button:hover i{
    color: black;
    background-color: white;
}

.additional-inputs {
    display: flex;
    flex-direction: column;
    width: 85%;
    margin-top: 10px;
}

.experience{
    height: 40px; 
    width: 100%;
    margin-bottom: 10px;
    padding-left: 10px; 
    border-radius: 10px;
    border: 1px solid #ccc;
}
.file-upload{
    height: 40px; 
    width: 100%;
    margin-bottom: 10px;
    padding-left: 10px;
    border-radius: 10px;
}
</style>