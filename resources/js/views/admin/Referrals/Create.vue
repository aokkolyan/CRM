<template>

            <!-- Add Referral-->
            <v-card flat tile>
                <v-toolbar
                    color="deep-purple accent-4"
                    dark
                > <v-toolbar-title>Add Referral</v-toolbar-title> </v-toolbar>
                    <form @submit.prevent="submit">
                        <v-card-title>
                            Parent Information
                        </v-card-title>
                        <v-container fluid>
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
                        </v-container>
                        <v-card-title>
                            Student Info
                        </v-card-title>
                        <v-container fluid>
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
                                    <v-text-field
                                        dense
                                        type="date"
                                        v-model="form.start_date"
                                        label="Start_date"
                                    ></v-text-field>
                                    <div v-if="errors.start_date">
                                        <span
                                            style="color: red"
                                            v-for="(error, index) in errors.start_date"
                                            :key="index"
                                            >{{ error }}
                                        </span>
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row>
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
                        </v-container>
                    </form>
                    <v-card-actions>
                        <v-btn color="success" @click="submit"> Add </v-btn>
                        <v-btn @click="clear"> clear </v-btn>
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
        branches:Array(),
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
            title: "Referral",
            snackbar: false,
            snackColor: "",
            message: "",
            form: {
                parent_name: "",
                parent2_name: "",
                phone: '',
                phone2: '',
                start_date : '',
                branch_id : null,
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
    methods: {
        submit() {
            this.$v.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                this.$root.Overlay.on();
                axios
                .post("/api/referrals", this.form)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                        this.$root.Overlay.off();
                        this.$router
                            .push('/referrals/'+response.data.referral.id) 
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
