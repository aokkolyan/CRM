<template>       
    <v-container>
         <h2 class="font-semibold text-xl text-gray-800 referraling-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <v-card flat>
            
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    :label="$t('Search')+$t('My Referral')"
                    hide-details
                ></v-text-field>
                <v-divider></v-divider>
                <v-btn color="primary" @click="createDialog=true">
                    {{ $t('create') }}
                </v-btn>
                
            </v-card-title>
            <v-data-table 
                :headers="headers"
                :items="locations"
                :loading="loading"
                :search="search"
            >
                <template v-slot:[`item.index`]="{ index }">
                        {{ index + 1 }}
                </template>
                <template v-slot:[`item.branch_code`]="{ item }">
                    {{ item.branch_code }}<span v-if="item.is_competitor">'s competitor</span>
                </template>
                <template v-slot:[`item.is_competitor`]="{ item }">
                        <v-checkbox v-model="item.is_competitor"  :readonly="true"></v-checkbox>
                </template>
                <template v-slot:[`item.icon`]="{ item }">
                
                        <img :src="item.icon?('/storage/'+item.icon):null">
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                
                  <v-btn
                    class="ma-2"
                    x-small
                    icon
                    color="primary"
                    @click="openEditDialog(item)"
                >
                    <Tooltip tooltip="Edit">
                        <v-icon>
                            mdi-pencil
                        </v-icon>
                    </Tooltip>
                </v-btn>
                <v-btn
                class="ma-2" v-if="item.can?item.can.delete:true"
                x-small
                icon
                color="error"
                @click="openDialogConfirmation(item)"
                >
            <Tooltip tooltip="Delete">
                <v-icon>
                    mdi-delete
                </v-icon>
            </Tooltip>

        </v-btn>
        <Tooltip tooltip="Create">
            <v-icon  @click="CreateProgramDailog(item)"
            small
            color="orange darken-4"
            >
            mdi-arrow-up-bold-box-outline
           
        </v-icon> </Tooltip>
        <!-- <v-btn color="primary" @click="CreateProgramDailog(item)">
            <Tooltip tooltip="Create">
                       create
            </Tooltip>
        </v-btn> -->
            </template>
            </v-data-table>
            <v-dialog
            v-model="dialog"
            max-width="600"
            >
            <v-card elevation="5" v-if="deleting_item">
                <v-card-title class="text-h7">
                Are you sure you want to delete this {{deleting_item.name}}?
                </v-card-title>
                <v-card-actions>
                <v-spacer></v-spacer>
                
                    <v-btn
                        color="grey darken-1"
                        text
                        @click="dialog = false"
                    >
                        Disagree
                    </v-btn>

                    <v-btn
                        color="red darken-1"
                        text
                        @click="deleteItem(deleting_item)"
                    >
                        Agree
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
     
        <v-dialog
            v-model="programDialog"
            max-width="600"
            >
         
            <v-card elevation="5" v-if="selectedItem">
                <table class="table" >
                <thead >
                <tr style="padding: 2px; margin: 2px;">
                    <td>#</td>
                    <th>Program Name</th>
                    <th>Promotion</th>
                    <th>Price</th>
                    <th>Student</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(pro,index) in selectedItem.programs" :key="pro.id">
                    <td>{{ index + 1}}</td>
                    <td>{{programs.find(x=>x.id==pro.program_id)?programs.find(x=>x.id==pro.program_id).name_kh:'Deleted Prrogram'}}</td>
                    <td>{{ pro.promotion }}</td>
                    <td>${{ pro.program_price }}</td>
                    <td>{{ pro.program_student }}</td>
                    <td> <v-btn
                        class="ma-2"
                        x-small
                        icon
                        color="primary"
                        @click="openEditProgramDailog(pro)"
                        >
                        <Tooltip tooltip="Edit">
                            <v-icon>
                                mdi-pencil
                            </v-icon>
                        </Tooltip>
                    </v-btn>
                  </td>
                    <td><v-btn
                        class="ma-2"
                        x-small
                        icon
                        color="error"
                        @click="deleteProgramDailog(pro)"
                        >
                        <Tooltip tooltip="Delete">
                            <v-icon>
                                mdi-delete
                            </v-icon>
                        </Tooltip>

                </v-btn></td>
                </tr>
                </tbody>
            </table>
                <v-card-title class="text-h7">
                    Add Program for {{ selectedItem.title }}
                </v-card-title>
                <v-card-text>
                    <form @submit.prevent="submit">
                        <v-select
                            v-model="formProgram.program_id"
                            :items="programs"
                            label="Program"
                            item-value="id"
                            item-text="name_en"
                            required
                            :error-messages="programError"
                            @input="$v.formProgram.program_id.$touch()"
                            @blur="$v.formProgram.program_id.$touch()"
                        ></v-select>
                        <v-text-field type="number"
                                v-model="formProgram.program_price"
                                label="Price"
                                required
                                
                                :error-messages="programPriceError"
                                @input="$v.formProgram.program_price.$touch()"
                                @blur="$v.formProgram.program_price.$touch()"
                        ></v-text-field>
                               
                            <v-text-field type="number"
                                v-model="formProgram.program_student"
                                label="Student"
                                required
                                :error-messages="studentEnErrors"
                                @input="$v.formProgram.program_student.$touch()"
                                @blur="$v.formProgram.program_student.$touch()"
                                ></v-text-field>
                            <v-text-field
                                v-model="formProgram.promotion"
                                label="Promotion"
                                item-text="promotion"
                            ></v-text-field>

                        <v-btn color="success" @click="submit" style="margin-top: 5px; "> Add </v-btn>
                    </form>
                </v-card-text>
            </v-card>
          
           
        </v-dialog>
       <!-- dialog delete Program -->
        <v-dialog
            v-model="dialogPro"
            max-width="600"
            >
            <v-card elevation="5" v-if="deleting_pro">
                <v-card-title class="text-h7">
                    Are you sure you want to delete this item?
                </v-card-title>
                <v-card-actions>
                <v-spacer></v-spacer>
                
                    <v-btn
                        color="grey darken-1"
                        text
                        @click="dialogPro = false"
                    >
                        Disagree
                    </v-btn>

                    <v-btn
                        color="red darken-1"
                        text
                        @click="deletePro(deleting_pro)"
                    >
                        Agree
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>



       <!-- Dailog Edit program -->
       <v-dialog
            v-model="editProgramDailog"
            max-width="700"
            >
            <v-card  elevation="5">
                <v-card-title>
                            Edit program: 
                            <v-spacer>
                            </v-spacer>
                            <v-btn icon @click="editProgramDailog=false"><v-icon>mdi-close</v-icon></v-btn>
                </v-card-title>
                <v-card-text>
                    <form>
                        <v-text-field type="number"
                            v-model="formProgram.program_price"
                                label="Price"
                                :error-messages="programPriceError"
                                @input="$v.formProgram.program_price.$touch()"
                                @blur="$v.formProgram.program_price.$touch()"
                        ></v-text-field>
                            
                        <v-text-field type="number"
                            v-model="formProgram.program_student"
                            label="Student"
                            :error-messages="studentEnErrors"
                            @input="$v.formProgram.program_student.$touch()"
                            @blur="$v.formProgram.program_student.$touch()"
                            ></v-text-field>
                        <v-text-field
                            v-model="formProgram.promotion"
                            label="Promotion"
                            item-text="promotion"
                        ></v-text-field>
                        
                        <v-btn color="success" @click="openEditProgram(edit_Program)" style="margin-top: 5px; "> Update </v-btn> 
                    </form>
                </v-card-text>
            </v-card>           
       </v-dialog>
            <v-dialog v-model="createDialog" max-width="1000">
                <Create  @refresh="refresh()" :icons="icons" :branches="branches"></Create>
            </v-dialog>
            <v-dialog v-model="editDialog"   max-width="1000">
                <Create :map="editing_map" @refresh="refresh()" :icons="icons" :branches="branches"></Create>
            </v-dialog>
        </v-card>
    </v-container>
