<template>
    <v-container>
        <h2 class="font-semibold text-xl text-gray-800 referraling-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <v-card :loading="loading" :disabled="(this.form.referral_status_id!==null)">
            <v-card-title>
                Parent's Info
                <v-spacer></v-spacer>
                <v-chip v-if="(this.form.referral_status_id==null)">
                    Draft
                </v-chip>
                <v-chip
                    v-else
                    class="ma-2"
                    :color="statuses.find(x=>x.id==form.referral_status_id).color+'2F'"
                    :text-color="statuses.find(x=>x.id==form.referral_status_id).color"
                >
                    {{statuses.find(x=>x.id==form.referral_status_id).name_en}}
                </v-chip>
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col>
                        <v-text-field
                            dense
                            v-model="form.parent_name"
                            :error-messages="parentNameErrors"
                            :counter="30"
                            label="Parent Name*"
                            required
                            @input="$v.form.parent_name.$touch()"
                            @blur="$v.form.parent_name.$touch()"
                        ></v-text-field>
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
                            :counter="10"
                            label="Phone*"
                            required
                            @input="$v.form.phone.$touch()"
                            @blur="$v.form.phone.$touch()"
                        ></v-text-field>
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
                <v-row>
                    <v-col>
                        <v-text-field
                            dense
                            v-model="form.parent2_name"
                            :counter="30"
                            label="Parent2 Name"
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
                            :counter="10"
                            label="Phone2"
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
                </v-row>
                
            </v-card-text>
            <v-card-title>
                Student Info
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col>
                        <v-text-field
                            dense
                            v-model="form.student_name"
                            :counter="30"
                            label="Student_name"
                        ></v-text-field>
                        <div v-if="errors.student_name">
                            <span
                                style="color: red"
                                v-for="(error, index) in errors.student_name"
                                :key="index"
                                >{{ error }}
                            </span>
                        </div>
                    </v-col>
                    <v-col>
                        <v-select
                            dense
                            v-model="form.branch_id"
                            :items="branches"
                            item-value="id"
                            item-text="code"
                            label="Branch"
                            :error-messages="branchErrors"
                            @input="$v.form.branch_id.$touch()"
                            @blur="$v.form.branch_id.$touch()"
                        ></v-select>
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
            <v-card-actions >
                <v-btn
                    color="primary"
                    @click="submit"
                    v-if="(this.form.referral_status_id==null)"
                >
                    Submit
                </v-btn>
            </v-card-actions>
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

import axios from "axios";
export default {
    mixins: [validationMixin],
    props: {
    },
    components: {
        Snackbar,
    },
    validations: {
        form: {
            parent_name: { required, maxLength: maxLength(30) },
            phone: { required, maxLength: maxLength(30) },
            branch_id: { required},
        },
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
            form: {
                parent_name: "",
                parent2_name: "",
                phone: '',
                phone2: '',
                branch_id : null,
                referral_status_id : null,
            },
            overlay: false,
            breadcrumbs:[
                {
                text: this.$t('Dashboard'),
                disabled: false,
                to: '/dashboard',
                },
                {
                text: this.$t('My Referral'),
                disabled: false,
                to: '/myReferrals',
                exact: true
                },
                {
                text: this.$t('Show'),
                disabled: true,
                },
           
            ]
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
                .get("/api/myReferrals/"+this.$route.params.referral_id)
                .then(response => {

                    console.log(response.data);
                    this.form=response.data.referral;
                    this.branches=response.data.branches;
                    this.statuses=response.data.statuses;
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
            this.$v.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                this.$root.Overlay.on();
                axios
                .put("/api/myReferrals/"+this.$route.params.referral_id, this.form)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                        this.$root.Overlay.off();
                        this.$router
                            .push('/myReferrals') 
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
                        this.$root.Overlay.off();
                    }else{
                        this.$root.Snackbar.show({message: error.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }
                });
            }
        },
        clear() {
            this.$v.form.$reset();
            this.form.parent_name='';
            this.form.name_kh='';
            this.form.code='';
            this.form.description='';
        },
    },
    watch: {},
};
</script>
