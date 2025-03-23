<script setup>
import { computed, onMounted, watch } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

// Get Current URL
const page = usePage();

// **Cek apakah halaman adalah Dashboard**
const isDashboard = computed(() => {
    return page.url === '/' || page.url === '/dashboard';
});

// **Bangun breadcrumbs berdasarkan URL**
const breadcrumbs = computed(() => {
    const segments = page.url.split('/').filter(segment => segment);
    let fullPath = '';
    
    let items = segments.map((segment, index) => {
        fullPath += `/${segment}`;
        return {
            name: segment.charAt(0).toUpperCase() + segment.slice(1), // Kapitalisasi nama
            url: index === segments.length - 1 ? null : fullPath, // Hanya beri URL ke breadcrumb sebelum terakhir
        };
    });

    // **Tambahkan Dashboard sebagai root breadcrumbs**
    if (items.length > 0) {
        items.unshift({
            name: 'Dashboard',
            url: '/dashboard',
        });
    }

    return items;
});

</script>

<template>
    <div v-if="!isDashboard" class="card mt-3 mb-1" style="border-radius: 0;">
        <nav class="d-flex align-items-center ps-3" aria-label="breadcrumb">
            <!-- Feather Icon -->
            <i class="align-middle me-2" data-feather="chevron-right"></i>
            <ol class="breadcrumb mb-0">
                <li v-for="(breadcrumb, index) in breadcrumbs" :key="index"
                    :class="{ 'breadcrumb-item': true, 'active': !breadcrumb.url }">
                    <template v-if="breadcrumb.url">
                        <Link :href="breadcrumb.url">{{ breadcrumb.name }}</Link>
                    </template>
                    <template v-else>
                        {{ breadcrumb.name }}
                    </template>
                </li>
            </ol>
        </nav>
    </div>
</template>

<style lang="css" scoped>
    li a {
        color: var(--bs-breadcrumb-item-active-color);
        text-decoration: none;
        font-weight: bolder;
    }

    li a:hover {
        color: var(--bs-primary-color);
    }

    li.breadcrumb-item.active {
        font-weight: lighter;
    }
</style>