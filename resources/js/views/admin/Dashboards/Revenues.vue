<template>
    <div class="dasboard">
    
            <v-divider></v-divider>
            <v-row>
                <h4>
                    Total Potential Revenue 
                </h4>
                
            </v-row>
            <v-divider></v-divider>
            <v-row>
                <v-col cols="12">
                
                    <v-card flat>
                        <v-card-title style="font-size:64pt;padding:50px">
                          
                            $26,200,000.00
                            
                            
                        </v-card-title>
                    </v-card>
                </v-col>
            </v-row>
        
            <v-row>
                <v-col cols="12" >
                
                    <v-card flat>
                        <v-card-title>
                            By Stage
                            
                        </v-card-title>
                        <v-card-text > 
                            <BarChart
                                id="my-chart-id"
                                :data="revenueData"
                                :options="revenueOption"
                            />
                        </v-card-text>
                    
                    </v-card>
                </v-col>
            </v-row>
    </div>
</template>
<script>
import BarChart from '@/components/BarChart.vue';
import DashboardFilterMenu from '@/components/DashboardFilterMenu.vue';

export default {
    name: 'Revenue',
    components: { BarChart,DashboardFilterMenu },
    props: {

    
    },
    data() {
        return {
            
         
            revenueData: {
                labels: ['Qualification', 'Proposal', 'Neegotiating','Closed-Won', 'Closed-Lost'],
                datasets: [
                    {
                        
                        label:"Revenues",
                        backgroundColor: ['#41B883', '#E46651', '#00D8FF','#0D44FF', '#DD1B16'],
                        data: [2000000, 20000000, 8000000,1200000, 10000000]
                    },
                ]
            },
            revenueOption:{
                responsive: true,
                maintainAspectRatio: false, 
                indexAxis: 'y',
                plugins:{
                    tooltip:{
                        enabled:true
                    },
                },
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
