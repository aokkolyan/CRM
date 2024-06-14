<template>       
    <div class="m-1" style="margin:auto">
        <v-card flat>
            
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    :label="$t('Search')+$t('My Referral')"
                    hide-details
                ></v-text-field>
                <v-divider></v-divider>
                <v-btn color="primary" @click="openCreateDialog()">
                    {{ $t('create') }}
                </v-btn>
            </v-card-title>
            <v-card-title class="d-flex flex-row">
             
                <div style="width:150px;margin-right: 2px;">
                    <v-select
                    outlined
                    dense
                    hide-details
                    v-model="filteredBranch"
                    :label="$t('Branch')"
                    :items="branches"
                    item-text="displayText"
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
                    :items="statuses"
                    :label="$t('Status')"
                    item-text="name_en"
                    item-value="id"
                    clearable
                    >
                    </v-select>
                </div>
                <div style="width:150px;margin-right: 2px;">
                    <v-select
                    onchange="onChangeSelectedOption"
                    outlined
                    dense
                    hide-details
                    v-model="filteredPaymentStatus"
                    :items="paymentStatuses"
                    :label="$t('Payment Status')"
                    item-text="name_en"
                    item-value="id"
                    clearable
                    >
                    </v-select>
                </div>
            </v-card-title>
            <v-card-text>
            
                <v-simple-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>
                                {{$t('Parent')}}
                            </th>
                            <th>
                                {{$t('Phone')}}
                            </th>
                            <th>
                                {{$t('Student Name')}}
                            </th>
                            <th>
                                {{$t('Branch')}}
                            </th>
                            <th>
                                {{$t('Status')}}
                            </th>
                            <th>
                                {{$t('Revenue')}}
                            </th>
                            <th>
                                {{$t('Commission(%)')}}
                            </th>
                            <th>
                                {{$t('Commission')}}
                            </th>
                            <th>
                                {{$t('Point') }}
                            </th>
                            <th>
                                {{$t('Coupon') }}
                            </th>
                            <th>
                                {{$t('Payment Status')}}
                            </th>
                            <th>
                                {{$t('Paid')}}
                            </th>
                            <th>
                                {{$t('Created')}}
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
                        <tr v-for="(ref,index) in filteredMyReferrals" :key="ref.id">
                            <td>{{ index+1 }}</td>
                            <td>{{ ref.parent_name }}</td>
                            <td>{{ ref.phone }}</td>
                            <td>{{ ref.student_name }}</td>
                            <td>{{ ref.branch.displayText }}</td>
                            <td>
                                <v-chip
                                    v-if="ref.referral_status_id"
                                    class="ma-2"
                                    :color="statuses.find(x=>x.id==ref.referral_status_id).color+'2F'"
                                    :text-color="statuses.find(x=>x.id==ref.referral_status_id).color"
                                >
                                    {{statuses.find(x=>x.id==ref.referral_status_id).name_en}}
                                </v-chip>
                            </td>
                            <td class="text-right"><span v-if="ref.payment_status_id==2">{{ ref.fee | currency(ref.is_riel?'KHR':'USD') }}</span></td>
                            <td class="text-right"><span v-if="ref.payment_status_id==2">{{ ref.commission_percentage }}%</span></td>
                            <!-- <td class="text-right">
                                <span v-if="ref.payment_status_id==2">{{ ref.commission | currency(ref.is_riel?'KHR':'USD')}}</span>
                            </td> -->
                            <td class="text-right"
                                v-if="ref.commission_type =='cash'"
                                > <span v-if="ref.commission_type=='cash'">{{ ref.commission | currency(ref.is_riel?'KHR':'USD')}}</span>
                                </td>
                                <td  class="text-right"
                                    v-else-if="ref.commission_type == 'point'"
                                ><span v-if="ref.payment_status_id==2">{{ ref.commission }}Pts</span>
                                </td>
                                <td class="text-right"
                                    v-else
                                    >{{  ref.commission }} coupon
                                </td> 
                                <td class="text-right">
                                    <span v-if="ref.commission_type =='point'">{{ ref.commission }}Pts</span>
                                </td>
                                <td class="text-right">
                                 <span v-if="ref.commission_type =='coupon'">{{ ref.commission }}coupon</span>
                                </td>
                                
                              <td>
                                <v-chip
                                    v-if="ref.referral_status_id==4&&ref.payment_status_id"
                                    class="ma-2"
                                    :color="paymentStatuses.find(x=>x.id==ref.payment_status_id).color+'2F'"
                                    :text-color="paymentStatuses.find(x=>x.id==ref.payment_status_id).color"
                                >
                                    {{paymentStatuses.find(x=>x.id==ref.payment_status_id).name_en}}
                                </v-chip>
                            </td>
                            
                            <td>{{ ref.paid_at }}</td>
                            <td>{{ ref.created_at }}</td>
                            <td>
                                <v-btn v-if="ref.referral_status_id==1" small icon color="primary" @click="openEditDialog(ref)">
                                    <v-icon>
                                        mdi-fountain-pen-tip
                                    </v-icon>
                                </v-btn>
                            </td>
                        </tr>
                        <tr v-if="filteredMyReferrals.length>0">
                            <td colspan="6">{{ $t('Total') }}:</td>
                            <td class="text-right">
                                {{ filteredMyReferrals.filter(x=>x.payment_status_id==2&&!x.is_riel).reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0)  | currency('USD')}}
                                ({{ filteredMyReferrals.filter(x=>x.payment_status_id==2&&x.is_riel).reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0)  | currency('KHR')}})
                            
                            </td>
                            <td></td>
                            <td class="text-right">
                            
                            <span v-show="isDisplayed"> {{ filteredMyReferrals.filter(x=>x.payment_status_id==2&&x.commission_type=='cash'&&!x.is_riel).reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0)  | currency('USD')}}Paid</span>
                            <span v-show="isDisplayed">{{ filteredMyReferrals.filter(x=>x.payment_status_id==1&&!x.is_riel).reduce((accumulator,currentValue)=> accumulator+currentValue.commission, 0) | currency('USD')}}</span>
                                ({{ filteredMyReferrals.filter(x=>x.payment_status_id==2&&x.is_riel).reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0)  | currency('KHR')}})    
                            </td>

                            <td  class="text-right">
                                {{ filteredMyReferrals.filter(x=>x.payment_status_id==2&&x.commission_type=='point'&&!x.is_riel).reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0)}}Pts
                            </td>
                            <td  class="text-right">
                                {{ filteredMyReferrals.filter(x=>x.payment_status_id==2&&x.commission_type=='coupon'&&!x.is_riel).reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0)}}coupon
                            </td>
                            
                            <td colspan="3"></td> 
                        </tr>
                        <tr v-else>
                            <td colspan="12" style="text-align: center;">
                                {{ $t('noDataText') }}
                            </td>
                        </tr>
                    </tbody>
                </v-simple-table>
              
                    <!--Edit dialog-->
                    <v-dialog
                    v-model="createDialog"
                    max-width="700"
                    >
                        <Create :canCreateForOthers="can.createForOthers" :users="users" :schools="schools" :programs="programs" :branches="branches" v-on:refresh="refresh" v-on:closeDialog="createDialog=false"></Create>
                    </v-dialog>
                    <v-dialog
                    v-model="editDialog"
                    max-width="700"
                    >
                        <v-card v-if="form">
                            <v-card-title>
                                {{ form.student_name }}
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col>
                                        <v-select
                                            dense
                                            v-model="form.school_id"
                                            :items="schools"
                                            item-value="id"
                                            :item-text="$isKh()?'name_kh':'name_en'"
                                            :label="$t('School')"
                                        ></v-select>
                                        <div v-if="errors.school">
                                            <span
                                                style="color: red"
                                                v-for="(error, index) in errors.school"
                                                :key="index"
                                                >{{ error }}
                                            </span>
                                        </div>
                                    </v-col>
                                    <v-col>
                                        <v-select
                                            dense
                                            v-model="form.branch_id"
                                            :items="branchFiltered"
                                            item-value="id"
                                            item-text="displayText"
                                            :error-messages="branchErrors"
                                            @input="$v.form.branch_id.$touch()"
                                            @blur="$v.form.branch_id.$touch()"
                                            @change="editItem(form)"
                                            >
                                            <template #label>
                                                {{$t('Branch')}}<strong class="red--text">* </strong>
                                            </template>
                                        </v-select>
                                        <div v-if="errors.branch">
                                            <span
                                                style="color: red"
                                                v-for="(error, index) in errors.branch"
                                                :key="index"
                                                >{{ error }}
                                            </span>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
            </v-card-text>
          

        
        
        </v-card>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength, minLength, email } from 'vuelidate/lib/validators';
    import Table from "@/components/Table.vue"
    import Create from './Create.vue';
    
    import Tooltip from '../../../components/Tooltip.vue';
    import axios from "axios";
    export default {
        mixins: [validationMixin],
        props:{
            user:Object(),
            myReferrals:Array(),
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
            users:Array(),
        },
        components: {
            Table,Create,Tooltip
        },
        validations: {
            form:{
                branch_id: { required},
            }
        },
     
        data () {
            return {
                errors:[],
                title:'Referral',
                routeString:'myReferrals',
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
                    school_id:null,
                    branch_id:null
                },
                overlay:false,
                dialog:false,
                createDialog:false,
                editDialog:false,
                deleting_Referral:Object(),
                editing_Referral:Object(),
                search: '',
                filteredBranch:null,
                filteredStatus:null,
                filteredPaymentStatus:null,
                isDisplayed:true,
                breadcrumbs:[
                    {
                    text: 'Dashboard',
                    disabled: false,
                    href: '/dashboard',
                    },
                    {
                    text: 'My Referral',
                    disabled: true,
                    href: '/myReferrals',
                    },
                ],
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
            filteredMyReferrals(){
                let a=this.myReferrals;
                if(this.search) a=a.filter(x=>x.parent_name.toLowerCase().includes(this.search.toLowerCase())||x.phone.toLowerCase().includes(this.search.toLowerCase()));
                if(this.filteredBranch) a=a.filter(x=>x.branch_id==this.filteredBranch);
                if(this.filteredStatus) a=a.filter(x=>x.referral_status_id==this.filteredStatus);
                if(this.filteredPaymentStatus) a=a.filter(x=>x.payment_status_id==this.filteredPaymentStatus);
                return  a;
            },
            branchErrors() {
                const errors = [];
                if (!this.$v.form.branch_id.$dirty) return errors;
                !this.$v.form.branch_id.required && errors.push("Branch is required.");
                return errors;
            },
            branchFiltered(){
                return this.form.school_id?this.branches.filter(x=>x.school_id==this.form.school_id):this.branches;
            },
        },
        created() {
            // this.initialize();
        },
        methods:{
            editItem (item) {
                axios
                .put("/api/myReferrals/"+item.id,this.form)
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
                this.form=item;
                this.form.school_id=item.branch.school_id;
                
                this.editDialog = true;
            },
            openCreateDialog(){
                this.createDialog = true;
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
                // this.createDialog=false;
            }
        },
         watch:{
            createDialog:function(){
                if(this.createDialog==false){
                    this.clear();
                }
            },
            editDialog:function(){
                if(this.editDialog==false){
                    this.form=null;
                }
            }
        }
    }

</script>
