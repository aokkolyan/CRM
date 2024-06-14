<template >
           
            <v-card elevation="5">
                <v-card-title v-if="!map"> Add ReferralStatus </v-card-title>
                <v-card-title v-else> Edit ReferralStatus </v-card-title>
                <v-card-text>
                    <form @submit.prevent="submit" >
                        <v-text-field
                            v-model="form.name"
                            :error-messages="nameEnErrors"
                            :counter="30"
                            label="Name"
                            required
                            @input="$v.form.name.$touch()"
                            @blur="$v.form.name.$touch()"
                        ></v-text-field>
                        <v-row>
                            <v-col
                            cols="12"
                            md="6"
                            >
                        <v-text-field
                            v-model="form.name_branch_manager"
                            :counter="30"
                            label="Name branch manager"
                            @input="$v.form.name_branch_manager"
                        ></v-text-field>
                        </v-col>
                        <v-col
                        cols="12"
                        md="6"
                        >
                        <v-text-field
                            v-model="form.cultural_level"
                            label="Grade Level"
                            @input="$v.form.cultural_level"
                        ></v-text-field>
                        </v-col>
                    </v-row>
                        <div v-if="errors.name">
                            <span
                                style="color: red"
                                v-for="(error, index) in errors.name"
                                :key="index"
                                >{{ error }}
                            </span>
                        </div>
                        <!-- <v-text-field
                            v-model="form.specail_program"
                            label="Special Program"
                            @input="$v.form.specail_program"
                           
                        ></v-text-field> -->
                        <v-text-field
                            v-model="form.address"
                            label="Address"
                            @input="$v.form.address"
                        ></v-text-field>
                        <v-row>
                    <v-col
                        cols="12"
                        md="12">
                        <v-text-field
                           placeholder="Facebook , Instragram..."
                            v-model="form.social_media"
                            label="Social Media"
                            @input="$v.form.social_media"
                        ></v-text-field>
                    </v-col>
                    </v-row>
                       
                        <v-text-field
                        width="300" 
                            v-model="form.other"
                            label="Others"
                            @input="$v.form.other"
                        ></v-text-field>
                      
                        <v-radio-group
                            v-model="form.icon_id"
                            row
                        >
                            <v-radio
                                v-for="att in mapIcons"
                                :key="att.id"
                                :label="att.name"
                                :value="att.id"
                                
                               
                            >
                                <template v-slot:label>
                                  <div class="tooltip-wrap">
                                    <img :src="'/storage/'+att.path"  >
                                    <div class="tooltip-content">
                                          {{ att.name.split('.')[0] }}
                                    </div> 
                                  </div>
                                 </template>
                            </v-radio>
                           
                        </v-radio-group>
                      
                        <div v-if="errors.icon">
                            <span
                                style="color: red"
                                v-for="(error, index) in errors.icon"
                                :key="index"
                                >{{ error }}
                            </span>
                        </div>
                        <v-card-text>
                            <v-file-input
                                v-model="file"
                                label="Select an image (PNG) with transparent background only"
                                :accept="acceptTypes"
                            ></v-file-input>
                            <v-btn @click="upload">
                                Upload New Icon
                            </v-btn>
                        </v-card-text>
                      
                        <v-text-field
                            v-model="form.position"
                            :counter="100"
                            label="Position"
                            :error-messages="positionErrors"
                            @input="$v.form.position.$touch()"
                            @blur="$v.form.position.$touch()"
                        ></v-text-field>
                        <div v-if="errors.position">
                            <span
                                style="color: red"
                                v-for="(error, index) in errors.position"
                                :key="index"
                                >{{ error }}
                            </span>
                        </div>
                        <v-select
                                label="Branch"
                                v-model="form.branch_id"
                                :items="branches"
                                item-value="id"
                                item-text="displayText"
                                :error-messages="branchErrors"
                                required
                                @input="$v.form.branch_id.$touch()"
                                @blur="$v.form.branch_id.$touch()"
                        >
                        </v-select>
                        <div v-if="errors.branch_id">
                            <span
                                style="color: red"
                                v-for="(error, index) in errors.branch_id"
                                :key="index"
                                >{{ error }}
                            </span>
                        </div>
                        <v-checkbox style="margin: 10px; "
                            label="Competitor"
                            color="success"
                            hide-details
                            v-model="form.is_competitor"
                        ></v-checkbox>
                        
                    </form>
                    <v-btn v-if="!map" color="success" @click="submit" style="margin-top: 10px;"> Add </v-btn>
                    <v-btn v-else color="success" @click="update"  style="margin-top: 10px;"> Update </v-btn>
                    <v-btn outlined @click="clear"  color="error"  style="margin-top: 10px;"> clear </v-btn>
                </v-card-text>
         
            </v-card>
