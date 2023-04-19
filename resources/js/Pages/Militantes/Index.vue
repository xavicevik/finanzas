<template>
    <AppLayout title="Militantes">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lista de Militantes
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
                                    Militantes
                                </h1>
                            </div>

                            <div class="pr-2 w-1/3 text-center">
                                <button v-if="$can('militantes-create')" @click="openModal('registrar')" class="bg-blue-500 text-xs  hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">Nuevo Militante</button>
                            </div>
                            <div class="w-1/6 text-center">
                                <button v-if="$can('militantes-import')" @click="openModalImport()" class="bg-orange-500 text-xs  hover:bg-orange-700 text-white font-bold py-2 px-4 rounded ">Importar</button>
                            </div>
                            <div class="w-1/6 text-center">
                                <button v-if="$can('militantes-import')" @click="isImportprogress = true; handleChange()" class="bg-orange-500 text-xs  hover:bg-orange-700 text-white font-bold py-2 px-4 rounded ">Progreso</button>
                            </div>
                        </div>
                    </section>
                    <!-- Fin Encabezado y titulo Busqueda -->
                    <section>
                        <div class="px-4">
                            <form @submit.prevent="getmilitantes('', '', formbusqueda)" @keyup.enter="getmilitantes('', '', formbusqueda)">
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <input type="text" v-model="formbusqueda.documento" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-gray-600 peer" placeholder=" "  />
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Documento
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <input type="text" v-model="formbusqueda.nombre" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-gray-600 peer" placeholder=" "  />
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Nombre
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <input type="text" v-model="formbusqueda.email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-gray-600 peer" placeholder=" "  />
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            email
                                        </label>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">


                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.iddepartamento" @change="getCiudadesBusqueda">
                                            <option value="-" >Seleccione departamento</option>
                                            <option v-for="departamento in arrayDepartamentos" :key="departamento.id" :value="departamento.id" v-text="departamento.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Departamento
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.idciudad">
                                            <option value="-" >Seleccione Ciudad</option>
                                            <option v-for="ciudad in arrayCiudades" :key="ciudad.id" :value="ciudad.id" v-text="ciudad.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Ciudad
                                        </label>
                                    </div>

                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.idinscripcion">
                                            <option value="-" >Seleccione</option>
                                            <option v-for="tipomil in arrayInscripciones" :key="tipomil.id" :value="tipomil.id" v-text="tipomil.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Inscripción
                                        </label>
                                    </div>



<!-- Movil
                                    <div class="relative z-0 w-full mb-6 group">
                                        <input type="text" v-model="formbusqueda.movil" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" placeholder=" "  />
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Movil
                                        </label>
                                    </div>
