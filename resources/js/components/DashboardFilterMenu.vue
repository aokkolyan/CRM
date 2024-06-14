<template>
     <v-menu
        bottom
        :close-on-content-click="false"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                icon
                v-bind="attrs"
                v-on="on"
            >
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </template>

        <v-card>
            <!-- <v-card-title>
                Filter
            </v-card-title> -->
            <v-card-text>
                <!-- <v-select
                
                    v-model="form.school"
                    :items="schools"
                    label="School"
                    item-value="id"
                    item-text="title"
                ></v-select> -->
                <v-select
                    v-model="form.branch"
                    :items="branches"
                    label="Branches"
                    item-value="id"
                    item-text="displayText"
                    @change="filter"
                    clearable
                    hide-details
                ></v-select>
                <!-- <v-btn
                    color="success"
                    class="mr-4"
                    @click="filter()"
                    >
                    Filter
                </v-btn> -->
            </v-card-text>
        </v-card>
    </v-menu>
</template>

<script>
export default {
    props:{
        // schools: {
        //     type: Array,
        //     default: null,
        // },
        branches: {
            type: Array,
            default: null,
        },
    },
    data() {
        return {
            form:{
                school:null,
                branch:null,
            }
        }
    },
    methods:{
        filter(){
            this.$emit('filter',this.form);
        }
    }
}
</script>

<style>

</style>