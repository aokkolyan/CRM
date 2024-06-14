<template>
    <div class="dasboard">
    
            <v-divider></v-divider>
            <v-row>
                <h4>
                    Opportunities 
                </h4>
            </v-row>
            <v-divider></v-divider>
            <v-row>
                <v-col cols="12" md="8">
                
                    <v-card flat>
                        <v-card-title>
                            By Source
                            
                        </v-card-title>
                        <v-card-text > 
                            <BarChart
                                id="my-chart-id"
                                :data="opportunityData"
                            />
                        </v-card-text>
                    
                    </v-card>
                </v-col>
                <v-col cols="12" md="4">
                    <v-card flat>
                        <v-card-title>
                            By Status
                            
                        </v-card-title>
                        <v-card-text>
                            <DoughnutChart
                                
                                :data="opportunityData"
                            />
                        </v-card-text>
                    
                    </v-card>
                </v-col>
            </v-row>
   
    </div>
</template>
<script>
import BarChart from '@/components/BarChart.vue';
import DoughnutChart from '@/components/DoughnutChart.vue';
import DashboardFilterMenu from '@/components/DashboardFilterMenu.vue';

export default {
    name: 'Opportunities',
    components: { BarChart,DoughnutChart,DashboardFilterMenu },
    props: {

    
    },
    data() {
        return {
            
         

            opportunityData: {
                labels: ['VueJs', 'EmberJs', 'ReactJs','Nth', 'AngularJs'],
                datasets: [
                    {
                        
                        label:"hello",
                        backgroundColor: ['#34568B', '#CD212A', '#FFA500','#56C6A9', '#4B5335'],
                        data: [40, 20, 80,120, 10]
                    },
                ]
            },
           
            items: [
                {id:1, title: 'January' },
                {id:2, title: 'Feburary' },
                {id:3, title: 'March' },
                {id:4, title: 'April' },
            ],
            
        }
    },
    methods:{
        filter(item){
            console.log(item);
        }
    }
}
</script>
