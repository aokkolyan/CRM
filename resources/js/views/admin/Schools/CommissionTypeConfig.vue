<template>
            <!-- Add School-->
            <v-card elevation="5">
                <v-card-title> Commistion Type </v-card-title>
                <v-card-text v-if="school">
                    <v-simple-table>
                        <thead>
                            <tr>
                                <th>
                                    User Type
                                </th>
                                <th>
                                    Commission Type
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    {{ $isKh()?userTypes[0].name_kh:userTypes[0].name_en }}
                                </td>

                                <td>
                                    <v-radio-group v-model="commission_type.employee.value">
                                        <v-radio
                                            label="Cash"
                                            color="green"
                                            value="cash"
                                            hide-details
                                        ></v-radio>
                                        <v-radio
                                            label="Point"
                                            color="blue"
                                            
                                            value="point"
                                            hide-details
                                        ></v-radio>
                                        <v-radio
                                            label="Coupon"
                                            color="orange"
                                            
                                            value="coupon"
                                            hide-details
                                        ></v-radio>
                                    </v-radio-group>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $isKh()?userTypes[1].name_kh:userTypes[1].name_en }}
                                </td>

                                <td>
                                    <v-radio-group v-model="commission_type.parent.value">
                                        <v-radio
                                            label="Cash"
                                            color="green"
                                            value="cash"
                                            hide-details
                                        ></v-radio>
                                        <v-radio
                                            label="Point"
                                            color="blue"
                                            
                                            value="point"
                                            hide-details
                                        ></v-radio>
                                        <v-radio
                                            label="Coupon"
                                            color="orange"
                                            
                                            value="coupon"
                                            hide-details
                                        ></v-radio>
                                    </v-radio-group>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $isKh()?userTypes[2].name_kh:userTypes[2].name_en }}
                                </td>

                                <td>
                                    <v-radio-group v-model="commission_type.student.value">
                                        <v-radio
                                            label="Cash"
                                            color="green"
                                            value="cash"
                                            hide-details
                                        ></v-radio>
                                        <v-radio
                                            label="Point"
                                            color="blue"
                                            
                                            value="point"
                                            hide-details
                                        ></v-radio>
                                        <v-radio
                                            label="Coupon"
                                            color="orange"
                                            
                                            value="coupon"
                                            hide-details
                                        ></v-radio>
                                    </v-radio-group>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $isKh()?userTypes[3].name_kh:userTypes[3].name_en }}
                                </td>

                                <td>
                                    <v-radio-group v-model="commission_type.alumni.value">
                                        <v-radio
                                            label="Cash"
                                            color="green"
                                            value="cash"
                                            hide-details
                                        ></v-radio>
                                        <v-radio
                                            label="Point"
                                            color="blue"
                                            
                                            value="point"
                                            hide-details
                                        ></v-radio>
                                        <v-radio
                                            label="Coupon"
                                            color="orange"
                                            
                                            value="coupon"
                                            hide-details
                                        ></v-radio>
                                    </v-radio-group>
                                </td>
                            </tr>
                        </tbody>
                    </v-simple-table>
                </v-card-text>
            </v-card>
</template>

<script>


import axios from "axios";
import Table from '../../../components/Table.vue';
export default {
    props: {
        userTypes: Array(),
        school:Array(),
    },
    components: {
        Table
    },

    data() {
        return {
            commission_type:{
                employee:{user_type_id:this.userTypes[0].id,label:'employee',value:this.school.commission_types.find(x=>x.user_type_id==this.userTypes[0].id)?this.school.commission_types.find(x=>x.user_type_id==this.userTypes[0].id).commission_type:null},
                parent:{user_type_id:this.userTypes[1].id,label:'parent',value:this.school.commission_types.find(x=>x.user_type_id==this.userTypes[1].id)?this.school.commission_types.find(x=>x.user_type_id==this.userTypes[1].id).commission_type:null},
                student:{user_type_id:this.userTypes[2].id,label:'student',value:this.school.commission_types.find(x=>x.user_type_id==this.userTypes[2].id)?this.school.commission_types.find(x=>x.user_type_id==this.userTypes[2].id).commission_type:null},
                alumni:{user_type_id:this.userTypes[3].id,label:'alumni',value:this.school.commission_types.find(x=>x.user_type_id==this.userTypes[3].id)?this.school.commission_types.find(x=>x.user_type_id==this.userTypes[3].id).commission_type:null},
    
            }
        };
    },
    computed: {

    },
    methods: {
        submit() {
            axios
            .post("/api/schools/"+this.school.id+"/commissionType", this.commission_type)
            .then((response) => {
            })
            .catch((error) => {
            });
        },
        clear() {
            this.$v.form.$reset();
            this.form.name_en='';
            this.form.name_kh='';
            this.form.code='';
            this.form.description='';
        },
    },
    watch: {
        commission_type:{
            deep: true,
            handler(newForm) {
                this.submit();
            }
        }
    },
};
</script>
