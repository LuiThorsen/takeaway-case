<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar">
            <!-- <AppSidebarHeader :breadcrumbs="breadcrumbs" /> -->
            <slot />
        </AppContent>
    </AppShell>
</template>
