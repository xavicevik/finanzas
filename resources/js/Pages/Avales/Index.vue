<template>
    <AppLayout title="Avales">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Solicitudes de aval
            </h2>
        </template>
        <div class="py-4 lg:px-4 md:px-2 sm:px-2">
            <div class="mx-auto 2xl:8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- Mensajes Flash -->
                    <section>
                        <div @click="cleanMessage()" mx-auto class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert" v-show="$page.props.flash.message">
                            <div class="flex">
                                <div>
                                    <p class="text-sm">{{ $page.props.flash.message }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Fin Mensajes Flash -->
                    <!-- Encabezado y titulo -->
                    <section>
                        <div class="flex justify-between mx-auto p-4">
                            <div class="w-1/3">
                                <h1 class="font-bold text-xl text-black-800 leading-tight">
                                    Solicitudes de Aval
                                </h1>
                            </div>

                            <div v-if="roluser === 3 && Object.keys(arrayData.data).length === 0" class="pr-2 w-1/3 text-center">
                                <button v-if="$can('aval-create')" @click="solicitudAvalMilitante(militante)" class="bg-blue-500 text-xs  hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">Registrar Solicitud</button>
                            </div>
                            <div class="w-1/6 text-center">
                                <button v-if="$can('aval-import')" @click="openModalImport()" class="bg-orange-500 text-xs  hover:bg-orange-700 text-white font-bold py-2 px-4 rounded ">Importar</button>
                            </div>
                            <div class="w-1/6 text-center">
                                <button v-if="$can('aval-import')" @click="isImportprogress = true; handleChange()" class="bg-orange-500 text-xs  hover:bg-orange-700 text-white font-bold py-2 px-4 rounded ">Progreso</button>
                            </div>
                        </div>
                    </section>
                    <!-- Fin Encabezado y titulo Busqueda -->
                    <section v-if="roluser != 3">
                        <div class="px-4">
                            <form @submit.prevent="getSolicitudesAval('', '', formbusqueda)" @keyup.enter="getSolicitudesAval('', '', formbusqueda)">
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <input type="text" v-model="formbusqueda.documento" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "  />
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Documento
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <input type="text" v-model="formbusqueda.nombre" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" "  />
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Nombre
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="formbusqueda.idcorporacion">
                                            <option value="0" >Seleccione</option>
                                            <option v-for="dato in arrayCorporaciones" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Corporación
                                        </label>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="formbusqueda.estado_ant">
                                            <option value="0" >Seleccione Estado</option>
                                            <option v-for="dato in estados" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Estado anterior
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="formbusqueda.estado">
                                            <option value="0" >Seleccione Estado</option>
                                            <option v-for="dato in estados" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Estado actual
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="formbusqueda.idrevisor">
                                            <option value="0" >Seleccione</option>
                                            <option v-for="dato in revisores" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Revisor
                                        </label>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-4 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="formbusqueda.iddelegado">
                                            <option value="0" >Seleccione</option>
                                            <option v-for="dato in delegados" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Delegado
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <Datepicker v-model="formbusqueda.fechainicioregistro" :enableTimePicker="false" autoApply placeholder="Fecha inicio registro" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <Datepicker v-model="formbusqueda.fechafinregistro" :enableTimePicker="false" autoApply placeholder="Fecha fin registro" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="formbusqueda.iddepartamento" @change="getCiudadesBusqueda">
                                            <option value="0" >Seleccione departamento</option>
                                            <option v-for="departamento in departamentos" :key="departamento.id" :value="departamento.id" v-text="departamento.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Departamento
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <Datepicker v-model="formbusqueda.fechainiciorevision" :enableTimePicker="false" autoApply placeholder="Fecha inicio revisión" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <Datepicker v-model="formbusqueda.fechafinrevision" :enableTimePicker="false" autoApply placeholder="Fecha fin revisión" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" v-model="formbusqueda.idciudad">
                                            <option value="0" >Seleccione Ciudad</option>
                                            <option v-for="ciudad in arrayCiudades" :key="ciudad.id" :value="ciudad.id" v-text="ciudad.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Ciudad
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <Datepicker v-model="formbusqueda.fechainicioaprobacion" :enableTimePicker="false" autoApply placeholder="Fecha inicio aprobación" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <Datepicker v-model="formbusqueda.fechafinaprobacion" :enableTimePicker="false" autoApply placeholder="Fecha fin aprobación" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"/>
                                    </div>
                                </div>
                                <div class="flex mx-auto">
                                    <button type="button" @click="getSolicitudesAval('', '', formbusqueda)" class="mx-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Buscar</button>
                                    <Popper v-if="false" content="Exportar" hover=true placement="top" arrow=true>
                                        <a  href="#" @click="militantesExport(formbusqueda)">
                                            <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" class="h-8 w-8"><path fill="#4CAF50" d="M41,10H25v28h16c0.553,0,1-0.447,1-1V11C42,10.447,41.553,10,41,10z"/><path fill="#FFF" d="M32 15H39V18H32zM32 25H39V28H32zM32 30H39V33H32zM32 20H39V23H32zM25 15H30V18H25zM25 25H30V28H25zM25 30H30V33H25zM25 20H30V23H25z"/><path fill="#2E7D32" d="M27 42L6 38 6 10 27 6z"/><path fill="#FFF" d="M19.129,31l-2.411-4.561c-0.092-0.171-0.186-0.483-0.284-0.938h-0.037c-0.046,0.215-0.154,0.541-0.324,0.979L13.652,31H9.895l4.462-7.001L10.274,17h3.837l2.001,4.196c0.156,0.331,0.296,0.725,0.42,1.179h0.04c0.078-0.271,0.224-0.68,0.439-1.22L19.237,17h3.515l-4.199,6.939l4.316,7.059h-3.74V31z"/></svg>
                                        </a>
                                    </Popper>
                                </div>
                            </form>
                        </div>
                    </section>
                    <!-- Tabla de contenido -->
                    <section>
                        <div class="lg:px-4 md:px-2 sm:px-0 py-2 pb-6">
                            <table class="table-fixed w-full">
                                <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 w-1/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'fechaingreso')" class="font-bold">
                                            Fecha de solicitud
                                            <div v-show="sortBy == 'nombre'">
                                                <span v-show="!sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <span v-show="sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                      <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </button>
                                    </th>
                                    <th class="px-4 py-2 w-2/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'documento')" class="font-bold">
                                            Militante
                                            <div v-show="sortBy == 'documento'">
                                                <span v-show="!sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <span v-show="sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                      <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </button>
                                    </th>
                                    <th class="px-4 py-2 w-1/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'nombre')" class="font-bold">
                                            Estado
                                            <div v-show="sortBy == 'nombre'">
                                                <span v-show="!sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <span v-show="sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                      <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </button>
                                    </th>
                                    <th class="px-4 py-2 w-2/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'idciudad')" class="font-bold">
                                            Delegado
                                            <div v-show="sortBy == 'idciudad'">
                                                <span v-show="!sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <span v-show="sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                      <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </button>
                                    </th>
                                    <th class="px-4 py-2 w-1/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'estado')" class="font-bold">
                                            Fecha de revisión
                                            <div v-show="sortBy == 'estado'">
                                                <span v-show="!sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <span v-show="sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                      <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </button>
                                    </th>
                                    <th class="px-4 py-2 text-sm font-bold w-1/12 hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'avalado')" class="font-bold">
                                            Fecha de aprobación
                                            <div v-show="sortBy == 'avalado'">
                                                <span v-show="!sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <span v-show="sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                      <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </button>
                                    </th>
                                    <th class="px-4 py-2 text-sm font-bold w-1/12 hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'avalado')" class="font-bold">
                                            Revisor
                                            <div v-show="sortBy == 'avalado'">
                                                <span v-show="!sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                                <span v-show="sortOrder">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                      <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </button>
                                    </th>
                                    <th class="lg:px-4 md:px-1 mx-auto py-2 text-sm font-bold lg:w-1/12 md:w-1/11 hover:bg-blue-500 hover:text-gray-50 rounded-b">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr :class="solicitud.id === selectedRow ? 'bg-blue-200' : ''"  class="text-center hover:bg-blue-400" v-on:dblclick="ver(solicitud)" @click="rowSelect(solicitud.id)" text-sm v-if="arrayData.data" v-for="(solicitud, id) in arrayData.data" :key="id">
                                    <td class="border px-1 py-2 text-sm truncate" v-text="dateTime(solicitud.fechasolicitud)"></td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="solicitud.militante.full_name.toUpperCase()"></td>
                                    <td class="border px-2 py-2 text-sm truncate">
                                        <span v-if="solicitud.estado == 17" class="inline-flex px-2 text-sm font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                            {{ solicitud.estados.nombre }}
                                        </span>
                                        <span v-if="solicitud.estado == 16" class="inline-flex px-2 text-sm font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            {{ solicitud.estados.nombre }}
                                        </span>
                                        <span v-if="solicitud.estado == 15" class="inline-flex px-2 text-sm font-semibold leading-5 text-orange-800 bg-orange-100 rounded-full">
                                            {{ solicitud.estados.nombre }}
                                        </span>
                                        <span v-if="solicitud.estado == 14" class="inline-flex px-2 text-sm font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                            {{ solicitud.estados.nombre }}
                                        </span>
                                        <span v-if="solicitud.estado == 9" class="inline-flex px-2 text-sm font-semibold leading-5 text-grey-800 bg-grey-300 rounded-full">
                                            {{ solicitud.estados.nombre }}
                                        </span>
                                    </td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="solicitud.delegado?solicitud.delegado.full_name.toUpperCase():''"></td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="solicitud.fecharevision"></td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="solicitud.fechaaprobacion"></td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="solicitud.revisor?solicitud.revisor.full_name.toUpperCase():''"></td>
                                    <td class="border px-1 py-1 flex mx-auto items-center justify-center">
                                        <div v-if="$can('aval-list')" >
                                            <Popper content="Ver Solicitud Aval" hover=true placement="top" arrow=true>
                                                <button @click="solicitudAval(solicitud)" class="hover:bg-red-600 text-red-800 font-bold rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </button>
                                            </Popper>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td class="border px-4 py-2 text-xs text-center" colspan="7"> La consulta no obtuvo datos</td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- Paginacion -->
                            <section class="mt-6">
                                <div v-if="arrayData.links.length > 3">
                                    <div class="flex flex-wrap -mb-1">
                                        <template v-for="(link, p) in arrayData.links" :key="p">
                                            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                                                 v-html="link.label" />
                                            <button  v-else
                                                     class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                                                     :class="{ 'bg-blue-700 text-white': link.active }"
                                                     v-on:click="this.cambiarPage(link.url, 'avales', formbusqueda)"
                                                     v-html="link.label" />
                                        </template>
                                    </div>
                                </div>
                            </section>
                            <!-- Paginacion -->
                        </div>
                    </section>
                    <!-- Fin Tabla de contenido -->




                </div>
            </div>


            <!-- Ventana modal Solicitud -->
            <section> <!-- Ventana modal -->
                <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpenAvalSol">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                        <div @click="closeModalAvalSol()" class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>
                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                        <div class="inline-block md:w-8/12 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                            <!-- Menu Tabs -->
                            <section>

                                <div class="border-b border-gray-200">
                                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                                        <li class="mr-2">
                                            <a href="#" v-on:click="activetabSol='1'; tituloModalDetalle = 'Solicitud de Aval'" v-bind:class="[ activetabSol === '1' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                <svg v-bind:class="[ activetabSol === '1' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                                </svg>Solicitud de Aval
                                            </a>
                                        </li>
                                        <li class="mr-2">
                                            <a href="#" v-on:click="activetabSol='2'; getHistorialAval(militanteaval.id); tituloModalDetalle = 'Historial'" v-bind:class="[ activetabSol === '2' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                <svg v-bind:class="[ activetabSol === '2' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                                </svg>Historial
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </section>
                            <!-- Fin Menu Tabs -->

                            <!-- Seccion menu Nueva Solicitud -->
                            <section v-if="activetabSol ==='1'" >
                                <!-- Ventana modal Documentos -->
                                <!-- Main modal -->
                                <div class="flex items-start justify-between">

                                    <button type="button" @click="closeModalAvalSol()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <h2 class="text-xl font-bold text-gray-900 px-4 py-4">Militante {{ militanteaval.full_name }}</h2>
                                    <Popper v-if="militanteaval.solicitud" class="px-1" content="Descargar zip" hover=true placement="top" arrow=true>
                                        <a :href="route('file.downloadZip', 'idmilitante')+militanteaval.id">
                                            <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859M12 3v8.25m0 0l-3-3m3 3l3-3" />
                                                </svg>
                                            </button>
                                        </a>
                                    </Popper>
                                    <div v-if="militanteaval.solicitud" class="ml-4 pl-4 text-bold text-xl">
                                        <span v-if="militanteaval.solicitud.estado == 17" class="inline-flex p-2 font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                            {{ estado }}
                                        </span>
                                        <span v-else-if="militanteaval.solicitud.estado == 14" class="inline-flex p-2 font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                            {{ estado }}
                                        </span>
                                        <span v-else-if="militanteaval.solicitud.estado == 15" class="inline-flex p-2 font-semibold leading-5 text-orange-800 bg-orange-100 rounded-full">
                                            {{ estado }}
                                        </span>
                                        <span v-else-if="militanteaval.solicitud.estado == 16" class="inline-flex p-2 font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            {{ estado }}
                                        </span>
                                        <span v-else-if="militanteaval.solicitud.estado == 9" class="inline-flex p-2 font-semibold leading-5 text-gray-800 bg-gray-300 rounded-full">
                                            {{ estado }}
                                        </span>
                                    </div>
                                </div>
                                <div class="bg-white px-4 pt-2 pb-4 ">
                                    <div class="">
                                        <section>
                                            <div class="mt-2 grid grid-cols-2 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Corporación</label>
                                                    <div class="mt-1">
                                                        <select v-if="(roluser == 3 || roluser == 13 || roluser == 4)"
                                                                :disabled="militanteaval.solicitud?(militanteaval.solicitud.estado != 14):null"
                                                                :class="{'bg-blue-100' : (militanteaval.solicitud?(militanteaval.solicitud.estado != 14):null)}"
                                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="formaval.idcorporacion">
                                                            <option value="-" >Seleccione corporación</option>
                                                            <option v-for="dato in arrayCorporacionesregionales" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                                        </select>
                                                        <select v-else :disabled="true" class="bg-blue-100 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="formaval.idcorporacion">
                                                            <option value="-" >Seleccione corporación</option>
                                                            <option v-for="dato in arrayCorporacionesregionales" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                                        </select>
                                                        <div v-if="$page.props.errors.idcorporacion" class="text-red-500">{{ $page.props.errors.idcorporacion }}</div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Periodo electoral</label>
                                                    <div class="mt-1">
                                                        <select  v-if="(roluser == 3 || roluser == 13 || roluser == 4)"
                                                                 :disabled="militanteaval.solicitud?(militanteaval.solicitud.estado != 14):null"
                                                                 :class="{'bg-blue-100' : (militanteaval.solicitud?(militanteaval.solicitud.estado != 14):null)}"
                                                                 class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="formaval.periodo">
                                                            <option value="-" >Seleccione periodo</option>
                                                            <option value="2023" >2023</option>
                                                        </select>
                                                        <select v-else :disabled="true" class="bg-blue-100 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="formaval.periodo">
                                                            <option value="-" >Seleccione periodo</option>
                                                            <option value="2023" >2023</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>

                                        <section>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-4 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div class="col-span-1">
                                                        <label class="block text-md font-medium text-gray-700">Solicitud de Aval</label>
                                                    </div>
                                                    <div class="col-span-1">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 16)"
                                                               name="file16"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file16" />
                                                    </div>
                                                    <div class="flex px-1 items-center col-span-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[16]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[16].url?this.archivoformaval.archivos[16].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[16] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[16]?this.archivoformaval.archivos[16].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[16]?this.archivoformaval.archivos[16].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Información mínima Aval</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 17)"
                                                               name="file17"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file17" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[17]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[17].url?this.archivoformaval.archivos[17].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[17] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[17]?this.archivoformaval.archivos[17].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[17]?this.archivoformaval.archivos[17].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Hoja de Vida</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 18)"
                                                               name="file18"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file18" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[18]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[18].url?this.archivoformaval.archivos[18].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[18] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[18]?this.archivoformaval.archivos[18].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[18]?this.archivoformaval.archivos[18].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Cédula de Ciudadanía</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 19)"
                                                               name="file19"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file19" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[19]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[19].url?this.archivoformaval.archivos[19].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[19] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[19]?this.archivoformaval.archivos[19].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[19]?this.archivoformaval.archivos[19].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Declaración bienes</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 20)"
                                                               name="file20"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file20" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[20]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[20].url?this.archivoformaval.archivos[20].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[20] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[20]?this.archivoformaval.archivos[20].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[20]?this.archivoformaval.archivos[20].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Acta compromiso</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 21)"
                                                               name="file21"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file21" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[21]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[21].url?this.archivoformaval.archivos[21].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[21] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[21]?this.archivoformaval.archivos[21].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[21]?this.archivoformaval.archivos[21].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Relación investigaciones</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 22)"
                                                               name="file22"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file22" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[22]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[22].url?this.archivoformaval.archivos[22].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[22] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[22]?this.archivoformaval.archivos[22].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[22]?this.archivoformaval.archivos[22].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Antecedentes disciplinarios</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 23)"
                                                               name="file23"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file23" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[23]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[23].url?this.archivoformaval.archivos[23].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[23] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[23]?this.archivoformaval.archivos[23].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[23]?this.archivoformaval.archivos[23].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Responsabilidad Fiscal</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 24)"
                                                               name="file24"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file24" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[24]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[24].url?this.archivoformaval.archivos[24].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[24] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[24]?this.archivoformaval.archivos[24].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[24]?this.archivoformaval.archivos[24].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Antecedentes judiciales</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 25)"
                                                               name="file25"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file25" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[25]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[25].url?this.archivoformaval.archivos[25].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[25] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[25]?this.archivoformaval.archivos[25].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[25]?this.archivoformaval.archivos[25].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">RNMC</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 26)"
                                                               name="file26"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file26" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[26]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[26].url?this.archivoformaval.archivos[26].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[26] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[26]?this.archivoformaval.archivos[26].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[26]?this.archivoformaval.archivos[26].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Pagaré e instrucciones</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 27)"
                                                               name="file27"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file27" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[27]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[27].url?this.archivoformaval.archivos[27].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[27] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[27]?this.archivoformaval.archivos[27].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[27]?this.archivoformaval.archivos[27].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Registro de contador y gerente</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 28)"
                                                               name="file28"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file28" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[28]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[28].url?this.archivoformaval.archivos[28].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[28] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[28]?this.archivoformaval.archivos[28].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[28]?this.archivoformaval.archivos[28].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Anexo de firmas</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 29)"
                                                               name="file29"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file29" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[29]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[29].url?this.archivoformaval.archivos[29].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[29] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[29]?this.archivoformaval.archivos[29].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[29]?this.archivoformaval.archivos[29].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Carta de motivación</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 30)"
                                                               name="file30"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file30" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[30]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[30].url?this.archivoformaval.archivos[30].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[30] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[30]?this.archivoformaval.archivos[30].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[30]?this.archivoformaval.archivos[30].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Autorización Habeas data</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 31)"
                                                               name="file31"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file31" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[31]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[31].url?this.archivoformaval.archivos[31].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[31] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[31]?this.archivoformaval.archivos[31].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[31]?this.archivoformaval.archivos[31].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Certificado afiliación militante</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 32)"
                                                               name="file32"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file32" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[32]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[32].url?this.archivoformaval.archivos[32].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[32] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[32]?this.archivoformaval.archivos[32].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[32]?this.archivoformaval.archivos[32].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>
                                            <form @submit.prevent="submit" enctype="multipart/form-data">
                                                <div class="items-center pt-2 py-1 grid gap-y-6 flex grid-cols-3 sm:grid-cols-3">
                                                    <div>
                                                        <label class="block text-md font-medium text-gray-700">Foto en formato JPG</label>
                                                    </div>
                                                    <div class="">
                                                        <input v-model="archivoformaval.idmilitante" type="hidden">
                                                        <input :disabled="!((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"
                                                               :class="{'bg-blue-100' : !((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))}"
                                                               @change="onFileChangeAval($event, 33)"
                                                               name="file33"
                                                               class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-white bg-clip-padding px-3 py-1 text-base font-normal text-black outline-none transition duration-300 ease-in-out file:-mx-3 file:-my-1.5 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-400 file:px-3 file:py-1.5 file:text-black file:transition file:duration-150 file:ease-in-out file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-neutral-200 focus:border-primary focus:bg-white focus:text-black focus:shadow-[0_0_0_1px] focus:shadow-primary focus:outline-none dark:bg-transparent dark:text-neutral-200 dark:focus:bg-transparent"
                                                               type="file"
                                                               id="file33" />
                                                    </div>
                                                    <div class="flex px-1">
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[33]" content="Ver" hover=true placement="top" arrow=true>
                                                            <a href="#" @click="descargarArchivo(this.archivoformaval.archivos[33].url?this.archivoformaval.archivos[33].url:'')">
                                                                <button class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </button>
                                                            </a>
                                                        </Popper>
                                                        <Popper class="px-1" v-else content="Editar" hover=true placement="top" arrow=true>
                                                            <button class="hover:bg-red-400 text-red-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <Popper class="px-1" v-if="this.archivoformaval.archivos[33] && ((roluser == 3 || roluser == 13 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 14):true))"  content="Eliminar" hover=true placement="top" arrow=true>
                                                            <button @click="eliminarArchivo(this.archivoformaval.archivos[33]?this.archivoformaval.archivos[33].id:'')" class="hover:bg-blue-400 text-gray-700 font-bold rounded">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                                </svg>
                                                            </button>
                                                        </Popper>
                                                        <span class="items-center px-2 text-xs">{{ this.archivoformaval.archivos[33]?this.archivoformaval.archivos[33].nombre:'' }}</span>
                                                    </div>
                                                </div>
                                            </form>

                                            <div v-show="((roluser == 14 || roluser == 4) && (militanteaval.solicitud?(militanteaval.solicitud.estado == 17):false))" class="mt-2 grid gap-y-6 flex grid-cols-2 sm:grid-cols-2">
                                                <label class="block text-md font-medium text-gray-700">Observaciones</label>
                                                <div class="mt-1">
                                                    <textarea v-model="formaval.observaciones" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" placeholder="Observaciones"></textarea>
                                                </div>
                                            </div>
                                            <div class="mt-2 grid gap-y-6 flex">
                                                <div class="flex">
                                                    <div v-if="$can('aval-create') && !militanteaval.solicitud" class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"><!-- No tiene solicitud -->
                                                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                          <button wire:click.prevent="submit()" @click="registrarSolicitud()" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                              Solicitar Aval
                                                          </button>
                                                        </span>
                                                    </div>
                                                    <span class="sm:flex sm:flex-row-reverse" v-else-if="$can('aval-revisar') && militanteaval.solicitud?militanteaval.solicitud.estado == 17:false"> <!-- Solicitud de aval -->
                                                        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                                <button wire:click.prevent="submit()" @click="revisarSolicitud()" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-yellow-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-yellow-500 focus:outline-none focus:border-yellow-700 focus:shadow-outline-yellow transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                                    Revisar solicitud
                                                                </button>
                                                            </span>
                                                        </div>
                                                        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                                <button wire:click.prevent="submit()" @click="rechazarSolicitud()" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                                    Rechazar solicitud
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </span>
                                                    <span class="sm:flex sm:flex-row-reverse" v-else-if="$can('aval-edit') && militanteaval.solicitud?militanteaval.solicitud.estado == 14:false"> <!-- Rechazado -->
                                                        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                                <button wire:click.prevent="submit()" @click="actualizarSolicitud()" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                                    Actualizar solicitud
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </span>
                                                    <span class="sm:flex sm:flex-row-reverse" v-else-if="$can('aval-avalar') && militanteaval.solicitud?militanteaval.solicitud.estado == 15:false"> <!-- Revisado -->
                                                        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                              <button wire:click.prevent="submit()" @click="avalarSolicitud()" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                                Avalar militante
                                                              </button>
                                                            </span>
                                                        </div>
                                                        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                              <button wire:click.prevent="submit()" @click="anularSolicitud()" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                                Anular solicitud
                                                              </button>
                                                            </span>
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>

                                <!-- Fin Ventana modal Documentos -->
                            </section>
                            <!-- fin menu 1 -->

                            <!-- Seccion menu 2 historial -->
                            <section v-if="activetabSol ==='2'" >
                                <!-- Ventana modal Historial -->
                                <!-- Main modal -->

                                <button type="button" @click="closeModalAvalSol()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                                <div class="flex">
                                    <h2 class="text-xl font-bold text-gray-900 px-4 py-4">Historial Solicitud Aval</h2>
                                </div>

                                <div class="bg-white px-4 pt-2 pb-4 ">

                                    <section>
                                        <div class="lg:px-4 md:px-2 sm:px-0 py-2 pb-6">
                                            <table class="table-fixed w-full">
                                                <thead>
                                                <tr class="bg-blue-100 hover:bg-blue-500 hover:text-gray-50">
                                                    <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                        <button @click="getRifas(buscar, 'loteria.nombre')" class="font-bold">
                                                            Fecha
                                                            <div v-show="sortBy == 'loteria.nombre'">
                                                                    <span v-show="!sortOrder">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                                <span v-show="sortOrder">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                          <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                            </div>
                                                        </button>
                                                    </th>
                                                    <th class="px-4 py-2 w-2/12 text-sm font-bold rounded-b">
                                                        <button @click="getRifas(buscar, 'serie')" class="font-bold">
                                                            Usuario
                                                            <div v-show="sortBy == 'serie'">
                                                                    <span v-show="!sortOrder">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                                <span v-show="sortOrder">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                          <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                            </div>
                                                        </button>
                                                    </th>
                                                    <th class="px-4 py-2 w-3/12 text-sm font-bold rounded-b">
                                                        <button @click="getRifas(buscar, 'serie')" class="font-bold">
                                                            Observaciones
                                                            <div v-show="sortBy == 'serie'">
                                                                    <span v-show="!sortOrder">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                                <span v-show="sortOrder">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                                          <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                                        </svg>
                                                                    </span>
                                                            </div>
                                                        </button>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr @click="verHistorial(dato.observaciones)" class="hover:bg-blue-100 text-center" text-sm v-if="arrayHistorialAval" v-for="(dato, id) in arrayHistorialAval.data" :key="id">
                                                    <td class="border px-1 py-2 text-sm truncate" v-text="dateTimeFull(dato.updated_at)"></td>
                                                    <td class="border px-1 py-2 text-sm truncate" v-text="dato.usuario.full_name"></td>
                                                    <td class="border px-1 py-2 text-sm truncate" v-text="dato.observaciones"></td>
                                                </tr>
                                                <tr v-else>
                                                    <td class="border px-4 py-2 text-xs text-center" colspan="4"> La consulta no obtuvo datos</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <!-- Paginacion -->
                                            <section class="mt-6">
                                                <div v-if="arrayHistorialAval.links.length > 3">
                                                    <div class="flex flex-wrap -mb-1">
                                                        <template v-for="(link, p) in arrayHistorialAval.links" :key="p">
                                                            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                                                                 v-html="link.label" />
                                                            <button  v-else
                                                                     class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                                                                     :class="{ 'bg-blue-700 text-white': link.active }"
                                                                     v-on:click="this.cambiarPage(link.url, 'historialaval', null, null, form.id)"
                                                                     v-html="link.label" />
                                                        </template>
                                                    </div>
                                                </div>
                                            </section>
                                            <!-- Paginacion -->
                                        </div>
                                    </section>

                                </div>

                                <!-- Fin Ventana modal Documentos -->
                            </section>
                            <!-- fin menu 2 -->

                        </div>
                    </div>
                </div>
            </section>
            <!-- Fin Ventana modal Solicitud -->


            <!-- Ventana modal ver historial Detalle -->
            <!-- Main modal -->
            <section> <!-- Ventana modal -->
                <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpenverHistorial">
                    <div class="items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                        <div class="inline-block lg:w-6/12 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                            <button type="button" @click="isOpenverHistorial = !isOpenverHistorial" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <div class="">
                                <h2 class="text-xl font-bold text-gray-900 px-4 py-4">Observaciones</h2>
                            </div>
                            <div class="bg-white px-4 pt-2 pb-4 ">
                                <div class="">
                                    <section>
                                        <div class="mt-2 grid gap-y-6 ">
                                            <div>
                                                <div class="mt-1">
                                                    <div class="mt-1">
                                                        <textarea rows="4" v-text="verHistorialobs" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Fin Ventana modal ver historial  Detalle-->

            <!-- Ventana modal Motivo rechazo -->
            <!-- Main modal -->
            <section> <!-- Ventana modal -->
                <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpenMotivoFechazo">
                    <div class="items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                        <div class="inline-block lg:w-6/12 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                            <button type="button" @click="isOpenMotivoFechazo = !isOpenMotivoFechazo" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            <div class="">
                                <h2 class="text-xl font-bold text-gray-900 px-4 py-4">Motivo de Rechazo de solicitud de Aval</h2>
                            </div>
                            <div class="bg-white px-4 pt-2 pb-4 ">
                                <div class="">
                                    <section>
                                        <div class="mt-2 grid gap-y-6 ">
                                            <div>
                                                <div class="mt-1">
                                                    <div class="mt-1">
                                                        <textarea rows="4" v-text="motivorechazo" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Fin Ventana modal Motivo rechazo-->
            <!-- Ventana modal dirección de loading -->
            <!-- Main modal -->
            <section> <!-- Ventana modal -->
                <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="loading">
                    <div class="items-end justify-center min-h-screen pt-10 px-2 pb-20 text-center sm:block sm:p-0">

                        <div class="fixed inset-0 transition-opacity">
                            <div class="absolute inset-0 bg-gray-400 opacity-75"></div>
                        </div>

                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden inline-block align-middle h-screen"></span>
                        <section>
                            <div class=" mt-10 w-full" id="app">
                                <semipolar-spinner class="mt-10 mx-auto" :animation-duration="2000" :size="85" color="#ff1d5e" />
                            </div>
                        </section>


                    </div>
                </div>
            </section>
            <!-- Fin Ventana modal dirección de loading -->
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Swal from "sweetalert2";
import { Icon } from '@iconify/vue';
import Toggle from '@vueform/toggle';
import '@vueform/toggle/themes/default.css';
import Button from "../../Jetstream/Button";
import moment from 'moment'
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { ref, onMounted } from 'vue';
import { Money3Component } from 'v-money3'
import {Head, Link, usePage} from '@inertiajs/inertia-vue3';
import JetNavLink from '@/Jetstream/NavLink.vue';
import NavLink from "../../Jetstream/NavLink";
import Input from "../../Jetstream/Input";

