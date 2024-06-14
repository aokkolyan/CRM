<template>       
    <div class="m-1" style="margin:auto">
        <v-card flat>
            
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    :label="$t('Search')"
                    hide-details
                    items-per-page="50"
                ></v-text-field>
                <v-spacer></v-spacer>
            </v-card-title>
            <v-card-title class="d-flex flex-row">
                    <div style="width:150px;margin-right: 2px;">
                        <v-select
                        outlined
                        dense
                        hide-details
                        v-model="filteredBranch"
                        label="Branch"
                        :items="branches"
                        item-text="code"
                        item-value="id"
                        clearable
                        >

                        </v-select>
                    </div>
                    <div style="width:150px;margin-right: 2px;">
                        <v-select
                        outlined
                        dense
                        hide-details
                        v-model="filteredStatus"
                        :items="statuses"
                        label="Status"
                        item-text="name_en"
                        item-value="id"
                        clearable
                        >

                        </v-select>
                    </div>
                <v-spacer></v-spacer>
                <tooltip tooltip="View By Parent">
                    <v-btn @click="view='parent'" :color="view=='parent'?'primary':'secondary'" icon :outlined="view=='parent'" tile>
                        <v-icon>
                                mdi-human-male-female
                        </v-icon>
                    </v-btn>
                </tooltip>
                <tooltip tooltip="View By Children">
                    <v-btn @click="view='children'" :color="view=='children'?'primary':'secondary'" icon :outlined="view=='children'" tile>
                        <v-icon>
                                mdi-baby-face 
                        </v-icon>
                    </v-btn>
                </tooltip>
            
            </v-card-title>
            <v-card-text>
                <v-data-table 
                    :headers="view=='parent'?headers:childrenHeaders"
                    :items="filteredMyReferrals"
                    :loading="loading"
                    :search="search"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                >
                    <template v-slot:[`item.index`]="{ index }">
                            {{ index + 1 }}
                    </template>
                    
                    <template v-slot:[`item.status`]="{ item }">
                        <div v-if="view=='parent'">
                            <v-chip
                                v-for="referral in item.referrals "
                                :key=referral.id
                                class="ma-2"
                                :color="statuses.find(x=>x.id==referral.referral_status_id).color+'2F'"
                                :text-color="statuses.find(x=>x.id==referral.referral_status_id).color"
                            >
                                {{statuses.find(x=>x.id==referral.referral_status_id).name_en}}
                            </v-chip>
                        </div>
                        <v-chip
                            v-else
                            class="ma-2"
                            :color="statuses.find(x=>x.id==item.referral_status_id).color+'2F'"
                            :text-color="statuses.find(x=>x.id==item.referral_status_id).color"
                        >
                            {{statuses.find(x=>x.id==item.referral_status_id).name_en}}
                        </v-chip>
                     
                    </template>
                    <!-- <template v-slot:[`header.status`]="{ header }">
                        {{ header }}
                    </template> -->

                    <template v-slot:[`item.actions`]="{ item }">
                        <router-link :to="'referrals/'+(view=='parent'?item.id:item.parent_referral_id)">
                            <Tooltip
                                tooltip="view"
                            >
                                <v-btn
                                    label="View"
                                    icon
                                    color="primary"
                                    x-small
                                >
                                    <v-icon>
                                        mdi-eye
                                    </v-icon>
                                </v-btn>
                            </Tooltip>
                          
                        </router-link>
                           
                    </template>
                </v-data-table>
               
            </v-card-text>
          

        
        
        </v-card>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength, minLength, email } from 'vuelidate/lib/validators';
    import Table from "@/components/Table.vue"
    
    import Tooltip from '../../../components/Tooltip.vue';
    import axios from "axios";
    export default {
        mixins: [validationMixin],
        props:{
            user:Object(),
            referrals:Array(),
            loading:{
                type:Boolean(),
                default:false   
            },
            can:Array(),
            branches:Array(),
            statuses:Array(),
        },
        components: {
            Table,Tooltip
        },
        validations: {
            form:{
                name_en: { required, maxLength: maxLength(30) },
                name_kh: { required, maxLength: maxLength(30) },
                code: {  maxLength: maxLength(5) },
            }
        },
        data () {
            return {
                // referrals:[],
                errors:[],
                title:'Referral',
                routeString:'referrals',
                headers: [
                    { text: '#', value: 'index', },
                    { text: this.$t('Parent'), value: 'parent_name' },
                    { text: this.$t('Phone'), value: 'phone' },
                    { text: this.$t('Parent'), value: 'parent2_name' },
                    { text: this.$t('Phone'), value: 'phone2' },
                    { text: this.$t('Status'), value: 'status' },
                    { text: this.$t('Created'), value: 'created_at' },
                    { text: this.$t('Action'), value: 'actions', sortable: false},
                ],
                childrenHeaders: [
                    { text: this.$t('#'), value: 'index', },
                    { text: this.$t('Student'), value: 'student_name' },
                    { text: this.$t('Parent'), value: 'parent_name' },
                    { text: this.$t('Phone'), value: 'phone' },
                    { text: this.$t('Branch'), value: 'branch.displayText' },
                    { text: this.$t('Status'), value: 'status' },
                    { text: this.$t('Created'), value: 'created_at' },
                    { text: this.$t('Action'),  value: 'actions', sortable: false},
                ],
                //end props for table
                form:{
                    name_en: '',
                    name_kh: '',
                    code: '',
                    description: '',
                    color:'#FF0000',
                },
                overlay:false,
                dialog:false,
                editDialog:false,
                deleting_Referral:Object(),
                editing_Referral:Object(),
                search: '',
                
                filteredBranch:null,
                filteredStatus:null,
                breadcrumbs:[
                    {
                    text: this.$t('Dashboard'),
                    disabled: false,
                    to: '/dashboard',
                    },
                    {
                    text: this.$t('Referral'),
                    disabled: true,
                    to: '/referrals',
                    },
                ],
                view:'parent',
                sortBy: '',
                sortDesc: false, // Initially false (ascending order)
            }
        },
        computed: {
            nameEnErrors() {
                const errors = [];
                if (!this.$v.form.name_en.$dirty) return errors;
                !this.$v.form.name_en.maxLength && errors.push("Name_en must be at most 30 characters long");
                !this.$v.form.name_en.required && errors.push("Name_en is required.");
                return errors;
            },
            nameKhErrors() {
                const errors = [];
                if (!this.$v.form.name_kh.$dirty) return errors;
                !this.$v.form.name_kh.maxLength && errors.push("Name_kh must be at most 30 characters long");
                !this.$v.form.name_kh.required && errors.push("Name_kh is required.");
                return errors;
            },
            
            codeErrors() {
                const errors = [];
                if (!this.$v.form.code.$dirty) return errors;
                !this.$v.form.code.maxLength && errors.push("code must be at most 30 characters long");
                return errors;
            },
            filteredMyReferrals(){
                if(this.view=='parent'){
                    let a=this.referrals;
                    if(this.filteredBranch) a=a.filter(item => {
                        const hasDivisibleReferrals = item.referrals.some(referral => referral.branch_id==this.filteredBranch);
                       
                        return hasDivisibleReferrals;
                    });
                    if(this.filteredStatus)  a=a.filter(item => {
                        const hasDivisibleReferrals = item.referrals.some(referral => referral.referral_status_id==this.filteredStatus);
                        return hasDivisibleReferrals;
                    });
                    return  a;
                }else{
                    console.log(this.sortBy+this.sortDesc);
                    const allReferrals = this.referrals.reduce((referrals, item) => {
                        const itemReferrals = item.referrals;
                        return [...referrals, ...itemReferrals];
                    }, []);
                    let a=allReferrals;
                    if(this.sortBy=='status'){
                        if(this.sortDesc==true){
                            a.sort((a, b) => {
                                return b.referral_status_id-a.referral_status_id;
                            });
                        }else{
                            a.sort((a, b) => {
                                return a.referral_status_id-b.referral_status_id;
                            });
                        }
                    }
                    if(this.filteredBranch) a=a.filter(x=>x.branch_id==this.filteredBranch);
                    if(this.filteredStatus) a=a.filter(x=>x.referral_status_id==this.filteredStatus);
                    return  a;
                }
              

             
            }
        },
        created() {
            // this.initialize();
        },
        methods:{
          
            editItem (item) {
                axios
                .put("/api/referrals/"+item.id,this.form)
                .then(response => {
                    
                    this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                    this.$emit('refresh');
                    this.editDialog=false;
                })
                .catch(error => {
                     //console.log(error);
                    if(error.response){
                        this.errors.name = null;
                        this.errors = error.response.data.errors;
                        this.$root.Snackbar.show({message: error.response.data.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }else{
                        this.$root.Snackbar.show({message: error.message,snackColor:'error',})
                        this.$root.Overlay.off();
                    }
                });
            },
            openEditDialog(item){
                this.editDialog = true;
            },
            clear () {
                this.$v.form.$reset()
                this.form.name_en='';
                this.form.name_kh='';
                this.form.code='';
                this.form.description='';
            },
        },
         watch:{
            editDialog:function(){
                if(this.editDialog==false){
                    this.clear();
                }
            },
          

        }
    }

</script>
