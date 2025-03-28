<script setup>
import Sidebar from '@/Components/Sidebar.vue';
import Navbar from '@/Components/Navbar.vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import Footer from '@/Components/Footer.vue';
import MainContent from '@/Components/MainContent.vue';

import { nextTick, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

onMounted( async () => {
    await nextTick();
    const t = localStorage.getItem("theme") || "light"; 
    document.body.dataset.theme = t; 
    document.querySelector(".js-stylesheet").setAttribute("href", window.themeUrls[t] || window.themeUrls["light"]);

    const setSidebarLayout = (layout) => {
        document.body.setAttribute('data-sidebar-layout', layout);
        localStorage.setItem('sidebarLayout', layout);
    };
    const savedLayout = localStorage.getItem('sidebarLayout') || 'default';
    setSidebarLayout(savedLayout);
    document.querySelectorAll('input[name="sidebarLayout"]').forEach(input => {
        input.addEventListener('change', (e) => {
            setSidebarLayout(e.target.value);
        });
    });
    
    if (window.feather) {
        window.feather.replace();
    }
});

</script>

<template>
    <div class="wrapper">
        <Sidebar />
        <div class="main">
            <Navbar />
            <Breadcrumbs />
            <MainContent>
                <slot />
            </MainContent>
            <Footer />
        </div>
    </div>
</template>