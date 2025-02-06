<template>
    <div class="login-body">
        <div class="left-side">
            <div class="logo-container">
                <img src="/public/images/TastyBites.png" alt="Tastybites Logo">
            </div>
        </div>
        <div class="right-side">
            <div class="login-container">
                <div class="login-header">
                    <p>LOGIN</p>
                </div>
                <form class="login-form" @submit.prevent="login">
                    <input type="email" v-model="form.email" placeholder="Email" name="email" required>
                    <i class="fa-solid fa-envelope"></i>
                    <input type="password" v-model="form.password" placeholder="Password" required>
                    <i class="fa-solid fa-lock"></i>
                    <button type="submit">Login</button>
                </form>
                <div class="dont-have-account">
                    <p>Don't have an account? <Link href="/register">Register</Link></p>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/inertia-vue3';

const form = reactive({
    email: '',
    password: '',
});

const login = () => {
    Inertia.post('/login', form, {
        onSuccess: () => {
            console.log('Login successful');
        },
        onError: (errors) => {
            console.error('Login failed', errors);
            alert('Login failed. Please check your credentials.');
        },
    });
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
.login-body {
    font-family: 'Poppins', sans-serif;
    overflow: hidden;
    height: 100vh;
}
.left-side {
    width: 47%;
    height: 100vh;
    float: left;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.logo-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    margin: auto;
}

.left-side img {
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

.right-side {
    width: 47%;
    height: 100vh;
    float: right;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 70%;
    height: 55%;
    margin: auto;
    background-color: #E0E7FF;
    border-radius: 30px;
    margin: auto;
}

.login-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 20%;
    padding: 10px;
    padding-bottom: 0%;
}

.login-header p {
    color: black;
    font-weight:550;
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

input {
    padding: 10px;
    margin: 10px;
    border-radius: 5px;
    border: none;
    outline: none;
    width: 80%;
    height: 28px;
}

.fa-envelope {
    top: 37%;
    right: 12.5%;
    position: absolute;
    color: gray;
} 

.fa-lock {
    top: 43%;
    right: 12.5%;
    position: absolute;
    color: grey;
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

.dont-have-account {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: auto;
}

.dont-have-account p{
    font-size: 14px; 
    color: black;     
    margin: 10px 0; 
}

.dont-have-account a {
    color: #3498db;  
    text-decoration: none; 
    font-style: italic;
}

.dont-have-account a:hover {
    text-decoration: underline; 
}
</style>