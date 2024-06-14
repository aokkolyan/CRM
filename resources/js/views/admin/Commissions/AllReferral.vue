<template>       

<v-container>
    <div class="m-1" style="margin:auto">
        <v-card flat>
            <v-card-title>
                <v-row>
                    <v-col  v-if="branches!=null">
                        <v-select
                            v-model="filteredBranch_id"
                            :items="branches"
                            label="Select Branch"
                            item-value="id"
                            item-text="displayText"
                            hide-details
                            clearable
                        >
                        </v-select>
                    </v-col>
                    <v-col>
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Search Name..."
                            hide-details
                        ></v-text-field>
                    </v-col>
                    <v-col style="text-align: -webkit-right;text-align: right;" >
                        <!-- <export-excel
                        :data= "dataExcel"  type="csv">
                        <v-btn id="export" >
                        <label style="color: whitesmoke;">Excel</label>
                            </v-btn>
                    </export-excel> -->
                        <a class="btn btn-success" id="export"
                            href="api/weeklyReportReferrals/exportRerral"
                            style="color:#f2f2f2"
                            >
                            <label style="margin-bottom: 2px;cursor: pointer;color:white" id="labtn">Export Excel</label>
                        </a>
                    </v-col>
                </v-row>

            </v-card-title>
            <v-card-text>
                <v-simple-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                Referrer
                            </th>
                            <th>
                                # Referral
                            </th>
                            <th>
                                # Lead
                            </th>
                            <th>
                                # Discussion
                            </th>
                            <th>
                                # Enrolled
                            </th>
                            <th>
                                # Rejected
                            </th>
                            <th>
                                Revenue
                            </th>
                            <th>
                                Commission
                            </th>
                            <th>
                                Pts
                            </th>
                            <th>
                                Coupon
                            </th>
                            <th>
                              Paid  Commission
                            </th>
                            <th>
                                Unpaid Commission
                            </th>
                            
                            <!-- <th>
                                View
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(ref,index) in filteredReferrers" :key="ref.id">

                            <td>{{ index+1 }}</td>
                            <td>{{ ref.name }}</td>
                            <td class="text-right">{{ ref.referrals.length }}</td>
                            <td class="text-right">{{ ref.referrals.filter(x=>x.referral_status_id==1).length }}</td>
                            <td class="text-right">{{ ref.referrals.filter(x=>x.referral_status_id==2).length }}</td>
                            <td class="text-right">{{ ref.referrals.filter(x=>x.referral_status_id==4).length }}</td>
                            <td class="text-right">{{ ref.referrals.filter(x=>x.referral_status_id==3).length }}</td>
                            <td class="text-right">{{ ref.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0)  | currency(is_riel?'KHR':'USD') }}</td>
                            <td class="text-right">
                                {{ ref.referrals.filter(x=>x.commission_type =='cash').reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)  | currency(is_riel?'KHR':'USD')}}
                            </td>
                            <!-- <td
                            v-if="ref.referrals == 'point'"
                            > {{ ref.referrals.filter(x=>x.commission_type =='point').reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)}}pts

                            </td> -->
                            <!-- <td class="text-right">{{ref.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)  | currency(is_riel?'KHR':'USD') }}</td> -->
                            <td class="text-right"
                            >  {{ ref.referrals.filter(x=>x.commission_type =='point').reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)}}pts</td>
                            <td class="text-right"
                            >  {{ ref.referrals.filter(x=>x.commission_type == 'coupon').reduce((accumulator , currentValue) => accumulator + currentValue.commission,0) }}%</td>
                            <td class="text-right">{{ (ref.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0) - ref.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) )| currency(is_riel?'KHR':'USD') }}</td>
                           
                            <td class="text-right">{{ ref.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) | currency(is_riel?'KHR':'USD') }}</td>
                            <!-- <td>
                                <Tooltip tooltip="Make payment to unpaid commission">
                                    <v-btn
                                        v-if="can.make_payment"
                                        :disabled="ref.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission),
                                         0)==0"
                                        icon color="primary" x-small @click="view(ref.id)">
                                        <v-icon>
                                            mdi-cash
                                        </v-icon>
                                    </v-btn>+ 
                                </Tooltip>
                            </td> -->
                        </tr>
                   
                        <tr>
                            <td colspan="2">Total:</td>
                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.length , 0)}}</td>
                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.referral_status_id==1).length , 0)}}</td>
                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.referral_status_id==2).length , 0)}}</td>
                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.referral_status_id==4).length , 0)}}</td>
                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.referral_status_id==3).length , 0)}}</td>
                            
                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0) , 0) | currency(is_riel?'KHR':'USD')}}</td>

                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.commission_type=='cash').reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0) , 0) | currency(is_riel?'KHR':'USD')}}</td>

                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.commission_type=='point').reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0) , 0) }}Pts</td>
                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) => accumulator + currentValue.referrals.filter(x=>x.commission_type =='coupon').reduce
                            ((accumulator,currentValue) => accumulator + currentValue.commission,0) , 0) }}</td>
                            <td class="text-right">{{ (filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0) , 0) - filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) , 0)) | currency(is_riel?'KHR':'USD')}}

                            </td>
                            <td class="text-right">{{ filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) , 0) | currency(is_riel?'KHR':'USD')}} 

                            </td>
                          
            






                            

                            <!-- <td class="text-right">{{ filteredAllReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0)  | currency(false?'KHR':'USD')}}</td>
                            <td></td>
                            <td class="text-right">{{ filteredAllReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)  | currency(false?'KHR':'USD')}}</td>
                            <td></td> <td></td> <td></td> -->
                          
                        </tr>
                    </tbody>
                </v-simple-table>

            </v-card-text>
          

        
        
        </v-card>
    </div>
    </v-container>
