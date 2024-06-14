<template>       
    <div class="m-1" style="margin:auto">
        
            <Table 
            :loading="loading"
            :headers="headers" 
            :items="statuses" 
            :title="title" 
            :routeString="routeString" 
            :openEditDialog="openEditDialog"
            @refresh="$emit('refresh')"
            >
                <template v-slot:[`item.index`]="{ index }">
                        {{ index + 1 }}
                </template>
                <template v-slot:[`item.color`]="{ item }">
                                
                    <v-chip
                        class="ma-2"
                        :color="item.color+'2F'"
                        :text-color="item.color">
                            {{item.color}}
                    </v-chip>
                </template>
               
            </Table>
                <!--Edit dialog-->
                <v-dialog
                v-model="editDialog"
                max-width="600"
                >
                    <v-card elevation="5">
                        <v-card-title>
                            Edit Status:  {{editing_Status.name}}?
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
                                    :counter="5"
                                    label="Code"
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
                                <div v-if="errors.name">
                                    <span
                                        style="color: red"
                                        v-for="(error, index) in errors.description"
                                        :key="index"
                                        >{{ error }}
                                    </span>
                                </div>
                                <v-color-picker
                                    v-model="form.color"
                                    hide-canvas
                                    hide-sliders
                                    mode="hexa"
                                    show-swatches
                                ></v-color-picker>
                                <div style="color:red" v-if="errors.color">{{ errors.color }}</div>
                            </form>
                                <v-btn
                                color="success"
                                @click="editItem(editing_Status)"
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
            statuses:Array(),
            loading:Boolean(),
        },
        components: {
            Table,
        },
        validations: {
            form:{
                name_en: { required, maxLength: maxLength(30) },
                name_kh: { required, maxLength: maxLength(30) },
            }
        },
        data () {
            return {
                // statuses:[],
                errors:[],
                title:'Status',
                routeString:'statuses',
                headers: [
                    { text: '#', value: 'index', },
                    { text: 'Name En', value: 'name_en' },
                    { text: 'Name Kh', value: 'name_kh' },
                    { text: 'Code', value: 'code' },
                    { text: 'Description', value: 'description' },
                    { text: 'Color', value: 'color' },
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
                deleting_Status:Object(),
                editing_Status:Object(),
                search: '',
                
                breadcrumbs:[
                    {
                    text: 'Dashboard',
                    disabled: false,
                    href: '/dashboard',
                    },
                    {
                    text: 'Status',
                    disabled: true,
                    href: '/statuses',
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
                .put("/api/statuses/"+item.id,this.form)
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
                this.editing_Status=item;
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
