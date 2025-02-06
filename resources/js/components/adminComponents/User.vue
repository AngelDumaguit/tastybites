<template>
    <div class="user-main-con">
        <div class="users-main">
            <div class="header-user-main"></div>
            <div class="header-text-con">
                <h3>Registered Users</h3>
            </div>
            <div class="user-search-con">
                <label for="">Search</label>
                <input type="search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Full Name</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th>Date Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users" :key="index">
                        <td>{{ getEmail(user.account_id) }}</td>
                        <td>{{ user.fullname }}</td>
                        <td class="Pending-active">{{ getStatus(user.account_id) }} </td>
                        <td>{{ formatDate(user.created_at) }}</td>
                        <td>{{ formatDate(user.updated_at) }}</td>
                        <td class="Action-user">
                            <button class="block" @click="toggleAccountStatus(user, index)">
                                {{ getStatus(user.account_id) === 'blocked' ? 'Unblock' : 'Block' }}
                            </button>
                            <button class="Delete" @click="deleteAccount(user, index)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    accounts: {
        type: Array,
        required: true,
    },
});

const getEmail = (accountId) => {
    const account = props.accounts.find((account) => account.id === accountId);
    return account ? account.email : 'Email Not Found';
};

const getStatus = (accountId) => {
    const account = props.accounts.find((account) => account.id === accountId);
    return account ? account.status : 'Status Not Found';
};

const toggleAccountStatus = (account, index) => {
    const newStatus = getStatus(account.account_id) === 'blocked' ? 'active' : 'blocked';
    const action = newStatus === 'blocked' ? 'block' : 'unblock';

    console.log(account.account_id); // Debug ID
    if (confirm(`Are you sure you want to ${action} this account?`)) {
        Inertia.post(`/admin/blockAccount/${account.account_id}`, { status: newStatus }, {
            onSuccess: () => {
                alert('Account status updated successfully');
                location.reload(); // Optional: reload data
            },
            onError: () => {
                alert('Error updating account status');
            }
        });
    }
};

const deleteAccount = (user, index) => {
    if (confirm(`Are you sure you want to delete this account and the associated user?`)) {
        props.users.splice(index, 1);

        // Send the delete request to the backend
        Inertia.post(`/admin/deleteAccount/${user.account_id}`, {}, {
            onSuccess: () => {
                alert('Account and user deleted successfully');
            },
            onError: () => {
                alert('Error deleting account and user');
            }
        });
    }
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

</script>



<style scoped>  
.user-main-con{
    height: 100%;
    width: 100%;
}
.users-main{
    width: 100%;
    height: auto;
    display: flex;
    align-items: center;
    flex-direction: column;
}
.header-user-main{
    width: 90%;
    height: 50px;
    border-bottom: 1px solid black;
}
.header-text-con{
    width: 90%;
    height: 40px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    background-color: white;
}
.header-text-con h3{
    font-size: 1.2em;
    margin-left: 10px;
    font-style: italic;
}
.user-search-con {
    width: 80%;
    height: 40px;
    margin-top: 10px;
    display: flex;
    flex-direction: row;
    justify-content: end;
    align-items: center;
    position: relative;
}

.user-search-con input {
    width: 20%;
    height: 80%;
    padding-left: 40px; 
    font-size: 16px;
    border-radius: 20px;
    background-color: #D9D9D9;
    border: none;
}
.user-search-con label{
    font-size: 1em;
    margin-right: 10px;
}
.user-search-con i {
    position: absolute;
    right: 17%; 
    top: 50%;
    transform: translateY(-50%);
    color: black;
}
.users-main table{
    width: 90%;
    height: auto;
    margin-top: 20px;
    overflow: auto;
    max-height: 300px;
}

.Pending {
    font-style: italic;
    color: red;
    font-weight: bold;
}

.Pending-active {
    font-style: italic;
    color: green;
    font-weight: bold;
}
.Action-user {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.block {
    width: 45%;
    height: 30px;
    background-color: #435F77;
    border-radius: 10px;
    font-weight: bold;
    font-size: .8em;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    box-shadow: 5px 5px 5px #888888;
}

.unblock {
    width: 45%;
    height: 30px;
    background-color: #E0E7FF;
    border-radius: 10px;
    font-weight: bold;
    font-size: .8em;
    color: black;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    box-shadow: 5px 5px 5px #888888;
}

.Delete {
    background-color: red;
    width: 45%;
    height: 30px;
    border-radius: 10px;
    font-weight: bold;
    font-size: .8em;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    box-shadow: 5px 5px 5px #888888;
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
    font-style: italic;
}

tr:hover {
    background-color: #f1f1f1; 
}
</style>