</template>
<style>
    .tooltip-wrap {
    position: relative;
    }
    .tooltip-wrap .tooltip-content {
    display: none;
    color: #0d1210;
    position: absolute;
    font-family:sans-serif;
    bottom: 80%;
    background-color:rgba(216, 195, 208, 0.01);
    padding: .5em;
    min-width: 5rem;
    }
    .tooltip-wrap:hover .tooltip-content {
    display: block;
    }
</style>
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
        branches: Array(),
        icons:Array(),
        map:Object(),
    },
    components: {
        Snackbar,
    },
    validations: {
        form: {
            name: { required, maxLength: maxLength(50) },
            position: { required},
            branch_id: { required},
        },
    },
    data() {
        return {
            overlay: false,
            upHere: false,
            errors: {
                name: null,
                icon: null,
            },
            title: "ReferralStatus",
            snackbar: false,
            snackColor: "",
            message: "",
            form: this.map?{...this.map}:{
                name: "",
                icon : '',
                position : '',
                branch_id:null,
                is_competitor:false,
            },
            overlay: false,
            file:null,
            acceptTypes: "image/jpeg,image/png",
            mapIcons:this.icons,
          
           
            
        };
    },
    computed: {
        nameEnErrors() {
            const errors = [];
            if (!this.$v.form.name.$dirty) return errors;
            !this.$v.form.name.maxLength && errors.push("Name must be at most 30 characters long");
            !this.$v.form.name.required && errors.push("Name is required.");
            return errors;
        },
        positionErrors() {
            const errors = [];
            if (!this.$v.form.position.$dirty) return errors;
            !this.$v.form.position.required && errors.push("Position is required.");
            return errors;
        },
        branchErrors() {
            const errors = [];
            if (!this.$v.form.branch_id.$dirty) return errors;
            !this.$v.form.branch_id.required && errors.push("Branch is required.");
            return errors;
        },
        
       

        iconErrors() {
            const errors = [];
            if (!this.$v.form.icon.$dirty) return errors;
            !this.$v.form.icon.maxLength && errors.push("icon must be at most 30 characters long");
            return errors;
        },
    },
    methods: {
        submit() {
            this.$v.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            this.form.lat=this.form.position.split(',')[0];
            this.form.long=this.form.position.split(',')[1];
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                axios
                .post("/api/mapLocations", this.form)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.$emit("refresh");
                      
                    
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
        update() {
            this.$v.$touch();
            let isFormCorrect = this.$v.form.$invalid;
            this.form.lat=this.form.position.split(',')[0];
            this.form.long=this.form.position.split(',')[1];
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) {
                axios
                .put("/api/mapLocations/"+this.form.id,this.form)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.$emit("refresh");
                      
                    
                      // this.clear();
                        
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
        clear() {
            this.$v.form.$reset();
            this.form.name='';
            this.form.name_branch_manager = '',
            this.form.cultural_level= '',
            this.form.price_program='',
            this.form.specail_program='',
            this.form.address='',
            this.form.qaunity_staff='',
            this.form.quantity_student='',
            this.form.social_media='',
            this.form.other='',
            this.form.icon='';
            this.form.position='';
            this.form.branch_id=null;
        },
        upload(){
            const formData = new FormData();
            formData.append('file', this.file);
            axios.post('api/mapLocations/uploadIcon',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                //console.log(response);
                if (response) {
                    console.log(response);
                    this.mapIcons=response.data.icons;
                    this.file=null;
                    
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
    watch: {
        map:{
            handler(newMap){
                console.log(newMap)
                if(!newMap){
                    this.clear();
                }else{
                    this.form=this.map;
                }
            }
        },

    },
};

</script>
