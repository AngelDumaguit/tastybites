<template>
    <div class="chef-main-con">
        <div class="chef-main">
            <div class="chef-top-content">
                <div class="chef-head-table">
                    <h2>CHEF REQUESTS</h2>
                </div>
                <div class="chef-table">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Date Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(chef, index) in pendingChefs" :key="index">
                                <td class="table-id">{{ chef.id }}</td>
                                <td>{{ chef.email }}</td>
                                <td class="table-status">{{ chef.status }}</td>
                                <td>{{ formatDate(chef.created_at) }}</td>
                                <td>{{ formatDate(chef.updated_at) }}</td>
                                <td class="table-action">
                                    <a class="table-accept" @click="acceptChef(chef.id)">Accept</a>
                                    <a class="table-reject" @click="deleteChef(chef.id)">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>                            
                </div>
            </div>
            <div class="chef-bottom-content">
                <div class="chef-bottom-head">
                    <h2>REGISTERED CHEFS</h2>
                </div>
                <div class="register-chef-con">
                    <div class="registered-chef-card" v-for="(chef, index) in activeChefs" :key="index">
                        <div class="img-con-reg-chef">
                            <img src="/public/images/the transparent.png" alt="images">
                        </div>
                        <div class="reg-chef-name-con">
                            <h3>{{ chef.chef.fullname }}</h3>
                        </div>
                        <div class="reg-chef-year-con">
                            <p>{{ formatDate(chef.created_at, 'year') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
import { computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    chefs: {
        type: Array,
        required: true,
    },
});

const formatDate = (dateString, type = 'full') => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    if (type === 'year'){
        return year.toString();
    }

    return `${year}-${month}-${day}`;
};
const pendingChefs = computed(() => {
    return props.chefs.filter(chef => chef.status === 'pending');
});
const activeChefs = computed(() => {
    return props.chefs.filter(chef => chef.status === 'active');
});

const acceptChef = (id) => {
    if (confirm('Are you sure you want to accept this chef?')) {
        Inertia.post(`/admin/acceptChef/${id}`, {}, {
            onSuccess: () => alert('Chef status updated to active.'),
            onError: () => alert('Failed to update chef status.'),
        });
    }
};

const deleteChef = (id) => {
    if (confirm('Are you sure you want to delete this chef? This action cannot be undone.')) {
        Inertia.post(`/admin/deleteChef/${id}`, {}, {
            onSuccess: () => alert('Chef and account deleted successfully.'),
            onError: () => alert('Failed to delete chef.'),
        });
    }
};
</script>

<style scoped>  
.chef-main-con{
    height: 100%;
    width: 100%;
}
.chef-main{
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.chef-top-content{
    width: 90%;
    height: 30%;
    display: flex;
    flex-direction: column;
}
.chef-head-table{
    width: 100%;
    height: 15%;
    display: flex;
    align-items: center;
}
.chef-head-table h2{
    font-style: italic;
}
.chef-table{
    width: 100%;
    height: auto;
}
table {
    width: 100%; 
    border-collapse: collapse; 
}
td:nth-child(6){
    display: flex;
    flex-direction: row;
}
td a{
    text-decoration: none;
    color: black;
}
.table-id{
    text-align: center;
}
.table-status{
    text-align: center;
    font-style: italic;
    font-weight: 700;
    color: red;
}
.table-action{
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 20px;
}
.table-accept{
    width: 100px;
    height: 30px;
    border: 2px solid #006400;
    background-color: #28A745;
    color: black;
    font-size: .8em;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-weight: 600;
}
.table-reject{
    width: 100px;
    height: 30px;
    background-color: #DC3545;
    border: 2px solid #800000;
    color: black;
    font-size: .8em;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-weight: 600;
}
td {
    background-color: #ffffff;
    text-align: center;
    padding: 8px;
    border: 1px solid black;
}

th {
    background-color: #ffffff;
    text-align: center;
    padding: 8px;
    border: 2px solid black;
    font-style: italic;
}

tr:hover {
    background-color: #f1f1f1; 
}
.chef-bottom-content{
    width: 90%;
    height: 60%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.chef-bottom-head{
    width: 100%;
    height: 25%;
    display: flex;
    align-items: center;
}
.chef-bottom-head h2{
    font-style: italic;
}
.register-chef-con {
    display: flex; 
    align-items: center;
    justify-content: center;
    flex-wrap: wrap; 
    width: 100%;
    height: 85%;
    gap: 30px; 
    padding: 10px; 
    overflow: auto; 
}

.register-chef-con::-webkit-scrollbar {
    display: none; 
}

.register-chef-con {
    -ms-overflow-style: none;  
    scrollbar-width: none; 
}

.registered-chef-card {
    width: 180px;
    height: 170px;
    background-color: #E0E7FF;
    border-radius: 20px;
    box-shadow: 5px 5px 5px #888888;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.img-con-reg-chef{
    width: 100%;
    height: 60%;
    display: flex;
    align-items: center;
    justify-content: center; 
}
.img-con-reg-chef img{
    width: 100%;
    height: auto;
}
.reg-chef-name-con{
    width: 100%;
    height: 20%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.reg-chef-year-con{
    width: 100%;
    height: 20%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.reg-chef-year-con p{
    font-size: .6em;
    margin-top: -10px;
}
</style>