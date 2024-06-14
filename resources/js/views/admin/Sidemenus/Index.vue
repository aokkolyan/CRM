<template>       
       <v-container>
        <v-tabs v-model="tab">
            <v-tabs-slider ></v-tabs-slider>
            <v-tab>
                <v-icon class="mr-2">mdi-view-list </v-icon> List sidemenus
            </v-tab>
            <v-tab v-if="can.create">
                <v-icon class="mr-2">mdi-plus-box-multiple </v-icon> Create Module
            </v-tab>
        </v-tabs>
        <v-divider style="background-color:#ddd; height: 5px; width:100%; margin-top: auto;"></v-divider>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <Datatable :loading="loading" :sidemenus="sidemenus" :modules="modules" @refresh="initialize()" />
            </v-tab-item>
             <v-tab-item>
                <Create :modules="modules" :sidemenus="sidemenus" v-on:refresh="initialize()"/>
            </v-tab-item>
        </v-tabs-items>
    </v-container>
</template>

<script>
    import Datatable from "./Datatable.vue";
    import Create from "./Create.vue";
    
    import axios from "axios";
    export default {
        components: {
            Datatable,
            Create
        },
        data: () => ({
            loading:true,
            modules:[],
            sidemenus:[],
            can:[],
            tab: null,
            breadcrumbs:[
                {
                text: 'Dashboard',
                disabled: false,
                to: '/dashboard',
                },
                {
                text: 'Module',
                disabled: true,
                href: '/sidemenus',
                },
            ]
        }),
        created() {
            this.initialize();
        },
        methods:{
            initialize() {
                this.getModules();
                return new Promise((resolve, reject) => {
                    axios
                    .get("/api/sidemenus")
                    .then(response => {

                        this.sidemenus = response.data.data;
                        this.can=response.data.can;
                        
                        setTimeout(() => {
                            this.loading = false;
                            resolve(response);
                        }, 100);
                    })
                    .catch(error => {
                        reject(error);
                    });
                });
            },
            getModules() {
                return new Promise((resolve, reject) => {
                    axios
                        .get("/api/modules")
                        .then(response => {
                            this.modules = response.data.data;
                        })
                        .catch(error => {
                            reject(error);
                        });
                });
            },
      
        },
      
    }

</script>
