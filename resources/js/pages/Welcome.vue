<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Velkommen">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#f8f8f8] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <div class="duration-750 starting:opacity-0 flex min-h-[80vh] w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <div
                    class="flex-1 justify-items-center gap-4 rounded-bl-lg rounded-br-lg bg-white p-6 pb-12 text-[13px] leading-[20px] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] lg:flex lg:rounded-br-none lg:rounded-tl-lg lg:p-20"
                >
                    <div class="justify-items-center lg:justify-items-start">
                        <h1 class="mb-1 text-xl font-medium">Lad os komme igang!</h1>
                        <p class="mb-2 text-lg text-[#706f6c] dark:text-[#A1A09A]">
                            <span v-if="!$page.props.auth.user">Log ind for at oprette eller ændre i salgsprodukter.</span>
                            <span v-else>Gå til salgsprodukter siden eller log ud.</span>
                        </p>
                        <nav class="mt-6 flex items-center gap-4">
                            <div class="contents" v-if="$page.props.auth.user">
                                <Link
                                    :href="route('salgsprodukter')"
                                    class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm font-medium leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                                >
                                    Salgsprodukter
                                </Link>
                                <Link
                                    class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm font-medium leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                                    method="post"
                                    :href="route('logout')"
                                    as="button"
                                >
                                    <LogOut />
                                    Log ud
                                </Link>
                            </div>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm font-medium leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                                >
                                    Log ind
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm font-medium leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white"
                                >
                                    Opret konto
                                </Link>
                            </template>
                        </nav>
                    </div>
                    <div class="relative -mb-px mt-6 h-20 w-20 shrink-0 overflow-hidden rounded-t-lg lg:ml-40 lg:rounded-r-lg lg:rounded-t-none">
                        <AppLogoIcon />
                    </div>
                </div>
            </main>
        </div>
        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
