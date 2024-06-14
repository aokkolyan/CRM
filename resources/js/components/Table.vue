<template>
   <v-card elevation="17">
        <v-card-title>
        {{title}}
        <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search..."
                hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="items"
            :search="search"
            :loading="loading"
        >
            <slot></slot>
            <template v-for="(slot, name) in $scopedSlots" v-slot:[name]="item">
                <slot :name="name" v-bind="item"></slot>
            </template>
            <template v-slot:[`item.actions`]="{ item }">
<!-- 
                <v-btn
                v-if="item.can?item.can.view:true"
                class="ma-2"
                x-small
                 icon
                color="teal"
                @click="openViewDialog(item)"
                >
                    <Tooltip tooltip="View">
                        <v-icon>
                            mdi-eye
                        </v-icon>
                    </Tooltip>
                </v-btn> -->
                <v-btn
                
                v-if="item.can?item.can.update:true"
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

                v-if="item.can?item.can.delete:true"
                class="ma-2"
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
            </template>
        </v-data-table>
        <v-dialog
        v-model="dialog"
        max-width="600"
        >
            <v-card elevation="5">
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
         <Snackbar
            :message="message"
            :snackColor="snackColor"
            :snackbar="snackbar"
        >
        </Snackbar>

    </v-card>
</template>
<script>

import Tooltip from "@/components/Tooltip.vue";
import Snackbar from "@/components/Snackbar"
import axios from "axios";
export default {
    components:{
        Tooltip,
        Snackbar
    },
    props:{
            headers:{default:[],type:Array},
            items:{default:[],type:Array},
            title:{default:'Title',type:String},
            routeString:{default:'',type:String},
            openEditDialog: { type: Function },
            openViewDialog: { type: Function },
            loading:Boolean(),

        },
    data () {
        return {
            snackbar: false,
            snackColor:'',
            message:'',
            search:'',
            dialog:false,
            deleting_item:Object(),
        }
    },
    // computed: {
    //     noData () {
    //         return this.headers.filter('No result found')
    //     }
    // },
    methods:{

            openDialogConfirmation(item){
                this.message = '';
                this.dialog = true;
                this.deleting_item=item;
            },
            deleteItem (item) {
                    this.dialog = false;
                    this.$root.Overlay.on();
                    axios.delete("/api/"+this.routeString+"/"+item.id)
                    .then(response => {
                        if (response) {

                            // this.message = response.data.message;
                            // this.snackColor=response.data.snackColor;
                            // this.snackbar = true;
                            this.$emit('refresh');

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
     }
}
</script>

<style>

</style>
