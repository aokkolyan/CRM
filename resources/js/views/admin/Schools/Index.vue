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
                <v-icon class="mr-2">mdi-view-list </v-icon> List School
            </v-tab>
            <v-tab v-if="can.create">
                <v-icon class="mr-2">mdi-plus-box-multiple </v-icon> Create School
            </v-tab>
        </v-tabs>
        <v-divider style="background-color:#ddd; height: 5px; width:100%; margin-top: auto;"></v-divider>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <Datatable :user_types="user_types" :loading="loading" :schools="schools" @refresh="initialize()" />
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
            schools:[],
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
                href: '/schools',
                },
            ],
            user_types:[],
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
                    .get("/api/schools")
                    .then(response => {

                        this.schools = response.data.data;
                        
                        this.can = response.data.can;
                        this.user_types=response.data.user_types;
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
