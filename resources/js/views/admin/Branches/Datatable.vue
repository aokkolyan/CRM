<template>
    <div class="m-1" style="margin:auto">

            <Table
            @refresh="$emit('refresh')"
            :loading="loading"
            :headers="headers"
            :items="branches"
            :title="title"
            :routeString="routeString"
            :openEditDialog="openEditDialog"
            >
                <template v-slot:[`item.index`]="{ index }">
                        {{ index + 1 }}
                </template>
                <template v-slot:[`item.is_main`]="{ item }">
                    <v-checkbox
                        :input-value="(item.is_main==1)"
                        disabled
                        value
                    ></v-checkbox>
                </template>
                <template v-slot:[`item.commission`]="{ item }">
                    {{ item.commission }}%
                </template>
                <template v-slot:[`item.have_workflow`]="{ item }">
                    <v-checkbox
                        :input-value="(item.have_workflow==1)"
                        disabled
                        value
                    ></v-checkbox>
                </template>
            </Table>
                <!--Edit dialog-->
                <v-dialog
                v-model="editDialog"
                max-width="600"
                >
                    <v-card elevation="5">
                        <v-card-title>
                            Edit Branch:  {{editing_Sidemenu.name_en}}?
                        </v-card-title>
                        <v-card-text>
                            <form @submit.prevent="submit">
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
                                    v-model="form.commission"
                                    :counter="2"
                                    label="Comcommission"
                                    type="number"
                                ></v-text-field>
                                <v-text-field
                                    v-model="form.max_discount"
                                    :counter="2"
                                    label="max_discount"
                                    type="number"
                                ></v-text-field>
                                <div v-if="errors.commission">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.commission"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                                <v-text-field
                                    v-model="form.description"
                                    :counter="100"
                                    label="Description"
                                ></v-text-field>
                                <div v-if="errors.name">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.description"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                                <v-select
                                    v-model="form.school_id" 
                                    :items="schools" 
                                    item-text="name_en"
                                    item-value="id" 
                                    label="Role">
                                </v-select>
                                <div v-if="errors.school_id">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.school_id"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                            </form>
                            <v-btn
                                color="success"
                                @click="editItem(editing_Sidemenu)"
                                >
                                Update
                            </v-btn>
                            <v-btn @click="clear">
                                clear
                            </v-btn>
                        </v-card-text>
                    </v-card>
                </v-dialog>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength, minLength, email } from 'vuelidate/lib/validators';
    import Table from "@/components/Table.vue"

    import axios from "axios";
    export default {
        mixins: [validationMixin],
        props:{
            user:Object(),
            branches:Array(),
            modules:Array(),
            loading:Boolean(),
            schools:Array(),
        },
        components: {
            Table,
        },
        validations: {
            form:{
                name_en: { required, maxLength: maxLength(30) },
                name_kh: { required, maxLength: maxLength(30) },
                code: { required, maxLength: maxLength(5) },
                school_id: { required },
            }
        },
        data () {
            return {
                // sidemenus:[],
                routeString:"branches",
                types:[
                    {id:0,name:'Group'},
                    {id:1,name:'Item'},
                ],

                errors:[],
                title:'',
                routeString:'branches',
                headers: [
                    { text: '#', value: 'index', },
                    { text: 'Name En', value: 'name_en' },
                    { text: 'Name Kh', value: 'name_kh' },
                    { text: 'Code', value: 'code' },
                    { text: 'Commission', value: 'commission' },
                    { text: 'Description', value: 'description' },
                    { text: 'School', value: 'school_name' },
                    { text: 'Action',  value: 'actions', sortable: false},
                ],
                //end props for table
                form:{
                    name_en: '',
                    name_kh: '',
                    code: '',
                    commission:5,
                    description: '',
                    school_id : null,
                },
                show1: false,
                dialog:false,
                editDialog:false,
                editing_Sidemenu:Object(),
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
                .put("/api/branches/"+item.id,this.form)
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
                this.editing_Sidemenu=item;
                this.editDialog = true;
              
                this.form.name_en=item.name_en;
                this.form.name_kh=item.name_kh;
                this.form.code=item.code;
                this.form.commission=item.commission;
                this.form.description=item.description;
                this.form.school_id=item.school_id;

            },
            clear() {
                this.$v.form.$reset();
                this.form.name_en='';
                this.form.name_kh='';
                this.form.code='';
                this.form.description='';
                this.form.school_id=null;
            },
        },
         watch:{
            editDialog:function(){
                if(this.editDialog==false){
                    this.clear();
                }
            }
        }
    }

</script>