</template>
<style scoped>
#export {
  
  background-color: rgb(30, 174, 52);
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 20px ;
}
#export:hover {
  background-color: #059e59;
  transition: all 0.3s;
}
</style>
<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength, minLength, email } from 'vuelidate/lib/validators';
    import Table from "@/components/Table.vue"
    import Tooltip from '../../../components/Tooltip.vue';
    import Vue from 'vue'
    import excel from 'vue-excel-export'
    Vue.use(excel)
    import axios from "axios";
    export default {
        mixins: [validationMixin],
        props:{
            user:Object(),
          
         
        },
        components: {
            Table,
            Tooltip
            
        },
        data () {
            return {

                dataExcel:[],


                // allReferrers:[],
                errors:[],
                title:'Referral',
                routeString:'allReferrers',
                loading:false,
                allReferrers:[
               
                ],
                can:[],
                branches:null,
                statuses:[],
                paymentStatuses:[],
                //end props for table
                form:{
                    name_en: '',
                    name_kh: '',
                    code: '',
                    description: '',
                    color:'#FF0000',
                },
                overlay:false,
                dialog:false,
                editDialog:false,
                deleting_Referral:Object(),
                editing_Referral:Object(),
                search: '',
                filteredBranch_id:null,
                filteredStatus:null,
                filteredPaymentStatus:null,
                breadcrumbs:[
                    {
                    text: 'Dashboard',
                    disabled: false,
                    href: '/dashboard',
                    },
                    {
                    text: 'Referrers',
                    disabled: true,
                    href: '/allReferrers',
                    },
                ],
                is_riel:false,
                can:[],
               
            }
           
        },
        computed: {

            sortedReferrers(){

            },
            filteredReferrers(){
                const result=this.filteredBranch_id?this.filterBranch(this.filteredBranch_id):this.allReferrers;
                return this.search?result.filter(user => user.name.toLowerCase().includes(this.search.toLowerCase())):result;
            },
        
        },
        created() {
            this.initialize();
        },
        methods:{
            initialize() {
                this.loading=true;
                  return new Promise((resolve, reject) => {
                    axios
                    .get("/api/allReferrers/")
                    .then(response => {
                        this.allReferrers = response.data.allReferrers;
                        this.is_riel=response.data.is_riel;
                        this.can=response.data.can;
                        this.branches=response.data.allCustomBranches;
                        this.accuData();
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
            paid(allReferrals){
                let paidded = allReferrals.paid((c,p) => {
                    const sumC = c.paid_commision.reduce(
                        (accumulator , currentValue) => 
                        accumulator + parseFloat(currentValue.commission),
                        0
                    );
                    const sumP = p.paid_commision.reduce(
                        (accumulator , currentValue) => 
                        accumulator + parseFloat(currentValue.commission),
                        0
                    );
                    return sumC - sumP;
                });
                return paidded ;
            },
          
            view(user_id){
                this.$router
                    .push('/allReferrals/'+user_id) 
                    .catch((error) => {
                    // Handle any errors that occur during the POST request
                    console.error(error);
                });
            },
            sort(allReferrers){
                let sorted=allReferrers.sort((a, b) => {
                    const sumA = a.referrals_in_my_branch_approved_not_yet_paid.reduce(
                        (accumulator, currentValue) =>
                        accumulator + parseFloat(currentValue.commission),
                        0
                    );
                    const sumB = b.referrals_in_my_branch_approved_not_yet_paid.reduce(
                        (accumulator, currentValue) =>
                        accumulator + parseFloat(currentValue.commission),
                        0
                    ); 
                    return sumB - sumA ; // Sort in descending order
                });    
                // console.log(sorted)
                return sorted
            },
           
           
            
            filterBranch(filteredBranch_id){
                // Filter out users who don't have a my_ref entry with type equal to 1
                const filteredUsers = this.allReferrers.filter(user =>
                    user.referrals.some(entry => entry.branch_id === filteredBranch_id)
                );

                // Keep only referrals entries with branch_id equal to filteredBranch_id
                const modifiedData = filteredUsers.map(user => ({
                ...user,
                referrals: user.referrals.filter(entry => entry.branch_id === filteredBranch_id)
                }));
                
                return modifiedData;
            },
            
            accuData(){
                this.allReferrers.forEach(element => {
                            this.dataExcel.push({
                                'referrer':element.name,
                                'referral':element.referrals.length,
                                'lead':element.referrals.filter(x=>x.referral_status_id==1).length,
                                'discussion':element.referrals.filter(x=>x.referral_status_id==2).length,
                                'enrolled':element.referrals.filter(x=>x.referral_status_id==4).length,
                                'rejected':element.referrals.filter(x=>x.referral_status_id==3).length,
                                'revenue':element.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0),
                                'commission':element.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0),
                                'paid_commission':element.ref.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0) - ref.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) ,
                                'unpaid_commission':element.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0),
                            });    
                });
                this.dataExcel.push({
                    'referrer':'Total:',
                    'referral':this.allReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.length , 0),
                    'lead':this.filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.referral_status_id==1).length , 0),
                    'discussion':this.filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.referral_status_id==2).length , 0),
                    'enrolled':this.filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.referral_status_id==4).length , 0),
                    'rejected':this.filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.filter(x=>x.referral_status_id==3).length , 0),
                    'revenue':this.filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0) , 0),
                    'commission':this.filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0) , 0),
                    'paid_commission':this.filteredReferrers.reduce ,
                    'unpaid_commission':this.filteredReferrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) , 0),
                });     
            }

        },
         watch:{
           
        }
    }

</script>
