<template>
    <Bar
    height="300px"
        id="my-chart-id"
        :options="options"
        :data="data"
    />
</template>

<script>
import { Bar } from 'vue-chartjs'
import ChartJsPluginDataLabels from 'chartjs-plugin-datalabels';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
export default {
    name: 'BarChart',
    components: { Bar },
    props: {
        options: {
            type: Object,
            default: () => {
                return{
                    
                    responsive: true,
                    maintainAspectRatio: false, 
                    // indexAxis: 'y',
                    // plugins:{
                    //     tooltip:{
                    //         enabled:false
                    //     },
                    //     datalabels:{
                            
                    //         color: ['#FFF', '#FFF', '#FFF', '#FFF'],
                    //         // formatter:(value,context)=>{
                    //         //     return '%'
                    //         // }   
                    //     },
             
                    // },
                    // onClick: function(evt) {
                    //     console.log(evt);
                    // }
                }
            }
        },
        data: {
            type: Object,
            default: () => {}
        },
    
    },
    computed:{

    },
    data(){
        return{
            plugins:[ChartJsPluginDataLabels],
        }
    }
}
</script>

<style>

</style>