<template>       
    <v-container>
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <v-breadcrumbs
            :items="breadcrumbs"
            divider="/"
            ></v-breadcrumbs>
        </h2>
        <Datatable :loading="loading" :leads="leads" @refresh="initialize()" />
       
    </v-container>
</template>

<script>
    import Datatable from "./Datatable.vue";
    import Create from "./Create.vue";
    
    import axios from "axios";
    export default {
        components: {
            Datatable,
            Create
        },
        data: () => ({
            loading:true,
            leads:[
                {
                    name:'Teng Vanndavid',
                    phone:'012345332',
                    email:'vanndavid.teng@wegcambodia.com',
                    status:'Open',
                    created_date:'12/12/2022',
                    can:{
                        view:false,
                        update:false,
                    },
                },
                {
                    name:'Tes Simon',
                    phone:'012345332',
                    email:'efwe.ssswqq@wegcambodia.com',
                    status:'Open',
                    created_date:'12/12/2022',
                    can:{
                        view:false,
                        update:false,
                    },
                },
                {
                    name:'Dan Dol',
                    phone:'012345332',
                    email:'ssd.tenfavvsg@wegcambodia.com',
                    status:'Won',
                    created_date:'12/12/2022',
                    can:{
                        view:false,
                        update:false,
                    },
                },
                {
                    name:'Go God',
                    phone:'012345332',
                    email:'god.tenweg@wegcambodia.com',
                    status:'Lost',
                    created_date:'12/12/2022',
                    can:{
                        view:false,
                        update:false,
                    },
                },
            ],
            can:[],
            tab: null,
            breadcrumbs:[
                {
                text: 'Dashboard',
                disabled: false,
                to: '/dashboard',
                },
                {
                text: 'Lead',
                disabled: true,
                href: '/leads',
                },
            ]
        }),
        created() {
            //console.log(this.$api_url)
            this.initialize();
        },
        methods:{
            initialize() {
                this.loading=false;
                //   return new Promise((resolve, reject) => {
                //     axios
                //     .get("/api/leads")
                //     .then(response => {

                //         this.leads = response.data.data;
                        
                //         this.can = response.data.can;
                //         //console.log(response);
                //         setTimeout(() => {
                //             this.loading = false;
                //             resolve(response);
                //         }, 100);
                //     })
                //     .catch(error => {
                //         reject(error);
                //     });
                // });
            },
        },
    }

</script>
