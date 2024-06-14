
<template>

    <v-container class="fill-height" fluid
    :style="{
    'background-image': `url(${require('/images/background/bg.png')})`,
    'background-position': 'center center',
    'background-repeat':  'no-repeat',
    'background-attachment': 'fixed',
    'background-size':  'cover',
    }"
    >
    <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="4">
        <v-form action="#" @submit.prevent="register" @refresh="initialize()">
            <v-card class="elevation-12">
            <v-toolbar color="blue-grey" dark flat>
                <v-toolbar-title>
                <!-- <span style="color:red">UAT*** </span> -->
                System Security Register
                </v-toolbar-title>
                <v-spacer />
            </v-toolbar>
            <v-dialog
                v-model="loading"
                hide-overlay
                persistent
                width="300"
            >
                <v-card color="blue-grey" dark>
                <v-card-text>
                    Security Login
                    <v-progress-linear
                    indeterminate
                    color="white"
                    class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
                </v-card>
            </v-dialog>
            
            <v-card-text>
            <div v-if="errors" style="color:red">
                <span v-for="(err,i) in errors" :key="i">
                    <span style="display:block" v-for="e,index in err" :key="index">
                        {{i}}: {{e}}
                    </span>
                </span>
            </div>
           <v-select
                dense
                v-model="form.user_type_id" 
                @change="onChangeStudentId"
                
                required
                :items="usertypes"
                item-value="id"
                :item-text="$isKh()?'name_kh':'name_en'"
                label="User Type"
            >
            </v-select>  
       
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
                v-model="form.student_id"
                :error-messages="nameErrors"
                :counter="30"
                label="Student ID"
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
                v-model="form.password"
                :error-messages="passwordErrors"
                label="Password"
                required
                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show1 ? 'text' : 'password'"
                @click:append="show1 = !show1"
                @input="$v.form.password.$touch()"
                @blur="$v.form.password.$touch()"
            >
            </v-text-field>
         
            <!-- <v-text-field
                v-model="form.email"
                :error-messages="emailErrors"
                label="E-mail"
                required
                @input="$v.form.email.$touch()"
                @blur="$v.form.email.$touch()"
            ></v-text-field> -->

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
            </v-card-text>  
            </v-card>   
        </v-form>
        </v-col>
    </v-row>
    </v-container>
</template>

<script>

import axios from "axios";
import { validationMixin } from "vuelidate";
import {
    required,
    maxLength,
    minLength,
    email,
} from "vuelidate/lib/validators";
export default {

    mixins: [validationMixin],
    name: "register",
    data:() => { 
    return {
        student_id:false,
        show:false,
        overlay: false,
        errors: {
        name: null,
            },
        showStudentId:false,
        hideUsername:true,
        form:{
        name     : "" ,
        name_kh  : "" ,  
        user_name: "",
        password : "",
        phone    : "",
        email    : "",
        role_id  : null,
        user_type_id:null,
        student_id:"",
        },
        snackbar: false,
        show1:false,
        messageError:"",
        loading: false,
        usertypes:[],
    };
    },
    // props:{
    //   usertypes:Object(),
    // },
    mounted() {
        this.initialize();
    },
    validations: {
        form:{
            name: { required, maxLength: maxLength(30) },
            phone: { required, maxLength: maxLength(10) },
            password: { required,minLength: minLength(8) },
            user_type_id:{required},
        }

    },
    
    
    methods: {
    initialize() {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/register")
                    .then(response => {
                        // console.log(response);
                        this.errors = null;
                        this.usertypes = response.data.usertypes;
                        //  console.log(this.usertypes);
                       
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
        onChangeStudentId(event) {
        if (this.form.user_type_id === 2) {
            this.showStudentId = true;
            this.hideUsername = false;
        
            }  else if (this.form.user_type_id === 3) {
            this.showStudentId = true;
            this.hideUsername = false;
            }
            else if (this.form.user_type_id === 4) {
            this.hideUsername = false;
            }

            else {
            this.showStudentId = false;
            }
        },
    submit() {
            this.$v.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                this.$root.Overlay.on();
                axios
                    .post("/api/register", this.form)
                    .then((response) => {
                    // console.log(response);
                    this.errors = null ;
                        this.$store
                        .dispatch("retrieveToken", {'user_name':response.data.user_name,'password':this.form.password}).then(response => {
                    
                            this.loading =  false;
                                console.log(response);
                            if(response.data.status!=200){
                                this.snackbar=true
                                this.messageError=response.data.message;
                            }else{
                                let routeTo= localStorage.getItem('routeTo');
                                console.log(routeTo)
                                if(routeTo&&routeTo!='admin.logout'){
                                this.$router.go({ name: routeTo }).catch(error => {
                                    console.info(error.message)
                                });
                                }else{
                                this.$router.go({ path: "/dashboard" }).catch(error => {
                                    console.info(error.message)
                                });
                                }
                            }
                            
                            }).catch(error=>{
                                console.log(error)
                            });
                    setTimeout(() => {
                        this.loading = false;
                        resolve(response);
                    }, 100);
                    this.$router.push('/login')
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
            this.form.user_type_id = "";
            this.form.name_kh = '';
        },

    },
    computed: {
        nameErrors () {
            const errors = []
            if (!this.$v.form.name.$dirty) return errors
            !this.$v.form.name.maxLength && errors.push('Name must be at most 30 characters long')
            !this.$v.form.name.required && errors.push('Name is required.')
            return errors
        },
        // usernameErrors () {
        //   const errors = []
        //     if (!this.$v.form.user_name.$dirty) return errors
        //     !this.$v.form.user_name.maxLength && errors.push('User Name must be at most 7 characters long')
        //     !this.$v.form.user_name.required && errors.push('User Name is required.')
        //     return errors
        // },
        phoneErrors () {
            const errors = []
            if (!this.$v.form.phone.$dirty) return errors
            !this.$v.form.phone.maxLength && errors.push('Phone must be at most 10 characters long')
            !this.$v.form.phone.required && errors.push('Phone is required.')
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
        // usertypeErrors () {
        //     const errors = []
        //     if (!this.$v.form.user_type_id.$dirty) return errors
        //     !this.$v.form.user_type_id.minLength && errors.push('User type is required')
        //     !this.$v.form.user_type_id.required && errors.push('Password is required')
        //     return errors
        // },
    },
};
</script>
<style>
.hidden {
visibility: hidden;
}
</style>