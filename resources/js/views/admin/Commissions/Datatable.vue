<template>       
    <div class="m-1" style="margin:auto">
        <v-card flat>
            
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search Commissions..."
                    hide-details
                ></v-text-field>
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-title class="d-flex flex-row">
                <div style="width:150px;margin-right: 2px;">
                    <v-select
                    outlined
                    dense
                    hide-details
                    v-model="filteredBranch"
                    label="Branch"
                    :items="branches"
                    item-text="code"
                    item-value="id"
                    clearable
                    >

                    </v-select>
                </div>
                <div style="width:150px;margin-right: 2px;">
                    <v-select
                    outlined
                    dense
                    hide-details
                    v-model="filteredStatus"
                    :items="paymentStatuses"
                    label="Status"
                    item-text="name_en"
                    item-value="id"
                    clearable
                    >

                    </v-select>
                </div>
            </v-card-title>
            <v-card-text>
                <v-data-table 
                    :headers="headers"
                    :items="filteredMyCommissions"
                    :loading="loading"
                    :search="search"
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
                        <!-- <td  class="text-right">
                            {{ item.commission??0 | currency(item.is_riel?'KHR':'USD' )}}
                        </td> -->
                        <td
                        v-if="item.commission_type == 'cash'"

                        >  {{ item.commission??0 | currency(item.is_riel?'KHR':'USD' )}}
                         </td>
                         <td
                         v-else-if="item.commission_type == 'point'"
                            >{{ item.commission }} Pts
                         </td>
                         <td
                         v-else

                         >{{ item.commission }} coupon %</td>
                       
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
                    
                    <template v-slot:[`item.actions`]="{ item }">
                            <Tooltip
                                tooltip="view"
                            >
                                <v-btn
                                    label="View"
                                    icon
                                    color="primary"
                                    @click="openPaymentDialog(item)"
                                >
                                    <v-icon v-if="item.payment_status_id==1">
                                        mdi-cash
                                    </v-icon>
                                    <v-icon v-else>
                                        mdi-eye
                                    </v-icon>
                                </v-btn>
                            </Tooltip>
                           
                    </template>
                </v-data-table>
               
            </v-card-text>
          

        
        
        </v-card>
          <!--Edit dialog-->
          <v-dialog
            v-model="paymentDialog"
            max-width="600"
            >
                <v-card v-if="payment">
                    <v-card-title>
                        Referral Information
                        <v-spacer></v-spacer>
                        <v-chip
                            small
                            label
                            v-if="payment.payment_status_id"
                            class="ma-2"
                            :color="paymentStatuses.find(x=>x.id==payment.payment_status_id).color+'2F'"
                            :text-color="paymentStatuses.find(x=>x.id==payment.payment_status_id).color"
                        >
                            <v-icon small left>
                                mdi-cash
                            </v-icon>
                            {{paymentStatuses.find(x=>x.id==payment.payment_status_id).name_en}}
                        </v-chip>
                    </v-card-title>
                    <v-card-text>
                        <table>
                            <tr>
                                <th>
                                    Referror
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.creator }}
                                </td>
                                <th>
                                    Created At
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.created_at }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Parent1
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.parent_name }}
                                </td>
                                <th>
                                    Phone
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.phone }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Parent2
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.parent2_name }}
                                </td>
                                <th>
                                    Phone
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.phone2 }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Student name
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.student_name }}
                                </td>
                                <th>
                                    Branch
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.branch.name_en }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Program
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.program }}
                                </td>
                                <th>
                                    Level
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.level }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Shift
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.shift }}
                                </td>
                                <th>
                                    Start Date
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.start_date }}
                                </td>
                            </tr>
                            <tr>
                        
                                <th>
                                    Fee
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.fee | currency(payment.is_riel?'KHR':'USD') }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Commission(%)
                                </th>
                                <td class="pl-2 pr-5">
                                    {{ payment.commission_percentage }}%
                                </td>
                                <th style="color: rgb(148, 152, 39);">
                                    Commission
                                </th>
                                <td class="pl-2 pr-5"
                                    v-if="payment.commission_type == 'cash'"
                                >
                                    {{ payment.commission | currency(payment.is_riel?'KHR':'USD') }}
                                </td>
                                <td class="pl-2 pr-5"
                                v-else-if="payment.commission_type =='point'"
                                >{{ payment.commission }} Pts</td>
                                <td class="pl-2 pr-5"
                                v-else
                                >
                                {{ payment.commission }} Coupon %
                                </td>
                            </tr>
                        </table>
                   
                    </v-card-text>
                </v-card>
                <v-card v-if="payment">
                    <v-card-title>
                        Attachments
                    </v-card-title>
                    <v-card-text>
                        <v-list v-if="payment&&payment.attachments.length>0">
                            <v-list-item v-for="att in payment.attachments" :key="att.id">
                                <a :href="'storage/'+att.path">{{ att.name }}</a>
                            </v-list-item>
                        </v-list>
                        <v-list v-else>
                           No Attachments
                        </v-list>
                        <form enctype="multipart/form-data"  @submit="uploadAttachment">
                            <!--Error Invalid prop: custom validator check failed for prop "value" -->
                            <v-file-input
                            name="file"
                            placeholder="Ex: Invoice...."
                            v-model="formPayment.file"
                            ></v-file-input>
                            
                            
                            <v-btn
                                class="mx-2"
                                color="primary"
                                outlined
                                @click="uploadAttachment"
                                >
                                Upload
                            </v-btn>
                        </form>
                    </v-card-text>
                </v-card>
                <v-card v-if="payment&&payment.payment_status_id==1">
                    <v-card-title>
                        Make Payment
                    </v-card-title>
                    <v-card-text>
                        <form enctype="multipart/form-data"  @submit="makePayment">
                            
                            <v-btn
                                class="mx-2"
                                color="primary"
                                @click="makePayment"
                                >
                                Mark as Paid
                            </v-btn>
                        </form>
                    </v-card-text>
                </v-card>
         
            </v-dialog>
    </div>
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
            commissions:Array(),
            loading:{
                type:Boolean(),
                default:false   
            },
            can:Array(),
            branches:Array(),
            paymentStatuses:Array(),
        },
        components: {
            Table,Tooltip
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
                // commissions:[],
                errors:[],
                title:'Commission',
                routeString:'commissions',
                headers: [
                    { text: '#', value: 'index', },
                    { text: 'Referrer', value: 'creator' },
                    { text: 'Student Name ', value: 'student_name' },
                    { text: 'Branch', value: 'branch' },
                    { text: 'Fee', value: 'fee' },
                    { text: 'Comission(%)', value: 'commission_percentage' },
                    { text: 'Commission', value: 'commission' },
                    { text: 'Status', value: 'status' },
                    { text: 'Paid At', value: 'paid_at' },
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
                formPayment:{
                    file:null,
                },
                overlay:false,
                dialog:false,
                paymentDialog:false,
                payment:null,
                deleting_Commission:Object(),
                editing_Commission:Object(),
                search: '',
                
                filteredBranch:null,
                filteredStatus:null,
                breadcrumbs:[
                    {
                    text: 'Dashboard',
                    disabled: false,
                    to: '/dashboard',
                    },
                    {
                    text: 'Commission',
                    disabled: true,
                    to: '/commissions',
                    },
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
            filteredMyCommissions(){
                let a=this.commissions;
                if(this.filteredBranch) a=a.filter(x=>x.branch_id==this.filteredBranch);
                if(this.filteredStatus) a=a.filter(x=>x.payment_status_id==this.filteredStatus);
                return  a;
            }
        },
        created() {
            // this.initialize();
        },
        methods:{
          
            editItem (item) {
                axios
                .put("/api/commissions/"+item.id,this.form)
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
            openEditDialog(item){
                this.editDialog = true;
            },
            clear () {
                this.$v.form.$reset()
                this.form.name_en='';
                this.form.name_kh='';
                this.form.code='';
                this.form.description='';
                this.form.file=null;
            },
            openPaymentDialog(item){
                this.paymentDialog=true;
                this.payment=item;
            },
            uploadAttachment(){
                const formData = new FormData();
                formData.append('file', this.formPayment.file);
                // axios.post('api/user/import',formData,{
                //     headers: {
                //         'Content-Type': 'multipart/form-data'
                //     }
                // })
                axios.post('api/commissions/'+this.payment.id+'/import',formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.errors = null;
                        this.$emit('refresh');
                        this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                        this.$root.Overlay.off();
                        this.clear();
                    }
                })
                .catch((error) => {
                    //console.log(error);
                    if(error.response){
                        this.errors = error.response.data.errors;
                        this.$root.Snackbar.show({message: error.response.data.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }else{
                        this.$root.Snackbar.show({message: error.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }

                });
                
                this.paymentDialog=false;
            },
            makePayment(){
           
                axios.put('api/commissions/'+this.payment.id)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.errors = null;
                        this.$emit('refresh');
                        this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                        this.$root.Overlay.off();
                        this.clear();
                    }
                })
                .catch((error) => {
                    //console.log(error);
                    if(error.response){
                        this.errors = error.response.data.errors;
                        this.$root.Snackbar.show({message: error.response.data.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }else{
                        this.$root.Snackbar.show({message: error.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }

                });
                
                this.paymentDialog=false;
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