</template>

<script>
    import Create from "./Create.vue";
    import { validationMixin } from "vuelidate";
    import {
    required,
    maxLength,
    minLength,
    email,
} from "vuelidate/lib/validators";
    import Snackbar from "@/components/Snackbar";
    import axios from "axios";
    import Tooltip from '../../../components/Tooltip.vue';
    export default {
        components: {
            Create,
            Snackbar,
            Tooltip
        },
        props:{
            routeString:{default:'',type:String},
        },
        validations: {
                formProgram: {
                    program_student: { required },
                    program_price  : { required },
                    program_id     : { required }
                
                },
        },
        data() {
            
            return{

                loading:true,
                locations:[
                
                ],
                errors: {
                program_student: null,
                
            },
                search:'',
                icons:[],
                can:[],
                is_competitor:'',
                branches:[],
                snackbar: false,
                snackColor: "",
                message: "",
                item:[],
                breadcrumbs:[
                    {
                    text: this.$t('Dashboard'),
                    disabled: false,
                    to: '/dashboard',
                    },
                    {
                    text: this.$t('Locations'),
                    disabled: true,
                    href: '/maps',
                    },
                ],
                headers: [
                    { text: '#', value: 'index', },
                    { text: this.$t('Branch'), value: 'branch_code' },
                    { text: this.$t('Name'), value: 'title' },
                    // { text: this.$t('Competitor'), value: 'is_competitor' },
                    { text: this.$t('Icon'), value: 'icon' },
                    { text: this.$t('Lat'), value: 'position.lat' },
                    { text: this.$t('Long'), value: 'position.lng' },
                    { text: this.$t('Action'), value: 'actions', sortable: false},
                ],
                edit_Program:[],
                dialog:false,
                dialogPro:false,
                deleting_item:null,
                deleting_pro:null,
                createDialog:false,
                icons:[],
                selectedItem:[],
                editDialog:false,
                editing_map:null,
                programDialog:false,
                // openEditProgram:false,
                editProgramDailog:false,
                programs:[],
                formProgram:{
                    'program_id':null,
                    'map_location_id':'',
                    'program_price':'',
                    'program_student':'',
                    'promotion' :'',
                },

            };
        },
        computed:{
            studentEnErrors() {
                const errors = [];
                if (!this.$v.formProgram.program_student.$dirty) return errors;
                !this.$v.formProgram.program_student.required && errors.push("Student is required");
                // !this.$v.formProgram.program_student.required && errors.push("Program student is required.");
                return errors;
            },
            programError(){
                const errors = [];
                if (!this.$v.formProgram.program_id.$dirty) return errors;
                !this.$v.formProgram.program_id.required && errors.push("Program is required ");
                return errors;
            },
            programPriceError(){
                const errors = [];
                if (!this.$v.formProgram.program_price.$dirty) return errors;
                !this.$v.formProgram.program_price.required && errors.push("Price is required");
                return errors;
            },
       },
        created() {
            //console.log(this.$api_url)
            this.initialize();
        },
        methods:{
            submit() {
                this.$v.$touch();
                let isFormCorrect = this.$v.formProgram.$invalid;
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
                this.formProgram.map_location_id=this.selectedItem.id;
                if (!isFormCorrect) {
                    axios
                    .post("/api/competitorPrograms", this.formProgram)
                    .then((response) => {
                        //console.log(response);
                        if (response) {
                            this.refresh();
                            this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                            this.$root.Overlay.off();
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
             openEditProgram(formProgram){
                //  console.log(this.formProgram);
                   this.editProgramDailog = false
                    axios
                    .put("/api/competitorPrograms/"+this.formProgram.id,this.formProgram)
                    .then((response) => {
                        console.log(response);
                        if (response) {
                            this.refresh();
                            this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                            // this.$emit("refresh");
                        
                        // this.clear();
                            
                        // this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
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
                
             },
            //  Delete Program dialog
             deletePro(formProgram) {
                    this.dialogPro = false;
                    this.$root.Overlay.on();
                    axios.delete("/api/competitorPrograms/"+formProgram.id)
                    .then(response => {
                        if (response) {

                            // this.message = response.data.message;
                            // this.snackColor=response.data.snackColor;
                            // this.snackbar = true;
                            this.refresh();
                            this.initialize();
                            this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                            this.$root.Overlay.off();
                        }
                    }).catch(error => {
                        if(error){
                            // this.message = error;
                            // this.snackbar = true;
                            // this.snackColor='error';
                            //console.log(error.response);
                            this.$root.Snackbar.show({message:error+' '+error.response.data.message,snackColor:'error',})
                            this.$root.Overlay.off();
                        }
                    });
            },
            
            openEditProgramDailog(formProgram){
                 console.log(formProgram)
                 this.editProgramDailog = true;
                 this.formProgram.id = formProgram.id,
                 this.formProgram.program_id=formProgram.program_id;
                 this.formProgram.map_location_id=formProgram.map_location_id;
                 this.formProgram.promotion=formProgram.promotion;
                 this.formProgram.program_price=formProgram.program_price;
                 this.formProgram.program_student=formProgram.program_student;
            },
            openDialogConfirmation(item){

                this.deleting_item={...item};
                this.message = '';
                this.dialog = true;
            },
            deleteProgramDailog(item){
            this.deleting_pro={...item};
            this.message = '';
            this.dialogPro = true;
            },
          
            CreateProgramDailog(item){
                
                this.selectedItem={...item};
                this.programDialog = true;
               
            },
            
        //   delete map location
            deleteItem(item) {
                    this.dialog = false;
                    this.$root.Overlay.on();
                    axios.delete("/api/mapLocations/"+item.id)
                    .then(response => {
                        if (response) {

                            // this.message = response.data.message;
                            // this.snackColor=response.data.snackColor;
                            // this.snackbar = true;
                            this.initialize();
                            this.$root.Snackbar.show({message: response.data.message,snackColor:response.data.snackColor,})
                            this.$root.Overlay.off();
                        }
                    }).catch(error => {
                        if(error){
                            // this.message = error;
                            // this.snackbar = true;
                            // this.snackColor='error';
                            //console.log(error.response);
                            this.$root.Snackbar.show({message:error+' '+error.response.data.message,snackColor:'error',})
                            this.$root.Overlay.off();
                        }
                    });
            },

            initialize() {
                this.loading=true;
                  return new Promise((resolve, reject) => {
                    axios
                    .get("/api/mapLocations")
                    .then(response => {
                        
                        this.locations = response.data.data;
                        this.icons     = response.data.icons;
                        this.can = response.data.can;
                        this.branches  = response.data.branches;
                        this.programs  = response.data.programs;
                        console.log(response);
                        setTimeout(() => {
                            this.loading = false;
                            resolve(response);
                        }, 100);
                    })
                    .catch(error => {
                        reject(error);
                    });
                });
            },
            refresh(){
                this.createDialog=false;
                // this.CreateProgramDailog=false;
                this.programDialog=false;
                this.editDialog=false;
                this.initialize();
            },
            openEditDialog(item){
                
                this.editing_map=null;
                this.editing_map={...item};
                this.editing_map.position=this.editing_map.position.lat+','+this.editing_map.position.lng;
                this.editDialog=true;
                
            },

        },
       
        watch:{
            editDialog:{
                handler(){
                    console.log(this.editDialog);
                    if(this.editDialog==false)this.editing_map=null;
                }
            }
        },
        // clear() {
        //     this.$v.form.$reset();
        //     this.formProgram.program_id='';
        //     this.form.promotion='';
        //     this.form.program_price='';
        //     this.form.program_student='';
        // },
    }

</script>
