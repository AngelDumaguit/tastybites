<template>
<header>
    <div class="logo-con">
    <a href="#"><img src="/public/images/header-logo.png" alt="Tastybites Logo"></a>
    </div>
    <div class="navbar-con">
    <a href="#" id="notification-bell" @click="toggleNotificationBox">
        <img src="/public/images/bell.png" alt="Notifications">
    </a>
    <!-- Emit event on "My Account" button click -->
    <a href="#" type="button" @click="toggleProfile">
        <img src="/public/images/user-logo.png" alt="My Account">
    </a>
    <a href="#" @click="logout"><img src="/public/images/logout.png" alt="Logout"></a>
    </div>
    <div class="notification-box" v-if="isNotificationBoxVisible">
    <div class="notification-head">
        <h2>Notifications</h2>
    </div>
    <div class="notification-body" v-if="notifications.length > 0">
        <ul>
        <li v-for="(notification, index) in notifications" :key="index">
            <p>{{ notification.data.message }}</p>
            <button @click="deleteNotification(notification)">Delete</button>
        </li>
        </ul>
    </div>
    <div v-else>
        <p>No new notifications</p>
    </div>
    </div>
</header>
</template>

<script setup>
import { ref, defineEmits, defineProps } from 'vue';
import { router } from '@inertiajs/vue3';

const emit = defineEmits(['toggleProfile']);
const props = defineProps({
notifications: {
    type: Array,
    required: true,
},
});

const isNotificationBoxVisible = ref(false);

const toggleNotificationBox = () => {
isNotificationBoxVisible.value = !isNotificationBoxVisible.value;
};

const toggleProfile = () => {
    emit('toggleProfile'); // Emit event to parent to toggle Profile visibility
};

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
header {
    width: 100%;
    height: 90px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    background-color: #E0E7FF;
}
.notification-box{
    position: absolute;
    width: 330px;
    height: 330px;
    right: 6%;
    top: 90px;
    z-index: 10;
    overflow-y: auto;
    scrollbar-width: none;
    border-bottom-left-radius: 30px;
    border-bottom-right-radius: 30px;
    border-top-left-radius: 30px;
    box-shadow: 3px 4px 8px black;
    background-color: white;
    }
    .notification-head {
    width: 100%;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: start;
    padding-left: 20px;
    padding-top: 5px;
    position: sticky; 
    top: 0;
    z-index: 10; 
    background-color: #333; 
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
}

.notification-head h2 {
    font-size: 1.5em;
    font-weight: bold;
    color: white; 
    margin: 0; 
}

.notification-body {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: start; 
    max-height: 330px; 
    overflow-y: auto; 
    background-color: #E0E7FF; 
    border-top: 1px solid #ccc; 
    margin-top: -15px;
    font-size: .8em;
    opacity: 100%;
}
.notification-body ul li {
    flex-direction: row;
    display: flex;
    align-items: center;
    justify-content:center;
    padding: 5px;
    padding-right: 6%;
    gap: 5px;
}
.notification-body ul li p{
    margin-left: -15px;
}
.notification-body button{
    width: 130px;
    height: 30px;
    background-color: #435F77;
    border: none;
    border-radius: 20px;
    color: white;
    font-weight: bold;
    box-shadow: 3px 2px 3px black;
}

.notification-body button:hover{
    background-color:white;
    color: red;
    border-right: red 2px solid;
    border-bottom: red 2px solid;
    cursor: pointer;
}
.logo-con{
    width: 12%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center; 
}
.logo-con img{
    width: 100%;
    height: auto;
}   
.navbar-con{
    width: 15%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: end;
    position: relative;
}
.navbar-con a{
    width: 30px;
    height: 30px;
    margin: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.navbar-con a img{
    width: 90%;
    height: auto;
}

</style>