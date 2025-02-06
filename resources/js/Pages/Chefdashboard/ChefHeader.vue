<template>
    <header>
        <div class="logo-con">
            <a href="{{ route('home') }}">
                <img src="/public/images/header-logo.png" alt="Tastybites Logo">
            </a>
        </div>
        <div class="navbar-con">
            <a href="#" id="notification-bell" @click="toggleNotificationBox">
                <img src="/public/images/bell.png" alt="Notifications">
            </a>
            <a href="#" @click="toggleProfile">
                <img src="/public/images/navbar-chef.png" alt="My Account">
            </a>
            <a href="#" method="post" @click="logout">
                <img src="/public/images/logout.png" alt="Logout">
            </a>
        </div>
        <div class="notification-box" v-if="isNotificationBoxVisible">
            <div class="notification-head">
                <h2>Notifications</h2>
            </div>
            <div v-if="notifications.length > 0">
                <ul>
                    <li v-for="(notification, index) in notifications" :key="index">
                        <p>{{ notification.data.message }}</p>
                        <!-- Conditional rendering based on purchase status -->
                        <div v-if="notification.data.status === 'pending'">
                            <button @click="confirmPurchase(notification)" class="confirm-button">Confirm</button>
                            <button @click="rejectPurchase(notification)" class="reject-button">Reject</button>
                        </div>
                        <div v-else>
                            <button @click="deleteNotification(notification)" class="delete-button">Delete</button>
                        </div>
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
import { ref, defineProps, defineEmits } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { router } from '@inertiajs/vue3';

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

const confirmPurchase = async (notification) => {
    const purchaseId = notification.data.purchase_id;

    try {
        await Inertia.post('/update-purchase-status', { 
            purchaseId, 
            status: 'owned' 
        });

        // Optionally remove the notification from the UI after confirmation
        const index = props.notifications.findIndex(n => n.id === notification.id);
        if (index !== -1) {
            props.notifications.splice(index, 1); // Remove the notification from the list
        }
    } catch (error) {
        console.error('Error updating purchase status:', error);
    }
};

const rejectPurchase = async (notification) => {
    const purchaseId = notification.data.purchase_id;

    try {
        // Send request to the backend to update the purchase status to 'rejected'
        await Inertia.post('/update-purchase-status', { 
            purchaseId, 
            status: 'rejected' 
        });

        // Optionally remove the notification from the UI after rejection
        const index = props.notifications.findIndex(n => n.id === notification.id);
        if (index !== -1) {
            props.notifications.splice(index, 1); // Remove the notification from the list
        }
    } catch (error) {
        console.error('Error rejecting purchase:', error);
    }
};

const deleteNotification = async (notification) => {
    try {
        // Send delete request to the backend
        await Inertia.delete(`/delete/notifications/${notification.id}`);

        // Remove the notification from the local array
        const index = props.notifications.findIndex(n => n.id === notification.id);
        if (index !== -1) {
            props.notifications.splice(index, 1);
        }
    } catch (error) {
        console.error('Error deleting notification:', error);
    }
};

const emit = defineEmits(['toggleProfile']);

const toggleProfile = () => {
    emit('toggleProfile'); 
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
        position: relative;
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
.confirm-button, .reject-button, .delete-button{
    width: 100px;
    height: 30px;
    background-color: #435F77;
    border: none;
    border-radius: 20px;
    color: white;
    font-weight: bold;
    box-shadow: 3px 2px 3px black;
}
.confirm-button, .reject-button{
    margin-right: 10px;
}
.confirm-button:hover{
    background-color: white;
    color:green;
    border-right: green 2px solid;
    border-bottom: green 2px solid;
    cursor: pointer;
}
.delete-button:hover, .reject-button:hover{
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