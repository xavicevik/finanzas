<script setup>
import { ref, computed  } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';
import { usePage } from '@inertiajs/inertia-vue3'

defineProps({
    title: String,
    isOpenCart: false,
    total: null,
});

import Button from "../Jetstream/Button";
const showingNavigationDropdown = ref(false);
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
<script>
export default {
    data() {
        return {
            cartx: [],
            isMenutransac: false,
            isMenuConfig: false,
            isMenuUsers: false,
            isMenuPuntoventa: false,
            isMenuCajas: false,
            isMenuRifas: false,
            isMenuVentas: false,
            isMenuComision: false,
            isMenuExamen: false,
            sidebarOpen: true,
            selectedRow: null,
        }
    },
    computed: {

    },
}
</script>

<template>
    <div x-data="{ sidebarOpen: true }" class="flex overflow-x-hidden h-screen">
        <aside class="h-screen bg-white text-red-900 overflow-auto sticky top-0 flex-shrink-0 w-12 sm:w-52 md:w-64 flex flex-col border-r transition-all duration-300" :class="{ '-ml-64': !sidebarOpen }">
            <div class="flex flex-col h-screen">
                <div class="flex-grow">
                    <ul class="flex flex-col py-2 space-y-1">
                        <span>
                            <img :src="'../storage/img/2_logo_fondo.png'" class="mx-auto w-1/2 sm:w-full" alt=""/>
                        </span>
                        <li class="px-5 md:block">
                            <div class="flex flex-row items-center h-8">
                                <div class="text-sm font-bold tracking-wide text-gray-900 uppercase">Menú</div>
                            </div>
                        </li>
                        <li v-if="$can('dashboard-list')">
                            <Link :href="route('bidashboard')" @click="rowSelect('bidashboard')" :class=" (selectedRow === 'bidashboard' || route().current('bidashboard')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-red-500">
                                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Dashboard</span>
                            </Link>
                        </li>
                        <li>
                            <a v-if="$can('ventas-list')" href="#" @click="isMenuVentas = !isMenuVentas; rowSelect('isMenuVentas')" :class=" (selectedRow === 'isMenuVentas' || route().current('ventas.index')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-8 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Reposición</span>
                            </a>
                        </li>
                        <div v-show="isMenuVentas && $can('ventas-list')" class="pl-8 bg-blue-100 text-white-600 hover:text-white-600">
                            <li>
                                <Link v-if="$can('ventas-list')" :href="route('ventas.index')" class="relative flex flex-row items-center h-8 focus:outline-none border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                      <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                  </svg>
                                </span>
                                    <span class="ml-2 text-sm tracking-wide truncate">Buscar</span>
                                </Link>
                            </li>
                            <li>
                                <Link v-if="$can('ventas-create')" :href="route('ventas.create')" href="#" class="relative flex flex-row items-center h-8 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                            <span class="inline-flex justify-center items-center ml-4">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            </span>
                                    <span class="ml-2 text-sm tracking-wide truncate">Nueva</span>
                                </Link>
                            </li>
                        </div>
                        <li>
                            <Link v-if="$can('users-list')" :href="route('users.index')" :class=" (selectedRow === 'users.index' || route().current('users.index')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-pink-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Usuarios</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('militantes-list')" :href="route('militantes.index')" :class=" (selectedRow === 'militantes.index' || route().current('militantes.index')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-blue-500">
                                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Militantes</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('aval-list')" :href="route('avales.index')" :class=" (selectedRow === 'avales.index' || route().current('avales.index')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-blue-500">
                                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Avales</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('simpatizantes-list')" href="" class="relative flex flex-row items-center h-11 focus:outline-none text-gray-600 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Simpatizantes(v2)</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('listas-list')" href="" class="relative flex flex-row items-center h-11 focus:outline-none text-gray-600 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Lideres y Listas(v2)</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('testigos-list')" href="" class="relative flex flex-row items-center h-11 focus:outline-none text-gray-600 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Testigos electorales(v2)</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('diad-list')" href="" class="relative flex flex-row items-center h-11 focus:outline-none text-gray-600 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Día “D”(v2)</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('auditoria-list')" :href="route('militantes.indexAuditoria')" :class=" (selectedRow === 'militantes.indexAuditoria' || route().current('militantes.indexAuditoria')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-green-500">
                                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                                  </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Auditoria</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('configuracion-list')" :href="route('master.paises')" :class=" (selectedRow === 'master.paises' || route().current('master.paises')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4">
                                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                  </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Configuración</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('cursos-edit')" :href="route('examens.index')" :class=" (selectedRow === 'examens.index' || route().current('examens.index')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Cursos</span>
                            </Link>
                        </li>
                        <li>
                            <Link v-if="$can('cursos-list')" :href="route('examens.show', 1)" :class=" (selectedRow === 'examens.show' || route().current('examens.show')) ? 'border-blue-500 bg-blue-200' : 'hover:border-gray-500'" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-blue-200 text-white-600 hover:text-white-800 border-l-4 border-transparent pr-6">
                                <span class="inline-flex justify-center items-center ml-4 text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                    </svg>
                                </span>
                                <span class="ml-2 text-sm tracking-wide truncate">Curso</span>
                            </Link>
                        </li>

                    </ul>
                </div>
                <footer class="text-center text-white">
                    <div class="container pt-4">
                        <div class="flex justify-center mb-4">
                            <a href="#!" class="mx-3 text-gray-800">
                                <svg
                                    aria-hidden="true"
                                    focusable="false"
                                    data-prefix="fab"
                                    data-icon="facebook-f"
                                    class="svg-inline--fa fa-facebook-f w-2.5"
                                    role="img"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 320 512"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
                                    ></path>
                                </svg>
                            </a>
                            <a href="#!" class="mx-3 text-gray-800">
                                <svg
                                    aria-hidden="true"
                                    focusable="false"
                                    data-prefix="fab"
                                    data-icon="instagram"
                                    class="svg-inline--fa fa-instagram w-3.5"
                                    role="img"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
                                    ></path>
                                </svg>
                            </a>
                            <a href="#!" class="mx-3 text-gray-800">
                                <svg
                                    aria-hidden="true"
                                    focusable="false"
                                    data-prefix="fab"
                                    data-icon="linkedin-in"
                                    class="svg-inline--fa fa-linkedin-in w-3.5"
                                    role="img"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512"
                                >
                                    <path
                                        fill="currentColor"
                                        d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"
                                    ></path>
                                </svg>
                            </a>

                        </div>
                    </div>

                    <div class="text-center text-gray-800 p-2 text-sm bg-gray-200">
                        © 2023 Copyright:
                        <a class="text-gray-800 text-sm" href="https://www.afrotic.com.co/">AfroTic</a>
                    </div>
                </footer>
            </div>
        </aside>
        <div class="flex-1">
            <header class="sticky top-0 w-full flex items-center p-4 text-semibold text-blue-900 bg-white">
                <button class="p-1 mr-4 text-black" @click="sidebarOpen = !sidebarOpen;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <!-- Header -->
                <div class="flex items-center w-1/3 justify-start md:justify-center pl-3 h-10 border-none">
                    <img v-if="$page.props.user.profile_photo_path" class="w-5 md:w-8 mr-2 rounded-md overflow-hidden" :src="'../storage/' + $page.props.user.profile_photo_path" />
                    <img v-else class="w-5 h-5 md:w-7 md:h-7 mr-2 rounded-md overflow-hidden" src="https://therminic2018.eu/wp-content/uploads/2018/07/dummy-avatar.jpg" />

                    <span class="hidden md:block">
                        <span class="font-bold"> {{ $page.props.auth.user.roles[0].name }}</span> -
                        {{ $page.props.user.full_name }}
                    </span>
                </div>
                <div class="flex w-1/3 justify-end pr-2 items-right h-10 header-right">
                    <ul class="flex">
                        <Popper content="Profile" hover=true placement="top" arrow=true>
                            <a href="/user/profile">
                                <button class="flex mr-4 hover:text-blue-100">
                                    <li>
                                        <div class="block w-px h-7 mx-3 px-3 text-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                    </li>
                                </button>
                            </a>
                        </Popper>
                        <li>
                            <div class="block w-px h-7 mx-3 px-3 text-yellow-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                                </svg>
                            </div>
                        </li>

                        <li>
                            <div class="block w-px h-7 mx-3 px-4 text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                                </svg>
                            </div>
                        </li>
                        <li>
                            <div class="block w-px h-7 mx-3 px-4">
                            <!-- Authentication -->
                            <Popper content="Salir" hover=true placement="top" arrow=true>
                                <form method="POST" @submit.prevent="logout">
                                    <button class="flex mr-4 hover:text-blue-100">
                                        <span class="inline-flex mr-1">
                                          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        </span>
                                    </button>
                                </form>
                            </Popper>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- ./Header -->

            </header>
            <main class="p-4">
                <!-- Page Content -->
                <main>
                    <slot />
                </main>
            </main>
        </div>
    </div>
</template>
<style>
/* Compiled dark classes from Tailwind */
.dark .dark\:divide-gray-700 > :not([hidden]) ~ :not([hidden]) {
    border-color: rgba(55, 65, 81);
}
.dark .dark\:bg-gray-50 {
    background-color: rgba(249, 250, 251);
}
.dark .dark\:bg-gray-100 {
    background-color: rgba(243, 244, 246);
}
.dark .dark\:bg-gray-600 {
    background-color: rgba(75, 85, 99);
}
.dark .dark\:bg-blue-100 {
    background-color: rgba(55, 65, 81);
}
.dark .dark\:bg-gray-800 {
    background-color: rgba(31, 41, 55);
}
.dark .dark\:bg-gray-900 {
    background-color: rgba(17, 24, 39);
}
.dark .dark\:bg-red-700 {
    background-color: rgba(185, 28, 28);
}
.dark .dark\:bg-green-700 {
    background-color: rgba(4, 120, 87);
}
.dark .dark\:hover\:bg-gray-200:hover {
    background-color: rgba(229, 231, 235);
}
.dark .dark\:hover\:bg-gray-600:hover {
    background-color: rgba(75, 85, 99);
}
.dark .dark\:hover\:bg-blue-100:hover {
    background-color: rgba(55, 65, 81);
}
.dark .dark\:hover\:bg-gray-900:hover {
    background-color: rgba(17, 24, 39);
}
.dark .dark\:border-gray-100 {
    border-color: rgba(243, 244, 246);
}
.dark .dark\:border-gray-400 {
    border-color: rgba(156, 163, 175);
}
.dark .dark\:border-gray-500 {
    border-color: rgba(107, 114, 128);
}
.dark .dark\:border-gray-600 {
    border-color: rgba(75, 85, 99);
}
.dark .dark\:border-gray-700 {
    border-color: rgba(55, 65, 81);
}
.dark .dark\:border-gray-900 {
    border-color: rgba(17, 24, 39);
}
.dark .dark\:hover\:border-gray-800:hover {
    border-color: rgba(31, 41, 55);
}
.dark .dark\:text-white {
    color: rgba(255, 255, 255);
}
.dark .dark\:text-gray-50 {
    color: rgba(249, 250, 251);
}
.dark .dark\:text-gray-100 {
    color: rgba(243, 244, 246);
}
.dark .dark\:text-gray-200 {
    color: rgba(229, 231, 235);
}
.dark .dark\:text-gray-400 {
    color: rgba(156, 163, 175);
}
.dark .dark\:text-gray-500 {
    color: rgba(107, 114, 128);
}
.dark .dark\:text-gray-700 {
    color: rgba(55, 65, 81);
}
.dark .dark\:text-gray-800 {
    color: rgba(31, 41, 55);
}
.dark .dark\:text-red-100 {
    color: rgba(254, 226, 226);
}
.dark .dark\:text-green-100 {
    color: rgba(209, 250, 229);
}
.dark .dark\:text-blue-400 {
    color: rgba(96, 165, 250);
}
.dark .group:hover .dark\:group-hover\:text-gray-500 {
    color: rgba(107, 114, 128);
}
.dark .group:focus .dark\:group-focus\:text-gray-700 {
    color: rgba(55, 65, 81);
}
.dark .dark\:hover\:text-gray-100:hover {
    color: rgba(243, 244, 246);
}
.dark .dark\:hover\:text-blue-500:hover {
    color: rgba(59, 130, 246);
}
/* Custom style */
.header-right {
    width: calc(100% - 3.5rem);
}
.sidebar:hover {
    width: 16rem;
}
@media only screen and (min-width: 768px) {
    .header-right {
        width: calc(100% - 16rem);
    }
}
</style>
