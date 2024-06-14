<template>
    <v-container>
        <h2 class="font-semibold text-xl text-gray-800 referraling-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <v-row>
            <v-col cols="12" lg="8">
                <v-card :loading="loading" flat >
                    <v-card-text>
                        <v-row >
                            <v-col>
                                <v-card-title>
                                    {{ $t('Student List') }} {{ weeklyReportReferral.from | formatDateOnly}}-{{ weeklyReportReferral.to | formatDateOnly}}
                                 
                                </v-card-title>

                                <v-data-table
                                    :headers="headers"
                                    :items="weeklyReportReferral.referrals"
                                    item-key="id"
                                    :items-per-page="-1"
                                    hide-default-footer
                                    checkbox-color="primary"
                                    fixed-header
                                    dense
                                    
                                >
                                    <template v-slot:[`item.index`]="{ index }">
                                            {{ index + 1 }}
                                    </template>
                                    <template  v-slot:[`item.full_fee`]="{ item }">
                                        <td  class="text-right">
                                            {{ item.full_fee | currency(item.is_riel?'KHR':'USD') }}
                                        </td>
                                    </template>
                                    <template v-slot:[`item.discount_percentage`]="{ item }">
                                        <td  class="text-right">
                                            {{ item.discount_percentage }}%
                                        </td>
                                    </template>
                                    <template v-slot:[`item.fee`]="{ item }">
                                        <td  class="text-right">
                                            {{ item.fee | currency(item.is_riel?'KHR':'USD')}}
                                        </td>
                                    </template>
                                    <template v-slot:[`item.commission`]="{ item }">
                                        <!-- <td  class="text-right">
                                            {{ item.commission | currency(item.is_riel?'KHR':'USD')}}
                                        </td> -->
                                        <td
                                        v-if="item.commission_type == 'cash'"  
                                        >  {{ item.commission??0 | currency(item.is_riel?'KHR':'USD')}}</td>
                                        <td
                                        v-else-if="item.commission_type =='point'"
                                        >{{  item.commission  }} Pts</td>
                                        <td
                                        v-else
                                        >{{ item.commission }}%coupon</td>
                                    </template>
                                    <template v-slot:[`item.attachments`]="{ item }">
                                        <td  class="text-right" v-if="item.attachments.length>0">
                                            <div v-for="att in item.attachments" :key="att.id">
                                                {{ att.name }}
                                            </div>
                                        </td>
                                        <td class="text-right" v-else>
                                            <span style="font-size:10px"> {{ $t('No Attachment') }} </span>
                                        </td>
                                    </template>
                                    <template v-slot:[`item.enrolled_at`]="{ item }">
                                        <td  class="text-right">
                                            {{ item.enrolled_at | formatDateOnly}}
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
                                    <template v-slot:[`item.actions`]="{ item }">
                                        <td  class="text-right">
                                            <Tooltip :tooltip="$t('View Attachment')">
                                                <v-btn :disabled="item.attachments.length==0" color="primary" x-small icon @click="view(item)">
                                                    <v-icon>mdi-eye</v-icon>
                                                </v-btn>
                                            </Tooltip>
                                            <!-- Delete -->
                                            <v-btn  v-if="!weeklyReportReferral.checker" 
                                            x-small
                                            icon>
                                            <Tooltip tooltip="Delete">
                                            <v-icon 
                                               style="font-size: 18px;"
                                               color="red"
                                               @click="openDialogConfirmation(item)"
                                               >
                                                mdi-delete
                                                </v-icon>
                                            </Tooltip>
                                           </v-btn>
                                           <!-- hide delete -->
                                           <v-btn  v-else :disabled="weeklyReportReferral.checker"
                                            x-small
                                            icon>
                                            <Tooltip tooltip="Delete">
                                            <v-icon 
                                               style="font-size: 18px;"
                                               color="red"
                                               :disabled="can.check"
                                               @click="openDialogConfirmation(item)"
                                               >
                                                mdi-delete
                                                </v-icon>
                                            </Tooltip>
                                           </v-btn>
                                        
                                        </td>
                                        
                                    </template>
                                    
                                </v-data-table>
                                <v-dialog
                                    v-model="deleteDialog"
                                    max-width="600"
                                >
                                    <v-card elevation="5" v-if="deleting_item">
                                        <v-card-title class="text-h7" >
                                        Are you sure you want to delete this  {{ deleting_item.student_name }}?
                                        </v-card-title>
                                        <v-card-text>
                                            <v-text-field
                                                v-model="reason"
                                                label="reason"
                                            >

                                            </v-text-field>
                                        </v-card-text>
                                        <v-card-actions>
                                        <v-spacer></v-spacer>

                                            <v-btn
                                                color="grey darken-1"
                                                text
                                                @click="deleteDialog = false"
                                            >
                                                Disagree
                                            </v-btn>

                                            <v-btn
                                                color="red darken-1"
                                                text
                                                @click="deleteItem(deleting_item)"
                                            >
                                            Agree
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>

                          </v-col>
                        </v-row>
                     
                    </v-card-text>
                    <v-card-text  v-if="referrers&&referrers.length>0&&can.pay">
                        <v-card-title>
                            {{ $t('Referrer List') }}
                            <v-spacer></v-spacer>
                            <v-btn
                                small
                                color="success"
                                @click="print()"
                            >
                                <v-icon dark>
                                    mdi-printer
                                </v-icon>
                                    Print
                            </v-btn>
                        </v-card-title>
                        <v-data-table
                            
                            :headers="referrerHeaders"
                            :items="referrers"
                            item-key="id"
                            :items-per-page="-1"
                            hide-default-footer
                            checkbox-color="primary"
                            fixed-header
                            dense
                            
                        >
                            <template v-slot:[`item.index`]="{ index }">
                                    {{ index + 1 }}
                            </template>
                            <template v-slot:[`item.revenue`]="{ item }">
                                <td class="text-right">{{ item.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0)  | currency(weeklyReportReferral.branch.is_riel?'KHR':'USD') }}</td>
                            </template>
                            <template v-slot:[`item.commission`]="{ item }">
                                <td class="text-right">{{ item.referrals.filter(x=>x.commission_type=='cash').reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)  | currency(weeklyReportReferral.branch.is_riel?'KHR':'USD') }}</td>
                            </template>
                            <template v-slot:[`item.point`]="{ item }">
                                <td class="text-right">{{ item.referrals.filter(x=>x.commission_type=='point').reduce((accumulator, currentValue) =>accumulator + currentValue.commission,0)}}Pts</td>
                            </template>
                            <template v-slot:[`item.coupon`]="{ item }">
                                <td class="text-right" >{{ item.referrals.filter(x=>x.commission_type=='coupon').reduce((accumulator, currentValue) =>accumulator + currentValue.commission,0)}}% </td>
                            </template>
                            <template v-slot:[`item.wht`]="{ item }">
                                <td class="text-right">{{ item.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.wht, 0)  | currency(weeklyReportReferral.branch.is_riel?'KHR':'USD') }}</td>
                            </template>
                            <!-- <template v-slot:[`item.unpaid_commission`]="{ item }">
                                <td class="text-right">{{ item.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)  | currency(weeklyReportReferral.branch.is_riel?'KHR':'USD') }}</td>
                                
                            </template> -->
                            <template v-slot:[`item.actions`]="{ item }">
                                <td  class="text-right">
                                    <Tooltip :tooltip="$t('Pay Commission')">
                                        <v-btn :disabled="item.referrals_in_my_branch_approved_not_yet_paid.length==0" color="primary" x-small icon @click="openPaymentDialog(item)">
                                            <v-icon>mdi-cash</v-icon>
                                        </v-btn>
                                    </Tooltip>
                                </td>
                            </template>
                            
                        </v-data-table>
                    </v-card-text>
                  
                    <v-dialog v-model="dialog" v-if="viewItem">
                        <v-card>
                            <v-card-title>
                                <v-spacer></v-spacer>
                                <v-btn @click="dialog=false" icon color="red"> <v-icon>mdi-close</v-icon></v-btn>
                            </v-card-title>
                            <v-card-text>
                                <div v-for="att in viewItem.attachments" :key="att.id">                           
                                    <iframe v-if="att.name.split('.')[1]=='pdf'" :src="'/storage/'+att.path" style="width:100%; height:auto;" frameborder="0"></iframe>                           
                                    <img v-if="att.name.split('.')[1]=='jpg'||att.name.split('.')[1]=='jpeg'||att.name.split('.')[1]=='png'" :src="'/storage/'+att.path" alt=""  style="width:100%; height:auto;" class="my-2">
                                    <a v-else :href="'/storage/'+att.path">{{ att.name }}</a>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <v-dialog
                        v-model="paymentDialog"
                        width="600px"
                    >
                        <v-card v-if="allUserReferrals.length>0">
                            <v-card-title>
                                Are you sure you want to make commission payment &nbsp;
                                <strong style="color:darkgreen">{{ allUserReferrals.reduce((accumulator, currentValue) =>accumulator + parseFloat(currentValue.commission), 0) | currency(allUserReferrals[0].is_riel?'KHR':'USD')}}</strong>&nbsp;for user 
                                &nbsp;<strong> {{ payingUser.name }}</strong> ?
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
            </v-col>
            <v-col cols="12" lg="4">
                <v-card flat>
                    <v-card-text>
                        
                    <v-timeline dense>
                        <v-timeline-item 
                            :color="weeklyReportReferral.preparer?'primary':'secondary'"
                            :icon="weeklyReportReferral.preparer?'mdi-star':''"
                        >
                            <v-card
                                v-if="weeklyReportReferral.preparer"
                            >
                                <v-card-subtitle>
                                {{ $t('Prepared By') }}:  <strong> {{  weeklyReportReferral.preparer.name}} </strong>
                                </v-card-subtitle>  
                                <v-card-subtitle v-if="weeklyReportReferral.prepared_remark">
                                    <strong> {{  weeklyReportReferral.prepared_remark}} </strong>
                                </v-card-subtitle>
                                <v-card-text >
                                    {{ weeklyReportReferral.prepared_at }}
                                </v-card-text>
                            </v-card>
                            <v-btn
                                color="secondary"
                                class="mx-0"
                                outlined
                                @click="prepare(weeklyReportReferral.id)"
                                v-else
                            >
                                Prepare
                            </v-btn>
                        </v-timeline-item>
                        <v-timeline-item 
                            :reverse="true" 
                            :icon="weeklyReportReferral.checker?'mdi-star':''"
                            :color="weeklyReportReferral.checker?'primary':'secondary'"
                        >
                            <v-card
                                v-if="weeklyReportReferral.checker"
                            >
                                <v-card-subtitle>
                                {{ $t('Checked By') }}:  <strong> {{  weeklyReportReferral.checker.name}} </strong>
                                </v-card-subtitle>
                                <v-card-subtitle v-if="weeklyReportReferral.checked_remark">
                                    <strong> {{ weeklyReportReferral.checked_remark }} </strong>
                                </v-card-subtitle>
                                <v-card-text >
                                    {{ weeklyReportReferral.checked_at }}
                                </v-card-text>
                            </v-card>
                            <v-text-field label="Comment" v-if="!weeklyReportReferral.checker&&can.check" v-model="checked_remark" outlined>

                            </v-text-field>
                            <v-btn
                                v-if="!weeklyReportReferral.checker"
                                color="primary"
                                class="mx-0"
                                outlined
                                :disabled="!can.check"
                                @click="check(weeklyReportReferral.id)"
                            >
                                {{ $t('Check') }}
                            </v-btn>
                          
                        </v-timeline-item>
                        <v-timeline-item
                            
                            :icon="weeklyReportReferral.approver?'mdi-star':''"
                            :color="weeklyReportReferral.approver?'primary':'secondary'"
                        >
                            <v-card
                                v-if="weeklyReportReferral.approver"
                            >
                                <v-card-subtitle>
                                {{ $t('Approved By') }}:  <strong> {{  weeklyReportReferral.approver.name}} </strong>
                                </v-card-subtitle>
                                <v-card-subtitle v-if="weeklyReportReferral.approved_remark">
                                    <strong> {{  weeklyReportReferral.approved_remark}} </strong>
                                </v-card-subtitle>
                                <v-card-text >
                                    {{ weeklyReportReferral.approved_at }}
                                </v-card-text>
                            </v-card>
                            <v-text-field label="Comment" v-if="!weeklyReportReferral.approver&&(can.approve&&weeklyReportReferral.checker)" v-model="approved_remark" outlined>

                            </v-text-field>
                            <v-btn
                                v-if="!weeklyReportReferral.approver"
                                color="primary"
                                class="mx-0"
                                outlined
                                :disabled="!can.approve||!weeklyReportReferral.checker"
                                @click="approve(weeklyReportReferral.id)"
                            >
                                {{ $t('Approve') }}
                            </v-btn>
                        </v-timeline-item>
                    </v-timeline>
                    
                </v-card-text>
                </v-card>
           
            </v-col>
        </v-row>
        <div id="printMe" v-if="referrers&&referrers.length>0" class="d-print-block" style="font-family: 'KhmerOsSiemReap';">
            <h1>
                Referrer List({{ referrers[0].branch.name_en }}) {{ weeklyReportReferral.from | formatDateOnly}}-{{ weeklyReportReferral.to | formatDateOnly}}
            </h1>
            <table class="table" style="font-family: 'KhmerOsSiemReap';">
                <thead>
                    <tr>
                        <th> #</th>
                        <th> Referrer</th>
                        <th class="text-right"> Revenue</th>
                        <th class="text-right"> Commission</th>
                        <th class="text-right"> WHT</th>
                        <th class="text-right"> Unpaid commission</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item,index in referrers" :key="index">
                        
                        <td>{{ index+1 }}</td>
                        <td>{{ item.name }}</td>
                        <td class="text-right" :disabled="mymethod">{{ item.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0)  | currency(weeklyReportReferral.is_kh?'KHR':'USD') }}</td>
                        <td class="text-right">{{ item.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)  | currency(weeklyReportReferral.is_kh?'KHR':'USD') }}</td>
                        <td class="text-right">{{ item.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.wht, 0)  | currency(weeklyReportReferral.is_kh?'KHR':'USD') }}</td>
                        <td class="text-right">{{ item.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0)  | currency(weeklyReportReferral.is_kh?'KHR':'USD') }}</td>
                            
                    </tr>
                    <tr>
                        <td colspan="2">
                            Total:
                        </td>
                        <td class="text-right">{{ referrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.fee, 0),0) | currency(weeklyReportReferral.is_kh?'KHR':'USD') }}</td>
                        <td class="text-right">{{ referrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0),0) | currency(weeklyReportReferral.is_kh?'KHR':'USD') }}</td>
                        <td class="text-right">{{ referrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals.reduce((accumulator, currentValue) =>accumulator + currentValue.wht, 0),0) | currency(weeklyReportReferral.is_kh?'KHR':'USD') }}</td>
                        <td class="text-right">{{ referrers.reduce((accumulator, currentValue) =>accumulator + currentValue.referrals_in_my_branch_approved_not_yet_paid.reduce((accumulator, currentValue) =>accumulator + currentValue.commission, 0),0) | currency(weeklyReportReferral.is_kh?'KHR':'USD') }}</td>
                        
                    </tr>
                </tbody>
            </table>
          
        </div>
      
    </v-container>
