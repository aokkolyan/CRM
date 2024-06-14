<template>
    <div>
        <br>
        <v-row>
            <h4>
                Monthly Statistic 
                <DashboardFilterMenu @filter="filter" :schools="items"></DashboardFilterMenu>
            </h4>
        </v-row>
        <v-divider></v-divider>
        <v-row>
            
            <v-col cols="12" sm="6" md="3">
                <v-card flat>
                    <span style="position:absolute;right:0"></span>
                    <v-card-title style="display:flex" >
                        <v-avatar color="#CD212A1F" size="56">
                            <v-icon color="#CD212A">
                            mdi-account-group
                            </v-icon>
                        </v-avatar>
                        <div class="ml-2">
                            <div class="font-weight-normal">
                            <strong>20</strong>
                            </div>
                            <div>Leads</div>
                        </div>

                    </v-card-title>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="3">
                <v-card flat>
                    <span style="position:absolute;right:0"></span>
                    <v-card-title style="display:flex" >
                        <v-avatar color="#34568B1F" size="56">
                            <v-icon color="#34568B">
                            mdi-account-cash
                            </v-icon>
                        </v-avatar>
                        <div class="ml-2">
                            <div class="font-weight-normal">
                            <strong>12</strong>
                            </div>
                            <div>Oppurtunities</div>
                        </div>

                    </v-card-title>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="3">
                <v-card flat>
                    <span style="position:absolute;right:0"></span>
                    <v-card-title style="display:flex" >
                        <v-avatar color="#FFA5001F" size="56">
                            <v-icon color="#FFA500">
                            mdi-currency-usd
                            </v-icon>
                        </v-avatar>
                        <div class="ml-2">
                            <div class="font-weight-normal">
                            <strong>$2,250,000.00</strong>
                            </div>
                            <div>Potential Revenue</div>
                        </div>

                    </v-card-title>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="3">
                <v-card flat>
                    
                    <v-card-title style="display:flex" >
                    <v-avatar color="#FA7A351F" size="56">
                        <v-icon color="#FA7A35">
                        mdi-source-branch
                        </v-icon>
                    </v-avatar>
                    <div class="ml-2">
                        <div class="font-weight-normal">
                        <strong>25</strong>
                        </div>
                        <div>Branches</div>
                    </div>

                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>
<script>
import DashboardFilterMenu from '@/components/DashboardFilterMenu.vue';

export default {
    name: 'MonthlyStatistic',
    components: { DashboardFilterMenu },
    props: {

    
    },
    data() {
        return {
            
          
            items: [
                {id:1, title: 'January' },
                {id:2, title: 'Feburary' },
                {id:3, title: 'March' },
                {id:4, title: 'April' },
            ],
            
        }
    },
    created() {
        this.user = this.$store.state.auth.user;
        // this.initialize();
    },
    methods:{
        filter(item){
            console.log(item);
        },
        initialize() {
            return new Promise((resolve, reject) => {
                axios
                    .get("/api/users")
                    .then(response => {
                        this.users = response.data.data;
                        this.can = response.data.can;
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
    }
}
</script>
