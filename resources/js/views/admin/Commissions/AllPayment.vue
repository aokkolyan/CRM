<template>       

<v-container>
    <div class="m-1" style="margin:auto">
        <v-card flat>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search Name..."
                    hide-details
                ></v-text-field>
                <v-divider></v-divider>
         
            </v-card-title>
            <v-card-text>
                <v-data-table 
                    v-if="commissionPayments.length>0"
                    :headers="headers"
                    :items="commissionPayments"
                    :search="search"
                    :items-per-page="-1"
                    :hide-default-footer="true"
                >
                    <template v-slot:[`item.index`]="{ index }">
                            {{ index + 1 }}
                    </template>
          
         
                    
                  
                 
                </v-data-table>

            </v-card-text>
          

        
        
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
                // commissionPayments:[],
                errors:[],
                title:'Referral',
                routeString:'commissionPayments',
                loading:false,
                commissionPayments:[
               
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
                    href: '/commissionPayments',
                    },
                ],
                headers: [
                    { text: '#', value: 'index', },
                    { text: 'Referrer', value: 'referrer.name' },
                    { text: 'Cashier', value: 'payer.name' },
                    { text: 'Total_payment', value: 'total_payment' },
                    { text: 'Paid At', value: 'paid_at' },
                    { text: 'Action',  value: 'actions', sortable: false},
                ],
                is_riel:false,
                can:[],
            }
        },
        computed: {

            sortedReferrers(){

            },
            filteredReferrers(){
                return this.search?this.commissionPayments.filter(user => user.name.toLowerCase().includes(this.search.toLowerCase())):this.sort()
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
                    .get("/api/commissionPayments")
                    .then(response => {

                        this.commissionPayments = response.data.data;
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
                    .push('/allReferrals/'+user_id) 
                    .catch((error) => {
                    // Handle any errors that occur during the POST request
                    console.error(error);
                });;
            },
            sort(){
            
            }

        },
         watch:{
      
        }
    }

</script>
