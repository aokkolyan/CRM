<template>

            <!-- Add Referral-->
            <v-card flat tile>
                <v-toolbar
                    color="deep-purple accent-4"
                    dark
                > 
                    <v-toolbar-title>{{ $t('Add Referral') }}</v-toolbar-title> 
                    <v-spacer></v-spacer>
                    <v-btn icon @click="$emit('closeDialog')">
                        <v-icon>
                            mdi-close
                        </v-icon>
                    </v-btn>
                </v-toolbar>
                    <form @submit.prevent="submit">
                        <v-container fluid v-if="canCreateForOthers">
                            <v-row>
                                <v-col>
                                    <v-radio-group
                                        v-model="createFor"
                                        column
                                        dense
                                        hide-details
                                    >
                                        <v-row >
                                            <v-col>
                                                <v-radio
                                                    label="For Yourself"
                                                    color="blue"
                                                    value="you"
                                                    hide-details
                                                ></v-radio>
                                            </v-col>
                                            <v-col>
                                                <v-radio
                                                    label="For Others"
                                                    color="red darken-3"
                                                    value="others"
                                                    hide-details
                                                ></v-radio>
                                            </v-col>
                                        
                                        </v-row>
                                    </v-radio-group>
                                </v-col>
                            </v-row>
                            <v-row v-if="createFor=='others'">
                                <v-col>
                                    <v-combobox
                                        v-model="form.forUser"
                                        :items="users"
                                        label="For User"  
                                        item-value="id"
                                        item-text="name"
                                        hide-details
                                    >
                                        
                                    </v-combobox>
                                </v-col>
                            </v-row>
                        </v-container>
                     
                        
                        <v-card-title>
                            {{ $t('Parent Information') }}
                            
                       
                        </v-card-title>
                        <v-container fluid>
                            <v-row>
                                <v-col>
                                    <v-text-field
                                        dense
                                        v-model="form.parent_name"
                                        :error-messages="parentNameErrors"
                                        :counter="30"
                                        required
                                        @input="$v.form.parent_name.$touch()"
                                        @blur="$v.form.parent_name.$touch()"
                                    >
                                        <template #label>
                                            {{$t('Parent Name')}}<strong class="red--text">* </strong>
                                        </template>
                                    </v-text-field>
                                    <div v-if="errors.parent_name">
                                        <span
                                            style="color: red"
                                            v-for="(error, index) in errors.parent_name"
                                            :key="index"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </v-col>
                                <v-col>
                                    <v-text-field
                                        dense
                                        v-model="form.phone"
                                        :error-messages="phoneErrors"
                                        inputmode="nurmeric"
                                        type="number"
                                        :counter="10"
                                        required
                                        @input="$v.form.phone.$touch()"
                                        @blur="$v.form.phone.$touch()"
                                    >
                                        <template #label>
                                            {{$t('Phone')}}<strong class="red--text">* </strong>
                                        </template>
                                    </v-text-field>
                                    <div v-if="errors.phone">
                                        <span
                                            style="color: red"
                                            v-for="(error, index) in errors.phone"
                                            :key="index"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </v-col>
                            </v-row>
                            <!-- <v-row>
                                <v-col>
                                    <v-text-field
                                        dense
                                        v-model="form.parent2_name"
                                        :counter="30"
                                        :label="$t('Parent2 Name')"
                                    ></v-text-field>
                                    <div v-if="errors.parent2_name">
                                        <span
                                            style="color: red"
                                            v-for="(error, index) in errors.parent2_name"
                                            :key="index"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </v-col>
                                <v-col>
                                    <v-text-field
                                        dense
                                        v-model="form.phone2"
                                        inputmode="nurmeric"
                                        type="number"
                                        :counter="10"
                                        :label="$t('Phone')"
                                    ></v-text-field>
                                    <div v-if="errors.phone2">
                                        <span
                                            style="color: red"
                                            v-for="(error, index) in errors.phone2"
                                            :key="index"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </v-col>
                            </v-row> -->
                        </v-container>
                        <v-card-title>
                            {{ $t('Student Info') }}
                        </v-card-title>
                        <v-container fluid>
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
                            <v-row v-for="student,index in form.students" :key="index">

                                <v-col>

                                    <v-text-field
                                        dense
                                        v-model="student.student_name"
                                        :counter="30"
                                        hide-details
                                    >
                                        <template #label>
                                            {{$t('Student Name')}} {{ index+1 }}<strong v-if="index==0"
                                            @change="disableStudent"
                                             class="red--text" >* </strong>
                                           
                                        </template>
                                    </v-text-field>
                                </v-col>
                                <v-col>
                                    <v-select
                                        dense
                                        v-model="student.program_id"
                                        :items="programs"
                                        item-value="id"
                                        :item-text="$isKh()?'name_kh':'name_en'"
                                        :label="$t('Program')"
                                        hide-details
                                    ></v-select>
                                </v-col>
                            </v-row>
                      
                        </v-container>
                    </form>
                    <v-card-actions>
                        
                        <v-btn color="success" 
                       v-show="visableStudent"
                        @click="submitAndClose"
                        > {{ $t('Save') }} </v-btn>
                        <v-btn outlined color="success" @click="submit"> {{ $t('Save/New') }} </v-btn>
                        <v-btn text @click="clear"> {{$t('Clear')}} </v-btn>
                    </v-card-actions>
           
           
            </v-card>
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

