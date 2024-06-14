<template>
    <v-container>
        <v-tabs v-model="tab">
            <v-tabs-slider ></v-tabs-slider>
            <v-tab>
                <v-icon class="mr-2">mdi-view-list </v-icon> List user
            </v-tab>
            <v-tab v-if="can.create">
                <v-icon class="mr-2">mdi-plus-box-multiple </v-icon> Create new
            </v-tab>
        </v-tabs>
        <v-divider style="background-color:#ddd; height: 5px; width:100%; margin-top: auto;"></v-divider>
        <v-tabs-items v-model="tab">
            <v-tab-item>
                <Datatable :branches="branches" :loading="loading"  :listBranches="listBranches"  :listRole="listRole" :users="users" @refresh="initialize()"/>
            </v-tab-item>
            <v-tab-item>
                <Create :branches="branches" :listRole="listRole" @refresh="initialize()"/>
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
        Create,
    },
    data: () => ({
        tab: null,
        loading:true,
        listRole:[],
        listBranches:[],
        users:[],
        can:[],
        branches:[],
        usertypes:[],
    }),
     beforeMount() {
        this.initialize();
    },

    created() {
        this.user = this.$store.state.auth.user;
        this.initialize();
        this.getRoles();
    },

    methods: {
        initialize() {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/users")
                    .then(response => {
                        this.users = response.data.data;
                        // this.usertypes =response.usertype_id.data;
                        this.can = response.data.can;
                        this.branches = response.data.branches;
                        this.listBranches=response.data.branches;
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
        getRoles() {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/roles")
                    .then(response => {

                        //console.log('asd');
                        //console.log(response.data.data);
                        this.listRole = response.data.data.roles;
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        getBranches() {
            // return new Promise((resolve, reject) => {
            //     axios
            //         .get("/api/branches")
            //         .then(response => {

            //             //console.log('asd');
            //             //console.log(response.data.data);
            //             this.listBranches = response.data.data;
            //         })
            //         .catch(error => {
            //             reject(error);
            //         });
            // });
        },
    }
}
</script>
