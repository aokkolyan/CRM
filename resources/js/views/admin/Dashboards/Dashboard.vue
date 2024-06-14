<template>
    <div class="dasboard">
        <v-container>
            <ReferralStatistic v-if="can.referral" @filteredBranch="filteredBranch_id=$event" :branches="branches" :referralStatuses="referralStatuses" :my="my" :all="all" :allReferrers="allReferrers"></ReferralStatistic>
            <MonthlyStatistic v-if="can.lead"></MonthlyStatistic>
            <Leads v-if="can.lead"></Leads>
            <Opportunities v-if="can.lead"></Opportunities>
            <Revenues v-if="can.lead"></Revenues>
        </v-container>
    </div>
</template>
<script>
import MonthlyStatistic from './MonthlyStatistic.vue';
import Leads from './Leads.vue';
import Opportunities from './Opportunities.vue';
import Revenues from './Revenues.vue';
import ReferralStatistic from './ReferralStatistic.vue';
import axios from "axios";
export default {
    name: 'Dashboard',
    components: { MonthlyStatistic,Leads,Opportunities,Revenues,ReferralStatistic },
    props: {

    
    },
    data() {
        return {
            can:{
                'lead':false,
                'referral':false,
            },
            allReferrers:[],
            myReferrals:[],
            allReferrals:[],
            referralStatuses:[],
            branches:null,
            filteredBranch_id:null,
        }
    },
    created() {
        //console.log(this.$api_url)
        this.initialize();
    },
    computed:{
        my(){
            return {
                'total':this.myReferrals.length,
                'won':this.myReferrals.filter(x=>x.referral_status_id==4).length,
                'lost':this.myReferrals.filter(x=>x.referral_status_id==3).length,
                'enquiring':this.myReferrals.filter(x=>x.referral_status_id==2).length,
            }
        },
        all(){
            const result=this.filteredBranch_id?this.allReferrals.filter(x=>x.branch_id===this.filteredBranch_id):this.allReferrals;
            return {
                'total':result.length,
                'won':result.filter(x=>x.referral_status_id==4).length,
                'lost':result.filter(x=>x.referral_status_id==3).length,
                'enquiring':result.filter(x=>x.referral_status_id==2).length,
            }
        }

    },
    methods:{
        initialize() {
                  return new Promise((resolve, reject) => {
                    axios
                    .get("/api/dashboards")
                    .then(response => {
                        
                        this.can = response.data.can;
                        this.allReferrers = response.data.allReferrers;
                        this.myReferrals = response.data.myReferrals;
                        this.allReferrals = response.data.allReferrals;
                        this.referralStatuses=response.data.referralStatuses;
                        this.branches=response.data.branches;
                    })
                    .catch(error => {
                        reject(error);
                    });
                });
            },
    }
}
</script>
