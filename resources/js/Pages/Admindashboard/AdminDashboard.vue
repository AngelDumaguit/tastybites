<template>
    <div class="admin-body">
        <header>
            <div class="header-logo-con">
                <img src="/public/images/header-logo.png" alt="Tastybites Logo">
            </div>
            <div class="header-logout-con">
                <a class="header-logout-bttn" @click="logout">
                    <img src="/public/images/logout.png" alt="Logout">
                </a>
            </div>
        </header>
        <main>
            <div class="left-side-con">
                <div class="button-con">
                    <div class="top-row">
                        <a class="dashboard-bttn" @click="navigateTo('dashboard')">
                            <div class="bttn-image-con">
                                <img src="/public/images/dashboard.png" alt="Dashboard">
                            </div>
                            <div class="bttn-label-con">
                                <p>Dashboard</p>
                            </div>
                        </a>
                        <a class="chef-bttn" @click="navigateTo('chef')">
                            <div class="bttn-image-con">
                                <img src="/public/images/chef.png" alt="Chef">
                            </div>
                            <div class="bttn-label-con">
                                <p>Chef</p>
                            </div>
                        </a>
                    </div>
                    <div class="bottom-row">
                        <a class="recipe-bttn" @click="navigateTo('recipe')">
                            <div class="bttn-image-con">
                                <img src="/public/images/recipe.png" alt="Recipe">
                            </div>
                            <div class="bttn-label-con">
                                <p>Recipe</p>
                            </div>
                        </a>
                        <a class="users-bttn" @click="navigateTo('users')">
                            <div class="bttn-image-con">
                                <img src="/public/images/user.png" alt="Users">
                            </div>
                            <div class="bttn-label-con">
                                <p>Users</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="right-side-con">
                <div class="right-image-con">
                    <img src="/public/images/TastyBites.png" alt="images">
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3';


const navigateTo = (route) => {
    Inertia.visit('/adminMain', {
        data: { section: route },
    })
}

const props = defineProps({
    user: Array, 
    chef: Array,
    account: Array,
})
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
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rouge+Script&display=swap');
.admin-body {
    height: 100vh;
    margin: 0;
    padding: 0;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
}
header{
    height: 100px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
.header-logo-con{
    width: 20%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: start;
}
.header-logo-con img{
    width: 80%;
    height: auto;
}
.header-logout-con{
    width: 20%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: end;
}
.header-logout-bttn{
    width: 20%;
    height: 60%;
    margin-right: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.header-logout-bttn img{
    width: 70%;
    height: auto;
}
main{
    height: 86vh;
    width: 100%;
    display: flex;
    flex-direction: row;
}
.left-side-con{
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.button-con{
    width: 70%;
    height: 70%;
}
.top-row,.bottom-row{
    width: 100%;
    height: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: row;
    gap: 20px;
}
.dashboard-bttn,.chef-bttn,.recipe-bttn,.users-bttn{
    width: 50%;
    height: 90%;
    margin: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background-color: #E0E7FF;
    border-radius: 20px;
    text-decoration: none;
}
.bttn-image-con{
    width: 100%;
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
.bttn-image-con img{
    width: 100%;
    height: auto;
}
.bttn-label-con{
    width: 100%;
    height: 25%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bttn-label-con p{
    color: black;
    font-size: 1em;
    font-family: 'poppins';
    font-weight: 400;
}
.right-side-con{
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.right-image-con{
    width: 80%;
    height: 80%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.right-image-con img{
    width: 100%;
    height: auto;
}
</style>
