<template>       
       <v-container>
         <!-- <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2> -->
        <v-tabs v-model="tab">
            <v-tabs-slider ></v-tabs-slider>
            <v-tab>
                <v-icon class="mr-2">mdi-view-list </v-icon> List roles
            </v-tab>
            <v-tab v-if="can.create">
                <v-icon class="mr-2">mdi-plus-box-multiple </v-icon> Create Role
            </v-tab>
        </v-tabs>
        <v-divider style="background-color:#ddd; height: 5px; width:100%; margin-top: auto;"></v-divider>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <Datatable :loading="loading" :roles="roles" :modules="modules" @refresh="initialize()" />
            </v-tab-item>
             <v-tab-item>
                <Create v-on:refresh="initialize()"/>
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
            roles:[],
            modules:[],
            can:[],
            tab: null,
            breadcrumbs:[
                {
                text: 'Dashboard',
                disabled: false,
                href: '/dashboard',
                },
                {
                text: 'Module',
                disabled: true,
                href: '/roles',
                },
            ]
        }),
        created() {
            //console.log(this.$api_url)
            this.initialize();
        },
        methods:{
            initialize() {
                //console.log('123');
                  return new Promise((resolve, reject) => {
                    axios
                    .get("/api/roles")
                    .then(response => {

                        this.roles = response.data.data.roles;
                        this.modules = response.data.data.modules;
                        this.can=response.data.data.can;
                        //console.log(response);
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
      
        },
      
    }

</script>