import axios from "axios";
export default {
    mixins: [validationMixin],
    props: {
        user: Object(),
        schools:Array(),
        branches:Array(),
        programs:Array(),
        users:Array(),
        canCreateForOthers:Boolean(),
    },
    components: {
        Snackbar,
    },
    validations: {
        form: {
            parent_name: { required, maxLength: maxLength(30) },
            phone: { required, minLength: minLength(9), maxLength: maxLength(10) },
            branch_id: { required},
        },
    },
    data() {
        return {
            visableStudent:true,
            submitAndClos:"",
            overlay: false,
            errors: {
                parent_name: null,
                name_kh: null,
                code: null,
            },
            createFor:'you',
            title: "Referral",
            snackbar: false,
            snackColor: "",
            message: "",
            form: {
                parent_name: "",
                parent2_name: "",
                phone: '',
                phone2: '',
                branch_id : null,
                school_id : null,
                students:[
                    {student_name:'', program_id:null},
                    {student_name:'', program_id:null},
                    {student_name:'', program_id:null},
                ],
                
                forUser:null,
            },
            overlay: false,
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
            const firstChar = this.form.phone.charAt(0);
            if(firstChar!=0)this.form.phone='0'+this.form.phone
            const errors = [];
            if (!this.$v.form.phone.$dirty) return errors;
            !this.$v.form.phone.minLength && errors.push("Phone Number must be at least 9 digits long");
            !this.$v.form.phone.maxLength && errors.push("Phone Number must be at most 10 digits long");
            !this.$v.form.phone.required && errors.push("Phone is required.");
            return errors;
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
    methods: {
        submit() {
            this.$v.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                this.$root.Overlay.on();
                axios
                .post("/api/myReferrals", this.form)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                        this.$root.Overlay.off();
                        // this.clear();
                        this.$emit('refresh');
                        // this.$router
                        // .push('/myReferrals/'+response.data.referral.id) 
                        // .catch((error) => {
                        //     console.error(error);
                        // });;
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
            }
        },
        submitAndClose(){
            this.submit();
            this.clear();
            this.$emit('closeDialog');
        },
        clear() {
            this.$v.form.$reset();
            this.form.parent_name='';
            this.form.phone='';
            this.form.parent2_name='';
            this.form.phone2='';
            this.form.student_name='';
            this.form.students.forEach(element => {
                element.student_name=''
                element.program_id=null

            });
        },
        disableStudent(event) {
            if(form.students !== null) {
                console.log('Hello');
            }
        }
       
    
    },
    watch: {
        createFor:function(){
            if(this.createFor=='you'){
                this.form.forUser=null;
            }
        },
    //    form:{
    //         deep: true,
    //         handler(newForm) {
    //             console.log('abc');
    //             this.disableSave=false;
               
    //         }
    //     },
    //     student:{
    //         deep: true,
    //         handler(newForm) {
    //             console.log('abc');
    //             this.disableSave=false;
               
    //         }
    //     }
    },
};
</script>
