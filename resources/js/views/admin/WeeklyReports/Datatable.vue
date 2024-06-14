<template>       
    <div class="m-1" style="margin:auto">
        <v-card flat>
            
            <v-card-title>
                <div style="width:150px;margin: 10px;">
                    <v-select
                    outlined
                    dense
                    hide-details
                    v-model="filteredByProgress"
                    :items="progresses"
                    :label="$t('Progress')"
                    item-value="id"
                    clearable
                    >
                    </v-select>
                </div>
                <v-divider></v-divider>
                <v-btn v-if="can.create" color="primary" @click="goToCreate()">
                    {{ $t('create') }}
                </v-btn>
            </v-card-title>
            
            <v-card-title class="d-flex flex-row">
             
            
            </v-card-title>
            <v-card-text>
            
                <v-simple-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                {{$t('Branch')}}
                            </th>
                            <th>
                                {{$t('Created At')}}
                            </th>
                            <th>
                                {{$t('Prepared By')}}
                            </th>
                            <th>
                                {{$t('Checked By')}}
                            </th>
                            <th>
                                {{$t('Approved By')}}
                            </th>
                            <th>
                                {{$t('Remark')}}
                            </th>
                            <th>
                                {{$t('Actions')}}
                            </th>
                        </tr>
                        <tr v-if="loading">
                            <th colspan="12">
                                <v-progress-linear
                                indeterminate
                                color="primary"
                                ></v-progress-linear>
                            </th>
                         
                        </tr>
                    </thead>
                 
                    <tbody v-if="!loading">
                        <tr v-for="(ref,index) in filteredWeeklyReportReferrals" :key="ref.id">
                            <td>{{ index+1 }}</td>
                            <td>{{ ref.branch.name_en }}</td>
                            <td>{{ ref.created_at }}</td>
                            <td>
                                <span style="color:darkgreen" v-if="ref.preparer">
                                    {{ ref.preparer.name }}
                                </span>
                                <span style="color:darkred" v-else>
                                    Not Yet
                                </span>
                            </td>
                            <td>
                                <span style="color:darkgreen" v-if="ref.checker">
                                    {{ ref.checker.name }}
                                </span>
                                <span style="color:darkred" v-else>
                                    Not Yet
                                </span>
                            </td>
                            <td>
                                <span style="color:darkgreen" v-if="ref.approver">
                                    {{ ref.approver.name }}
                                </span>
                                <span style="color:darkred" v-else>
                                    Not Yet
                                </span>
                            </td>
                      
                            <td>{{ ref.remark }}</td>
                            <td>
                                <v-btn color="primary" x-small icon @click="view(ref.id)">
                                    <v-icon>mdi-eye</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                        <tr v-if="filteredWeeklyReportReferrals.length==0">
                            <td colspan="7" style="text-align: center;">
                                {{ $t('noDataText') }}
                            </td>
                        </tr>
                    </tbody>
                </v-simple-table>
              
                    <!--Edit dialog-->
                    <!-- <v-dialog
                    v-model="editDialog"
                    max-width="700"
                    >
                        <Create :schools="schools" :programs="programs" :branches="branches" v-on:refresh="refresh" v-on:closeDialog="editDialog=false"></Create>
                    </v-dialog> -->
            </v-card-text>
          

        
        
        </v-card>
    </div>
