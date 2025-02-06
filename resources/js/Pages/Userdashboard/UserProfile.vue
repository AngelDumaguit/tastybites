<template>
    <body>
        <Header 
            :notifications="notifications"
        />
        <main>
            <div class="back-con">
                <a href="/admin/userDashboard"><img src="/public/images/back to me.png" alt="back"></a>
            </div>
            <div class="cover-photo">
                <div class="profile-photo">
                    
                </div>
            </div>
            <div class="user-info-con">
                <h2>Angel Dumaguit <a href="#" id="edit-profile-button"><img src="/public/images/edit.png" alt="edit"></a></h2>
                <p>angeldumaguit1226@gmail.com</p>
                <p>@zellyace</p>
                <div class="edit-profile-box" id="edit-profile-box" style="display: none;">
                    <div class="close-button">
                        <button class="profile-close-button" id="close-profile-box">X</button>
                    </div>
                    <h2>Personal Information</h2>
                    <div class="group-input">
                        <p>Full Name:</p>
                        <input type="text" placeholder="Angel Dumaguit">
                    </div>
                    <div class="group-input">
                        <p>Username:</p>
                        <input type="text" placeholder="@zellyace">
                    </div>
                    <div class="group-input">
                        <p>Email:</p>
                        <input type="text" placeholder="angeldumaguit1226@gmail.com">
                    </div>
                    <button>Save</button>
                </div>
            </div>
            
            <div class="liked-menus-con">
                <div class="liked-headers">
                    <img src="/public/images/shaded-heart.png" alt="heart">
                    <p>Liked Menus</p>
                </div>
                <div class="liked-list">
                    <div class="list-card">
                        <div class="menu-image-con">
                            <img src="/public/images/the transparent.png" alt="image">
                        </div>
                        <h2>Adobo</h2>
                        <p>Filipino Cuisine</p>
                    </div>
                    <div class="list-card">
                        <div class="menu-image-con">
                            <img src="/public/images/the transparent.png" alt="image">
                        </div>
                        <h2>Adobo</h2>
                        <p>Filipino Cuisine</p>
                    </div>
                    <div class="list-card">
                        <div class="menu-image-con">
                            <img src="/public/images/the transparent.png" alt="image">
                        </div>
                        <h2>Adobo</h2>
                        <p>Filipino Cuisine</p>
                    </div>
                    <div class="list-card">
                        <div class="menu-image-con">
                            <img src="/public/images/the transparent.png" alt="image">
                        </div>
                        <h2>Adobo</h2>
                        <p>Filipino Cuisine</p>
                    </div>
                    <div class="list-card">
                        <div class="menu-image-con">
                            <img src="/public/images/the transparent.png" alt="image">
                        </div>
                        <h2>Adobo</h2>
                        <p>Filipino Cuisine</p>
                    </div>
                    <div class="list-card">
                        <div class="menu-image-con">
                            <img src="/public/images/the transparent.png" alt="image">
                        </div>
                        <h2>Adobo</h2>
                        <p>Filipino Cuisine</p>
                    </div>
                    
                </div>
            </div>
        </main>
    </body>
</template>

<script setup>
import Header from './UserHeader.vue';
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { defineProps } from 'vue';

const props = defineProps({
    notifications: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rouge+Script&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
* {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
}
body{
    margin: 0;
    display: flex;
    flex-direction: column;
}
main{
    width: 100%;
    height: auto;
    display: flex;
    flex-direction: column;
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
.user-info-con{
    width: 80%;
    height: auto;
    display: flex;
    flex-direction: column;
    align-self: center;
    margin-top: 50px;
    position: relative;
}


.edit-profile-box{
    position: absolute;
    width: 350px;
    height:auto;
    left: 40%;
    top: -200px;
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