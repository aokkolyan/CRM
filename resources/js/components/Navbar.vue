<template>
    <div>
        <template v-if="!$route.meta.requiresVisitor">
            <nav>
                <v-app-bar app >
                    <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
                    <!-- <v-toolbar-title><span style="color:red"> ***UAT Customer Relationship Management System</span></v-toolbar-title> -->
                    <v-spacer></v-spacer>
                    <v-menu
                        v-model="notificationMenu"
                        :close-on-content-click="false"
                        :nudge-width="400"
                        offset-x
                        offset-y
                        left
                    >
                        <template v-slot:activator="{ on, attrs }">
                        <v-badge
                            color="error"
                            :content="newNotifications"
                            :value="newNotifications"
                            bordered
                            overlap
                        >
                            <v-btn
                                color="indigo"
                                text
                                v-bind="attrs"
                                v-on="on"
                                small
                                fab
                            >
                                <v-icon >
                                    mdi-bell
                                </v-icon>
                            </v-btn>
                        </v-badge>
                        </template>
                
                        <v-card max-height="354">
                        <v-card-text>Notification</v-card-text>
                        <v-list v-if="notifications.length==0">
                            <v-list-item>
                                <v-list-item-content>
                                    <v-list-item-title>No notification</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list>
                        <v-list v-else>
                        <v-list-item-group
                            v-model="selected"
                            active-class="pink--text"
                            multiple
                        >
                        <template  >
                            <a
                            v-for="notification in notifications"
                            @click="noticed(notification)"
                            :key="notification.id">
                                <v-list-item 
                                class=""
                                >
                                    <v-list-item-avatar>
                                        <img
                                        :src="notification.img"
                                        alt="John"
                                        >
                                    </v-list-item-avatar>
                        
                                    <v-list-item-content>
                                        <v-list-item-title>{{notification.title}}</v-list-item-title>
                                        <v-list-item-subtitle>{{notification.message}}</v-list-item-subtitle>
                                    </v-list-item-content>
                                    <v-list-item-action v-if="notification.status==0">
                                        <v-btn icon>
                                            <v-icon color="pink">mdi-information</v-icon>
                                        </v-btn>
                                    </v-list-item-action>
                                    <v-list-item-action v-else>
                                        {{ notification.created_at }}
                                    </v-list-item-action>
                                </v-list-item>
                            </a>
                            </template>
                        </v-list-item-group>
                        </v-list>
                
                        </v-card>
                    </v-menu>
                    <v-menu
                        v-model="menu"
                        :close-on-content-click="false"
                        :nudge-width="200"
                        offset-x
                        >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            color="indigo"
                            icon
                            v-bind="attrs"
                            v-on="on"
                            >
                                <v-icon>
                                    mdi-account
                                </v-icon>
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="pa-md-4 mx-lg-auto">
                                    <v-icon right>mdi-account-circle</v-icon>
                                    <span> {{ user.name }}</span>
                                </span>
                            </v-card-title>
                            <v-card-text>
                                <v-btn outlined color="primary" to="/profile" ><v-icon dark >mdi-account</v-icon> Profile</v-btn>
                                <div>
                                    <v-radio-group v-model="$vuetify.lang.current" row @change="langChange()">
                                        <v-radio value="kh">
                                            <template v-slot:label>
                                                <div><strong>KH</strong></div>
                                            </template>
                                        </v-radio>
                                        <v-radio value="en">
                                            <template v-slot:label>
                                                <div> <strong>EN</strong></div>
                                            </template>
                                        </v-radio>
                                    </v-radio-group>
                                </div>
                                <div>
                                    <v-switch
                                    v-model="$vuetify.theme.dark"
                                    @change="themeChange()"
                                    label="Dark Mode"
                                    ></v-switch>
                                </div>
                             
                                <v-btn dark color="blue-grey darken-2" router-link="/login"  @click.native="logout">
                                    
                                    <v-icon dark >mdi-exit-to-app</v-icon>Log Out
                                </v-btn>
                            </v-card-text>
                        </v-card>
                    </v-menu>
                   
                </v-app-bar>
                <v-navigation-drawer v-model="drawer" app>
                    <v-list dense>
                        <v-card>
                            <v-img
                                style="margin:auto"
                                lazy-src="/images/logo/logo.png"
                                max-width="100px"
                                src="/images/logo/logo2.png"
                                >
                            </v-img>
                            <div style="text-align: center;">Westline Education Group</div>
                        </v-card>
                        <template
                        v-for="menu in menus"
                        >
                        <v-list-item
                            color="primary"
                            link
                            :to="menu.link"
                            :key="menu.id"
                            v-if="(menu.parentId==null||menu.parentId==0)&&menu.children.length==0&&menu.type==1"
                            :class="[(($route.fullPath===menu.link)||($route.fullPath==='/dashboard'&&menu.link==='/'))?'v-item--active v-list-item--active':'']"
                            >
                                <template>
                                    <v-list-item-icon>
                                        <v-icon>{{menu.icon}}</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-title>{{$t(menu.name) || 'Default Translation'}}</v-list-item-title>
                                </template>
                            </v-list-item>
                            <v-list-group
                                :key="menu.id"
                                v-if="(menu.parentId==null||menu.parentId==0)&&menu.children.length>0&&menu.type==0"
                                :value="currentLinkIsChild(menu.children)"
                                :prepend-icon="menu.icon"
                            >
                                <template v-slot:activator>
                                    <v-list-item-title>{{$t(menu.name)}}</v-list-item-title>
                                </template>
                                <v-list-item
                                    v-for="sub,i in menu.children" :key="i"
                                    link
                                    :to="sub.link"
                                >
                                    <v-list-item-icon>
                                        <v-icon>{{ sub.icon }}</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-title
                                    >{{ sub.name }}</v-list-item-title>
                                </v-list-item>
                            </v-list-group>
                        </template>
                    </v-list>

                </v-navigation-drawer>
            </nav>
        </template>
    </div>
