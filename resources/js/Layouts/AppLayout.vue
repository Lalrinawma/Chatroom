<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';

defineProps({
    title: String,
    users: Object
});

const showingNavigationDropdown = ref(false);
var online = ref(false);

const switchToTeam = (team) => {
    Inertia.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    Inertia.post(route('logout'));
};
</script>

<template>
    <div class="sm:flex items-center justify-center">
        <Head :title="title" />

        <JetBanner />

        <div class="min-h-screen bg-gray-200 overflow-hidden sm:w-1/2">
            <nav class="bg-slate-600 shadow-md border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center gap-4 h-16">
                        <div class="flex items-center">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <JetDropdown align="left" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-12 w-12 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>
                                        <JetDropdownLink :href="route('dashboard')">
                                            Chatroom
                                        </JetDropdownLink>
                                        <JetDropdownLink :href="route('profile.show')">
                                            Profile
                                        </JetDropdownLink>
                                        <div class="border-t border-gray-100" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <JetDropdownLink as="button">
                                                Log Out
                                            </JetDropdownLink>
                                        </form>
                                    </template>
                                </JetDropdown>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="font-bold text-blue-400 font-dosis" >NIELIT CHATROOM</span>
                        </div>

                        <!-- <div style="font-size:10px" class="flex gap-1 h-full font-bold items-center justify-between p-1 ">
                            <div class="border border-green-500 text-green-600 h-full p-1 flex items-center tracking-wider">
                                ONLINE
                            </div>
                        </div> -->
                        <div class="relative inline-block text-left">
                            <div > 
                                <button @click="online = !online" type="button" class="inline-flex text-center  items-center text-green-500 font-dosis justify-center w-full rounded-sm border border-green-300 shadow-sm px-4 py-2 bg-white text-xs font-medium hover:bg-gray-50 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true">
                                ONLINE
                                <!-- Heroicon name: solid/chevron-down -->
                                <div class="h-2 w-2 ml-2 rounded-full bg-green-500 py-1">
                                </div>
                                </button>
                            </div>

                            <!--
                                Dropdown menu, show/hide based on menu state.

                                Entering: "transition ease-out duration-100"
                                From: "transform opacity-0 scale-95"
                                To: "transform opacity-100 scale-100"
                                Leaving: "transition ease-in duration-75"
                                From: "transform opacity-100 scale-100"
                                To: "transform opacity-0 scale-95"
                            -->
                            <div v-show="online" class="origin-top-right absolute z-40 right-1 mt-4 w-44 rounded-sm shadow-lg bg-white focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                <div class="py-1" role="none">
                                  <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                  <a href="#" v-for="user in users" :key="user.id" class="text-gray-700 border-b block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">{{user.name}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
<style scoped>
 @import url('https://fonts.googleapis.com/css2?family=Dosis:wght@500&display=swap');

 .font-dosis{
       
           font-family: 'Dosis', sans-serif;
 }
</style>