</template>
<style scoped>
#export {
  
  background-color: rgb(30, 174, 52);
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0px ;
  margin: 3px;
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
    import Create from './Create.vue';
    
    import Tooltip from '../../../components/Tooltip.vue';
    import axios from "axios";
    import Vue from "vue";
    
    export default {
        mixins: [validationMixin],
        props:{
            user:Object(),
            weeklyReportReferrals:Array(),
            loading:{
                type:Boolean(),
                default:false   
            },
            can:Array(),
            schools:Array(),
            branches:Array(),
            programs:Array(),
            statuses:Array(),
            paymentStatuses:Array(),
        },
        components: {
            Table,Create,Tooltip
        },
        validations: {
            form:{
                name_en: { required, maxLength: maxLength(30) },
                name_kh: { required, maxLength: maxLength(30) },
                code: {  maxLength: maxLength(5) },
            }
        },
     
        data () {
            return {
                weeklyExcel:[],
                
               json_meta: [
                   [
                       {
                           'key': 'charset',
                           'value': 'utf-8'
                       }
                   ]
               ],
               filteredByProgress:null,
                //weeklyReportReferrals:[],
                errors:[],
                title:'Referral',
                routeString:'weeklyReportReferrals',
                headers: [
                    { text: '#', value: 'index', },
                    { text: 'Parent ', value: 'parent_name' },
                    { text: 'Phone', value: 'phone' },
                    { text: 'Parent2 ', value: 'parent2_name' },
                    { text: 'Phone2', value: 'phone2' },
                    { text: 'Branch', value: 'branch' },
                    { text: 'Status', value: 'status' },
                    { text: 'Created At', value: 'created_at' },
                    { text: 'Action',  value: 'actions', sortable: false},
                ],
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
                    text: 'My Referral',
                    disabled: true,
                    href: '/weeklyReportReferrals',
                    },
                ],
                progresses:[
                    'ALl',
                    'Prepared',
                    'Checked',
                    'Approved'
                ]
            }
        },
        computed: {
            nameEnErrors() {
                const errors = [];
                if (!this.$v.form.name_en.$dirty) return errors;
                !this.$v.form.name_en.maxLength && errors.push("Name_en must be at most 30 characters long");
                !this.$v.form.name_en.required && errors.push("Name_en is required.");
                return errors;
            },
            nameKhErrors() {
                const errors = [];
                if (!this.$v.form.name_kh.$dirty) return errors;
                !this.$v.form.name_kh.maxLength && errors.push("Name_kh must be at most 30 characters long");
                !this.$v.form.name_kh.required && errors.push("Name_kh is required.");
                return errors;
            },
            
            codeErrors() {
                const errors = [];
                if (!this.$v.form.code.$dirty) return errors;
                !this.$v.form.code.maxLength && errors.push("code must be at most 30 characters long");
                return errors;
            },
            filteredWeeklyReportReferrals(){
               
                let a=this.weeklyReportReferrals;
                if(this.search) a=a.filter(x=>x.parent_name.toLowerCase().includes(this.search.toLowerCase())||x.phone.toLowerCase().includes(this.search.toLowerCase()));
                // if(this.filteredBranch) a=a.filter(x=>x.branch_id==this.filteredBranch);
                if(this.filteredByProgress=='All') a=a;
                else if(this.filteredByProgress=='Prepared') {
                    a=a.filter(x=>x.checker==null);
                }
                else if(this.filteredByProgress=='Checked') {
                    a=a.filter(x=>x.approver==null&&x.checker!=null);
                }
                else if(this.filteredByProgress=='Approved') {
                    a=a.filter(x=>x.approver);
                }
               
                // if(this.filteredPaymentStatus) a=a.filter(x=>x.payment_status_id==this.filteredPaymentStatus);
                return  a;
            }
        },
        created() {
            // this.initialize();
        },
        methods:{
            editItem (item) {
                axios
                .put("/api/weeklyReportReferrals/"+item.id,this.form)
                .then(response => {
                    
                    this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                    this.$emit('refresh');
                    this.editDialog=false;
                    
                })
                
                .catch(error => {
                     //console.log(error);
                    if(error.response){
                        this.errors.name = null;
                        this.errors = error.response.data.errors;
                        this.$root.Snackbar.show({message: error.response.data.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }else{
                        this.$root.Snackbar.show({message: error.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }
                });
                
                
            },
            goToCreate(){
                this.$router
                    .push('/weeklyReportReferrals/create') 
                    .catch((error) => {
                        console.error(error);
                    });;
            },
            clear () {
                this.$v.form.$reset()
                this.form.name_en='';
                this.form.name_kh='';
                this.form.code='';
                this.form.description='';
            },
            refresh(){
                this.$emit('refresh');
                // this.editDialog=false;
            },
            view(id){
                this.$router
                    .push('/weeklyReportReferrals/'+id) 
                    .catch((error) => {
                    // Handle any errors that occur during the POST request
                    console.error(error);
                });;
            }
        },
        
         watch:{
            editDialog:function(){
                if(this.editDialog==false){
                    this.clear();
                }
            }
        }
    }

</script>