import { SemipolarSpinner } from 'epic-spinners'

export default {

    components: {
        Input,
        NavLink,
        Button,
        AppLayout,
        Icon,
        Toggle,
        QuillEditor,
        JetNavLink,
        Link,
        money3: Money3Component,
        SemipolarSpinner
    },
    props:{
        solicitudes : [],
        delegados: [],
        departamentos: [],
        revisores: [],
        estados: [],
        roluser: null,
        militante: Object,
        user: Object,
        errors: Object,
        _token: null
    },
    computed: {

    },
    data() {
        return {
            loading: false,
            estado: '',
            isOpenAvalSol: false,
            isOpenRechazoSol: false,
            isOpenMotivoFechazo: false,
            motivorechazo: '',

            verHistorialobs: null,
            activetab: '1',
            activetabSol: '1',
            tituloModal: '',
            tituloModalDetalle: '',

            form: {
                idpais: 1,
            },
            militanteaval: [],

            formaval: {
                id: null,
                idmilitante: '',
                idcorporacion: '-',
                periodo: '-',
                observaciones: ''
            },
            archivoformaval: {
                idmilitante: 0,
                file: '',
                idtipoarchivo: 0,
                status1: false,
                archivos: []
            },

            formbusqueda: {
                idmilitante: 0,
                nombre: '',
                documento: null,
                iddepartamento: 0,
                idciudad: 0,
                idpais: 1,
                iddelegado: 0,
                idrevisor: 0,
                estado: 0,
                estado_ant: 0,
                idcorporacion: 0,
                fechainicioregistro: null,
                fechafinregistro: null,
                fechainiciorevision: null,
                fechafinrevision: null,
                fechainicioaprobacion: null,
                fechafinaprobacion: null
            },
        }
    },
    methods: {
        getSolicitudesAval: async function (buscar, sortBy, filtros = []) {
            this.buscar = buscar;
            this.loading = true;

            if (sortBy == this.sortBy){
                this.sortOrder = !this.sortOrder;
            }
            let sortOrderdesc;
            if (this.sortOrder){
                sortOrderdesc = 'desc';
            } else {
                sortOrderdesc = 'desc';
            }
            this.sortBy = sortBy;
            this.ispage = true;

            var url= '/avales/index';
            let res;
            try {
                res = await axios.get(url, {
                    params: {
                        filtros: filtros,
                        buscar: this.buscar,
                        sortBy: this.sortBy,
                        sortOrder: sortOrderdesc,
                        ispage: this.ispage
                    }
                });
            } catch (error) {
                console.log(error);
                this.loading = false;
                return false;
            }

            var respuesta = res.data;
            this.arrayData = respuesta.solicitudes;
            this.loading = false;
        },
        openModalAval: function (data = []) {
            this.isOpenAvalSol = true;
            this.formaval.idmilitante = data.id;
        },
        closeModalAvalSol: function () {
            this.isOpenAvalSol = false;
            this.$page.props.errors = [];
            this.resetavalsol();
        },
        resetavalsol: function () {
            this.militanteaval = [];
            this.archivoformaval.idmilitante = 0;
            this.archivoformaval.file = '';
            this.archivoformaval.idtipoarchivo = 0;
            this.archivoformaval.status1 = false;
            this.archivoformaval.archivos = [];
            this.formaval.idmilitante = 0;
            this.estado = 0;
            this.formaval.idcorporacion = '-';
            this.formaval.periodo = '-';
            this.formaval.observaciones = '';
            this.arrayArchivos = [];
        },
        getHistorialAval: async function (data) {
            this.loading = true;
            console.log(data);
            var url= '/militantes/getHistorialAval';
            let res = await axios.get(url, {
                params: {
                    idmilitante: data
                }
            });
            this.arrayHistorialAval = res.data.historial;
            this.loading = false;
        },
        descargarArchivo: function (data) {
            window.open(data, '_blank');
        },
        onFileChangeAval(e, idtipoarchivo){
            console.log('cargar archivo');
            this.archivoformaval.file = e.target.files[0];
            this.subirArchivoAval(this.archivoformaval, idtipoarchivo);
        },
        subirArchivoAval: async function (data, idtipoarchivo) {
            console.log('tipo archivo ' + idtipoarchivo);
            var formData = new FormData();
            formData.append('idmilitante', data.idmilitante);
            formData.append('idtipoarchivo', idtipoarchivo);
            formData.append('file', data.file);
            let res = await axios.post('/file-upload' , formData);
            if (res.data.codigo == -1) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: res.data.mensaje,
                    showConfirmButton: true,
                })
                this.archivoformaval.file = '';
                console.log('archivo vacio');
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Archivo actualizado',
                    showConfirmButton: false,
                    timer: 1000
                })
                console.log(res.data);
                this.archivoformaval.archivos[idtipoarchivo] = res.data.archivo;
            }
        },
        eliminarArchivo: function (data) {
            axios.get('/file-delete', {
                params: {
                    id: data
                }
            }).then((res) => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Archivo eliminado',
                    showConfirmButton: false,
                    timer: 1500
                })
                this.archivoformaval.archivos[res.data.idtipoarchivo] = null;
            })

        },
        verHistorial: function (data) {
            this.verHistorialobs = data;
            this.isOpenverHistorial = true;
        },
        solicitudAvalMilitante: function(militante) {
            this.loading = true;
            this.militanteaval = militante;
            this.archivoformaval.idmilitante = this.militanteaval.id;
            this.formaval.idmilitante = this.militanteaval.id;
            this.estado = this.militanteaval.solicitud?this.militanteaval.solicitud.estados.nombre:'';
            if(this.militanteaval.solicitud) {
                this.formaval.idcorporacion = this.militanteaval.solicitud.idcorporacion;
                this.formaval.periodo = this.militanteaval.solicitud.periodo;
                this.formaval.observaciones = this.militanteaval.solicitud.observaciones;

                if (this.militanteaval.solicitud.estado == 14) {
                    this.motivorechazo = this.formaval.observaciones;
                    this.isOpenMotivoFechazo = true;
                }
            }

            if (this.militanteaval.archivos) {
                this.arrayArchivos = this.militanteaval.archivos;
                this.arrayArchivos.forEach((element, index, array) => {
                    this.archivoformaval.archivos[element.idtipoarchivo] = element;
                })
            }
            this.openModalAval();
            this.loading = false;
        },
        solicitudAval: function(solicitud) {
            this.loading = true;
            this.militanteaval = solicitud.militante;
            this.archivoformaval.idmilitante = this.militanteaval.id;
            this.formaval.idmilitante = this.militanteaval.id;
            this.estado = solicitud?solicitud.estados.nombre:'';
            if(solicitud) {
                this.formaval.idcorporacion = solicitud.idcorporacion;
                this.formaval.periodo = solicitud.periodo;
                this.formaval.observaciones = solicitud.observaciones;

                if (solicitud.estado == 14) {
                    this.motivorechazo = this.formaval.observaciones;
                    this.isOpenMotivoFechazo = true;
                }
            }

            if (this.militanteaval.archivos) {
                this.arrayArchivos = this.militanteaval.archivos;
                this.arrayArchivos.forEach((element, index, array) => {
                    this.archivoformaval.archivos[element.idtipoarchivo] = element;
                })
            }
            this.openModalAval();
            this.loading = false;
        },
        registrarSolicitud: async function () {
            let i = 0;
            this.archivoformaval.archivos.forEach((element) => {
                if (element) {
                    i++;
                }
            });
            if (this.archivoformaval.archivos < 18 || i < 18) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Todos los archivos son obligatorios',
                    showConfirmButton: true,
                })
            } else {
                if (this.formaval.idcorporacion == '-') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Seleccione una corporación',
                        showConfirmButton: true,
                    })
                } else {
                    if (this.formaval.periodo == '-') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Seleccione un periodo',
                            showConfirmButton: true,
                        })
                    } else {
                        var formData = new FormData();
                        formData.append('idmilitante', this.militanteaval.id);
                        formData.append('idcorporacion', this.formaval.idcorporacion);
                        formData.append('periodo', this.formaval.periodo);
                        formData.append('observaciones', this.formaval.observaciones);

                        let res = await axios.post('/avales' , formData);
                        if (res.data.codigo == -1) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: res.data.mensaje,
                                showConfirmButton: true,
                            })
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: res.data.mensaje,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            if (this.roluser == 3) {
                                this.formbusqueda.idmilitante =  this.militanteaval.id;
                            }
                            this.getSolicitudesAval('', '', this.formbusqueda);
                            this.resetavalsol();
                            this.closeModalAvalSol();
                        }
                    }
                }
            }
        },
        actualizarSolicitud: async function () {
            let i = 0;
            this.archivoformaval.archivos.forEach((element) => {
                if (element) {
                    i++;
                }
            });
            if (this.archivoformaval.archivos < 4 || i < 4) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Todos los archivos son obligatorios',
                    showConfirmButton: true,
                })
            } else {
                var formData = new FormData();
                formData.append('idmilitante', this.militanteaval.id);
                //formData.append('observaciones', this.formaval.observaciones);

                let res = await axios.post('/avales/actualizar' , formData);
                if (res.data.codigo == -1) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: res.data.mensaje,
                        showConfirmButton: true,
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: res.data.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    if (this.roluser == 3) {
                        this.formbusqueda.idmilitante =  this.militanteaval.id;
                    }
                    this.getSolicitudesAval('', '', this.formbusqueda);
                    this.resetavalsol();
                    this.closeModalAvalSol();
                }
            }
        },
        revisarSolicitud: async function () {
            let i = 0;
            this.archivoformaval.archivos.forEach((element) => {
                if (element) {
                    i++;
                }
            });
            if (this.archivoformaval.archivos < 4 || i < 4) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Todos los archivos son obligatorios',
                    showConfirmButton: true,
                })
            } else {
                var formData = new FormData();
                formData.append('idmilitante', this.militanteaval.id);
                //formData.append('observaciones', this.formaval.observaciones);

                let res = await axios.post('/avales/revisar' , formData);
                if (res.data.codigo == -1) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: res.data.mensaje,
                        showConfirmButton: true,
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: res.data.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getSolicitudesAval('', '', this.formbusqueda);
                    this.closeModalAvalSol();
                }
            }
        },
        rechazarSolicitud: async function () {
            let i = 0;
            this.archivoformaval.archivos.forEach((element) => {
                if (element) {
                    i++;
                }
            });
            if (this.archivoformaval.archivos < 4 || i < 4) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Todos los archivos son obligatorios',
                    showConfirmButton: true,
                })
            } else {
                if (this.formaval.observaciones === '') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Por favor incluya el motivo de rechazo de la solicitud',
                        showConfirmButton: true,
                    })
                } else {
                    var formData = new FormData();
                    formData.append('idmilitante', this.militanteaval.id);
                    formData.append('observaciones', this.formaval.observaciones);

                    let res = await axios.post('/avales/rechazar' , formData);
                    if (res.data.codigo == -1) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: res.data.mensaje,
                            showConfirmButton: true,
                        })
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: res.data.mensaje,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.getSolicitudesAval('', '', this.formbusqueda);
                        this.closeModalAvalSol();
                    }
                }
            }
        },
        avalarSolicitud: async function () {
            let i = 0;
            this.archivoformaval.archivos.forEach((element) => {
                if (element) {
                    i++;
                }
            });
            if (this.archivoformaval.archivos < 4 || i < 4) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Todos los archivos son obligatorios',
                    showConfirmButton: true,
                })
            } else {
                var formData = new FormData();
                formData.append('idmilitante', this.militanteaval.id);
                //formData.append('observaciones', this.formaval.observaciones);

                let res = await axios.post('/avales/avalar' , formData);
                if (res.data.codigo == -1) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: res.data.mensaje,
                        showConfirmButton: true,
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: res.data.mensaje,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getSolicitudesAval('', '', this.formbusqueda);
                    this.closeModalAvalSol();
                }
            }
        },
        anularSolicitud: async function () {
            var formData = new FormData();
            formData.append('idmilitante', this.militanteaval.id);
            formData.append('observaciones', 'Solicitud anulada');

            let res = await axios.post('/avales/anular' , formData);
            if (res.data.codigo == -1) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: res.data.mensaje,
                    showConfirmButton: true,
                })
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: res.data.mensaje,
                    showConfirmButton: false,
                    timer: 1500
                })
                this.getSolicitudesAval('', '', this.formbusqueda);
                this.closeModalAvalSol();
            }
        },
        verDetalleAvalado: function () {
            this.tituloModalDetalle = 'Detalle de Avalado';
            this.isOpenAval = true;
        },
    },
    created: function () {
        this.loading = true;
        this.arrayData = this.solicitudes;
        this.getCorporaciones();
        this.getCorporacionesregionales();
        this.loading = false;
    },
    mounted() {
    },
}
</script>