</template>

<script>

import axios from "axios";
export default {
    props: {
        source: String
    },
    data: () => ({
        user: null,
        drawer: null,

        notifications:[

        ],
        
        menu: false,
        notificationMenu:false,
        messages:2,
        selected:[],
    }),
    created() {
        this.menus=this.$store.state.auth.menu;
        
        this.listToTree(this.menus);
        this.user = this.$store.state.auth.user;
        if(this.user)this.getNotification();
        
    },
    computed:{
        newNotifications(){
            return this.notifications.filter(x=>x.status===0).length;
        }
    },
    methods: {
        getNotification(){

            return new Promise((resolve, reject) => {
                axios
                .get("/api/notifications")
                .then(response => {
                    this.notifications = response.data;
                    this.selected=this.notifications.map((x,i)=>({'status':x.status,'index':i})).filter(e=>e.status===0).map(f=>f.index),
                    setTimeout(() => {
                        resolve(response);
                    }, 100);
                })
                .catch(error => {
                    reject(error);
                });
            });
        },
        logout() {
            this.$store.dispatch("destroyToken").then(response => {
                //console.log(response);
                this.$router.push({ path: "/login" }).catch(error => {
                    console.info(error)
                });
            });
        },
        listToTree(list) {
            list.sort((a, b) => (a.ordering < b.ordering ? -1 : 1));
            console.log('asdfas');
            const map = {};
            let node;
            const roots = [];
            let i;
            for (i = 0; i < list.length; i += 1) {
                map[list[i].id] = i; // initialize the map
                list[i].children = []; // initialize the children
            }
            for (i = 0; i < list.length; i += 1) {

                node = list[i];
                if (node.parentId !== null && node.parentId !==0) {

                    // if you have dangling branches check that map[node.parentId] exists
                    if( list[map[node.parentId]]!=undefined){
                        // //console.log('here');
                        list[map[node.parentId]].children.push(node);
                    }
                } else {
                    roots.push(node);
                }
            }
            // Register the navigation to the service

            // Set the main navigation as our current navigation
            return roots;
        },
        currentLinkIsChild(children){
            let childHas=0;
            children.forEach(element => {
                //console.log(this.$route.fullPath);
                if(element.link===this.$route.fullPath){
                    childHas++;
                }
            });

            if(childHas>0){
                return true;
            }else{

                return false;
            }
        },
        noticed(n){
            // //console.log(this.notification);
            this.selected.splice(this.selected.indexOf(n.id));
            // //console.log(this.notification);
            axios.post('/api/notifications/noticed',n);
            this.$router
                .push(n.link) 
                .catch((error) => {
                // Handle any errors that occur during the POST request
                console.error(error);
            });;

        },
        langChange() {
            localStorage.setItem('lang', this.$vuetify.lang.current);
        },
        themeChange(){
            localStorage.setItem('theme_dark', this.$vuetify.theme.dark.toString());
        },
        toggleIsPlaying() {
            this.isPlaying = !this.isPlaying;
        },
        // t(s,e){
        //     return this.$vuetify.lang.t('$vuetify.label.'+s,e);
        // },
        // isKh(){
        //     return this.$vuetify.lang.current=='kh'
        // }
    },

};
</script>

