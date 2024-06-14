<template>
    <Doughnut
        :options="options"
        :data="data"
        :plugins="plugins"
    />
</template>

<script>
import { Doughnut } from 'vue-chartjs'
import ChartJsPluginDataLabels from 'chartjs-plugin-datalabels';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement,ArcElement, CategoryScale, LinearScale } from 'chart.js'
ChartJS.register(Title, Tooltip, Legend, ArcElement, BarElement, CategoryScale, LinearScale)
export default {
    name: 'BarChart',
    components: { Doughnut },
    props: {
        options: {
            type: Object,
            default: () => {
                return{
                    
                    responsive: true,
                    maintainAspectRatio: false, 
                    // indexAxis: 'y',
                    plugins:{
                        tooltip:{
                            enabled:true
                        },
                        datalabels:{
                            
                            color: ['#F2F2F2'],
                            formatter:(value,context)=>{
                                const total=context.dataset.data.reduce((a,b)=>a+b,0);
                                return (value/total*100).toFixed(2)+'%'
                            }   
                        },
             
                    },
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