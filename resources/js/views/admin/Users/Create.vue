<template>
    <div class="m-1" style="margin:auto">
        <div style="max-width:600px;" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Module-->
            <v-card elevation="5">
                <v-card-title> Add User </v-card-title>

                <v-card-text>
                    <div v-if="errors" style="color:red">
                        <span v-for="(err,i) in errors" :key="i">
                            
                            <span style="display:block" v-for="e,index in err" :key="index">
                               {{i}}: {{e}}
                            </span>
                        </span>
                    </div>
                    <form  @submit.prevent="submit">
                        <v-text-field
                            v-model="form.name"
                            :error-messages="nameErrors"
                            :counter="30"
                            label="Name"
                            required
                            @input="$v.form.name.$touch()"
                            @blur="$v.form.name.$touch()"
                        ></v-text-field>
                        <v-text-field
                            v-model="form.user_name"
                            :error-messages="staffErrors"
                            :counter="7"
                            label="Staff Id"
                            required
                            @input="$v.form.user_name.$touch()"
                            @blur="$v.form.user_name.$touch()"
                        ></v-text-field>
                        <v-text-field
                            v-model="form.phone"
                            :error-messages="phoneErrors"
                            :counter="10"
                            label="Phone"
                            required
                            @input="$v.form.phone.$touch()"
                            @blur="$v.form.phone.$touch()"
                        ></v-text-field>
                        <v-text-field
                            v-model="form.email"
                            :error-messages="emailErrors"
                            label="E-mail"
                            required
                            @input="$v.form.email.$touch()"
                            @blur="$v.form.email.$touch()"
                        ></v-text-field>
                        <v-text-field
                            v-model="form.password"
                            :error-messages="passwordErrors"
                            label="Password"
                            required
                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="show1 ? 'text' : 'password'"
                            @click:append="show1 = !show1"
                            @input="$v.form.password.$touch()"
                            @blur="$v.form.password.$touch()"
                        ></v-text-field>
                        <v-combobox
                            v-model="form.role_id"
                            :items="listRole"
                            multiple
                            item-value="id"
                            item-text="name"
                            label="Role"
                            required
                        ></v-combobox>
                        <v-select
                            dense
                            v-model="form.branch_id"
                            :items="branches"
                            item-value="id"
                            item-text="displayText"
                            label="Branch"
                        >
                        </v-select>
                        <v-select
                            dense
                            v-model="form.user_type_id"
                            :items="usertypes"
                            item-value="id"
                            item-text="name_kh"
                            label="User Type"
                        >
                        </v-select>
                        <v-btn
                            color="success"
                            class="mr-4"
                            @click="submit"
                        >
                            submit
                        </v-btn>
                        <v-btn @click="clear">
                            clear
                        </v-btn>
                    </form>
                </v-card-text>
            </v-card>

        </div>
    </div>
</template>

<script>
import { validationMixin } from "vuelidate";
import {
    required,
    maxLength,
    minLength,
    email,
} from "vuelidate/lib/validators";

import axios from "axios";
export default {
    mixins: [validationMixin],
    props: {
        user: Object(),
        listRole:Array(),
        branches:Array(),
    },
    components: {
    },
    validations: {
        form:{
            name: { required, maxLength: maxLength(30) },
            phone: { required, maxLength: maxLength(10) },
            email: { required, email },
            password: { required,minLength: minLength(8) },
            user_name:{ required, maxLength: maxLength(7) },

        }

    },
    data() {
        return {
            show1:false,
            overlay: false,
            errors: {
                name: null,
            },
            form:{
                name: '',
                user_name: '',
                email: '',
                phone:'',
                password:'',
                role_id:[],
                branch_id:null,
            },
            usertypes:[],
            overlay: false,
        };
    },
    mounted() {
        this.initialize();
    },
    computed: {
        nameErrors () {
            const errors = []
            if (!this.$v.form.name.$dirty) return errors
            !this.$v.form.name.maxLength && errors.push('Name must be at most 30 characters long')
            !this.$v.form.name.required && errors.push('Name is required.')
            return errors
        },
        phoneErrors () {
            const errors = []
            if (!this.$v.form.phone.$dirty) return errors
            !this.$v.form.phone.maxLength && errors.push('Phone must be at most 10 characters long')
            !this.$v.form.phone.required && errors.push('Phone is required.')
            return errors
        },
        staffErrors () {
            const errors = []
            if (!this.$v.form.user_name.$dirty) return errors
            !this.$v.form.user_name.maxLength && errors.push('User Name must be at most 7 characters long')
            !this.$v.form.user_name.required && errors.push('User Name is required.')
            return errors
        },
        emailErrors () {
            const errors = []
            if (!this.$v.form.email.$dirty) return errors
            !this.$v.form.email.email && errors.push('Must be valid e-mail')
            !this.$v.form.email.required && errors.push('E-mail is required')
            return errors
        },
        passwordErrors () {
            const errors = []
            if (!this.$v.form.password.$dirty) return errors
            !this.$v.form.password.minLength && errors.push('Password must be at least 8 characters long')
            !this.$v.form.password.required && errors.push('Password is required')
            return errors
        },
       
    },
    methods: {
        initialize() {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/register")
                    .then(response => {
                    //   console.log(response);
                      this.errors = null;
                       this.usertypes = response.data.usertypes;
                    //    console.log(this.usertypes);
                        setTimeout(() => {
                            this.loading = false;
                            resolve(response);
                        }, 100);
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
            });
        },
        submit() {
            this.$v.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                this.$root.Overlay.on();
                axios
                    .post("/api/users", this.form)
                    .then((response) => {

                        // console.log(response);
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
            }

        },
        clear() {
            this.$v.form.$reset();
            this.form.name = "";
            this.form.phone = "";
            this.form.password = "";
            this.form.email = '';
            this.form.password = '';
            this.form.user_name = '';
            this.form.role_id = [];
            this.form.user_type_id = "";
        },
    },
    watch: {},
};
</script>

