<template>
    <v-container>
        <h2 class="font-semibold text-xl text-gray-800 referraling-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <v-row v-if="form">
            <v-col cols="12" md="8" order-md="1" order="2">
                <v-row   
                    v-for="student in form.referrals"
                    :key="student.id"
                >
                    <v-col >
                        <v-card 
                            flat
                        >
                            <Student :programs="programs" :branches="formattedBranches" :statuses="statuses" :student="student"></Student>
                        </v-card>
                    </v-col>
              
                </v-row>
                <!-- <v-row >
                    <v-col>
                        <v-card>
                            <v-card-title>  
                                {{ $t('Add New Student') }}
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col>
                                        <v-text-field
                                            dense
                                            v-model="newStudent.student_name"
                                            :counter="30"
                                            hide-details
                                        >
                                            <template #label>
                                                {{$t('Student Name')}}<strong class="red--text">* </strong>
                                            </template>
                                        </v-text-field>
                                    </v-col>
                                    <v-col>
                                        <v-combobox
                                            dense
                                            v-model="newStudent.branch"
                                            :items="formattedBranches"
                                            item-value="id"
                                            item-text="displayText"
                                            hide-details
                                        >
                                            <template #label>
                                                {{$t('Branch')}}<strong class="red--text">* </strong>
                                            </template>
                                        </v-combobox>
                                    </v-col>
                                </v-row>
                                <v-row style="text-align: right;">
                                    <div >
                                        <ul v-for="error,index in errors" :key="error+index" style="color:red;list-style-type: none;">
                                            <li v-for="e,index in error" :key="index">{{ e }}</li>
                                        </ul>
                                    </div>
                                    <v-col>
                                        <v-btn :disabled="!newStudent.branch||!newStudent.student_name" outlined color="primary" @click="submit">
                                            {{ $t('Add') }} 
                                        </v-btn>
                                    </v-col>
                                </v-row>
                        
                            </v-card-text>
                        
                        </v-card>
                    </v-col>
                </v-row> -->
            </v-col>
            <v-col cols="12" md="4" order-md="2" order="1">
                <v-row>
                    <v-col>
                        <Parent :parentReferral="parentFilterForm">
                        </Parent>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <Contact :form="parentFilterForm" :referral_processes_list="referral_processes"></Contact>
                    </v-col>
                   
                </v-row>
                
            </v-col>
        </v-row>
     
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
import Student from './Student.vue';
import Parent from './Parent.vue';
import Contact from './Contact.vue';
import axios from "axios";
import { relativeTimeThreshold } from "moment";
export default {
    mixins: [validationMixin],
    props: {
    },
    components: {
        Snackbar,Tooltip,Student,Parent,Contact
    },
 
    data() {
        return {
            overlay: false,
            errors:[],
            title: "Referral "+this.$route.params.referral_id,
            snackbar: false,
            snackColor: "",
            message: "",
            loading:false,
            branches:[],
            programs:[],
            statuses:[],
            schools:[],
            overlay: false,
            form:null,
            breadcrumbs:[
                {
                text: this.$t('Dashboard'),
                disabled: false,
                to: '/dashboard',
                },
                {
                text: this.$t('Referral'),
                disabled: false,
                to: '/referrals',
                exact: true
                },
                {
                text: this.$t('Show'),
                disabled: true,
                },
           
            ],
            referral_processes:[],
            newStudent:{
                student_name:null,
                branch_id:null,
            }
        };
    },
    computed: {
        parentFilterForm(){
            return {
                'id':this.form.id,
                'parent_name':this.form.parent_name,
                'parent2_name':this.form.parent2_name,
                'phone':this.form.phone,
                'phone2':this.form.phone2,
            };
        },
        formattedBranches() {
            return this.branches.map(item => ({
                    ...item,
                    displayText: `${this.schools.find(e=>e.id==item.school_id).code} - ${item.code}`,
            }));
        }
    },
    created(){
        this.initialize();
    },
    methods: {
        initialize() {
            this.loading=true;
                return new Promise((resolve, reject) => {
                axios
                .get("/api/referrals/"+this.$route.params.referral_id+'/show')
                .then(response => {

                    console.log(response.data);
                    this.form=response.data.referral;
                    this.branches=response.data.branches;
                    this.schools=response.data.schools;
                    this.programs=response.data.programs;
                    this.statuses=response.data.statuses;
                    this.referral_processes=response.data.referral_processes;
                    //console.log(response);
                    setTimeout(() => {
                        console.log(this.form);
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
            this.newStudent.parent_referral_id=this.form.id;
            this.newStudent.parent2_name=this.form.parent2_name;
            this.newStudent.parent_name=this.form.parent_name;
            this.newStudent.phone=this.form.phone;
            this.newStudent.phone2=this.form.phone2;
            axios
            .post("/api/referrals", this.newStudent)
            .then((response) => {
                //console.log(response);
                if (response) {
                    this.initialize();
                    this.newStudent.student_name='';
                    this.newStudent.branch=null;
                }
            })
            .catch((error) => {
                //console.log(error);
                if(error.response){
                    this.loading = false
                    this.errors = error.response.data.errors;
                
                }else{
                
                }
            });
        },
       
    },
    watch: {},
};
</script>
