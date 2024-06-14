<template>       

<v-container>
    <div class="m-1" style="margin:auto">
        <v-card flat >
            <v-card-title v-if="allUserReferrals.length>0">
                {{ allUserReferrals[0].creator }}
                <v-divider></v-divider>
         
            </v-card-title>
            <v-card-title v-else>
                No commission that has been approved and not yet paid found
            </v-card-title>
            <v-card-text>
                <v-data-table 
                    v-if="allUserReferrals.length>0"
                    :headers="headers"
                    :items="allUserReferrals"
                    :loading="loading"
                    :search="search"
                    :items-per-page="-1"
                    :hide-default-footer="true"
                >
                    <template v-slot:[`item.index`]="{ index }">
                            {{ index + 1 }}
                    </template>
                    <template  v-slot:[`item.fee`]="{ item }">
                        <td  class="text-right">
                            {{ item.fee??0 | currency(item.is_riel?'KHR':'USD')}}
                        </td>
                    </template>
                    <template  v-slot:[`item.commission_percentage`]="{ item }">
                        <td  class="text-right">
                            {{ item.commission_percentage }}%
                        </td>
                    </template>
                    <template  v-slot:[`item.commission`]="{ item }">
                        <td  class="text-right">
                            {{ item.commission??0 | currency(item.is_riel?'KHR':'USD')}}
                        </td>
                    </template>
                    <template v-slot:[`item.status`]="{ item }">
                        <v-chip
                            label
                            v-if="item.payment_status_id"
                            class="ma-2"
                            :color="paymentStatuses.find(x=>x.id==item.payment_status_id).color+'2F'"
                            :text-color="paymentStatuses.find(x=>x.id==item.payment_status_id).color"
                        >
                            <v-icon small left>
                                mdi-cash
                            </v-icon>
                            {{paymentStatuses.find(x=>x.id==item.payment_status_id).name_en}}
                        </v-chip>
                    </template>
                    
                  
                    <template
                        v-slot:[`body.append`]="{  }"
                    >
                        <tr>
                            <td colspan="4">
                                {{ $t('Total') }}
                            </td>
                            <td class="text-right">
                               {{ allUserReferrals.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.fee), 0) | currency(allUserReferrals[0].is_riel?'KHR':'USD')}}
                            
                            </td>
                            <td></td>
                            <td class="text-right">
                               {{ allUserReferrals.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) | currency(allUserReferrals[0].is_riel?'KHR':'USD')}}
                            
                            </td>
                            <td></td>
                            <td>
                                <v-btn color="primary" @click="paymentDialog=true">
                                    Make Payment
                                </v-btn>
                            </td>
                        </tr>
                    </template>
                </v-data-table>

            </v-card-text>
            <v-dialog
                v-model="paymentDialog"
                width="600px"
            >
                <v-card v-if="allUserReferrals.length>0">
                    <v-card-title>
                        Are you sure you want to make commission payment &nbsp;
                         <strong style="color:darkgreen">{{ allUserReferrals.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) | currency(allUserReferrals[0].is_riel?'KHR':'USD')}}</strong>&nbsp;for user 
                         &nbsp;<strong> {{ allUserReferrals[0].creator }}</strong> ?
                    </v-card-title>
                    <v-card-text>
                        <form enctype="multipart/form-data"  @submit="makePayment">
                            <v-file-input
                                name="file"
                                placeholder="Ex: Invoice...."
                                v-model="file"
                            ></v-file-input>
                   
                            <div class="text-right">
                                <v-btn color="primary" outlined @click="paymentDialog=false"> No</v-btn>
                                
                                <v-btn color="primary" @click="makePayment"> Yes</v-btn>
                            </div>
                        
                        </form>
                    </v-card-text>
                </v-card>
            </v-dialog>

        
        
        </v-card>
    </div>
    </v-container>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength, minLength, email } from 'vuelidate/lib/validators';
    import Table from "@/components/Table.vue"
    import Tooltip from '../../../components/Tooltip.vue';
    import axios from "axios";
    export default {
        mixins: [validationMixin],
        props:{
            user:Object(),
         
        },
        components: {
            Table,Tooltip
        },

     
        data () {
            return {
                // allUserReferrals:[],
                errors:[],
                title:'Referral',
                routeString:'allUserReferrals',
                loading:false,
                allUserReferrals:[
               
                ],
                can:[],
                branches:[],
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
                filteredBranch:null,
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
                    href: '/allUserReferrals',
                    },
                ],
                is_riel:false,
                headers: [
                    { text: '#', value: 'index', },
                    { text: 'Referrer', value: 'creator' },
                    { text: 'Student Name ', value: 'student_name' },
                    { text: 'Branch', value: 'branch.code' },
                    { text: 'Fee', value: 'fee' },
                    { text: 'Comission(%)', value: 'commission_percentage' },
                    { text: 'Commission', value: 'commission' },
                    { text: 'Status', value: 'status' },
                    { text: 'Action',  value: 'actions', sortable: false},
                ],
                paymentDialog:false,
                file:null,
            }
        },
        computed: {

            filteredReferrers(){
                return this.search?this.allUserReferrals.filter(user => user.name.toLowerCase().includes(this.search.toLowerCase())):this.allUserReferrals
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
                    .get("/api/allReferrers/"+this.$route.params.user_id)
                    .then(response => {

                        this.allUserReferrals = response.data.data;
                        this.is_riel=response.data.is_riel;
                        
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
            view(user_id){
                this.$router
                    .push('/allReferral/'+user_id) 
                    .catch((error) => {
                    // Handle any errors that occur during the POST request
                    console.error(error);
                });;
            },
            makePayment(){
                const formData = new FormData();
                formData.append('file', this.file);
                formData.append('allUserReferrals', JSON.stringify(this.allUserReferrals));
                axios.post('/api/commissionPayments',formData)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.errors = null;
                        this.$router
                            .push('/allReferrals') 
                            .catch((error) => {
                            // Handle any errors that occur during the POST request
                            console.error(error);
                        });;
                    }
                })
                .catch((error) => {
                    //console.log(error);
                    if(error.response){
                        this.errors = error.response.data.errors;
                        this.$root.Snackbar.show({message: error.response.data.message,snackColor:'error',})
                    }else{
                        this.$root.Snackbar.show({message: error.message,snackColor:'error',})
                    }

                });
                
                this.paymentDialog=false;
            }

        },
         watch:{
      
        }
    }

</script>
