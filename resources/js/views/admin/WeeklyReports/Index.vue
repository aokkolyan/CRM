<template>       
 
            <v-container>
                <!-- <v-btn id="btn" >
                <export-excel
                    :data= "weeklyExcel" 
                    :file-name="'weekly_report'"
                    type="csv"
                    :sheet-name="'weekly_report'">
                  
                    <label style="margin-bottom: 2px;" id="labtn" >Excel</label>
                       
                </export-excel>
                
            </v-btn>  -->
            <a class="btn btn-success" id="btn"
            href="api/weeklyReportReferrals/exportWeekly"
            dark>
           <label style="margin-bottom: 2px;cursor: pointer;" id="labtn">Export Excel</label>
            </a>
         <h2 class="font-semibold text-xl text-gray-800 referraling-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <Datatable  :can="can" :schools="schools" :programs="programs" :paymentStatuses="paymentStatuses" :statuses="statuses" :branches="branches" :loading="loading" :weeklyReportReferrals="weeklyReportReferrals" @refresh="initialize()" />
        
    </v-container>
  
</template>

<style scoped>
#btn {
    background-color:rgb(4, 145, 39);
  color: aliceblue;
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  margin-left: 1048px;
  /* float: right; */
  margin-bottom: -70px;
}
</style>
<script>
    import Datatable from "./Datatable.vue";
    import Create from "./Create.vue";
    
    import axios from "axios";
    import Tooltip from '../../../components/Tooltip.vue';
    // import Vue from 'vue'
    // import excel from 'vue-excel-export'
    // Vue.use(excel)
    export default {
        components: {
            Datatable,
            Create,
            Tooltip,
          
          
        },
        data() {
            return{
              
                weeklyExcel : [],
                loading:true,
                weeklyReportReferrals:[
                
                ],
              
                can:[],
                branches:[],
                schools:[],
                programs:[],
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
                    text: this.$t('Weekly Report'),
                    disabled: true,
                    to: '/weeklyReportReferrals',
                    },
                ]
            };
            
        },
        created() {
            // console.log(this.$api_url)
            this.initialize();
        },
        methods:{
            initialize() {
                this.loading=true;
                  return new Promise((resolve, reject) => {
                    axios
                    .get("/api/weeklyReportReferrals")
                    .then(response => {

                        this.weeklyReportReferrals = response.data.data;
                        this.can = response.data.can;
                        this.weeklyReportReferrals.forEach(element => {
                            this.weeklyExcel.push({
                                'branch':element.branch.name_en,
                                'prepared_by' :element.preparer.name,
                                'checked_by'  :element.checker?element.checker.name:'Not Yet',
                                'approved_by' :element.approver?element.approver.name:'Not Yet',
                                'created_at'  :element.created_at,
                                'remark':element.remark,
                            });
                        });
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
