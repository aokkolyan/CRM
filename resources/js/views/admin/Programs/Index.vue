<template>
       <v-container>
        <v-tabs v-model="tab">
            <v-tabs-slider ></v-tabs-slider>
            <v-tab>
                <v-icon class="mr-2">mdi-view-list </v-icon> List programs
            </v-tab>
            <v-tab v-if="can.create">
                <v-icon class="mr-2">mdi-plus-box-multiple </v-icon> Create New
            </v-tab>
        </v-tabs>
        <v-divider style="background-color:#ddd; height: 5px; width:100%; margin-top: auto;"></v-divider>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <Datatable :loading="loading"  :programs="programs" @refresh="initialize()" />
            </v-tab-item>
             <v-tab-item>
                <Create :programs="programs" :schools="listSchool" v-on:refresh="initialize()"/>
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
            listSchool:[],
            programs:[],
            can:[],
            tab: null,
        }),
        beforeMount() {
            this.initialize();
        },
        created() {
            console.log('khmer');
            this.user = this.$store.state.auth.user;
        },
        methods:{
            initialize() {
                return new Promise((resolve, reject) => {
                    axios
                    .get("/api/programs")
                    .then(response => {
                        this.programs = response.data.data;
                        this.can = response.data.can;
                    
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
