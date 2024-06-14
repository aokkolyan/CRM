<template>
    <div class="m-1" style="margin:auto">
        <div style="max-width:600px;" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Add Module-->
            <v-row>
                <v-col>
                    <v-card elevation="0">
                <v-card-title> Update Profile </v-card-title>

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
                            v-model="form.name_kh"
                            :counter="30"
                            label="Name Kh"
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
                      
              
                        <v-btn
                            color="success"
                            class="mr-4"
                            :disabled="save=='Saved'"
                            @click="submit"
                        >
                            {{ save }}
                        </v-btn>
                        <v-btn @click="clear">
                            clear
                        </v-btn>
                    </form>
                </v-card-text>
            </v-card>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-card elevation="0">
                        <v-card-title> Change Password </v-card-title>

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
                                    v-model="password.current"
                                    type="password"
                                    :error-messages="currentPasswordErrors"
                                    :counter="30"
                                    label="Current Password"
                                    required
                                    @input="$v.password.currentPassword.$touch()"
                                    @blur="$v.password.currentPassword.$touch()"
                                >
                                    
                                </v-text-field>
                                <v-text-field
                                    v-model="password.new"
                                    type="password"
                                    :error-messages="newPasswordErrors"
                                    :counter="30"
                                    label="New Password"
                                    required
                                    @input="$v.password.newPassword.$touch()"
                                    @blur="$v.password.newPassword.$touch()"
                                >
                                    
                                </v-text-field>
                                <v-btn
                                    color="success"
                                    class="mr-4"
                                    :disabled="savePassword=='Saved'"
                                    @click="changePassowrd"
                                >
                                    {{ savePassword }}
                                </v-btn>
                                <v-btn @click="clearPassword">
                                    clear
                                </v-btn>
                            </form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        

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
    },
    components: {
    },
    validations: {
        form:{
            name: { required, maxLength: maxLength(30) },
            name_kh: {  maxLength: maxLength(30) },
            phone: { required, maxLength: maxLength(10) },
            email: { required, email },
            user_name:{ required, maxLength: maxLength(7) },

        },
        password:{
            current: { required, minLength: minLength(8),maxLength: maxLength(30) },
            new: { required, minLength: minLength(8),maxLength: maxLength(30) },
        }
    },
    data() {
        return {
            show1:false,
            overlay: false,
            errors: {
                name: null,
            },
            save:'Update',
            savePassword:'Update',
            form:{
                name: '',
                name_kh: '',
                user_name: '',
                email: '',
                phone:'',
            },
            password:{
                current:'',
                new:'',
            },
            overlay: false,
        };
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
        currentPasswordErrors () {
            const errors = []
            if (!this.$v.password.current.$dirty) return errors
            !this.$v.password.current.required && errors.push('Current Password is required')
            return errors
        },
        newPasswordErrors () {
            const errors = []
            if (!this.$v.password.new.$dirty) return errors
            !this.$v.password.new.minLength && errors.push('Password must be at least 8 characters long')
            !this.$v.password.new.required && errors.push('New Password is required')
            return errors
        },
    },
    created() {
        this.user = this.$store.state.auth.user;
        this.initialize();
    },
    methods: {
        
        initialize() {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/profile")
                    .then(response => {
                        this.form = response.data;
                
                    })
                    .catch(error => {
                        reject(error);
                    });
            });
        },
        submit() {
            this.$v.form.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                axios.put("/api/users/" + this.user.id, this.form)
                    .then((response) => {
                        //console.log(response);
                        if (response) {
                            this.save='Saved'
                            this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                            
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
        changePassowrd() {
            this.$v.password.$touch();
            let isFormCorrect = this.$v.password.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                axios.put("/api/profile/changePassword", this.password)
                    .then((response) => {
                        //console.log(response);
                        if (response) {
                            this.savePassword='Saved'
                            this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                            
                            this.clearPassword();
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
            this.form.name_kh = "";
            this.form.phone = "";
            this.form.email = '';
            this.form.user_name = '';
        },
        clearPassword() {
            this.$v.password.$reset();
            this.password.current = "";
            this.password.new = "";
        },
    },
    watch: {
        form:{
            deep: true,
            handler(newForm) {
                this.save='Update';
            }
            
        },
        password:{
            deep: true,
            handler(newForm) {
                this.savePassword='Update';
            }
            
        },
    },
};
</script>

