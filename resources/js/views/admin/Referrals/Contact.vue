<template>
     <v-card flat tile>
        <v-card-title>
            {{ $t('Contact') }}
            <v-spacer></v-spacer>
            <v-btn 
                label="Call"
                icon
                color="primary"
                x-small
                :disabled="form.payment_status_id==2"
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
                            {{$t('At')}}
                        </th>
                        <th>
                            {{$t('Note')}}
                        </th>
                        <th>
                            {{$t('By')}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="rp in referral_processes" :key="rp.id">
                        <td>
                            {{ rp.created_at | formatDateOnly}}
                        </td>
                        <td>
                            {{ rp.remark }}
                        </td>
                        <td>
                            {{ rp.creator }}
                        </td>
                    </tr>
                </tbody>    
            </v-simple-table>
        </v-card-text>
        <v-dialog
        v-model="contactDialog"
        max-width="800"
        >
            <v-card v-if="form">
                <v-card-title>
                    {{$t('Contact')}}
                </v-card-title>
                <v-card-text style="text-align: right;">
                    <v-textarea
                        :label="$t('Remark/Result')"
                        v-model="remark"
                    >

                    </v-textarea>
                    <Tooltip :tooltip="closedWonToolTip">
                        <v-btn
                            color="primary"
                            @click="contact"
                        >
                            {{$t('Note')}}
                        </v-btn>
                    </Tooltip>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>
    
</template>

<script>
import axios from "axios";
import Tooltip from '../../../components/Tooltip.vue';
export default {
    props: {
        form:Object(),
        referral_processes_list:Array(),
    },
    components: {
        Tooltip
    },

    data() {
        return {
            errors:[],
            disableUpdate:true,
            updateLabel:'Update',
            loading:false,
            contactDialog:false,
            referral_processes:this.referral_processes_list,
            
            remark:'',
        };
    },
    computed: {
        closedWonToolTip(){
            return 'You need to update fee first'+' Closed Case, Student accepts proposal';
        } 
      
    },
    methods: {
        contact(){
            let data={
                'remark':this.remark,
            }
            axios
                .post("/api/referrals/"+this.form.id+'/contact', data)
                .then((response) => {
                    //console.log(response);
                    if (response) {
                        this.referral_processes=response.data.data;
                        this.remark='';
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
    watch: {

    },
};
</script>
