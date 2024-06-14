<template>       
    <v-container>
         <h2 class="font-semibold text-xl text-gray-800 commissioning-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <Datatable :can="can" :paymentStatuses="paymentStatuses"  :branches="branches" :loading="loading" :commissions="commissions" @refresh="initialize()" />
       
    </v-container>
</template>

<script >
    import Datatable from "./Datatable.vue";
    
    import axios from "axios";
    import Tooltip from '../../../components/Tooltip.vue';
    export default {
        components: {
            Datatable,
            Tooltip
        },
        data: () => ({
            loading:true,
            commissions:[
               
            ],
            can:[],
            branches:[],
            paymentStatuses:[],
            tab: null,
            breadcrumbs:[
                {
                text: 'Dashboard',
                disabled: false,
                to: '/dashboard',
                },
                {
                text: 'Commission',
                disabled: true,
                href: '/commissions',
                },
            ]
        }),
        created() {
            //console.log(this.$api_url)
            this.initialize();
        },
        methods:{
            initialize() {
                this.loading=true;
                  return new Promise((resolve, reject) => {
                    axios
                    .get("/api/commissions")
                    .then(response => {

                        this.commissions = response.data.data;
                        
                        this.can = response.data.can;
                        this.branches = response.data.branches;
                        this.paymentStatuses = response.data.paymentStatuses;
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
