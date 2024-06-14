<template>       
    <div class="m-1" style="margin:auto">
        
            <Table 
            :loading="loading"
            :headers="headers" 
            :items="schools" 
            :title="title" 
            :routeString="routeString" 
            :openEditDialog="openEditDialog"
            @refresh="$emit('refresh')"
            >
                <template v-slot:[`item.index`]="{ index }">
                        {{ index + 1 }}
                </template>
                <template v-slot:[`item.is_riel`]="{ item }">
                        {{ item.is_riel?'Riel':'Dollar' }}
                </template>
                <template v-slot:[`item.commission_type`]="{ item }">
                        <v-btn small icon @click="openCommissiontDialog(item)">
                            <v-icon>
                                mdi-account-cash
                            </v-icon>
                        </v-btn>
                </template>
               
            </Table>
                <!--Edit dialog-->
                <v-dialog
                v-model="editDialog"
                max-width="600"
                >
                    <v-card elevation="5">
                        <v-card-title>
                            Edit School:  {{editing_School.name}}?
                        </v-card-title>
                        <v-card-text>
                            <form>
                                <v-text-field
                                    v-model="form.name_en"
                                    :error-messages="nameEnErrors"
                                    :counter="30"
                                    label="Name_en"
                                    required
                                    @input="$v.form.name_en.$touch()"
                                    @blur="$v.form.name_en.$touch()"
                                ></v-text-field>
                                <div v-if="errors.name_en">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.name_en"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                                <v-text-field
                                    v-model="form.name_kh"
                                    :error-messages="nameKhErrors"
                                    :counter="30"
                                    label="Name_kh"
                                    required
                                    @input="$v.form.name_kh.$touch()"
                                    @blur="$v.form.name_kh.$touch()"
                                ></v-text-field>
                                <div v-if="errors.name_kh">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.name_kh"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                                <v-text-field
                                    v-model="form.code"
                                    :error-messages="codeErrors"
                                    :counter="5"
                                    label="Code"
                                    required
                                    @input="$v.form.code.$touch()"
                                    @blur="$v.form.code.$touch()"
                                ></v-text-field>
                                <div v-if="errors.code">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.code"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                                <v-text-field
                                    v-model="form.description"
                                    :counter="100"
                                    label="Description"
                                ></v-text-field>
                                <v-text-field
                                    v-model="form.point_multiplyier"
                                    label="Point Multiplyier"
                                ></v-text-field>
                                <v-text-field
                                    v-model="form.coupon_percentage"
                                    label="Coupon Percentage"
                                ></v-text-field>
                                <div v-if="errors.name">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.description"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                                <v-switch
                                    v-model="form.is_riel"
                                    label="Using Khmer Riel? "
                                ></v-switch>
                            </form>
                                <v-btn
                                color="success"
                                @click="editItem(editing_School)"
                                >
                                Update
                            </v-btn>
                            <v-btn @click="clear">
                                clear
                            </v-btn>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <v-dialog
                    v-model="commission_type_dialog"
                >
                    <CommissionTypeConfig v-if="selectedSchool" :userTypes="user_types" :school="selectedSchool"></CommissionTypeConfig>
                </v-dialog>
        
        
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength, minLength, email } from 'vuelidate/lib/validators';
    import Table from "@/components/Table.vue"
    import CommissionTypeConfig from './CommissionTypeConfig.vue';
    import axios from "axios";
    export default {
        mixins: [validationMixin],
        props:{
            user:Object(),
            schools:Array(),
            loading:Boolean(),
            user_types:Array(),
        },
        components: {
            Table,CommissionTypeConfig
        },
        validations: {
            form:{
                name_en: { required, maxLength: maxLength(30) },
                name_kh: { required, maxLength: maxLength(30) },
                code: { required, maxLength: maxLength(5) },
            }
        },
        data () {
            return {
                // schools:[],
                errors:[],
                title:'School',
                routeString:'schools',
                headers: [
                    { text: '#', value: 'index', },
                    { text: 'Name En', value: 'name_en' },
                    { text: 'Name Kh', value: 'name_kh' },
                    { text: 'Code', value: 'code' },
                    { text: 'Description', value: 'description' },
                    { text: 'Point multiplyier', value: 'point_multiplyier' },
                    { text: 'Coupon percentage', value: 'coupon_percentage' },
                    { text: 'Currency', value: 'is_riel' },
                    { text: 'Commissiont Type',  value: 'commission_type', sortable: false},
                    { text: 'Action',  value: 'actions', sortable: false},
                ],
                //end props for table
                form:{
                    name_en: '',
                    name_kh: '',
                    code: '',
                    description: '',
                    point_multiplyier:'',
                    coupon_percentage:'',
                },
                overlay:false,
                dialog:false,
                editDialog:false,
                deleting_School:Object(),
                editing_School:Object(),
                search: '',
                
                breadcrumbs:[
                    {
                    text: 'Dashboard',
                    disabled: false,
                    href: '/dashboard',
                    },
                    {
                    text: 'School',
                    disabled: true,
                    href: '/schools',
                    },
                ],
                commission_type_dialog:false,
                selectedSchool:null,
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
                !this.$v.form.code.required && errors.push("code is required.");
                return errors;
            },
        },
        created() {
            // this.initialize();
        },
        methods:{
          
            editItem (item) {
                axios
                .put("/api/schools/"+item.id,this.form)
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
                this.editing_School=item;
                this.form.name_en=item.name_en;
                this.form.name_kh=item.name_kh;
                this.form.code=item.code;
                this.form.description=item.description;
                this.form.point_multiplyier=item.point_multiplyier;
                this.form.coupon_percentage=item.coupon_percentage
                this.form.is_riel=item.is_riel;
            },
            clear () {
                this.$v.form.$reset()
                this.form.name_en='';
                this.form.name_kh='';
                this.form.code='';
                this.form.description='';
                this.form.point_multiplyier='';
                this.form.coupon_percentage='';
            },
            openCommissiontDialog(school){
                this.commission_type_dialog=true;
                this.selectedSchool=school;
            }
        },
         watch:{
            editDialog:function(){
                if(this.editDialog==false){
                    this.clear();
                }
            },
            commission_type_dialog:function(){
                if(this.commission_type_dialog==false){
                    this.selectedSchool=null
                }
            }
        }
    }

</script>
