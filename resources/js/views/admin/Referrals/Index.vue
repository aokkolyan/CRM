<template>       
    <v-container>
         <h2 class="font-semibold text-xl text-gray-800 referraling-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <Datatable :can="can" :statuses="statuses"  :branches="branches" :loading="loading" :referrals="referrals" @refresh="initialize()" />
       
    </v-container>
</template>

<script>
    import Datatable from "./Datatable.vue";
    
    import axios from "axios";
    import Tooltip from '../../../components/Tooltip.vue';
    export default {
        components: {
            Datatable,
            Tooltip
        },
        data() {
            return {
            loading:true,
            referrals:[
               
            ],
            can:[],
            branches:[],
            statuses:[],
            tab: null,
            breadcrumbs:[
                {
                text: this.$t('Dashboard'),
                disabled: false,
                to: '/dashboard',
                },
                {
                text: this.$t('Referral'),
                disabled: true,
                href: '/referrals',
                },
            ]
        }},
        created() {
            //console.log(this.$api_url)
            this.initialize();
        },
        methods:{
            initialize() {
                this.loading=true;
                  return new Promise((resolve, reject) => {
                    axios
                    .get("/api/referrals")
                    .then(response => {

                        this.referrals = response.data.data;
                        
                        this.can = response.data.can;
                        this.branches = response.data.branches;
                        this.statuses = response.data.statuses;
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
