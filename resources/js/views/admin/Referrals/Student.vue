<template>
    <v-card flat tile>
        <v-card-title>
            {{ $t('Student Info') }}
            <v-spacer></v-spacer>
              
            <v-speed-dial
                direction="left"
                speed-dial-padding="1px"
                style="background-color: white;"
            >
                <template v-slot:activator>
                    <v-btn
                        small
                        rounded
                        elevation="0"
                        v-if="form.referral_status_id!=null"
                        class="ma-2"
                        :color="statuses.find(x=>x.id==form.referral_status_id).color+'2F'"
                    >
                        <span :style="{color:statuses.find(x=>x.id==form.referral_status_id).color}">
                            {{statuses.find(x=>x.id==form.referral_status_id).name_en}}
                        </span>
                        
                    </v-btn>
                </template>
                <div
                    v-for="status in statusesFiltered"
                    :key="status.id"
                    style="background-color: white;"
                >
                    <v-btn
                        
                        :disabled="status.name_en=='Enrolled'&&(form.fee==0||!form.fee)"
                        dark
                        elevation="3"
                        small
                        rounded
                        :color="statuses.find(x=>x.id==status.id).color+'2F'"
                        class="hover-grow"
                        @click="form.referral_status_id=status.id"
                    >
                        <span :style="{color:statuses.find(x=>x.id==status.id).color}">
                            {{statuses.find(x=>x.id==status.id).name_en}}
                        </span>
                    </v-btn>
                </div>
               
            </v-speed-dial>
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col>
                    <v-text-field
                        dense
                        v-model="form.student_name"
                        :counter="30"
                        :label="$t('Student Name')"
                        hide-details
                    ></v-text-field>
                </v-col>
                <v-col>
                    <v-combobox
                        dense
                        v-model="form.branch"
                        :items="branches"
                        item-value="id"
                        item-text="displayText"
                        :label="$t('Branch')"
                        hide-details
                    >
                    </v-combobox>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-select
                        dense
                        v-model="form.program_id"
                        :items="programs"
                        item-value="id"
                        :item-text="$isKh()?'name_kh':'name_en'"
                        :label="$t('Program')"
                        hide-details
                    ></v-select>
                </v-col>
                <v-col>
                    <v-text-field
                        dense
                        v-model="form.level"
                        :label="$t('Level')"
                        hide-details
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-select
                        dense
                        v-model="form.shift"
                        :items="shifts"
                        :label="$t('Shift')"
                        hide-details
                    ></v-select>
                </v-col>
                <v-col>
                    <v-text-field
                        dense
                        type="date"
                        v-model="form.start_date"
                        :label="$t('Start Date')"
                        hide-details
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        dense
                        type="number"
                        v-model="form.fee"
                        :label="$t('Net Tuition Fee')+(form.is_riel==true?'(KHR)':'(USD)')"
                    ></v-text-field>
                    <!-- <div v-if="form.fee">
                        <span
                            style="color: green"
                            >{{ form.fee | currency(form.is_riel?'KHR':'USD')  }}
                        </span>
                    </div> -->

                </v-col>
            
                <v-col>
                    <v-text-field
                        dense
                        type="number"
                        v-model="form.discount_percentage"
                        :label="$t('Discount')"
                        min="0"
                        max="100"
                        suffix="%"
                        hide-details
                    ></v-text-field>
                </v-col>
            
            
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field
                        dense
                        type="number"
                        v-model="form.full_fee"
                        :label="$t('Receipt Amount')+(form.is_riel==true?'(KHR)':'(USD)')"
                        hide-details
                    ></v-text-field>

                </v-col>
                <v-col >
                    <!-- <v-text-field
                        v-if="form.commission!=null"
                        color="green"
                        v-bind:readonly="true"
                        dense
                        type="number"
                        v-model="form.commission"
                        :label="form.commission_type=='cash'?($t('Commission')+(form.is_riel==true?'(KHR)':'(USD)')):$t('Commission')+'Pts' "
                    ></v-text-field> -->
                    <v-text-field
                        v-if="form.commission_type == 'cash'"
                            color="green"
                            v-bind:readonly="true"
                            dense
                            type="number"
                            v-model="form.commission"
                            :label="form.commission_type=='cash'?($t('Commission')+(form.is_riel==true?'(KHR)':'(USD)')):$t('Commission')+'Pts' "
                        >
                    </v-text-field>
                    <v-text-field
                        v-if="form.commission_type == 'point'"
                            color="green"
                            v-bind:readonly="true"
                            dense
                            type="number"
                            v-model="form.commission"
                            :label="form.commission_type=='cash'?($t('Commission')+(form.is_riel==true?'(KHR)':'(USD)')):$t('Commission')+'(Points)' "
                    ></v-text-field>
                    <v-text-field
                        v-if="form.commission_type == 'coupon'"
                            color="green"
                            v-bind:readonly="true"
                            dense
                            type="number"
                            v-model="form.commission"
                            :label="form.commission_type=='cash'?($t('Commission')+(form.is_riel==true?'(KHR)':'(USD)')):$t('Commission')+'-'+'(Coupon)'"
                    ></v-text-field>
                  
                    <!-- <div v-if="form.commission!=null">
                        <label >Commission</label><br>
                       
                        <span
                            v-if="form.commission_type == 'cash'"
                            style="color: green"
                            >{{ form.commission | currency(form.is_riel?'KHR':'USD')  }}
                        </span>
                        
                        <span
                            v-else-if="form.commission_type == 'point'"
                            style="color: green"
                            >{{ form.commission }} Points
                        </span>
                        <span
                        v-else
                        style="color: green"
                        >{{ form.commission }} coupon %</span>
                        
                    </div> -->

                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-list dense v-if="form&&form.attachments.length>0">
                        <v-list-item dense v-for="att in form.attachments" :key="att.id">
                            <a :href="'/storage/'+att.path">{{ att.name }}</a>
                            <v-list-item-action dense>
                                <v-btn x-small icon>
                                    <v-icon color="grey lighten-1">mdi-information</v-icon>
                                </v-btn>
                            </v-list-item-action>
                        </v-list-item>
                    </v-list>
                    <v-list v-else>
                        No Attachments
                    </v-list>
                </v-col>
                <v-col style="text-align-last: right;">
                    <div >
                        <ul v-for="error,index in errors" :key="error+index" style="color:red;list-style-type: none;">
                            <li v-for="e,index in error" :key="index">{{ e }}</li>
                        </ul>
                    </div>
                    <Tooltip tooltip="Upload File">
                        <v-file-input
                            name="file"
                            placeholder="Ex: Invoice...."
                            v-model="formPayment.file"
                            @change="uploadAttachment(form.id)"
                            hide-input
                            label="Upload"
                            style="display:inline-flex"
                        ></v-file-input>
                    </Tooltip>
                      
                    <v-btn
                        :disabled="!form.can.update||disableUpdate"
                        color="primary"
                        @click="submit"
                        :loading="loading"
                        :outlined="updateLabel==$t('Saved')"
                    >
                        {{ updateLabel }}
                    
                    </v-btn>
                </v-col>
            </v-row>
        
        </v-card-text>
        <v-card-actions>
          
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

