<template>
<div class="dashboard-main-con">
    <h2>Recipe Statistics - Recipes Per Week</h2>
    <canvas id="recipeChart"></canvas>
</div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
weeklyRecipes: {
    type: Array,
    required: true,
},
});

const recipeChart = ref(null);

onMounted(() => {
const ctx = document.getElementById('recipeChart').getContext('2d');

// Extract week numbers and recipe counts from props
const weeks = props.weeklyRecipes.map((item) => `Week ${item.week_number}`);
const counts = props.weeklyRecipes.map((item) => item.recipe_count);

// Render bar chart
new Chart(ctx, {
    type: 'bar',
    data: {
    labels: weeks,
    datasets: [
        {
        label: 'Recipes Added Per Week',
        data: counts,
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        },
    ],
    },
    options: {
    responsive: true,
    plugins: {
        legend: {
        display: true,
        position: 'top',
        },
    },
    scales: {
        y: {
        beginAtZero: true,
        title: {
            display: true,
            text: 'Number of Recipes',
        },
        },
        x: {
        title: {
            display: true,
            text: 'Weeks',
        },
        },
    },
    },
});
});
</script>

<style scoped>
.dashboard-main-con {
    height: 100%;
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 600;
}

canvas {
    max-width: 80%;
    height: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
