<template>       
    <v-container>
         <h2 class="font-semibold text-xl text-gray-800 referraling-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <Datatable :users="users" :can="can" :schools="schools" :programs="programs" :paymentStatuses="paymentStatuses" :statuses="statuses" :branches="branches" :loading="loading" :myReferrals="myReferrals" @refresh="initialize()" />
       
    </v-container>
</template>

<script>
    import Datatable from "./Datatable.vue";
    import Create from "./Create.vue";
    
    import axios from "axios";
    import Tooltip from '../../../components/Tooltip.vue';
    export default {
        components: {
            Datatable,
            Create,
            Tooltip
        },
        data() {
            return{
                loading:true,
                myReferrals:[
                
                ],
                can:[],
                branches:[],
                schools:[],
                programs:[],
                users: [],
                statuses:[],
                paymentStatuses:[],
                tab: null,
                breadcrumbs:[
                    {
                    text: this.$t('Dashboard'),
                    disabled: false,
                    to: '/dashboard',
                    },
                    {
                    text: this.$t('My Referral'),
                    disabled: true,
                    href: '/myReferrals',
                    },
                ]
            };
        },
        created() {
            //console.log(this.$api_url)
            this.initialize();
        },
        methods:{
            initialize() {
                this.loading=true;
                  return new Promise((resolve, reject) => {
                    axios
                    .get("/api/myReferrals")
                    .then(response => {

                        this.myReferrals = response.data.data;
                        
                        this.can = response.data.can;
                        this.users= response.data.users;
                        this.schools = response.data.schools;
                        this.branches = response.data.branches;
                        this.programs = response.data.programs;
                        this.statuses = response.data.statuses;
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