import Tooltip from '../../../components/Tooltip.vue';
import axios from "axios";
export default {
    mixins: [validationMixin],
    props: {
        student: Object(),
        branches:Array(),
        statuses:Array(),
        programs:Array(),
    },
    components: {
        Snackbar,Tooltip
    },
    validations: {

    },
    data() {
        return {
            errors:[],
            form: this.student,
            shifts:['Morning','Afternoon','Evening','Switch AM','Switch PM','Full Day'],
            loading:false,
            updateLabel:this.$t('Update'),
            disableUpdate:true,
            formPayment:{
                file:null,
            },
        };
    },
    computed: {
        statusesFiltered(){
            return this.statuses.filter(x=>x.id!=this.form.referral_status_id)??this.statuses;
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
            this.loading=true;
            axios
            .put("/api/referrals/"+this.form.id, this.form)
            .then((response) => {
                //console.log(response);
                if (response) {
                    
                    this.form=response.data.data;
                    console.log('beforeChange');
                    setTimeout(() => {

                   
                        this.loading = false
                        this.updateLabel=this.$t('Saved')
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
        lightenHexColor(hexColor, amount) {
            // Remove the '#' symbol from the hex color
            const hex = hexColor.replace('#', '');
            
            // Convert the hex color to RGB
            const rgb = {
                r: parseInt(hex.substr(0, 2), 16),
                g: parseInt(hex.substr(2, 2), 16),
                b: parseInt(hex.substr(4, 2), 16)
            };
            
            // Lighten the RGB color by increasing the values
            const lightenRGB = {
                r: rgb.r + amount,
                g: rgb.g + amount,
                b: rgb.b + amount
            };
            
            // Convert the RGB color back to hex
            const lightenHex = `#${(lightenRGB.r).toString(16)}${(lightenRGB.g).toString(16)}${(lightenRGB.b).toString(16)}`;
            
            return lightenHex;
        },
        validateDiscount(){
          
        },
        uploadAttachment(id){
            const formData = new FormData();
            formData.append('file', this.formPayment.file);
            // axios.post('api/user/import',formData,{
            //     headers: {
            //         'Content-Type': 'multipart/form-data'
            //     }
            // })
            axios.post('/api/commissions/'+id+'/import',formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                //console.log(response);
                if (response) {
                    this.errors = null;
                    this.form.attachments=response.data.attachments;
                }
            })
            .catch((error) => {
                //console.log(error);
                if(error.response){
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
                this.updateLabel=this.$t('Update');
            }
            
        },
        'form.discount_percentage'(val){
            if(this.form.discount_percentage>100){
                this.form.discount_percentage=100;
                console.log(this.form.discount_percentage);
            }
        }
    },
};
</script>
<style>
    .v-speed-dial__list,
    .v-speed-dial--direction-left {
        padding:0 !important;
    }
    .hover-grow {
        background:white;
    transition: transform 0.3s;
    }

    .hover-grow:hover {
    transform: scale(1.2);
    }
</style>