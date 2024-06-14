<template>
    <v-container>
        <h2 class="font-semibold text-xl text-gray-800 commissioning-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <v-row>
            <v-col cols="6">
                <v-card :loading="loading">
                    <v-card-title>
                        Parent's Info
                        <v-spacer></v-spacer>
                        <v-chip
                            v-if="form.commission_status_id!=null"
                            class="ma-2"
                            :color="statuses.find(x=>x.id==form.commission_status_id).color+'2F'"
                            :text-color="statuses.find(x=>x.id==form.commission_status_id).color"
                        >
                            {{statuses.find(x=>x.id==form.commission_status_id).name_en}}
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
                    <v-card-title>
                        Program
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col>
                                <v-select
                                    dense
                                    v-model="form.program_id"
                                    :items="programs"
                                    item-value="id"
                                    item-text="name_en"
                                    label="Program"
                                ></v-select>
                                <div v-if="errors.program_id">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.program_id"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                            </v-col>
                            <v-col>
                                <v-text-field
                                    dense
                                    v-model="form.level"
                                    label="Level"
                                ></v-text-field>
                                <div v-if="errors.level">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.level"
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
                                    v-model="form.shift"
                                    :items="shifts"
                                    label="Shift"
                                ></v-select>
                                <div v-if="errors.shift">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.shift"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                            </v-col>
                            <v-col>
                                <v-text-field
                                    dense
                                    type="number"
                                    v-model="form.fee"
                                    label="Fee"
                                ></v-text-field>
                                <div v-if="errors.fee">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.fee"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            @click="submit"
                        >
                            Update
                        
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="6">
                <v-card>
                    <v-card-title>
                        Notes
                        <v-spacer></v-spacer>
                        <v-btn 
                            label="Call"
                            icon
                            color="primary"
                            x-small
                            @click="contactDialog=true"
                        >
                            <v-icon>
                                mdi-cellphone
                            </v-icon>
                        </v-btn>
                    </v-card-title>
                    <v-card-text>
                        <v-simple-table 
                        
                        >
                            <thead>
                                <tr>
                                    <th>
                                        At
                                    </th>
                                    <th>
                                        Note
                                    </th>
                                    <th>
                                        Conacted By
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rp in commission_processes" :key="rp.id">
                                    <td>
                                        {{ rp.created_at }}
                                    </td>
                                    <td>
                                        {{ rp.remark }}
                                    </td>
                                    <td>
                                        {{ rp.creator }}
                                    </td>
                                    <td>
                                        <v-chip
                                            class="ma-2"
                                            :color="statuses.find(x=>x.id==rp.commission_status_id).color+'2F'"
                                            :text-color="statuses.find(x=>x.id==rp.commission_status_id).color"
                                        >
                                            {{statuses.find(x=>x.id==rp.commission_status_id).name_en}}
                                        </v-chip>
                                    </td>
                                </tr>
                            </tbody>    
                        </v-simple-table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog
        v-model="contactDialog"
        max-width="800"
        >
            <v-card>
                <v-card-title>
                    Contact
                </v-card-title>
                <v-card-text>
                    <v-textarea
                        label="Remark/Result"
                        v-model="remark"
                    >

                    </v-textarea>
                    <v-btn
                        color="primary"
                        @click="contact(4)"
                    >
                        Closed Deal Won
                    </v-btn>
                    <v-btn
                        color="primary"
                        outlined
                        @click="contact(3)"
                    >
                        Closed Deal Lost
                    </v-btn>
                    <v-btn
                        plain
                        color="primary"
                        @click="contact(2)"
                    >
                        Still Enquiring
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-dialog>
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
            title: "Commission "+this.$route.params.commission_id,
            snackbar: false,
            snackColor: "",
            message: "",
            loading:false,
            branches:[],
            programs:[],
            statuses:[],
            shifts:['Morning','Afternoon','Evening','Switch AM','Switch PM'],
            form: {
                parent_name: "",
                parent2_name: "",
                phone: '',
                phone2: '',
                commission_status_id:null,
                branch_id : null,
            },
            overlay: false,

            breadcrumbs:[
                {
                text: 'Dashboard',
                disabled: false,
                to: '/dashboard',
                },
                {
                text: 'Commission',
                disabled: false,
                to: '/commissions',
                exact: true
                },
                {
                text: 'Show',
                disabled: true,
                },
           
            ],
            contactDialog:false,
            remark:'',
            commission_processes:[],
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
                .get("/api/commissions/"+this.$route.params.commission_id)
                .then(response => {

                    console.log(response.data);
                    this.form=response.data.commission;
                    this.branches=response.data.branches;
                    this.programs=response.data.programs;
                    this.statuses=response.data.statuses;
                    this.commission_processes=response.data.commission_processes;
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
                .put("/api/commissions/"+this.$route.params.commission_id, this.form)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        console.log(response);
                        this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                        this.$root.Overlay.off();
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
        contact(type){
            let data={
                'commission_status_id':type,
                'remark':this.remark,
            }
            axios
                .post("/api/commissions/"+this.$route.params.commission_id+'/contact', data)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.commission_processes=response.data.commission_processes;
                        console.log(response.data.commission);
                        console.log(this.form);
                        this.form=response.data.commission;
                        this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                        this.$root.Overlay.off();
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
                this.contactDialog=false;
        }
    },
    watch: {},
};
</script>
