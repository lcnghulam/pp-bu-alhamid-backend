<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

// Get Current URL (Tanpa Query String)
const page = usePage();

const isDashboard = computed(() => {
    return page.url === '/';
});

const cleanUrl = computed(() => page.url.split('?')[0]); // Hapus query string

// Breadcrumbs Breakdown
const breadcrumbs = computed(() => {
    const segments = cleanUrl.value.split('/').filter(segment => segment);
    let fullPath = '';
    
    let items = segments.map((segment, index) => {
        fullPath += `/${segment}`;
        return {
            name: segment.charAt(0).toUpperCase() + segment.slice(1), 
            url: index === segments.length - 1 ? null : fullPath, 
        };
    });

    // Root of Breadcrumbs
    if (items.length > 0) {
        items.unshift({
            name: 'Dashboard',
            url: '/',
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
        font-weight: lighter;
    }

    li a:hover {
        color: var(--bs-primary-color);
    }

    li.breadcrumb-item.active {
        font-weight: bolder;
    }
</style>