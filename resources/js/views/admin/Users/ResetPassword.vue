<template>
    <v-container>
        <v-card>
            <v-card-title>
                Reset Password
            </v-card-title>
            <v-card-text>
                <v-text-field
                    v-model="form.password"
                    type="password"
                    :error-messages="passwordErrors"
                    :counter="30"
                    label="Password"
                    required
                    @input="$v.form.password.$touch()"
                    @blur="$v.form.password.$touch()"
                >
                    
                </v-text-field>
                <v-text-field
                    v-model="form.confirmPassword"
                    type="password"
                    :error-messages="confirmPasswordErrors"
                    :counter="30"
                    label="Confirm Password"
                    required
                    @input="$v.form.confirmPassword.$touch()"
                    @blur="$v.form.confirmPassword.$touch()"
                >
                    
                </v-text-field>
                <v-btn @click="reset" color="primary">Reset</v-btn>
            </v-card-text>
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
    sameAsPassword,
    sameAs
} from "vuelidate/lib/validators";
import Datatable from "./Datatable.vue";
import Create from "./Create.vue";
import axios from "axios";
export default {
    validations: {
        form:{
        
            password: { required,minLength: minLength(8) },
            confirmPassword:{ sameAsPassword: sameAs('password') },

        }

    },
    components: {
        Datatable,
        Create,
    },
    computed: {
        passwordErrors () {
            const errors = []
            if (!this.$v.form.password.$dirty) return errors
            !this.$v.form.password.minLength && errors.push('Password must be at least 8 characters long')
            !this.$v.form.password.required && errors.push('Password is required.')
            return errors
        },
        confirmPasswordErrors () {
            console.log(this.form.password==this.form.confirmPassword);
            const errors = []
            
            if (!this.$v.form.confirmPassword.$dirty) return errors
            !this.$v.form.confirmPassword.sameAsPassword && errors.push('Password mismatched')
            return errors
        },
    },
    data: () => ({
        tab: null,
        loading:true,
        listRole:[],
        users:[],
        can:[],
        form:{
       
            password:'',
            confirmPassword:'',
        },
    }),
     beforeMount() {
        this.initialize();
    },

    created() {
        this.user = this.$store.state.auth.user;
        this.initialize();
        this.getRoles();
    },

    methods: {
        initialize() {
            this.getRoles();
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/users")
                    .then(response => {
                        this.users = response.data.data;
                        this.can = response.data.can;
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
        getRoles() {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/roles")
                    .then(response => {

                        //console.log('asd');
                        //console.log(response.data.data);
                        this.listRole = response.data.data.roles;
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        reset(){
            this.$v.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
          
            if (!isFormCorrect) {
                axios.post("/api/user/resetPassword", this.form)
                .then(response => {
                    this.$store.dispatch("hasResetPassword");
                    this.$router.push("/dashboard");
                })
               
            }else{
                console.log('error');
            }

        },
    }
}
</script>
