<template>
    <v-container>
        <h2 class="font-semibold text-xl text-gray-800 referraling-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <v-card :loading="loading">
            <v-card-text>
                <v-row >
                    <v-col>
                        <v-card-title>
                            <v-row>
                                <v-col>
                                    <v-text-field type="date" v-model="form.from" label="from">
                                    </v-text-field>
                                </v-col>
                                <v-col>
                                    <v-text-field type="date" v-model="form.to" label="To">
                                    </v-text-field>
                                </v-col>
                            </v-row>
                        </v-card-title>
                        <v-card-text>
                            {{ $t('All Enrolled Student') }}
                        
                            <v-data-table
                                v-model="form.selected"
                                :headers="headers"
                                :items="enrolled_students"
                                item-key="id"
                                show-select
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
                                    v-if="item.commission_type =='cash' "
                                    > 
                                    {{ item.commission | currency(item.is_riel?'KHR':'USD')}}</td>
                                    <td
                                    v-else-if="item.commission_type =='point'"
                                    >
                                    {{ item.commission }} Pts
                                    </td>
                                    <td
                                    v-else
                                    > {{ item.commission }} coupon %</td>
                                </template>
                                <template v-slot:[`item.attachments`]="{ item }">
                                    <td  class="text-right" v-if="item.attachments.length>0">
                                        <div v-for="att in item.attachments" :key="att.id">
                                            {{ att.name }}
                                        </div>
                                    </td>
                                    <td class="text-right" v-else>
                                        {{ $t('No Attachments') }}
                                    </td>
                                </template>
                                <template v-slot:[`item.comment`]="{ item }">
                                    <td  class="text-right">
                                        {{ item.comment }}
                                    </td>
                                </template>
                                <template v-slot:[`item.actions`]="{ item }">
                                    <td  class="text-right">
                                        <Tooltip tooltip="Add Comment">
                                            <v-btn color="primary" x-small icon @click="openAddCommentDialog(item)">
                                                <v-icon>mdi-comment-arrow-left-outline</v-icon>
                                            </v-btn>
                                        </Tooltip>
                                        <Tooltip tooltip="View Attachment">
                                            <v-btn color="primary" x-small icon @click="view(item)">
                                                <v-icon>mdi-eye</v-icon>
                                            </v-btn>
                                        </Tooltip>
                                    
                                    </td>
                                    
                                </template>
                                
                            </v-data-table>
                        </v-card-text>
                    </v-col>
                </v-row>
                <v-row style="text-align-last: right;">
                    <v-col>
                        <v-btn
                            :disabled="form.selected.length==0"
                            color="primary"
                            @click="submit"
                            v-if="(this.form.referral_status_id==null)"
                        >
                            {{ $t('Submit') }}
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
          
            <v-dialog v-model="dialog" v-if="viewItem">
                <v-card>
                    <v-card-text>
                        <div v-for="att in viewItem.attachments" :key="att.id">                           
                            <iframe v-if="att.name.split('.')[1]=='pdf'" :src="'/storage/'+att.path" style="width:100%; height:auto;" frameborder="0"></iframe>                           
                            <img v-if="att.name.split('.')[1]=='jpg'||att.name.split('.')[1]=='jpeg'||att.name.split('.')[1]=='png'" :src="'/storage/'+att.path" alt=""  style="width:100%; height:auto;" class="my-2">
                            <a v-else :href="'/storage/'+att.path">{{ att.name }}</a>
                        </div>
                    </v-card-text>
                </v-card>
            </v-dialog>
            <v-dialog v-model="addCommentDialog" width="600px" v-if="viewItem">
                <v-card >
                    <v-card-title>
                        {{ viewItem.student_name }}
                    </v-card-title>
                    <v-card-text>
                        <v-textarea v-model="comment" label="Comment">
                            
                        </v-textarea>
                        <v-btn @click="addComment()">
                            Add Comment
                        </v-btn>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-card>
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
import Tooltip from "@/components/Tooltip";
import axios from "axios";
export default {
    mixins: [validationMixin],
    props: {
    },
    components: {
        Snackbar,Tooltip
    },
    validations: {
        form: {
            from: { required},
            to: { required},
        },
    },
    data() {
        const today = new Date();
        const sevenDaysAgo = new Date(today);
        sevenDaysAgo.setDate(today.getDate() - 7);
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
            form: {
                from:sevenDaysAgo.toISOString().split('T')[0],
                to:new Date().toISOString().split('T')[0],
                selected:[],
            },
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
                text: this.$t('Create'),
                disabled: true,
                },
           
            ],
            enrolled_students:[],
            headers: [
                { text: this.$t('#'), value: 'index', align:'end'},
                { text: this.$t('Student Name'), value: 'student_name' , align:'end'},
                { text: this.$t('Full Fee'), value: 'full_fee' , align:'end'},
                { text: this.$t('Discount'), value: 'discount_percentage' , align:'end'},
                { text: this.$t('Fee'), value: 'fee' , align:'end'},
                { text: this.$t('Commission'), value: 'commission' , align:'end'},
                { text: this.$t('Attachments'), value: 'attachments' , align:'end'},
                { text: this.$t('Comment'), value: 'comment' , align:'end'},
                { text: this.$t('Action'), value: 'actions', sortable: false, align:'end'},
            ],
            dialog:false,
            viewItem:null,
            addCommentDialog:false,
            comment:'',
        };
    },
    computed: {
        parentNameErrors() {
            const errors = [];
            if (!this.$v.form.parent_name.$dirty) return errors;
            !this.$v.form.parent_name.maxLength && errors.push("Parent_name must be at most 30 characters long");
            !this.$v.form.parent_name.required && errors.push("Parent_name is required.");
            return errors;
        },
        phoneErrors() {
            const errors = [];
            if (!this.$v.form.phone.$dirty) return errors;
            !this.$v.form.phone.maxLength && errors.push("Phone must be at most 10 characters long");
            !this.$v.form.phone.required && errors.push("Phone is required.");
            return errors;
        },
        branchErrors() {
            const errors = [];
            if (!this.$v.form.branch_id.$dirty) return errors;
            !this.$v.form.branch_id.required && errors.push("Branch is required.");
            return errors;
        },
        
        
      
    },
    created(){
        this.initialize();
    },
    methods: {
        initialize() {
            this.loading=true;
                return new Promise((resolve, reject) => {
                axios
                .get("/api/weeklyReportReferrals/getEnrolledStudents")
                .then(response => {

                    console.log(response.data);
                    this.enrolled_students=response.data.enrolled_students;
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
        submit() {
            axios
            .post("/api/weeklyReportReferrals", this.form)
            .then((response) => {
                //console.log(response);
                if (response) {
                    this.$router
                        .push('/weeklyReportReferrals/'+response.data) 
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
                }else{
                }
            });
        },
        view(item){
            this.viewItem=item;
            this.dialog=true;
        },
        clear() {
            this.$v.form.$reset();
        },
        openAddCommentDialog(item){
            this.viewItem=item;
            this.addCommentDialog=true;
        },
        addComment(){
            this.viewItem.comment=this.comment;
            console.log(this.viewItem);
            this.addCommentDialog=false;
            this.comment='';
        }
    },
    watch: {},
};
</script>
