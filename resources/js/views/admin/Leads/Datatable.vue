<template>       
    <div class="m-1" style="margin:auto">
        <v-card flat>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search Leads..."
                    hide-details
                ></v-text-field>
                <v-divider></v-divider>
                <v-btn @click="openEditDialog()">
                    Create
                </v-btn>
            </v-card-title>
            <v-data-table 
                :headers="headers"
                :items="leads"
                :loading="loading"
            >
                <template v-slot:[`item.index`]="{ index }">
                        {{ index + 1 }}
                </template>
            </v-data-table>
                <!--Edit dialog-->
                <v-dialog
                v-model="editDialog"
                max-width="600"
                >
                    <Create v-on:refresh="initialize()"></Create>
                </v-dialog>

        
        
        </v-card>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate';
    import { required, maxLength, minLength, email } from 'vuelidate/lib/validators';
    import Table from "@/components/Table.vue"
    import Create from './Create.vue';
    
    import axios from "axios";
    export default {
        mixins: [validationMixin],
        props:{
            user:Object(),
            leads:Array(),
            loading:Boolean(),
        },
        components: {
            Table,Create
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
                // leads:[],
                errors:[],
                title:'Lead',
                routeString:'leads',
                headers: [
                    { text: '#', value: 'index', },
                    { text: 'Name', value: 'name' },
                    { text: 'Phone', value: 'phone' },
                    { text: 'Email', value: 'email' },
                    { text: 'Status', value: 'status' },
                    { text: 'Created At', value: 'created_date' },
                    { text: 'Action',  value: 'actions', sortable: false},
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
                deleting_Lead:Object(),
                editing_Lead:Object(),
                search: '',
                
                breadcrumbs:[
                    {
                    text: 'Dashboard',
                    disabled: false,
                    href: '/dashboard',
                    },
                    {
                    text: 'Lead',
                    disabled: true,
                    href: '/leads',
                    },
                ]
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
        },
        created() {
            // this.initialize();
        },
        methods:{
          
            editItem (item) {
                axios
                .put("/api/leads/"+item.id,this.form)
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
                this.editing_Lead=item;
                this.form.name_en=item.name_en;
                this.form.name_kh=item.name_kh;
                this.form.code=item.code;
                this.form.description=item.description;
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
            }
        }
    }

</script>
