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
                <v-icon class="mr-2">mdi-view-list </v-icon> List Product
            </v-tab>
            <v-tab v-if="can.create">
                <v-icon class="mr-2">mdi-plus-box-multiple </v-icon> Create Product
            </v-tab>
        </v-tabs>
        <v-divider style="background-color:#ddd; height: 5px; width:100%; margin-top: auto;"></v-divider>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <Datatable :loading="loading" :categories="categories" :products="products" @refresh="initialize()" />
            </v-tab-item>
             <v-tab-item>
                <Create :categories="categories" v-on:refresh="initialize()"/>
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
            products:[],
            can:[],
            categories:[],
            tab: null,
            breadcrumbs:[
                {
                text: 'Dashboard',
                disabled: false,
                to: '/dashboard',
                },
                {
                text: 'Product',
                disabled: true,
                href: '/products',
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
                    .get("/api/products")
                    .then(response => {

                        this.products = response.data.data;
                        this.categories=response.data.categories;
                        this.can = response.data.can;
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
