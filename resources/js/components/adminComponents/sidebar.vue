<template>
    <div class="sidebar">
        <div class="side-bar-header">
            <div class="sidebar-logo-con">
                <img src="/public/images/header-logo.png" alt="Logo" />
            </div>
            <a class="sidebar-back-con" href="/admin/adminDashboard">
                <img src="/public/images/sidebar-1.png" alt="Back" />
            </a>
        </div>

        <div class="sidebar-navbar">
            <a href="javascript:void(0)" class="navbar-item dashboard-bttn" @click="emitSection('dashboard')">
                <div class="navbar-bttn-image">
                    <img src="/public/images/sidebar-dashboard.png" alt="Dashboard" />
                </div>
                <div class="navbar-label-bttn">
                    <p>Dashboard</p>
                </div>
            </a>
            <a href="javascript:void(0)" class="navbar-item chef-bttn" @click="emitSection('chef')">
                <div class="chef-img">
                    <img src="/public/images/navbar-chef.png" alt="Chef" />
                </div>
                <div class="navbar-label-bttn">
                    <p>Chef</p>
                </div>
            </a>
            <a href="javascript:void(0)" class="navbar-item recipe-bttn" @click="emitSection('recipe')">
                <div class="recipe-img">
                    <img src="/public/images/navbar-recipe.png" alt="Recipe" />
                </div>
                <div class="navbar-label-bttn">
                    <p>Recipe</p>
                </div>
            </a>
            <a href="javascript:void(0)" class="navbar-item users-bttn" @click="emitSection('users')">
                <div class="navbar-bttn-image">
                    <img src="/public/images/AdminUser.png" alt="Users" />
                </div>
                <div class="navbar-label-bttn">
                    <p>Users</p>
                </div>
            </a>
        </div>

        <div class="sidebar-logout-con">
            <!-- Logout Button -->
            <a @click="logout" class="logout-button">
                <img src="/public/images/logout.png" alt="Logout" /> LOGOUT
            </a>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { defineEmits } from 'vue';

// Emit events for navigation
const emit = defineEmits(['change-section']);
function emitSection(section) {
    emit('change-section', section);
}

// Logout function
function logout() {
    router.post('/logout', {}, {
        onSuccess: () => {
            console.log('Logged out successfully');
        },
        onError: (errors) => {
            console.error('Logout error:', errors);
        },
    });
}
</script>



<style scoped>
.sidebar{
    width: 18%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background-color: #B9D5D8;
}
.side-bar-header{
    width: 100%;
    height: 15%;
    display: flex;
    flex-direction: row;
}
.sidebar-logo-con{
    width: 60%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sidebar-logo-con img{
    width: 100%;
    height: auto;
}
.sidebar-back-con{
    width: 40%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sidebar-back-con img{
    width: auto;
    height: 25%;
}
.sidebar-navbar{
    width: 100%;
    height: 50%;
    margin-bottom: 40px;
    display: flex;
    flex-direction: column;
    align-items: end;
}
.sidebar-navbar a{
    width: 80%;
    height: 15%;
    margin-top: 20px;
    display: flex;
    flex-direction: row;
    text-decoration: none;
    gap: 20px;
    font-weight: 500;
    padding-left: 10px;
}
.sidebar-navbar a.active {
    background-color: #f0f0f0; 
    color: #000;
    border-radius: 30px 0 0 30px;
    font-weight: 500;
}
.sidebar-navbar a:hover {
    background-color: white; 
    color: #000;
    border-radius: 30px 0 0 30px;
    font-weight: 500;
}
.navbar-bttn-image{
    width: 20%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.navbar-bttn-image img{
    width: 90%;
    height: auto;
}
.navbar-label-bttn{
    width: 80%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: start;
}
.navbar-label-bttn p{
    color: black;
    font-size: 1.1em;
}
.sidebar-logout-con{
    width: 100%;
    height: 15%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sidebar-logout-con img{
    width: 10%;
    height: auto;
}
.sidebar-logout-con a{
    width: 60%;
    height: 30%;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    border-radius: 20px;
    background-color: white;
    color: #000;
    font-size: 12px;
    gap: 10px;
}
.chef-img img {
    width: 55%;
    height: auto;

} 
.chef-img {
    width: 20%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.recipe-img img {
    width: 120%;
    height: auto;
}
.recipe-img {
    width: 20%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>