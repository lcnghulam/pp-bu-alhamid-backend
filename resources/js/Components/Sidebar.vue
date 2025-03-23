<script setup>
import logoUrl from '@/../assets/img/logo-bu-compact.png';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const isActive = (routeName) => {
    return computed(() => route().current(routeName));
};
</script>

<template>
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <span class='sidebar-brand'>
                <span class="sidebar-brand-text align-middle">
                    Pondok Bahrul Ulum <br>Al-Hamid
                </span>
                <img :src="logoUrl"
                    alt="Pondok Bahrul Ulum Al-Hamid" class="sidebar-brand-icon align-middle">
            </span>

            <ul class="sidebar-nav">
                <li class="sidebar-item" :class="{ 'active': isActive('dashboard.index').value }">
                    <Link class="sidebar-link" :href="route('dashboard.index')">
                        <i class="align-middle" data-feather="home"></i>
                        <span class="align-middle">Dashboard</span>
                    </Link>
                </li>

                <li class="sidebar-header">Master Data</li>

                <li class="sidebar-item" :class="{ 'active': isActive('data-santri*').value }">
                    <Link class='sidebar-link' :href="route('data-santri')">
                        <i class="align-middle" data-feather="users"></i>
                        <span class="align-middle">Data Santri</span>
                    </Link>
                </li>
                <li class="sidebar-item @active('posts')">
                    <Link class='sidebar-link' href='#'>
                        <i class="align-middle" data-feather="edit-3"></i>
                        <span class="align-middle">Posts</span>
                    </Link>
                </li>
                <li class="sidebar-item @active('jadwal')">
                    <Link class='sidebar-link' href='#'>
                        <i class="align-middle" data-feather="calendar"></i>
                        <span class="align-middle">Jadwal</span>
                    </Link>
                </li>
                <li class="sidebar-item">
                    <Link class='sidebar-link' href='#'>
                        <i class="align-middle" data-feather="image"></i>
                        <span class="align-middle">Galeri</span>
                    </Link>
                </li>
                <li class="sidebar-item">
                    <Link class='sidebar-link' href='#'>
                        <i class="align-middle" data-feather="paperclip"></i>
                        <span class="align-middle">Pengumuman</span>
                    </Link>
                </li>
                <li class="sidebar-item">
                    <Link class='sidebar-link' href='#'>
                        <i class="align-middle" data-feather="archive"></i>
                        <span class="align-middle">Arsip</span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>