<template>
      <v-card  flat>
        <v-card-title>
            {{ $t("Parent Information") }}
            
        </v-card-title>
        <v-card-text>
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
                        :label="$t('Parent Name')"
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
            <v-row>
                <v-col style="text-align-last: right;">
                    <div >
                        <ul v-for="error,index in errors" :key="error+index" style="color:red;list-style-type: none;">
                            <li v-for="e,index in error" :key="index">{{ e }}</li>
                        </ul>
                    </div>
                    <!-- <span>Saved</span> -->
                    <v-btn
                        :disabled="form.payment_status_id==2||disableUpdate"
                        color="primary"
                        @click="submit"
                        :loading="loading"
                        :outlined="updateLabel=='Saved'"
                    >
                        {{ updateLabel }}
                    
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
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
        parentReferral:Object(),
    },
    components: {
        Snackbar,
    },
    validations: {
        form: {
            parent_name: { required, maxLength: maxLength(30) },
            phone: { required, maxLength: maxLength(30) },
        },
    },
    data() {
        return {
            errors:[],
            disableUpdate:true,
            updateLabel:'Update',
            loading:false,
            form:this.parentReferral,
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
      
    },
    methods: {
        submit() {
            this.loading=true;
            axios
            .put("/api/parentReferrals/"+this.form.id, this.form)
            .then((response) => {
                //console.log(response);
                if (response) {
                    
                    this.form=response.data;
                    console.log('beforeChange');
                    setTimeout(() => {

                   
                        this.loading = false
                        this.updateLabel='Saved'
                    }, 1000);
                    console.log('afterChange');

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
    watch: {
        form:{
            deep: true,
            handler(newForm) {
                // Watch for changes in the form fields
                console.log('asd');
                this.disableUpdate=false;
                this.updateLabel='Update';
            }
            
        },
    },
};
</script>
<style>
    .v-speed-dial__list,
    .v-speed-dial--direction-left {
        padding:0 !important;
    }
    .hover-grow {
    transition: transform 0.3s;
    }

    .hover-grow:hover {
    transform: scale(1.2);
    }
</style>