-->
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.idgenero">
                                            <option value="-" >Seleccione</option>
                                            <option v-for="dato in arrayGeneros" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Género
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.idgrupoetnico">
                                            <option value="-" >Seleccione</option>
                                            <option v-for="dato in arrayGruposetnicos" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Grupo Étnico
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.lider">
                                            <option value="-" >-</option>
                                            <option value="1" >Si</option>
                                            <option value="0" >No</option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Líder
                                        </label>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.avalado">
                                            <option value="-" >-</option>
                                            <option value="1" >Si</option>
                                            <option value="0" >No</option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Avalado
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.idcorporacion">
                                            <option value="-" >Seleccione</option>
                                            <option v-for="dato in arrayCorporaciones" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Corporación
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.electo">
                                            <option value="-" >-</option>
                                            <option value="1" >Si</option>
                                            <option value="0" >No</option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Electo
                                        </label>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-4 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.estado">
                                            <option value="-" >Seleccione</option>
                                            <option v-for="dato in arrayEstados" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Estado
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <Datepicker v-model="formbusqueda.fechainicio" :enableTimePicker="false" autoApply placeholder="Fecha inicio" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 appearance-none focus:outline-none focus:ring-0 focus:border-gray-600 peer"/>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <Datepicker v-model="formbusqueda.fechafin" :enableTimePicker="false" autoApply placeholder="Fecha fin" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 appearance-none focus:outline-none focus:ring-0 focus:border-gray-600 peer"/>
                                    </div>
                                </div>
                                <div class="grid sm:grid-cols-3 sm:gap-6">
                                    <div class="relative z-0 w-full mb-4 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.examen">
                                            <option value="-" >-</option>
                                            <option value="2" >No aprobado</option>
                                            <option value="1" >Aprobado</option>
                                            <option value="1" >No presentado</option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Certificado curso
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-4 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.estadocc">
                                            <option value="-" >-</option>
                                            <option v-for="dato in arrayEstadoscc" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Estado cuentas claras
                                        </label>
                                    </div>
                                    <div class="relative z-0 w-full mb-6 group">
                                        <select class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:border-gray-600 dark:focus:border-gray-500 focus:outline-none focus:ring-0 focus:border-gray-600 peer" v-model="formbusqueda.aportes">
                                            <option value="-" >-</option>
                                            <option value="1" >Si</option>
                                            <option value="0" >No</option>
                                        </select>
                                        <label class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-gray-600 peer-focus:dark:text-gray-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                            Aportes
                                        </label>
                                    </div>
                                </div>
                                <div class="flex mx-auto">
                                    <button type="button" @click="getmilitantes('', '', formbusqueda)" class="mx-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-gray-800">Buscar</button>
                                    <Popper content="Exportar" hover=true placement="top" arrow=true>
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
                                <tr class="bg-blue-100">
                                    <th class="px-4 py-2 w-1/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'fechaingreso')" class="font-bold">
                                            Fecha de ingreso
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
                                    <th class="px-4 py-2 w-1/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'documento')" class="font-bold">
                                            Documento
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
                                    <th class="px-4 py-2 w-2/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'nombre')" class="font-bold">
                                            Nombre
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
                                    <th class="px-4 py-2 w-1/12 text-sm font-bold hover:bg-blue-500 hover:text-gray-50 rounded-b">
                                        <button @click="getmilitantes(buscar, 'idciudad')" class="font-bold">
                                            Municipio
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
                                            Estado
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
                                            Avalado
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
                                <tr :class="user.id === selectedRow ? 'bg-blue-200' : ''"  class="text-center hover:bg-blue-400" v-on:dblclick="ver(user)" @click="rowSelect(user.id)" text-sm v-if="arrayData.data" v-for="(user, id) in arrayData.data" :key="id">
                                    <td class="border px-1 py-2 text-sm truncate" v-text="dateTime(user.fechaingreso)"></td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="user.documento"></td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="user.full_name.toUpperCase()"></td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="user.ciudad?user.ciudad.nombre:''"></td>
                                    <td class="border px-1 py-2 text-sm truncate" v-text="user.estados.nombre"></td>
                                    <td class="border px-2 py-2 text-sm truncate" v-if="user.solicitud">
                                        <span v-if="user.solicitud.estado == 17" class="inline-flex px-2 text-sm font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                            {{ user.solicitud.estados.nombre }}
                                        </span>
                                        <span v-if="user.solicitud.estado == 16" class="inline-flex px-2 text-sm font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            {{ user.solicitud.estados.nombre }}
                                        </span>
                                        <span v-if="user.solicitud.estado == 15" class="inline-flex px-2 text-sm font-semibold leading-5 text-orange-800 bg-orange-100 rounded-full">
                                            {{ user.solicitud.estados.nombre }}
                                        </span>
                                        <span v-if="user.solicitud.estado == 14" class="inline-flex px-2 text-sm font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                            {{ user.solicitud.estados.nombre }}
                                        </span>
                                    </td>
                                    <td class="border px-2 py-2 text-sm" v-else>
                                        <span v-if="user.avalado" class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                            Avalado
                                        </span>
                                        <span v-else class="inline-flex px-2 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                            No
                                        </span>
                                    </td>
                                    <td class="border px-1 py-1 flex mx-auto items-center justify-center">
                                          <Popper v-if="$can('militantes-list')" content="Ver" hover=true placement="top" arrow=true>
                                              <button @click="ver(user)" class="hover:bg-green-700 text-green-400 font-bold rounded" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                                                  </svg>
                                              </button>
                                        </Popper>
                                        <Popper v-if="$can('militantes-edit')" content="Editar" hover=true placement="top" arrow=true>
                                            <button @click="edit(user)" class="hover:bg-blue-700 text-gray-900 font-bold rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </Popper>
                                        <div v-if="$can('aval-list')" >
                                            <Popper v-if="user.avalado" content="Ver Solicitud Aval" hover=true placement="top" arrow=true>
                                                <button v-if="user.solicitud" @click="solicitudAval(user)" class="hover:bg-red-600 text-red-800 font-bold rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </button>
                                            </Popper>
                                            <Popper v-else-if="$can('aval-create') || $can('aval-revisar')" content="Solicitud Aval" hover=true placement="top" arrow=true>
                                                <button v-if="user.solicitud || $can('aval-revisar')" @click="solicitudAval(user)" class="hover:bg-red-600 text-red-800 font-bold rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                                                    </svg>
                                                </button>
                                                <button v-else @click="solicitudAval(user)" class="hover:bg-red-600 text-red-800 font-bold rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
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
                                                     v-on:click="this.cambiarPage(link.url, 'militantes', formbusqueda)"
                                                     v-html="link.label" />
                                        </template>
                                    </div>
                                </div>
                            </section>
                            <!-- Paginacion -->
                        </div>
                    </section>
                    <!-- Fin Tabla de contenido -->

                    <!-- Ventana modal Detalles -->
                    <section> <!-- Ventana modal -->
                        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div class="fixed inset-0 transition-opacity">
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
                                                    <a href="#" v-on:click="activetab='1'; tituloModalDetalle = 'Información'" v-bind:class="[ activetab === '1' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '1' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                                        </svg>Información
                                                    </a>
                                                </li>
                                                <li class="mr-2" v-if="form.avalado">
                                                    <a href="#" v-on:click="activetab='2'; tituloModalDetalle = 'Detalle Aval'" v-bind:class="[ activetab === '2' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '2' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                                        </svg>Detalle Aval
                                                    </a>
                                                </li>
                                                <li class="mr-2" v-if="form.electo">
                                                    <a href="#" v-on:click="activetab='3'; tituloModalDetalle = 'Detalle Electo'" v-bind:class="[ activetab === '3' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '2' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                                                        </svg>
                                                        Detalle Electo
                                                    </a>
                                                </li>
                                                <li class="mr-2" v-if="verMode||editMode">
                                                    <a href="#" v-on:click="activetab='4'; tituloModalDetalle = 'Documentos'" v-bind:class="[ activetab === '4' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '4' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                                                        </svg>
                                                        Documentos
                                                    </a>
                                                </li>
                                                <li class="mr-2">
                                                    <a href="#" v-on:click="activetab='5'; getHistorial(form.id); tituloModalDetalle = 'Historial'" v-bind:class="[ activetab === '5' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '5' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                                        </svg>Historial
                                                    </a>
                                                </li>
                                                <li class="mr-2" v-if="form.avalado && $can('cuentasclaras-list')">
                                                    <a href="#" v-on:click="activetab='6'; getCuentasClaras(form.id); tituloModalDetalle = 'Cuentas claras'" v-bind:class="[ activetab === '6' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '6' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                                                        </svg>
                                                        Cuentas claras
                                                    </a>
                                                </li>

                                                <!-- Historial Avales -->
                                                <li class="mr-2" v-if="form.historialaval?Object.keys(form.historialaval).length > 0:false">
                                                    <a href="#" v-on:click="activetab='8'; tituloModalDetalle = 'Historial Aval'" v-bind:class="[ activetab === '8' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '2' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                                        </svg>Historial Aval
                                                    </a>
                                                </li>
                                                <li class="mr-2" v-if="form.historialaval?Object.keys(form.historialaval).length > 0:false">
                                                    <a href="#" v-on:click="activetab='9'; tituloModalDetalle = 'Historial Electo'" v-bind:class="[ activetab === '9' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '2' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                                                        </svg>
                                                        Historial Electo
                                                    </a>
                                                </li>
                                                <li class="mr-2" v-if="form.cuentasclaras?Object.keys(form.cuentasclaras).length > 0:false && $can('cuentasclaras-list')">
                                                    <a href="#" v-on:click="activetab='10'; getCuentasClaras(form.id); tituloModalDetalle = 'Historial Cuentas claras'" v-bind:class="[ activetab === '10' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '6' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                                                        </svg>
                                                        Historial Cuentas claras
                                                    </a>
                                                </li>
                                                <!-- Fin Historial Avales -->

                                                <li v-if="$can('cursos-list')" class="mr-2">
                                                    <a href="#" v-on:click="activetab='7'; getExamen(form.id); tituloModalDetalle = 'Cursos'" v-bind:class="[ activetab === '7' ? ' text-gray-600 border-gray-600 active ' : ' text-gray-400 border-transparent hover:text-gray-900 hover:border-gray-900 ' ]" class="inline-flex p-4 rounded-t-lg border-b-2 group">
                                                        <svg v-bind:class="[ activetab === '7' ? 'group-active:text-gray-600 text-gray-600 ' : ' group-active:text-gray-600 text-gray-400 group-hover:text-gray-500 ']" class="mr-2 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                                        </svg>
                                                        Cursos
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </section>
                                    <!-- Fin Menu Tabs -->

                                    <!-- Seccion menu 1 -->
                                    <section v-if="activetab ==='1'">

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="">
                                            <h4 v-text="tituloModal" class="text-sm font-bold text-gray-900 px-4 py-4"></h4>
                                        </div>
                                        <hr>
                                        <form>
                                            <div class="bg-white px-4 pt-2 pb-4 sm:p-6 sm:pb-4">
                                                <div class="">
                                                    <section>
                                                        <div class="mt-2 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Fecha de Ingreso</label>
                                                                <div class="mt-1">
                                                                    <Datepicker v-model="form.fechaingreso" :disabled="verMode" required textInput :enableTimePicker="false" autoApply placeholder="Fecha ingreso" class="border border-gray-700 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                    <div v-if="$page.props.errors.fechaingreso" class="text-red-500">{{ $page.props.errors.fechaingreso }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Inscripción Militancia</label>
                                                                <div class="mt-1">
                                                                    <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.idinscripcion">
                                                                        <option value="-" >Seleccione</option>
                                                                        <option v-for="tipomil in arrayInscripciones" :key="tipomil.id" :value="tipomil.id" v-text="tipomil.nombre"></option>
                                                                    </select>
                                                                    <div v-if="$page.props.errors.idinscripcion" class="text-red-500">{{ $page.props.errors.idinscripcion }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
                                                                <div class="mt-1">
                                                                    <Datepicker v-model="form.fechanacimiento" textInput :enableTimePicker="false" :disabled="verMode" autoApply placeholder="Fecha nacimiento" class="border border-gray-700 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                    <div v-if="$page.props.errors.fechanacimiento" class="text-red-500">{{ $page.props.errors.fechanacimiento }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.nombre" autocomplete="given-name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.nombre" class="text-red-500">{{ $page.props.errors.nombre }}</div>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Apellido</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.apellido" autocomplete="family-name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.apellido" class="text-red-500">{{ $page.props.errors.apellido }}</div>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">email</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.email" autocomplete="street-address" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.email" class="text-red-500">{{ $page.props.errors.email }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 grid grid-cols-1 gap-y-6 sm:grid-cols-4 sm:gap-x-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Movil</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.movil" autocomplete="street-address" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.movil" class="text-red-500">{{ $page.props.errors.movil }}</div>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Tipo documento</label>
                                                                <div class="mt-1">
                                                                    <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.idtipos_documento">
                                                                        <option value="-" >Seleccione</option>
                                                                        <option v-for="tipodoc in arrayTiposdocumento" :key="tipodoc.id" :value="tipodoc.id" v-text="tipodoc.nombre_corto"></option>
                                                                    </select>
                                                                    <div v-if="$page.props.errors.idtipos_documento" class="text-red-500">{{ $page.props.errors.idtipos_documento }}</div>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Documento</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :verMode="editMode" :class="{'bg-blue-100' : verMode}" v-model="form.documento" autocomplete="postal-code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.documento" class="text-red-500">{{ $page.props.errors.documento }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Aportes</label>
                                                                <div class="mt-1">
                                                                    <money3 v-bind="configMoney" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.aportes" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></money3>
                                                                    <div v-if="$page.props.errors.aportes" class="text-red-500">{{ $page.props.errors.aportes }}</div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="mt-2 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4">

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Género</label>
                                                                <div class="mt-1">
                                                                    <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.idgenero">
                                                                        <option value="-" >Seleccione</option>
                                                                        <option v-for="dato in arrayGeneros" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                                                    </select>
                                                                    <div v-if="$page.props.errors.idgenero" class="text-red-500">{{ $page.props.errors.idgenero }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Nivel Educativo</label>
                                                                <div class="mt-1">
                                                                    <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.idniveleducativo">
                                                                        <option value="-" >Seleccione</option>
                                                                        <option v-for="tipodoc in arrayNiveleducativo" :key="tipodoc.id" :value="tipodoc.id" v-text="tipodoc.nombre"></option>
                                                                    </select>
                                                                    <div v-if="$page.props.errors.idniveleducativo" class="text-red-500">{{ $page.props.errors.idniveleducativo }}</div>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Grupo Étnico</label>
                                                                <div class="mt-1">
                                                                    <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.idgrupoetnico">
                                                                        <option value="-" >Seleccione</option>
                                                                        <option v-for="departamento in arrayGruposetnicos" :key="departamento.id" :value="departamento.id" v-text="departamento.nombre"></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Departamento</label>
                                                                <div class="mt-1">
                                                                    <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.iddepartamento" @change="getCiudades">
                                                                        <option value="-" >Seleccione departamento</option>
                                                                        <option v-for="departamento in arrayDepartamentos" :key="departamento.id" :value="departamento.id" v-text="departamento.nombre"></option>
                                                                    </select>
                                                                    <div v-if="$page.props.errors.iddepartamento" class="text-red-500">{{ $page.props.errors.iddepartamento }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                                                                <div class="mt-1">
                                                                    <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.idciudad">
                                                                        <option value="-" >Seleccione Ciudad</option>
                                                                        <option v-for="ciudad in arrayCiudades" :key="ciudad.id" :value="ciudad.id" v-text="ciudad.nombre"></option>
                                                                    </select>
                                                                    <div v-if="$page.props.errors.idciudad" class="text-red-500">{{ $page.props.errors.idciudad }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                                                <div class="mt-1">
                                                                    <input type="email" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.direccion" autocomplete="email" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.direccion" class="text-red-500">{{ $page.props.errors.direccion }}</div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                        <div class="mt-2 grid grid-cols-1 gap-y-6 sm:grid-cols-4 sm:gap-x-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.telefono" autocomplete="postal-code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.telefono" class="text-red-500">{{ $page.props.errors.telefono }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Facebook</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.facebook" autocomplete="postal-code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.facebook" class="text-red-500">{{ $page.props.errors.facebook }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Instagram</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.instagram" autocomplete="postal-code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.instagram" class="text-red-500">{{ $page.props.errors.instagram }}</div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Twitter</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.twitter" autocomplete="postal-code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                    <div v-if="$page.props.errors.twitter" class="text-red-500">{{ $page.props.errors.twitter }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 grid grid-cols-1 gap-y-6 sm:grid-cols-5 sm:gap-x-4">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Discapacitado?</label>
                                                                <div class="mt-1">
                                                                    <Toggle v-model="form.discapacitado" :disabled="verMode"/>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Víctima conflicto?</label>
                                                                <div class="mt-1">
                                                                    <Toggle v-model="form.victimaconflicto" :disabled="verMode"/>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Lider?</label>
                                                                <div class="mt-1">
                                                                    <Toggle v-model="form.lider" :disabled="verMode"/>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Avalado?</label>
                                                                <div class="mt-1">
                                                                    <Toggle v-model="form.avalado" :disabled="verMode" @change="openAvalado()"/>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                                                <div class="mt-1">
                                                                    <span class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-green-500 rounded-full">
                                                                        {{ form.estados.nombre }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                </div>
                                            </div>
                                            <div class="bg-blue-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <span v-if="form.estado != 10 && $can('militantes-delete')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                  <button v-show="editMode" @click="cambiarEstado(form.id, 'renunciar')" wire:click.prevent="cambiarEstado()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Renunciar
                                                  </button>
                                                </span>
                                                <span v-if="form.estado == 3 && $can('militantes-edit')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                  <button v-show="editMode" @click="cambiarEstado(form.id, 'aprobar')" wire:click.prevent="cambiarEstado()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-orange-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-orange-500 focus:outline-none focus:border-orange-700 focus:shadow-outline-orange transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Aprobar
                                                  </button>
                                                </span>
                                                <span v-if="$can('militantes-create')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                  <button v-show="newMode" @click="save(form)" wire:click.prevent="save()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Guardar
                                                  </button>
                                                </span>
                                                <span v-if="form.estado != 10 && $can('militantes-edit')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                  <button v-show="editMode" @click="update(form)" wire:click.prevent="update()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Actualizar
                                                  </button>
                                                </span>
                                                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">

                                              <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                Cancelar
                                              </button>
                                            </span>
                                            </div>
                                        </form>
                                    </section>
                                    <!-- fin menu 1 -->

                                    <!-- Seccion menu 2 -->
                                    <section v-if="activetab ==='2'" >
                                        <!-- Ventana modal Aval -->
                                        <!-- Main modal -->

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="">
                                            <h2 v-text="tituloModalDetalle" class="text-xl font-bold text-gray-900 px-4 py-4"></h2>
                                        </div>
                                        <div class="bg-white px-4 pt-2 pb-4 ">
                                            <div class="">
                                                <section>
                                                    <div class="mt-2 grid gap-y-6 ">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Corporación</label>
                                                            <div class="mt-1">
                                                                <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.idcorporacion">
                                                                    <option value="-" >Seleccione corporación</option>
                                                                    <option v-for="dato in arrayCorporaciones" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                                                </select>
                                                                <div v-if="$page.props.errors.idcorporacion" class="text-red-500">{{ $page.props.errors.idcorporacion }}</div>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Periodo electoral</label>
                                                            <div class="mt-1">
                                                                <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.periodo" autocomplete="given-name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Electo?</label>
                                                            <div class="mt-1">
                                                                <Toggle v-model="form.electo" :disabled="verMode" @change="openElecto()"/>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                                            <div class="mt-1">
                                                                <textarea v-model="form.observaciones" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" placeholder="Observaciones"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                              <button v-show="newMode" @click="save(form)" wire:click.prevent="save()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                Guardar
                                              </button>
                                            </span>
                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button v-show="form.estado != 10 && editMode" @click="update(form)" wire:click.prevent="update()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Actualizar
                                                </button>
                                            </span>
                                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                                <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                    Cancelar
                                                </button>
                                            </span>
                                        </div>
                                        <!-- Fin Ventana modal Aval -->
                                    </section>
                                    <!-- fin menu 2 -->

                                    <!-- Seccion menu 3 -->
                                    <section v-if="activetab ==='3'" >
                                        <!-- Ventana modal Aval -->
                                        <!-- Main modal -->

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="">
                                            <h2 v-text="tituloModalDetalle" class="text-xl font-bold text-gray-900 px-4 py-4"></h2>
                                        </div>
                                        <div class="bg-white px-4 pt-2 pb-4 ">
                                            <div class="">
                                                <section>
                                                    <div class="mt-2 grid gap-y-6 ">
                                                        <section>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Cantidad de votos</label>
                                                                <div class="mt-1">
                                                                    <input v-if="form.electo" type="number" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.votos" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block mt-2 text-sm font-medium text-gray-700">Coalición?</label>
                                                                <div class="mt-1">
                                                                    <Toggle v-model="form.coalicion" :disabled="verMode"/>
                                                                </div>
                                                            </div>

                                                            <div v-if="form.coalicion">
                                                                <label class="block text-sm mt-2 font-medium text-gray-700">Nombre coalición</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.nombrecoalicion" autocomplete="email" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm mt-2 font-medium text-gray-700">Renunció?</label>
                                                                <div class="mt-1">
                                                                    <Toggle v-model="form.renuncio" :disabled="verMode"/>
                                                                </div>
                                                            </div>

                                                            <div v-if="form.renuncio">
                                                                <label class="block text-sm mt-2 font-medium text-gray-700">Fecha de renuncia</label>
                                                                <div class="mt-1">
                                                                    <Datepicker v-model="form.fecharenuncia" :enableTimePicker="false" :disabled="verMode" required  autoApply placeholder="Fecha renuncia" class="border border-gray-700 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                </div>
                                                            </div>

                                                            <div v-if="form.renuncio">
                                                                <label class="block text-sm mt-2 font-medium text-gray-700">Remplazo</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.idremplazonombre" @keyup.enter="selectRemplazo(form.idremplazonombre)" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                </div>
                                                            </div>
                                                        </section>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                                            <div class="mt-1">
                                                                <textarea v-model="form.observaciones" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" placeholder="Observaciones"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                  <button v-show="newMode" @click="save(form)" wire:click.prevent="save()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Guardar
                                                  </button>
                                                </span>
                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                  <button v-show="form.estado != 10 && editMode" @click="update(form)" wire:click.prevent="update()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Actualizar
                                                  </button>
                                                </span>
                                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">

                                              <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                Cancelar
                                              </button>
                                            </span>
                                        </div>
                                        <!-- Fin Ventana modal Aval -->
                                    </section>
                                    <!-- fin menu 3 -->

                                    <!-- Seccion menu 4 -->
                                    <section v-if="activetab ==='4'" >
                                        <!-- Ventana modal Documentos -->
                                        <!-- Main modal -->

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="">
                                            <h2 v-text="tituloModalDetalle" class="text-xl font-bold text-gray-900 px-4 py-4"></h2>
                                        </div>
                                        <div class="bg-white px-4 pt-2 pb-4 ">
                                            <div class="">
                                                <section>
                                                    <div class="mt-2 grid gap-y-6 flex ">
                                                        <form @submit.prevent="submit" enctype="multipart/form-data">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Tipo de archivo</label>
                                                                    <select class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="archivoform.idtipoarchivo">
                                                                        <option value="0" >Seleccione</option>
                                                                        <option v-for="dato in arrayTiposarchivos" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                                                    </select>
                                                            </div>
                                                            <div>
                                                                <div class="mt-1">
                                                                    <input v-model="archivoform.idmilitante" type="hidden">
                                                                    <input @change="onFileChange" type="file" name="file" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-black-500 sm:text-sm">
                                                                </div>
                                                            </div>

                                                            <div v-if="form.estado != 10 && ($can('militantes-edit') || $can('militantes-create'))" class="bg-blue-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                                  <button wire:click.prevent="submit()" @click="subirArchivo(archivoform)" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                                    Adicionar
                                                                  </button>
                                                                </span>
                                                            </div>
                                                        </form>

                                                        <section>
                                                            <div class="lg:px-4 md:px-2 sm:px-0 py-2 pb-6">
                                                                <table class="table-fixed w-full">
                                                                    <thead>
                                                                    <tr class="bg-blue-100 hover:bg-blue-500 hover:text-gray-50">
                                                                        <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                                            <button @click="getRifas(buscar, 'loteria.nombre')" class="font-bold">
                                                                                Tipo Archivo
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
                                                                        <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                                            <button @click="getRifas(buscar, 'serie')" class="font-bold">
                                                                                Nombre
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
                                                                        <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                                            <button @click="getRifas(buscar, 'serie')" class="font-bold">
                                                                                Extensión
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
                                                                        <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                                            <button @click="getRifas(buscar, 'serie')" class="font-bold">
                                                                                Fecha actualización
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
                                                                    <tr @click="descargarArchivo(militante.url)" class="hover:bg-blue-100 text-center" text-sm v-if="arrayArchivos" v-for="(militante, id) in arrayArchivos" :key="id">
                                                                        <td class="border px-1 py-2 text-sm truncate" v-text="militante.tipoarchivo.nombre"></td>
                                                                        <td class="border px-1 py-2 text-sm truncate" v-text="militante.nombre"></td>
                                                                        <td class="border px-1 py-2 text-sm truncate" v-text="militante.extension"></td>
                                                                        <td class="border px-1 py-2 text-sm truncate" v-text="dateTimeFull(militante.updated_at)"></td>
                                                                    </tr>
                                                                    <tr v-else>
                                                                        <td class="border px-4 py-2 text-xs text-center" colspan="4"> La consulta no obtuvo datos</td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>

                                                            </div>
                                                        </section>

                                                    </div>
                                                </section>
                                            </div>
                                        </div>

                                        <!-- Fin Ventana modal Documentos -->
                                    </section>
                                    <!-- fin menu 4 -->

                                    <!-- Seccion menu 5 -->
                                    <section v-if="activetab ==='5'" >
                                        <!-- Ventana modal Historial -->
                                        <!-- Main modal -->

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="flex">
                                            <h2 v-text="tituloModalDetalle" class="text-xl font-bold text-gray-900 px-4 py-4"></h2>
                                            <Popper v-if="form.estado != 10" content="Nuevo" hover=true placement="top" arrow=true>
                                                <div class="items-center pt-3">
                                                    <a href="#" @click="isOpenregHistorial = !isOpenregHistorial">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </Popper>

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
                                                            <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                                <button @click="getRifas(buscar, 'serie')" class="font-bold">
                                                                    Tipo Historial
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
                                                            <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                                <button @click="getRifas(buscar, 'serie')" class="font-bold">
                                                                    Id Usuario
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
                                                            <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
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
                                                        <tr @click="verHistorial(dato.observaciones)" class="hover:bg-blue-100 text-center" text-sm v-if="arrayHistorial" v-for="(dato, id) in arrayHistorial.data" :key="id">
                                                            <td class="border px-1 py-2 text-sm truncate" v-text="dateTimeFull(dato.updated_at)"></td>
                                                            <td class="border px-1 py-2 text-sm truncate" v-text="dato.tipo.nombre"></td>
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
                                                        <div v-if="arrayHistorial.links.length > 3">
                                                            <div class="flex flex-wrap -mb-1">
                                                                <template v-for="(link, p) in arrayHistorial.links" :key="p">
                                                                    <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                                                                         v-html="link.label" />
                                                                    <button  v-else
                                                                             class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                                                                             :class="{ 'bg-blue-700 text-white': link.active }"
                                                                             v-on:click="this.cambiarPage(link.url, 'historial', null, null, form.id)"
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
                                    <!-- fin menu 5 -->

                                    <!-- Seccion menu 6 -->
                                    <section v-if="activetab ==='6'" >
                                        <!-- Ventana modal Aval -->
                                        <!-- Main modal -->

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="flex">
                                            <h2 v-text="tituloModalDetalle" class="text-xl font-bold text-gray-900 px-4 pt-4 pb-2"></h2>
                                            <div>
                                                <div class="mt-2">
                                                    <span v-if="formcc.estado == 0" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-blue-500 rounded-full">
                                                        Pendiente
                                                    </span>
                                                    <span v-else-if="formcc.estado == 1" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-blue-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="formcc.estado == 2" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-pink-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="formcc.estado == 3" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-orange-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="formcc.estado == 4" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-teal-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="formcc.estado == 5" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-yellow-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="formcc.estado == 6" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-green-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="formcc.estado == 7" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-red-500 rounded-full">
                                                        Renuente
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-white px-4 pt-2 pb-2">
                                            <div class="">
                                                <section>
                                                    <div class="border-t py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-4 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 0}" class="block text-sm font-medium text-gray-700">Fecha de presentación</label>
                                                            <div class="mt-1">
                                                                <Datepicker v-model="formcc.fechapresentacion" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 0}" @update:modelValue="validaEntrega(formcc.fechapresentacion)" required textInput :enableTimePicker="false" autoApply placeholder="Fecha presentación" class="border border-gray-300 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                <div v-if="$page.props.errors.fechapresentacion" class="text-red-500">{{ $page.props.errors.fechaingreso }}</div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 0}" class="block text-center text-sm font-medium text-gray-700">Entregó a tiempo</label>
                                                            <div class="mt-1 text-center">
                                                                <span v-if="!formcc.fechapresentacion" class="inline-flex px-3 py-2 text-sm font-semibold leading-5 text-white bg-orange-500 rounded-full">
                                                                    No presentado
                                                                </span>
                                                                <span v-else-if="formcc.presentacion" class="inline-flex px-3 py-2 text-sm font-semibold leading-5 text-white bg-blue-500 rounded-full">
                                                                    Si
                                                                </span>
                                                                <span v-else class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-red-500 rounded-full">
                                                                    No
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div v-show="formcc.estado >= 1">
                                                            <label :class="{'font-bold' : formcc.estado == 1}" class="block text-sm font-medium text-gray-700">Fecha de requerimientos</label>
                                                            <div class="mt-1">
                                                                <Datepicker v-model="formcc.requerimientos" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 1}" required textInput :enableTimePicker="false" autoApply placeholder="Fecha ingreso" class="border border-gray-300 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                <div v-if="$page.props.errors.fechaingreso" class="text-red-500">{{ $page.props.errors.fechaingreso }}</div>
                                                            </div>
                                                        </div>
                                                        <div v-show="formcc.estado >= 1">
                                                            <label :class="{'font-bold' : formcc.estado == 1}" class="block text-sm font-medium text-gray-700">Auto entrega CNE</label>
                                                            <div class="mt-1">
                                                                <input type="text" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 1}" v-model="formcc.autorequerimiento" autocomplete="given-name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                <div v-if="$page.props.errors.autorequerimiento" class="text-red-500">{{ $page.props.errors.autorequerimiento }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-show="formcc.estado >= 2" class="border-t py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 2}" class="block text-center text-sm font-medium text-gray-700">Requiere corrección?</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="formcc.correccion" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 2}"/>
                                                            </div>
                                                        </div>
                                                        <div v-show="formcc.correccion">
                                                            <label :class="{'font-bold' : formcc.estado == 2}" class="block text-center text-sm font-medium text-gray-700">Presentó corrección?</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="formcc.presentacorreccion" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 2}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-show="formcc.estado >= 3" class="border-t py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-4 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 3}" class="block text-center text-sm font-medium text-gray-700">En investigación?</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="formcc.investigado" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 3}"/>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 3}" class="block text-center text-sm font-medium text-gray-700">Renuente</label>
                                                            <div class="mt-1 text-center">
                                                                <span v-if="formcc.renuente" class="inline-flex px-3 py-2 text-sm font-semibold leading-5 text-white bg-red-500 rounded-full">
                                                                    Si
                                                                </span>
                                                                <span v-else class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-blue-500 rounded-full">
                                                                    No
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 3}" class="block text-center text-sm font-medium text-gray-700">Presentó recurso?</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="formcc.recurso" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 3}"/>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 3}" class="block text-center text-sm font-medium text-gray-700">Sanción</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="formcc.sancionado" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 3}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-show="formcc.estado >= 4" class="border-t rounded py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 4}" class="block mt-2 text-sm font-medium text-gray-700">Fecha reposición de votos</label>
                                                            <div class="mt-1">
                                                                <Datepicker v-model="formcc.fecharesolucion" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 4}" required textInput :enableTimePicker="false" autoApply placeholder="Fecha reposición" class="border border-gray-300 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                <div v-if="$page.props.errors.fechaingreso" class="text-red-500">{{ $page.props.errors.reposición }}</div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 4}" class="block text-sm mt-2 font-medium text-gray-700">Resolución</label>
                                                            <input type="text" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 4}" v-model="formcc.resolucionpago" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                        </div>
                                                    </div>
                                                    <div v-show="formcc.estado >= 5" class="border-t rounded py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : formcc.estado == 5}" class="block text-sm font-medium text-gray-700">Fecha de pago</label>
                                                            <div class="mt-1">
                                                                <Datepicker v-model="formcc.fechapago" :disabled="verMode" :class="{'border-gray-500 border-4' : formcc.estado == 5}" required textInput :enableTimePicker="false" autoApply placeholder="Fecha pago" class="border border-gray-300 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                            </div>
                                                        </div>
                                                        <div class="my-auto">
                                                            <form name="cc" id="cc" @submit.prevent="submit" enctype="multipart/form-data">
                                                                <div>
                                                                    <input v-model="archivoform.id" type="hidden">
                                                                    <input @change="onFileChange2" type="file" name="file" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-black-500 sm:text-sm">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="border-t mt-2 grid grid-cols-1 gap-y-6 sm:grid-cols-1 sm:gap-x-4">
                                                        <textarea v-model="formcc.observaciones" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" placeholder="Observaciones"></textarea>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <span v-if="$can('cuentasclaras-edit') || $can('cuentasclaras-create')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button v-show="formcc.estado == 0" @click="updateCuentasclaras(formcc, 1, form.id)" wire:click.prevent="save()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-yellow-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-yellow-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Entregar informe
                                                </button>
                                            </span>
                                            <span v-if="$can('cuentasclaras-edit') || $can('cuentasclaras-create')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button v-show="formcc.estado == 1" @click="updateCuentasclaras(formcc, 2, form.id)" wire:click.prevent="save()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-blue-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Requerimientos CNE
                                                </button>
                                            </span>
                                            <span v-if="$can('cuentasclaras-edit') || $can('cuentasclaras-create')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button v-show="formcc.estado == 2" @click="updateCuentasclaras(formcc, 3, form.id)" wire:click.prevent="save()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-teal-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-teal-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Correcciones
                                                </button>
                                            </span>
                                            <span v-if="$can('cuentasclaras-edit') || $can('cuentasclaras-create')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button v-show="formcc.estado == 3" @click="updateCuentasclaras(formcc, 4, form.id)" wire:click.prevent="save()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-orange-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-orange-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    En firme
                                                </button>
                                            </span>
                                            <span v-if="$can('cuentasclaras-edit') || $can('cuentasclaras-create')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button v-show="formcc.estado == 4" @click="updateCuentasclaras(formcc, 5, form.id)" wire:click.prevent="update()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-pink-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-pink-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Reposición
                                                </button>
                                            </span>
                                            <span v-if="$can('cuentasclaras-edit') || $can('cuentasclaras-create')" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                <button v-show="formcc.estado == 5" @click="updateCuentasclaras(formcc, 6, form.id)" wire:click.prevent="update()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                    Pagado
                                                </button>
                                            </span>
                                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                                <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                    Cancelar
                                                </button>
                                            </span>
                                        </div>
                                        <!-- Fin Ventana modal Aval -->
                                    </section>
                                    <!-- fin menu 6 -->

                                    <!-- Seccion menu 7 -->
                                    <section v-if="activetab ==='7'" >
                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <section>
                                            <div class="px-4">
                                            <form>
                                                <div class="grid xl:grid-cols-2 xl:gap-6 mt-4">
                                                    <div class="relative z-0 w-full mb-6 group">
                                                        El curso tiene una duración de 40 minutos y lo podrá repetir las veces que se desee.
                                                    </div>
                                                    <div class="relative z-0 w-full mb-6 group">
                                                        El examen de certificación tendrá una duración de 20 minútos y para poder aprobarlo deberá superar 70% de respuestas correctas.
                                                    </div>
                                                    <div class="relative z-0 w-full mb-6 group">
                                                        Estado examen: <span class="font-bold">{{ examenuser?(examenuser.estado==1?'Aprobado':'No aprobado'):'No presentado' }}</span>
                                                    </div>
                                                    <div class="relative z-0 w-full mb-6 group">
                                                        Cantidad de preguntas:  <span class="font-bold">{{ examen?examen.preguntas:'-' }}</span>
                                                    </div>
                                                    <div class="relative z-0 w-full mb-6 group">
                                                        Calificación:  <span class="font-bold">{{ examenuser?examenuser.calificacion:'0' }} ( {{examenuser?examenuser.respuestas:'0' }} de {{ examen?examen.preguntas:'-' }})</span>
                                                    </div>
                                                    <div class="relative z-0 w-full mb-6 group">
                                                        Fecha de presentación de examen: <span class="font-bold">{{ examenuser?dateTimeFull(examenuser.updated_at):'' }}</span>
                                                    </div>

                                                    <div v-if="(examenuser?(examenuser.estado==1)?true:false:false)" class="relative z-0 w-full mb-6 group">
                                                        <button @click="openModal('registrar')" :disabled="!(examenuser?examenuser.estado:false)" class="bg-yellow-500 text-xs  hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded ">Descargar certificado</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        </section>
                                        <div class="bg-blue-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                                <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                    Cancelar
                                                </button>
                                            </span>
                                        </div>
                                    </section>

                                    <!-- Seccion menu 8 -->
                                    <section v-if="activetab ==='8'" >
                                        <!-- Ventana modal Aval -->
                                        <!-- Main modal -->

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="">
                                            <h2 v-text="tituloModalDetalle" class="text-xl font-bold text-gray-900 px-4 py-4"></h2>
                                        </div>
                                        <div class="bg-white px-4 pt-2 pb-4 ">
                                            <div class="">
                                                <section>
                                                    <div class="mt-2 grid gap-y-6 ">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Corporación</label>
                                                            <div class="mt-1">
                                                                <select :disabled="verMode" :class="{'bg-blue-100' : verMode}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="form.historialaval.idcorporacion">
                                                                    <option value="-" >Seleccione corporación</option>
                                                                    <option v-for="dato in arrayCorporaciones" :key="dato.id" :value="dato.id" v-text="dato.nombre"></option>
                                                                </select>
                                                                <div v-if="$page.props.errors.idcorporacion" class="text-red-500">{{ $page.props.errors.idcorporacion }}</div>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Periodo electoral</label>
                                                            <div class="mt-1">
                                                                <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.historialaval.periodo" autocomplete="given-name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Electo?</label>
                                                            <div class="mt-1">
                                                                <Toggle v-model="form.historialaval.electo" :disabled="verMode" @change="openElecto()"/>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                                            <div class="mt-1">
                                                                <textarea v-model="form.historialaval.observaciones" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" placeholder="Observaciones"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                        <!-- Fin Ventana modal Aval -->
                                    </section>
                                    <!-- fin menu 8 -->

                                    <!-- Seccion menu 9 -->
                                    <section v-if="activetab ==='9'" >
                                        <!-- Ventana modal Aval -->
                                        <!-- Main modal -->

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="">
                                            <h2 v-text="tituloModalDetalle" class="text-xl font-bold text-gray-900 px-4 py-4"></h2>
                                        </div>
                                        <div class="bg-white px-4 pt-2 pb-4 ">
                                            <div class="">
                                                <section>
                                                    <div class="mt-2 grid gap-y-6 ">
                                                        <section>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Cantidad de votos</label>
                                                                <div class="mt-1">
                                                                    <input v-if="form.historialaval.electo" type="number" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.votos" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block mt-2 text-sm font-medium text-gray-700">Coalición?</label>
                                                                <div class="mt-1">
                                                                    <Toggle v-model="form.historialaval.coalicion" :disabled="verMode"/>
                                                                </div>
                                                            </div>

                                                            <div v-if="form.coalicion">
                                                                <label class="block text-sm mt-2 font-medium text-gray-700">Nombre coalición</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.historialaval.nombrecoalicion" autocomplete="email" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm mt-2 font-medium text-gray-700">Renunció?</label>
                                                                <div class="mt-1">
                                                                    <Toggle v-model="form.historialaval.renuncio" :disabled="verMode"/>
                                                                </div>
                                                            </div>

                                                            <div v-if="form.historialaval.renuncio">
                                                                <label class="block text-sm mt-2 font-medium text-gray-700">Fecha de renuncia</label>
                                                                <div class="mt-1">
                                                                    <Datepicker v-model="form.historialaval.fecharenuncia" :enableTimePicker="false" :disabled="verMode" required  autoApply placeholder="Fecha renuncia" class="border border-gray-700 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                </div>
                                                            </div>

                                                            <div v-if="form.historialaval.renuncio">
                                                                <label class="block text-sm mt-2 font-medium text-gray-700">Remplazo</label>
                                                                <div class="mt-1">
                                                                    <input type="text" :disabled="verMode" :class="{'bg-blue-100' : verMode}" v-model="form.historialaval.idremplazonombre" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                </div>
                                                            </div>
                                                        </section>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                                            <div class="mt-1">
                                                                <textarea v-model="form.historialaval.observaciones" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" placeholder="Observaciones"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                        <!-- Fin Ventana modal Aval -->
                                    </section>
                                    <!-- fin menu 9 -->

                                    <!-- Seccion menu 10 -->
                                    <section v-if="activetab ==='10'" >
                                        <!-- Ventana modal Aval -->
                                        <!-- Main modal -->

                                        <button type="button" @click="closeModal()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                        </button>
                                        <div class="flex">
                                            <h2 v-text="tituloModalDetalle" class="text-xl font-bold text-gray-900 px-4 pt-4 pb-2"></h2>
                                            <div>
                                                <div class="mt-2">
                                                    <span v-if="form.cuentasclaras.estado == 0" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-blue-500 rounded-full">
                                                        Pendiente
                                                    </span>
                                                    <span v-else-if="form.cuentasclaras.estado == 1" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-blue-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="form.cuentasclaras.estado == 2" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-pink-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="form.cuentasclaras.estado == 3" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-orange-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="form.cuentasclaras.estado == 4" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-teal-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="form.cuentasclaras.estado == 5" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-yellow-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="form.cuentasclaras.estado == 6" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-green-500 rounded-full">
                                                        {{ formcc.estadonombre.nombre }}
                                                    </span>
                                                    <span v-else-if="form.cuentasclaras.estado == 7" class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-red-500 rounded-full">
                                                        Renuente
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-white px-4 pt-2 pb-2">
                                            <div class="">
                                                <section>
                                                    <div class="border-t py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-4 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 0}" class="block text-sm font-medium text-gray-700">Fecha de presentación</label>
                                                            <div class="mt-1">
                                                                <Datepicker v-model="form.cuentasclaras.fechapresentacion" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 0}" @update:modelValue="validaEntrega(form.cuentasclaras.fechapresentacion)" required textInput :enableTimePicker="false" autoApply placeholder="Fecha presentación" class="border border-gray-300 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                <div v-if="$page.props.errors.fechapresentacion" class="text-red-500">{{ $page.props.errors.fechaingreso }}</div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 0}" class="block text-center text-sm font-medium text-gray-700">Entregó a tiempo</label>
                                                            <div class="mt-1 text-center">
                                                                <span v-if="!form.cuentasclaras.fechapresentacion" class="inline-flex px-3 py-2 text-sm font-semibold leading-5 text-white bg-orange-500 rounded-full">
                                                                    No presentado
                                                                </span>
                                                                <span v-else-if="form.cuentasclaras.presentacion" class="inline-flex px-3 py-2 text-sm font-semibold leading-5 text-white bg-blue-500 rounded-full">
                                                                    Si
                                                                </span>
                                                                <span v-else class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-red-500 rounded-full">
                                                                    No
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div v-show="form.cuentasclaras.estado >= 1">
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 1}" class="block text-sm font-medium text-gray-700">Fecha de requerimientos</label>
                                                            <div class="mt-1">
                                                                <Datepicker v-model="form.cuentasclaras.requerimientos" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 1}" required textInput :enableTimePicker="false" autoApply placeholder="Fecha ingreso" class="border border-gray-300 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                <div v-if="$page.props.errors.fechaingreso" class="text-red-500">{{ $page.props.errors.fechaingreso }}</div>
                                                            </div>
                                                        </div>
                                                        <div v-show="form.cuentasclaras.estado >= 1">
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 1}" class="block text-sm font-medium text-gray-700">Auto entrega CNE</label>
                                                            <div class="mt-1">
                                                                <input type="text" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 1}" v-model="form.cuentasclaras.autorequerimiento" autocomplete="given-name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                <div v-if="$page.props.errors.autorequerimiento" class="text-red-500">{{ $page.props.errors.autorequerimiento }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-show="form.cuentasclaras.estado >= 2" class="border-t py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 2}" class="block text-center text-sm font-medium text-gray-700">Requiere corrección?</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="form.cuentasclaras.correccion" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 2}"/>
                                                            </div>
                                                        </div>
                                                        <div v-show="form.cuentasclaras.correccion">
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 2}" class="block text-center text-sm font-medium text-gray-700">Presentó corrección?</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="form.cuentasclaras.presentacorreccion" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 2}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-show="form.cuentasclaras.estado >= 3" class="border-t py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-4 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 3}" class="block text-center text-sm font-medium text-gray-700">En investigación?</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="form.cuentasclaras.investigado" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 3}"/>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 3}" class="block text-center text-sm font-medium text-gray-700">Renuente</label>
                                                            <div class="mt-1 text-center">
                                                                <span v-if="form.cuentasclaras.renuente" class="inline-flex px-3 py-2 text-sm font-semibold leading-5 text-white bg-red-500 rounded-full">
                                                                    Si
                                                                </span>
                                                                <span v-else class="inline-flex px-2 py-2 text-sm font-semibold leading-5 text-white bg-blue-500 rounded-full">
                                                                    No
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 3}" class="block text-center text-sm font-medium text-gray-700">Presentó recurso?</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="form.cuentasclaras.recurso" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 3}"/>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 3}" class="block text-center text-sm font-medium text-gray-700">Sanción</label>
                                                            <div class="mt-1 text-center">
                                                                <Toggle v-model="form.cuentasclaras.sancionado" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 3}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-show="form.cuentasclaras.estado >= 4" class="border-t rounded py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 4}" class="block mt-2 text-sm font-medium text-gray-700">Fecha reposición de votos</label>
                                                            <div class="mt-1">
                                                                <Datepicker v-model="form.cuentasclaras.fecharesolucion" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 4}" required textInput :enableTimePicker="false" autoApply placeholder="Fecha reposición" class="border border-gray-300 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                                <div v-if="$page.props.errors.fechaingreso" class="text-red-500">{{ $page.props.errors.reposición }}</div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 4}" class="block text-sm mt-2 font-medium text-gray-700">Resolución</label>
                                                            <input type="text" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 4}" v-model="form.cuentasclaras.resolucionpago" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                        </div>
                                                    </div>
                                                    <div v-show="form.cuentasclaras.estado >= 5" class="border-t rounded py-2 mt-2 mb-2 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                                                        <div>
                                                            <label :class="{'font-bold' : form.cuentasclaras.estado == 5}" class="block text-sm font-medium text-gray-700">Fecha de pago</label>
                                                            <div class="mt-1">
                                                                <Datepicker v-model="form.cuentasclaras.fechapago" :disabled="verMode" :class="{'border-gray-500 border-4' : form.cuentasclaras.estado == 5}" required textInput :enableTimePicker="false" autoApply placeholder="Fecha pago" class="border border-gray-300 rounded w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                                                            </div>
                                                        </div>
                                                        <div class="my-auto">
                                                            <form name="cc" id="cc" @submit.prevent="submit" enctype="multipart/form-data">
                                                                <div>
                                                                    <input v-model="archivoform.id" type="hidden">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="border-t mt-2 grid grid-cols-1 gap-y-6 sm:grid-cols-1 sm:gap-x-4">
                                                        <textarea v-model="form.cuentasclaras.observaciones" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" placeholder="Observaciones"></textarea>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                        <!-- Fin Ventana modal Aval -->
                                    </section>
                                    <!-- fin menu 10 -->

                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Fin Ventana modal Detalles -->

                    <!-- Ventana modal buscar remplazo-->
                    <section>
                        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpenRemplazo">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 transition-opacity" @click="closeModalRemplazo()">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>

                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

                                <!-- Contenido modal -->
                                <div class="inline-block lg:w-6/12 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                    <button type="button" @click="closeModalRemplazo()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                    <section>
                                        <div class="flex justify-between mx-auto p-4">
                                            <div class="w-1/2">
                                                <h1 class="font-bold text-xl text-black-800 leading-tight">
                                                    Buscar Militantes Remplazo
                                                </h1>
                                            </div>

                                            <div class="w-1/2 pr-2">
                                                <div class="container flex justify-center items-center">
                                                    <div class="relative">
                                                        <div class="absolute top-4 left-3">
                                                            <i class="fa fa-search text-gray-400 z-20 hover:text-gray-500"></i> </div>
                                                            <input type="text" v-model="buscarmilitante" @keyup.enter="getmilitantes(buscarmilitante,'documento')" class="h-8 w-26 pl-4 pr-4 rounded-lg z-0 focus:shadow focus:outline-none" placeholder="Buscar (documento, nombre)">
                                                            <button @click="getmilitantes(buscarmilitante,'documento, nombre', true)">
                                                            <div class="absolute top-2 right-2">
                                                                <Icon icon="fe:search" class="h-4"  />
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </section>
                                    <!-- Fin Encabezado y titulo -->
                                    <!-- Tabla de contenido -->
                                    <section>
                                        <div class="lg:px-4 md:px-2 sm:px-0 py-2 pb-6">
                                            <table class="table-fixed w-full">
                                                <thead>
                                                <tr class="bg-blue-100 hover:bg-blue-500 hover:text-gray-50">
                                                    <th class="px-4 py-2 w-2/12 text-sm font-bold rounded-b">
                                                        <button @click="getRifas('', 'idloteria')" class="font-bold">
                                                            Nombre
                                                            <div v-show="sortBy == 'idloteria'">
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
                                                    <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                        <button @click="getRifas(buscar, 'loteria.nombre')" class="font-bold">
                                                            Documento
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
                                                    <th class="px-4 py-2 w-1/12 text-sm font-bold rounded-b">
                                                        <button @click="getRifas(buscar, 'serie')" class="font-bold">
                                                            Movil
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
                                                <tr @click="onSelectRemplazo(militante)" class="hover:bg-blue-100 text-center" text-sm v-if="arrayData.data" v-for="(militante, id) in arrayData.data" :key="id">
                                                    <td class="border px-1 py-2 text-sm truncate" v-text="militante.full_name"></td>
                                                    <td class="border px-1 py-2 text-sm truncate" v-text="militante.documento"></td>
                                                    <td class="border px-1 py-2 text-sm truncate" v-text="militante.movil"></td>
                                                </tr>
                                                <tr v-else>
                                                    <td class="border px-4 py-2 text-xs text-center" colspan="4"> La consulta no obtuvo datos</td>
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
                                                                     v-on:click="this.cambiarPage(link.url, 'militantes')"
                                                                     v-html="link.label" />
                                                        </template>
                                                    </div>
                                                </div>
                                            </section>
                                            <!-- Paginacion -->


                                        </div>
                                    </section>
                                    <!-- Fin form -->
                                </div>
                                <!-- Fin Contenido modal -->
                            </div>
                        </div>
                    </section>
                    <!-- Fin Ventana modal buscar remplazo -->

                    <!-- Ventana modal cambiar estado -->
                    <!-- Main modal -->
                    <section> <!-- Ventana modal -->
                        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpenCambioestado">
                            <div class="items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div class="fixed inset-0 transition-opacity">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>

                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                                <div class="inline-block lg:w-6/12 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                    <button type="button" @click="closeModalCambioestado()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                    <div class="">
                                        <h2 class="text-xl font-bold text-gray-900 px-4 py-4">Cambiar estado {{ formestado.tipo }}</h2>
                                    </div>
                                    <div v-if="formestado.tipo == 'aprobar'" class="lg:px-4 md:px-2 sm:px-0 py-2 pb-6">
                                        <div class="text-center w-full flex text-sm">
                                            <div class="px-4">
                                                Formulario de inscripción
                                            </div>
                                            <div v-if="form.archivoformulario" class="text-green-600 px-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </div>
                                            <div v-else class="text-red-600 px-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        </div>

                                        <div v-if="false" class="text-center w-full flex text-sm">
                                            <div class="px-4">
                                                Certificado del curso
                                            </div>
                                            <div v-if="form.archivocertificado" class="text-green-600 px-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </div>
                                            <div v-else class="text-red-600 px-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <form @submit.prevent="submit">
                                        <div class="bg-white px-4 pt-2 pb-4 ">
                                            <div class="">
                                                <section>
                                                    <div class="mt-2 grid gap-y-6 ">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Observación</label>
                                                            <div class="mt-1">
                                                                <textarea rows="4" v-model="formestado.observaciones" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                              <button wire:click.prevent="updateEstado()" @click="updateEstado(formestado)" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                Aceptar
                                              </button>
                                            </span>
                                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">

                                          <button @click="closeModalCambioestado()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                            Cancelar
                                          </button>
                                        </span>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Fin Ventana modal cambiar estado -->

                    <!-- Ventana modal ver historial -->
                    <!-- Main modal -->
                    <section> <!-- Ventana modal -->
                        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpenregHistorial">
                            <div class="items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div class="fixed inset-0 transition-opacity">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>

                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                                <div class="inline-block lg:w-6/12 align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                    <button type="button" @click="isOpenregHistorial = !isOpenregHistorial" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                    <div class="">
                                        <h2 class="text-xl font-bold text-gray-900 px-4 py-4">Registrar actividad</h2>
                                    </div>
                                    <form @submit.prevent="submit">
                                        <div class="bg-white px-4 pt-2 pb-4 ">
                                            <div class="">
                                                <section>
                                                    <div class="mt-2 grid gap-y-6 ">
                                                        <label class="block text-sm font-medium text-gray-700">Tipo de actividad</label>

                                                        <div class="relative z-0 w-full mb-6 group">
                                                            <select class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" v-model="formestado.tipo">
                                                                <option value="-" >Seleccione</option>
                                                                <option v-for="tipomil in arrayTipohistorial" :key="tipomil.id" :value="tipomil.id" v-text="tipomil.nombre"></option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Observacion</label>
                                                            <div class="mt-1">
                                                                <textarea rows="4" v-model="formestado.observaciones" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                              <button wire:click.prevent="registroHistorial()" @click="registroHistorial(form.id, formestado.tipo, formestado.observaciones)" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" >
                                                Aceptar
                                              </button>
                                            </span>
                                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">

                                          <button @click="closeModalPass()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                            Cancelar
                                          </button>
                                        </span>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Fin Ventana modal ver hitorial -->


                    <!-- Ventana modal Crear Importar-->
                    <section>
                        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isImport">
                            <div class="flex items-end justify-center h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div class="fixed inset-0 transition-opacity">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

                                <!-- Contenido modal -->
                                <div class="inline-block lg:w-6/12 align-bottom bg-white rounded-lg text-left h-fit shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                    <button type="button" @click="isImport = !isImport" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                    <div class="">
                                        <h2 v-text="tituloModal" class="text-xl font-bold text-gray-900 px-4 py-2"></h2>
                                    </div>
                                    <!-- Inicio Form -->
                                    <div class="bg-white px-4 pt-2 pb-2 sm:p-6 sm:pb-4">
                                        <div class="">

                                            <!-- Fin Mensajes Flash -->
                                            <!-- Formulario -->
                                            <section>
                                                <div class="flex py-1 w-full max-h-fit overflow-y-scroll">
                                                    <div class="mb-4 w-full">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">Importar</label>

                                                        <form name="import" id="import" :action="route('militantes.import')" method="POST" enctype="multipart/form-data">
                                                            <div class="form-group mb-4">
                                                                <div class="custom-file text-left">
                                                                    <input type="hidden" name="_token" :value="formpasswd._token">
                                                                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="file" name="file" id="customFile">
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <button onclick="document.getElementById('import').submit();" class="bg-orange-500 text-xs  hover:bg-orange-700 text-white font-bold py-2 px-4 rounded ">Importar</button>

                                                    </div>
                                                </div>
                                            </section>
                                            <!-- Fin formulario -->
                                        </div>
                                    </div>
                                    <!-- Fin form -->
                                </div>
                                <!-- Fin Contenido modal -->
                            </div>
                        </div>
                    </section>
                    <!-- Fin Ventana modal Importar -->

                    <!-- Ventana modal Crear Importar-->
                    <section>
                        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isImportprogress">
                            <div class="flex items-end justify-center h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div class="fixed inset-0 transition-opacity">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

                                <!-- Contenido modal -->
                                <div class="inline-block lg:w-6/12 align-bottom bg-white rounded-lg text-left h-fit shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                    <button type="button" @click="isImportprogress = !isImportprogress; closeimport()" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-blue-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-blue-800 dark:hover:text-white" data-modal-toggle="authentication-modal">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </button>
                                    <div class="">
                                        <h2 class="text-xl font-bold text-gray-900 px-4 py-2">Progreso de Importar archivo</h2>
                                    </div>
                                    <!-- Inicio Form -->
                                    <div class="bg-white px-4 pt-2 pb-2 sm:p-6 sm:pb-4">
                                        <div class="">

                                            <!-- Fin Mensajes Flash -->
                                            <!-- Formulario -->
                                            <section>
                                                <div class="flex py-1 w-full max-h-fit overflow-y-scroll">
                                                    <div class="mb-4 w-full">
                                                        <div class="flex">
                                                            <div class="w-1/2 p-4 mt-1">
                                                                <label class="block text-sm font-medium text-gray-700">Hora inicio</label>
                                                                <input type="text" v-model="start_date" class="h-8 w-26 pl-4 pr-4 rounded-lg z-0 focus:shadow focus:outline-none">
                                                            </div>
                                                            <div class="w-1/2 p-4 mt-1">
                                                                <label class="block text-sm font-medium text-gray-700">Hora fin</label>
                                                                <input type="text" v-model="finish_date" class="h-8 w-26 pl-4 pr-4 rounded-lg z-0 focus:shadow focus:outline-none">
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Progreso</label>
                                                            <div class="mt-1">
                                                                <textarea v-model="progresoimport" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-blue-50 rounded-lg border border-gray-300 focus:ring-gray-500 focus:border-gray-500" placeholder="Progreso"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <!-- Fin formulario -->
                                        </div>
                                    </div>
                                    <!-- Fin form -->
                                </div>
                                <!-- Fin Contenido modal -->
                            </div>
                        </div>
                    </section>
                    <!-- Fin Ventana modal Importar -->

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
                            <div class="absolute inset-0 bg-blue-400 opacity-75"></div>
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
        militantes : [],
        errors: Object,
        roluser: null,
        _token: null
    },
    computed: {

    },
    data() {
        this.trackProgress = _.debounce(this.trackProgress, 1000);

        return {
            loading: false,
            progresoimport: '',
            current_row: 0,
            total_rows: 0,
            progress: 0,
            start_date: '',
            finish_date: '',
            isImportprogress: false,
            isOpenAvalSol: false,
            isOpenRechazoSol: false,
            isOpenMotivoFechazo: false,
            motivorechazo: '',

            verHistorialobs: null,
            activetab: '1',
            activetabSol: '1',
            tituloModal: '',
            tituloModalDetalle: '',
            examenuser: null,
            examen: null,
            formpasswd: {
                _token: this._token,
                id: '',
                password: '',
                password_confirmation: '',
            },
            form: {
                id: null,
                nombre: '',
                email: null,
                username: null,
                apellido: null,
                estado: 3,
                idtipos_documento: '-',
                documento: null,
                direccion: null,
                iddepartamento: '-',
                idciudad: '-',
                idpais: 1,
                observaciones: null,
                movil: null,
                url: false,
                cambiarpassword: true,
                idgenero: '-',
                idniveleducativo: '-',
                idgrupoetnico: '-',
                discapacitado: 0,
                victimaconflicto: 0,
                idinscripcion: '-',
                lider: 0,
                avalado: 0,
                idcorporacion: '-',
                periodo: 0,
                electo: 0,
                votos: 0,
                aportes: 0,
                coalicion: 0,
                facebook: null,
                twitter: null,
                instagram: null,
                nombrecoalicion: null,
                renuncio: 0,
                fecharenuncia: null,
                fechanacimiento: null,
                idremplazo: null,
                estados: [],
                ccestado: null,
                ccreposicion: null,
                ccobservaciones: null,
                cccreated_at: null,
                ccupdated_at: null,
                archivoformulario: false,
                archivocertificado: false,
                historialaval: [],
                cuentasclaras: []
            },
            formbusqueda: {
                id: null,
                nombre: '',
                email: null,
                username: null,
                apellido: null,
                idrol: 0,
                idtipos_documento: 0,
                documento: null,
                direccion: null,
                iddepartamento: 0,
                idciudad: 0,
                idpais: 1,
                observaciones: null,
                movil: null,
                url: false,
                cambiarpassword: true,
                idgenero: '-',
                idinscripcion: '-',
                idniveleducativo: '-',
                idgrupoetnico: '-',
                discapacitado: '-',
                victimaconflicto: '-',
                lider: '-',
                avalado: '-',
                idcorporacion: '-',
                periodo: '-',
                electo: '-',
                estado: '-',
                estadocc: '-',
                votos: 0,
                aportes: '-',
                coalicion: '-',
                nombrecoalicion: null,
                renuncio: '-',
                fecharenuncia: null,
                idremplazo: null,
                fechainicio: null,
                fechafin: null
            },
            formcc: {
                id: null,
                fechapresentacion: null,
                estado: 0,
                correccion: 0,
                renuente: 0,
            },
            formestado: {
                idmilitante: null,
                estado: null,
                tipo: null,
                observaciones: null
            },
            archivoform: {
                idmilitante: 0,
                file: '',
                idtipoarchivo: 0
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
            }
        }
    },
    methods: {
        handleChange: function() {
            this.trackProgress();
        },
        async trackProgress() {
            const { data } = await axios.get('/import-status');

            if (data.finished || (data.current_row == data.total_rows)) {
                this.current_row = this.total_rows
                this.progress = 100
                return;
            };

            this.total_rows = data.total_rows;
            this.current_row = data.current_row;
            this.progress = Math.ceil(data.current_row / data.total_rows * 100) + '%';
            this.start_date = data.start_date;
            this.finish_date = data.end_date;
            this.statusfinal = data.statusfinal;
            console.log(this.statusfinal);
            this.progresoimport = 'Total registros: '+ this.total_rows+'\nRegistro actual: ' + this.current_row +  '\nProgreso: ' + this.progress + '\n' + this.statusfinal;
            if (this.isImportprogress == true) {
                this.trackProgress();
            }
        },
        closeimport() {
            this.isImportprogress = false;
            if (this.progress > 0 && this.progress < 100) {
                if (confirm('Do you want to close')) {
                    this.$emit('close')
                    window.location.reload()
                }
            } else {
                this.$emit('close')
                window.location.reload()
            }
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
                    timer: 1500
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

        onFileChange(e){
            this.archivoform.file = e.target.files[0];
        },
        onFileChange2(e){
            this.archivoform.file = e.target.files[0];
            this.subirArchivocc(this.archivoform);
        },
        cambiarPass: function(){
            this.isOpencambiopass = true;
        } ,
        updatePass: function(data) {
            this.$inertia.post('/changepasssu', data, {
                onSuccess: (page) => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Se ha cambiado la contraseña',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.formpasswd.id = 0;
                    this.formpasswd.password = '';
                    this.formpasswd.password_confirmation = '';
                    this.isOpencambiopass = false;
                    this.getmilitantes('','nombre');
                    this.editMode = false;
                    this.closeModal();
                },
            });
        },
        openModalImport: function() {
            this.tituloModal = 'Importar archivo Excel';
            this.isImport = true;
        },
        openModalAval: function (data = []) {
            this.isOpenAvalSol = true;
            this.formaval.idmilitante = data.id;
        },
        openModal: function (accion, data = []) {
            this.isOpen = true;
            this.reset();
            this.getDepartamentos();
            this.getTiposdocumento();
            switch (accion) {
                case 'registrar': {
                    this.form.fechaingreso = Date.now();
                    this.tituloModal = 'Crear nuevo Militante';
                    this.form.idpais = 1;
                    this.form.iddepartamento = '-';
                    this.form.idciudad = '-';
                    this.form.idtipos_documento = '-';
                    this.form.idgenero = '-';
                    this.form.idniveleducativo = '-';
                    this.form.idgrupoetnico = '-';
                    this.form.idcorporacion = '-';
                    this.form.idremplazo = null;
                    this.form.idremplazonombre = null;
                    this.form.estado = 3;
                    this.ccestado = 4;
                    this.aportes = 0;
                    this.newMode = true;
                    this.verMode = false;
                    this.editMode = false;
                    break;
                }
                case 'ver': {
                    this.tituloModal = 'Ver Usuario ' + data['nombre'] + ' ' + data['apellido'];
                    this.form.id = data['id'];
                    this.form.fechaingreso = data['fechaingreso'];
                    this.form.idpais = data['idpais'];
                    this.form.iddepartamento = data['iddepartamento'];
                    this.getCiudades();
                    this.form.idciudad = data['idciudad'];
                    this.form.idtipos_documento = data['idtipos_documento'];
                    this.form.nombre = data['nombre'];
                    this.form.apellido = data['apellido'];
                    this.form.email = data['email'];
                    this.form.movil = data['movil'];
                    this.form.documento = data['documento'];
                    this.form.direccion = data['direccion'];
                    this.form.telefono = data['telefono'];
                    this.form.observaciones = data['observaciones'];
                    this.form.idgenero = data['idgenero'];
                    this.form.idinscripcion = data['idinscripcion'];
                    this.form.idniveleducativo = data['idniveleducativo'];
                    this.form.idgrupoetnico = data['idgrupoetnico'];
                    this.form.discapacitado = data['discapacitado'];
                    this.form.victimaconflicto = data['victimaconflicto'];
                    this.form.lider = data['lider'];
                    this.form.avalado = data['avalado'];
                    this.form.idcorporacion = data['idcorporacion'];
                    this.form.periodo = data['periodo'];
                    this.form.electo = data['electo'];
                    this.form.estado = data['estado'];
                    this.form.votos = data['votos'];
                    this.form.aportes = data['aportes'];
                    this.form.coalicion = data['coalicion'];
                    this.form.nombrecoalicion = data['nombrecoalicion'];
                    this.form.renuncio = data['renuncio'];
                    this.form.twitter = data['twitter'];
                    this.form.facebook = data['facebook'];
                    this.form.instagram = data['instagram'];
                    this.form.fecharenuncia = data['fecharenuncia'];
                    this.form.idremplazo = data['idremplazo'];
                    this.form.idremplazonombre = data['remplazo']?(data['remplazo']['documento'] + ' - ' + data['remplazo']['full_name']):'';
                    this.form.fechainicio = data['fechainicio'];
                    this.form.fechafin = data['fechafin'];
                    this.form.fechanacimiento = data['fechanacimiento'];
                    this.form.estados = data['estados'];
                    this.form.ccestado = data['ccestado'];
                    this.form.ccreposicion = data['ccreposicion'];
                    this.form.ccobservaciones = data['ccobservaciones'];
                    this.form.cccreated_at = data['cccreated_at'];
                    this.form.ccupdated_at = data['ccupdated_at'];
                    this.newMode = false;
                    this.verMode = true;
                    this.editMode = false;
                    break;
                }
                case 'actualizar': {
                    this.tituloModal = 'Editar Usuario ' + data['nombre'] + ' ' + data['apellido'];
                    this.form.id = data['id'];
                    this.form.fechaingreso = data['fechaingreso'];
                    this.form.idpais = data['idpais'];
                    this.form.iddepartamento = data['iddepartamento'];
                    this.getCiudades();
                    this.form.idciudad = data['idciudad'];
                    this.form.idtipos_documento = data['idtipos_documento'];
                    this.form.nombre = data['nombre'];
                    this.form.apellido = data['apellido'];
                    this.form.email = data['email'];
                    this.form.movil = data['movil'];
                    this.form.documento = data['documento'];
                    this.form.direccion = data['direccion'];
                    this.form.telefono = data['telefono'];
                    this.form.observaciones = data['observaciones'];
                    this.form.idgenero = data['idgenero'];
                    this.form.idinscripcion = data['idinscripcion'];
                    this.form.idniveleducativo = data['idniveleducativo'];
                    this.form.idgrupoetnico = data['idgrupoetnico'];
                    this.form.discapacitado = data['discapacitado'];
                    this.form.victimaconflicto = data['victimaconflicto'];
                    this.form.lider = data['lider'];
                    this.form.estado = data['estado'];
                    this.form.avalado = data['avalado'];
                    this.form.idcorporacion = data['idcorporacion'];
                    this.form.twitter = data['twitter'];
                    this.form.facebook = data['facebook'];
                    this.form.instagram = data['instagram'];
                    this.form.periodo = data['periodo'];
                    this.form.electo = data['electo'];
                    this.form.votos = data['votos'];
                    this.form.aportes = data['aportes'];
                    this.form.estados = data['estados'];
                    this.form.coalicion = data['coalicion'];
                    this.form.nombrecoalicion = data['nombrecoalicion'];
                    this.form.renuncio = data['renuncio'];
                    this.form.fecharenuncia = data['fecharenuncia'];
                    this.form.idremplazo = data['idremplazo'];
                    this.form.idremplazonombre = data['remplazo']?(data['remplazo']['documento'] + ' - ' + data['remplazo']['full_name']):'';
                    this.form.fechainicio = data['fechainicio'];
                    this.form.fechafin = data['fechafin'];
                    this.form.fechanacimiento = data['fechanacimiento'];
                    this.form.ccestado = data['ccestado'];
                    this.form.ccreposicion = data['ccreposicion'];
                    this.form.ccobservaciones = data['ccobservaciones'];
                    this.form.cccreated_at = data['cccreated_at'];
                    this.form.ccupdated_at = data['ccupdated_at'];
                    this.form.historialaval = data['historialaval'][0];
                    this.form.cuentasclaras = data['historialaval'][0]['cuentasclaras'];
                    break;
                }
            }
        },
        openAvalado: function () {
            if (this.form.avalado == 1 && this.newMode) {
                this.tituloModalDetalle = 'Detalle de Avalado';
                this.isOpenAval = true;
                this.activetab = '2';
            }
        },
        openElecto: function () {
            if (this.form.electo == 1 && this.newMode) {
                this.tituloModalDetalle = 'Detalle de Electo';
                this.activetab = '3';
            }
        },
        verDetalleAvalado: function () {
            this.tituloModalDetalle = 'Detalle de Avalado';
            this.isOpenAval = true;
        },
        selectRemplazo: function (documento) {
            this.isOpenRemplazo = true;
            this.getmilitantes(documento,'documento',);
        },
        onSelectRemplazo: function(data){
            this.closeModalRemplazo();
            this.form.idremplazo = data.id;
            this.form.idremplazonombre = data.documento + ' - ' + data.full_name;
        },
        closeModal: function () {
            this.isOpen = false;
            this.reset();
            this.editMode = false;
            this.verMode  = false;
            this.newMode  = false;
            this.$page.props.errors = [];
            this.resetcc();
            this.activetab = '1';
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

        closeModalRemplazo: function(){
            this.isOpenRemplazo = false;
            this.$page.props.errors = [];
        },
        closeModalCambioestado: function() {
            //this.closeModal();
            this.isOpenCambioestado = false;
            this.getmilitantes('','updated_at');
            this.formestado = [];
        },
        closeModalPass: function () {
            this.isOpencambiopass = false
            this.$page.props.errors.updatePassword = null;
        },
        subirArchivo: function (data) {
            if (data.idtipoarchivo > 0 && data.file) {
                this.$inertia.post('/archivo/upload', data, {
                    onSuccess: (page) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Archivo actualizado',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.actualizarArchivos(data.idmilitante);
                        //this.archivoform.idmilitante = 0;
                        this.archivoform.file = '';
                        this.archivoform.idtipoarchivo = 0;
                    },
                });
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Seleccione un archivo',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        },
        subirArchivocc: function (data) {
            data.id = this.formcc.id;
            this.$inertia.post('/archivocc/upload', data, {
                onSuccess: (page) => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Archivo actualizado',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.actualizarArchivos(data.idmilitante);
                },
            });
        },
        actualizarArchivos: function (data) {
            var url= '/getArchivos';
            axios.get(url, {
                params: {
                    idmilitante: data
                }
            }).then((res) => {
                this.arrayArchivos = res.data.archivos;
            })
        },
        getExamen: function (data) {
            var url= '/examens/getExamen';
            axios.get(url, {
                params: {
                    idmilitante: data
                }
            }).then((res) => {
                this.examenuser = res.data.examenuser;
                this.examen = res.data.examen;
            })
        },
        getHistorial: function (data) {
            var url= '/militantes/getHistorial';
            axios.get(url, {
                params: {
                    idmilitante: data
                }
            }).then((res) => {
                this.arrayHistorial = res.data.historial;
            })
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
        getCuentasClaras: function (data) {
            var url= '/militantes/getCuentasClaras';
            axios.get(url, {
                params: {
                    idmilitante: data
                }
            }).then((res) => {
                if (res.data.cuentasclaras) {
                    this.formcc = res.data.cuentasclaras;
                    this.validaEntrega(this.formcc.fechapresentacion);
                    if (this.formcc.correccion == 1) this.formcc.correccion = true;
                    if (this.formcc.presentacion == 1) this.formcc.presentacion = true;
                    if (this.formcc.presentacorreccion == 1) this.formcc.presentacorreccion = true;
                    if (this.formcc.renuente == 1) this.formcc.renuente = true;
                    if (this.formcc.investigado == 1) this.formcc.investigado = true;
                    if (this.formcc.sancionado == 1) this.formcc.sancionado = true;
                    if (this.formcc.recurso == 1) this.formcc.recurso = true;
                    if (this.formcc.pagado == 1) this.formcc.pagado = true;
                }
            })
        },
        verHistorial: function (data) {
            this.verHistorialobs = data;
            this.isOpenverHistorial = true;
        },
        updateEstado: function (data) {
            if (data.tipo == 'renunciar') {
                let mensaje = 'Desea renunciar a la militancia?';
                let title = 'Renunciado!';
                let html = 'La renuncia se ha realizado con éxito';

                Swal.fire({
                    title: mensaje,
                    text: "La acción no se podrá reversar!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Proceder!'
                }).then((result) => {
                    var url= '/militantes/updateEstado/' + data.idmilitante;
                    axios.get(url, {
                        params: {
                            idmilitante: data.idmilitante,
                            tipo: data.tipo,
                            estado: data.estado,
                            observaciones: data.observaciones,
                        }
                    }).then((res) => {
                        this.getmilitantes('','nombre');
                        Swal.fire(
                            title,
                            html,
                            'success'
                        )
                        this.closeModalCambioestado();
                        this.closeModal();
                    })
                })
            } else {
                var url= '/militantes/updateEstado/' + data.idmilitante;
                axios.get(url, {
                    params: {
                        idmilitante: data.idmilitante,
                        tipo: data.tipo,
                        estado: data.estado,
                        observaciones: data.observaciones,
                    }
                }).then((res) => {
                    console.log(res.data);
                    if (res.data.estado == true) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'El proceso se realizó correctamente',
                            showConfirmButton: false,
                            timer: 2500
                        })
                        this.closeModalCambioestado();
                        this.closeModal();
                    } else {
                        var mensajes = '';
                        for (let i = 0; i < res.data.mensajeserror.length; i++) {
                            mensajes = mensajes + '\n' + res.data.mensajeserror[i];
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en la aprobación: ' + mensajes,
                            showConfirmButton: true,
                        })
                    }
                })
            }
        },

        ccreposicion: function (data) {
            var url= '/militantes/ccupdate/' + data.id;
            axios.get(url, {
                params: {
                    idmilitante: data.id,
                    tipo: 'reposicion',
                    ccreposicion: data.ccreposicion,
                    ccupdated_at: this.dateTimeFull(data.ccupdated_at),
                    ccobservaciones: data.ccobservaciones,
                }
            }).then((res) => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'El proceso se realizó correctamente',
                    showConfirmButton: false,
                    timer: 2500
                })
                this.closeModal();
                this.isOpenCambioestado = false;
                this.getmilitantes('','updated_at');
                this.formestado = [];
            })
        },
        cambiarEstado: function (id, tipo) {
            for(let i = 0; i < this.arrayArchivos.length; i++) {
                if (this.arrayArchivos[i].idtipoarchivo == 1) {
                    this.form.archivoformulario = true;
                }
                if (this.arrayArchivos[i].idtipoarchivo == 8) {
                    this.form.archivocertificado = true;
                }
            }

            this.formestado.idmilitante = id;
            this.formestado.tipo = tipo;
            this.isOpenCambioestado = true;
        },
        registroHistorial: function (id, tipo, observaciones) {
            var url= '/militantes/registroHistorial';
            axios.get(url, {
                params: {
                    id: id,
                    tipo: tipo,
                    observaciones: observaciones,
                }
            }).then((res) => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'El proceso se realizó correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                this.closeModal();
                this.isOpenregHistorial = false;
                this.getmilitantes('','updated_at');
                this.formestado = [];
            })
        },
        descargarArchivo: function (data) {
            window.open(data, '_blank');
        },
        resetcc: function () {
            this.formcc.id = null;
            this.formcc.idmilitante  = null;
            this.formcc.fecharegistro = null;
            this.formcc.estado = 0;
            this.formcc.fechapresentacion = null;
            this.formcc.ingresosgastos = null;
            this.formcc.presentacion = null;
            this.formcc.requerimientos = null;
            this.formcc.autorequerimiento = null;
            this.formcc.correccion = null;
            this.formcc.presentacorreccion = null;
            this.formcc.renuente = 0;
            this.formcc.investigado = null;
            this.formcc.sancionado = 0;
            this.formcc.recurso = null;
            this.formcc.resolucionpago = null;
            this.formcc.fecharesolucion = null;
            this.formcc.pagado = null;
            this.formcc.idarchivo  = null;
            this.formcc.fechapago = null;
            this.formcc.observaciones = null;
        },
        reset: function () {
            this.activetab = '1';
            this.tituloModalDetalle = '';
            this.form.id = null;
            this.tituloModal = '';
            this.form.fechaingreso = null;
            this.form.idpais = 1;
            this.form.iddepartamento = '-';
            this.form.idciudad = '-';
            this.form.idtipos_documento = '-';
            this.form.nombre = null;
            this.form.apellido = null;
            this.form.email = null;
            this.form.movil = null;
            this.form.documento = null;
            this.form.direccion = null;
            this.form.telefono = null;
            this.form.observaciones = null;
            this.form.idgenero = '-';
            this.form.idinscripcion = '-';
            this.form.idniveleducativo = '-';
            this.form.idgrupoetnico = '-';
            this.form.discapacitado = 0;
            this.form.victimaconflicto = 0;
            this.form.lider = 0;
            this.form.avalado = 0;
            this.form.idcorporacion = null;
            this.form.periodo = 0;
            this.form.electo = 0;
            this.form.votos = 0;
            this.form.estado = 0;
            this.form.coalicion = 0;
            this.form.nombrecoalicion = null;
            this.form.renuncio = 0;
            this.form.fecharenuncia = null;
            this.form.idremplazo = null;
            this.form.fechainicio = null;
            this.form.fechafin = null;
            this.form.ccestado = null;
            this.form.ccreposicion = null;
            this.form.ccobservaciones = null;
            this.form.cccreated_at = null;
            this.form.ccupdated_at = null;
            this.form.archivoformulario =  false;
            this.form.archivocertificado = false;
            this.form.historialaval = [];
            this.form.cuentasclaras = [];
        },
        save: function (data) {
            data.fechaingreso = this.dateTimeFull(data.fechaingreso);
            data.fechanacimiento = data.fechanacimiento?this.dateTimeFull(data.fechanacimiento):null;
            data.fecharenuncia = data.fecharenuncia?this.dateTimeFull(data.fecharenuncia):null;
            this.$inertia.post('/militantes', data, {
                onSuccess: (page) => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'El militante ha sido creado',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.reset();
                    this.closeModal();
                    this.getmilitantes('','updated_at');
                    this.editMode = false;
                },
            });

        },
        edit: function (data) {
            this.editMode = true;
            this.newMode = false;
            this.verMode = false;
            this.arrayArchivos = data.archivos;
            this.archivoform.idmilitante =  data['id'];
            this.openModal('actualizar', data);
        },
        ver: function (data) {
            this.verMode = true;
            this.editMode = false;
            this.newMode = false;
            this.arrayArchivos = data.archivos;
            this.archivoform.idmilitante =  data['id'];
            this.openModal('ver', data);
        },
        update: function (data) {
            data.fechaingreso = data.fechaingreso?this.dateTimeFull(data.fechaingreso):null;
            data.fechanacimiento = data.fechanacimiento?this.dateTimeFull(data.fechanacimiento):null;
            data.fecharenuncia = data.fecharenuncia?this.dateTimeFull(data.fecharenuncia):null;
            data._method = 'PUT';
            this.$inertia.post('/militantes/'  + data.id, data, {
                onSuccess: (page) => {
                    console.log(page.props.errors);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'El militante se ha actualizado!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.getmilitantes('','updated_at');
                    this.closeModal();
                    this.reset();
                    this.editMode = false;
                    this.verMode = false;
                    this.newMode = false;
                },
            });
        },
        getmilitantes: async function (buscar, sortBy, filtros = []) {
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

            var url= '/militantes';
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
            this.arrayData = respuesta.militantes;
            this.loading = false;
        },
        militantesExport: function (filtros = []) {
            let fecha = moment(new Date()).format('DDMMYYYY');
            var url= '/militantes/export';
            axios.get(url, {
                params: {
                    filtros: filtros,
                },
                responseType: 'blob',
            }).then((response) => {
                console.log(response.data);
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');

                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'militantes_'+ fecha + '.xlsx');
                document.body.appendChild(fileLink);

                fileLink.click();
            })
        },
        solicitudAval: function(militante) {
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
                            this.resetavalsol();
                            this.getmilitantes('', '', this.formbusqueda);
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
            if (this.archivoformaval.archivos < 18 || i < 18) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Todos los archivos son obligatorios',
                    showConfirmButton: true,
                })
            } else {
                var formData = new FormData();
                formData.append('idmilitante', this.militanteaval.id);
                formData.append('observaciones', this.formaval.observaciones);

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
                    this.resetavalsol();
                    this.getmilitantes('', '', this.formbusqueda);
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
            if (this.archivoformaval.archivos < 18 || i < 18) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Todos los archivos son obligatorios',
                    showConfirmButton: true,
                })
            } else {
                var formData = new FormData();
                formData.append('idmilitante', this.militanteaval.id);
                formData.append('observaciones', this.formaval.observaciones);

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
                    this.resetavalsol();
                    this.getmilitantes('', '', this.formbusqueda);
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
            if (this.archivoformaval.archivos < 18 || i < 18) {
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
                        this.resetavalsol();
                        this.getmilitantes('', '', this.formbusqueda);
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
            if (this.archivoformaval.archivos < 18 || i < 18) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Todos los archivos son obligatorios',
                    showConfirmButton: true,
                })
            } else {
                var formData = new FormData();
                formData.append('idmilitante', this.militanteaval.id);
                formData.append('observaciones', this.formaval.observaciones);

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
                    this.resetavalsol();
                    this.getmilitantes('', '', this.formbusqueda);
                    this.closeModalAvalSol();
                }
            }
        },
        deleteRow: function (data) {
            let mensaje = '';
            let title = '';
            let html = '';
            if (data.estado) {
                mensaje = 'Desea renunciar a la militancia?';
                title = 'Renunciado!';
                html = 'La renuncia se ha realizado con éxito';
            } else {
                mensaje = 'Desea activar el usuario?';
                title = 'Activado!';
                html = 'El usuario ha sido activado con éxito.';
            }
            Swal.fire({
                title: mensaje,
                text: "Solo un super usuario podrá revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Proceder!'
            }).then((result) => {
                data._method = 'DELETE';
                axios.post('/militantes/' + data.id, data)
                    .then((res) => {
                        this.getmilitantes('','nombre');
                        Swal.fire(
                            title,
                            html,
                            'success'
                        )
                    }).catch(function (error) {
                    //(error);
                });
            })

        },
    },
    created: function () {
        this.loading = true;
        this.arrayData = this.militantes;
        this.getInscripciones();
        this.getGeneros();
        this.getNiveleducativo();
        this.getGruposetnicos();
        this.getCorporaciones();
        this.getCorporacionesregionales();
        this.getTiposarchivos();
        this.getEstados();
        this.getEstadoscc();
        this.getTipohistorial();
        this.getDepartamentos();

        if (this.$page.props.flash.message == 'Archivo importado correctamente') {
            this.isImportprogress = true;
            this.handleChange();
        }
        this.loading = false;
    },
    mounted() {
        //console.log(this.militantes);
    },
}
</script>