</template>

<script>
import { validationMixin } from "vuelidate";
import {
    required,
    maxLength,
    minLength,
    email,
} from "vuelidate/lib/validators";
import Snackbar from "@/components/Snackbar";
import Tooltip from '../../../components/Tooltip.vue';

import axios from "axios";
export default {
    mixins: [validationMixin],
    props: {
    },
    components: {
        Snackbar,Tooltip
    },

    data() {
        return {
            overlay: false,
            errors: {
                parent_name: null,
                name_kh: null,
                code: null,
            },
            title: "Referral "+this.$route.params.referral_id,
            snackbar: false,
            snackColor: "",
            message: "",
            loading:false,
            branches:[],
            dialog:false,
            showDelete:true,
            reason:[],
            mymethod:'',
          
         
            overlay: false,
            breadcrumbs:[
                {
                text: this.$t('Dashboard'),
                disabled: false,
                to: '/dashboard',
                },
                {
                text: this.$t('Weekly Report'),
                disabled: false,
                to: '/weeklyReportReferrals',
                exact: true
                },
                {
                text: this.$t('Show'),
                disabled: true,
                },
           
            ],
            headers: [
                { text: this.$t('#'), value: 'index', align:'end' },
                { text: this.$t('Referrer'), value: 'creator', align:'end' },
                { text: this.$t('Student Name'), value: 'student_name', align:'end' },
                // { text: this.$t('Full Fee'), value: 'full_fee', align:'end' },
                // { text: this.$t('Discount'), value: 'discount_percentage', align:'end' },
                { text: this.$t('Fee'), value: 'fee', align:'end' },
                { text: this.$t('Commission'), value: 'commission', align:'end' },
                // { text: this.$t('Attachments'), value: 'attachments', align:'end' },
                { text: this.$t('Comment'), value: 'comment', align:'end' },
                { text: this.$t('Status'), value: 'status', align:'end' },
                { text: this.$t('Enrolled At'), value: 'enrolled_at', align:'end' },
                { text: this.$t('Attachments'),  value: 'actions', sortable: false},
            ],
            referrerHeaders: [
                { text: this.$t('#'), value: 'index', align:'end' },
                { text: this.$t('Referrer'), value: 'name', align:'end' },
                { text: this.$t('Revenue'), value: 'revenue', align:'end' },
                // { text: this.$t('Full Fee'), value: 'full_fee', align:'end' },
                // { text: this.$t('Discount'), value: 'discount_percentage', align:'end' },
                { text: this.$t('Commission'), value: 'commission', align:'end' },
                { text: this.$t('Point'), value: 'point', align:'end' },
                { text: this.$t('Coupon'), value: 'coupon', align:'end' },
                { text: this.$t('Wht'), value: 'wht', align:'end' },
                // { text: this.$t('Unpaid Commission'), value: 'unpaid_commission', align:'end' },
                { text: this.$t('Pay'),  value: 'actions', sortable: false, align:'end'},
            ],
            weeklyReportReferral:[],
            can:[],
            selected:[],
            dialog:false,
            viewItem:null,
            approved_remark:null,
            checked_remark:null,
            referrers:[],
            paymentDialog:false,
            file:null,
            allUserReferrals:[],
            payingUser:'',
            paymentStatuses:[],
            deleteDialog:false,
            deleting_item:null,
            reason:'',
        };
    },
    computed: {
 

        
      
    },
    created(){
        this.initialize();
    },
    methods: {
        openDialogConfirmation(item){
                this.deleting_item={...item};
                this.message = '';
                this.deleteDialog = true;
               
            },
            deleteItem (item) {
                    this.dialog = false;
                    this.$root.Overlay.on();
                    axios.put("/api/weeklyReportReferrals/"+item.id+'/removeReferral',{'reason':this.reason})
                    .then(response => {
                        if (response) {

                            // this.message = response.data.message;
                            // this.snackColor=response.data.snackColor;
                            // this.snackbar = true;
                            //this.$emit('refresh');
                            this.initialize();
                            this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                            this.$root.Overlay.off();
                            this.deleteDialog = false;
                            this.showDelete = false;
                           
                        }
                    }).catch(error => {
                        if(error){
                            // this.message = error;
                            // this.snackbar = true;
                            // this.snackColor='error';
                            //console.log(error.response);
                            this.$root.Snackbar.show({message:error+' '+error.response.data.message,snackColor:'error',})
                            this.$root.Overlay.off();
                        }
                    });
            },
        initialize() {
            this.loading=true;
                return new Promise((resolve, reject) => {
                axios
                .get("/api/weeklyReportReferrals/"+this.$route.params.weekly_report_referral_id)
                .then(response => {
                    this.weeklyReportReferral=response.data.weeklyReportReferral;
                    this.can=response.data.can;
                    this.referrers=response.data.referrers;
                    this.paymentStatuses=response.data.paymentStatuses;
                    // console.log(response);
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
      
        view(item){
            this.viewItem=item;
            this.dialog=true;
        },
        check(id){
            axios
            .put("/api/weeklyReportReferrals/"+id+'/check',{'checked_remark':this.checked_remark})
            .then((response) => {
                //console.log(response);
                if (response) {
                    this.weeklyReportReferral=response.data.data;
                }
            })
            .catch((error) => {
                //console.log(error);
                if(error.response){
                    this.errors = error.response.data.errors;
                }else{

                }
            });
        },
        approve(id){
            axios
            .put("/api/weeklyReportReferrals/"+id+'/approve',{'approved_remark':this.approved_remark})
            .then((response) => {
                //console.log(response);
                if (response) {
                    this.weeklyReportReferral=response.data.data;
                }
            })
            .catch((error) => {
                //console.log(error);
                if(error.response){
                    this.errors = error.response.data.errors;
                }else{

                }
            });
        },
        openPaymentDialog(item){
            this.payingUser=item;
            this.allUserReferrals=item.referrals_in_my_branch_approved_not_yet_paid;
            this.paymentDialog=true;
        },
        makePayment(){
            this.allUserReferrals.forEach(element => {
                element.creator_id=this.payingUser.created_by
            });
            const formData = new FormData();
            formData.append('file', this.file);
            formData.append('allUserReferrals', JSON.stringify(this.allUserReferrals));
            axios.post('/api/commissionPayments',formData)
            .then((response) => {
                //console.log(response);
                if (response) {
                    this.initialize();
                   
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
        },
        print () {
            this.$htmlToPaper('printMe');
        },
        
    },
    watch: {},
};
</script>
<style>
    #printMe{
        display:none;
    }
</style>
