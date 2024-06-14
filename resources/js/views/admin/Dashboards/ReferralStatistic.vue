<template>
    <div>
        <br>
        <v-row>
            <h4>
               {{$t('Referrals Statistic')}}
                <DashboardFilterMenu v-if="branches" @filter="filter" :branches="branches"></DashboardFilterMenu>
            </h4>
        </v-row>
        <v-divider></v-divider>
        <v-row>
            
            <v-col cols="12" sm="6" md="3">
                <v-card flat>
                    <span style="position:absolute;right:0"></span>
                    <v-card-title style="display:flex" >
                        <v-avatar color="#34568B1F" size="56">
                            <v-icon color="#34568B">
                            mdi-account-search
                            </v-icon>
                        </v-avatar>
                        <div class="ml-2">
                            <div class="font-weight-normal">
                            <strong>{{ $number(all.total??0)}}</strong>
                            </div>
                            <div>{{ $t('Referrals') }}</div>
                        </div>

                    </v-card-title>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="3">
                <v-card flat>
                    <span style="position:absolute;right:0"></span>
                    <v-card-title style="display:flex" >
                        <v-avatar color="#0c8d171F" size="56">
                            <v-icon color="#0c8d17">
                            mdi-account-cash
                            </v-icon>
                        </v-avatar>
                        <div class="ml-2">
                            <div class="font-weight-normal">
                            <strong>{{ $number(all.won??0)}}</strong>
                            </div>
                            <div>{{ $isKh()?referralStatuses[3].name_kh:referralStatuses[3].name_en }}</div>
                        </div>

                    </v-card-title>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="3">
                <v-card flat>
                    <span style="position:absolute;right:0"></span>
                    <v-card-title style="display:flex" >
                        <v-avatar color="#CD212A1F" size="56">
                            <v-icon color="#CD212A">
                            mdi-emoticon-sad
                            </v-icon>
                        </v-avatar>
                        <div class="ml-2">
                            <div class="font-weight-normal">
                            <strong>{{ $number(all.lost??0)}}</strong>
                            </div>
                            <div>{{ $isKh()?referralStatuses[2].name_kh:referralStatuses[2].name_en }}</div>
                        </div>

                    </v-card-title>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="3">
                <v-card flat>
                    
                    <v-card-title style="display:flex" >
                    <v-avatar color="#FA7A351F" size="56">
                        <v-icon color="#FA7A35">
                        mdi-phone-in-talk
                        </v-icon>
                    </v-avatar>
                    <div class="ml-2">
                        <div class="font-weight-normal">
                        <strong>{{ $number(all.enquiring??0)}}</strong>
                        </div>
                        <div>{{ $isKh()?referralStatuses[1].name_kh:referralStatuses[1].name_en }}</div>
                    </div>

                    </v-card-title>
                </v-card>
            </v-col>
        </v-row>
        
        <v-divider></v-divider>
        <v-row>
            <v-col>
                <v-card flat md="6" cols="12">
                      
                    <v-card-title style="display:flex" >
                        <v-icon color="#FFA500">
                            mdi-trophy
                        </v-icon>
                        {{ $t('Leaderboard') }}
                    </v-card-title>
                    <v-card-text>
                        <v-simple-table>
                            <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>{{ $t('Name') }}</th>
                                    <th># {{ $t('Referrals') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(r,index) in allReferrers" :key="r.id">
                                    <td>
                                        {{ index+1 }}
                                    </td>
                                    <td>
                                        {{ r.name }}
                                    </td>
                                    <td>
                                        {{ r.referrals_count }}
                                    </td>
                                </tr>
                            </tbody>
                        </v-simple-table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col>
                <v-card flat md="6" cols="12">
                    <v-card-title>
                        {{ $t('My Stat') }}
                    </v-card-title>
                    <v-card-text>
                        <table style="width:100%">
                            <tr>
                                <th>{{ $t('Total') }} {{ $t('Referrals') }}</th>
                                <td>{{ $number(my.total??0) }}</td>
                            </tr>
                            <tr>
                                <th>{{ $isKh()?referralStatuses[3].name_kh:referralStatuses[3].name_en }}</th>
                                <td>{{ $number(my.won??0) }}</td>
                            </tr>
                            <tr>
                                <th>{{ $isKh()?referralStatuses[2].name_kh:referralStatuses[2].name_en }}</th>
                                <td>{{ $number(my.lost??0) }}</td>
                            </tr>
                            <tr>
                                <th>{{ $isKh()?referralStatuses[1].name_kh:referralStatuses[1].name_en }}</th>
                                <td>{{ $number(my.enquiring??0) }}</td>
                            </tr>
                        </table>
                    </v-card-text>
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

        my:Array(),
        all:Array(),
        allReferrers:Array(),
        referralStatuses:Object(),
        branches:Array(),
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
            this.$emit('filteredBranch',item.branch);
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
