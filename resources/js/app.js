
window.Vue = require('vue').default;
import MainRouter from 'vue-router';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import Vue from 'vue';
import axios from "axios";
import router from './router';
import { store }  from './store';
import permission from './plugins/permissions';
import AxiosInterceptors from './plugins/axios';
import VueHtmlToPaper from 'vue-html-to-paper';
import Vuelidate from 'vuelidate';
import moment from 'moment';
import { reduce } from 'lodash'
import colors from 'vuetify/lib/util/colors'


import es from 'vuetify/lib/locale/es.js'
import en from './lang/en.js'
import kh from './lang/kh.js'
import i18n from './i18n';
import '../css/font.css';




//use vue
const options = {
  name: '_blank',
  specs: [
  'fullscreen=yes',
  'titlebar=yes',
  'scrollbars=yes'
  ],
  styles: [
  'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
  'https://unpkg.com/kidlat-css/css/kidlat.css',
  '/css/app.css'
  ],
  timeout: 1000, // default timeout before the print window appears
  autoClose: false, // if false, the window will not close after printing
  windowTitle: window.document.title, // override the window title
} 

Vue.use(MainRouter, router, store);
Vue.use(Vuetify);
Vue.use(Vuelidate);
Vue.use(permission,store);
Vue.use(AxiosInterceptors,axios);
Vue.use(VueHtmlToPaper,options);
//vue compoment
Vue.component('Admin', require('./App.vue').default);

Vue.filter('uppercase',function(value){
  return value.toUpperCase();
});


Vue.filter('currency',function(value,currency='USD'){
  const formatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: currency,
      minimumFractionDigits: 2
  })
  return formatter.format(value);
});
Vue.filter('formatDateWithTime', function(value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY h:mm a')
  }
});
Vue.filter('formatDateOnly', function(value) {
  if (value) {
    return moment(String(value)).format('DD/MM/YYYY')
  }
});
//axios header
axios.defaults.headers.common['Authorization'] = "Bearer " + store.state.auth.token
axios.defaults.headers.post['Content-Type'] = 'application/json';
// Add a response interceptor
axios.interceptors.response.use(function (response) {
  // Any status code that lie within the range of 2xx cause this function to trigger
  
  // Do something with response data
  return response;
}, function (error) {
      if(error.response.status===401){
          store.dispatch("destroyToken").then(response => {
              //console.log(response);
              router.push({ path: "/login" }).catch(error => {
                  console.info(error)
              });
          });
      }
  // Any status codes that falls outside the range of 2xx cause this function to trigger
  // Do something with response error
  return Promise.reject(error);
});

//router
router.beforeEach((to, from, next) => {
    if(to.path&&to.path!='/admin/logout'&&to.path!='/admin/login'){
        localStorage.setItem('routeTo',to.path);
    }
    if (to.matched.some(record => record.meta.requiresAuth)) {
      console.log(to.name)
      if (!store.getters.loggedIn) {
        localStorage.setItem('routeTo',to.name);
        next({
          name: 'admin.login',
        })
      } else {
        if(store.getters.usingDefaultPassword&& to.name !== 'admin.users.resetPassword'){
          
          next({
            name:'admin.users.resetPassword',
          })
        }else{
          next()
        }
       
      }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
      if (store.getters.loggedIn) {
          next({
            name: 'dashboard',
          })
      } else {
        next()
      }
    } else {
      next()
    }
});
//init vue
Vue.prototype.$number=function(number){
  if(this.$vuetify.lang.current=='en')return number;
  const khmerNumerals = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];

  const digits = String(number).split('');
  const khmerDigits = digits.map((digit) => khmerNumerals[digit]);

  return khmerDigits.join('');
};
Vue.prototype.$t = function (key) {
  return this.$vuetify.lang.locales[this.$vuetify.lang.current].label[key]?this.$vuetify.lang.t('$vuetify.label.'+key):key;
};
Vue.prototype.$isKh = function () {
  return this.$vuetify.lang.current=='kh';
};
Vue.prototype.$font = function () {
  return (this.$vuetify.lang.current=='kh')?'Khmer OS Siemreap':'Ariel';
};
new Vue({
  vuetify: new Vuetify({
      theme: {
        dark:(localStorage.getItem('theme_dark'))?(localStorage.getItem('theme_dark')=='true'):false,
        themes: {
          light: {
              primary:'#0d8c20',
              // primary: '#3f51b5',
              secondary: '#b0bec5',
              anchor: '#4B0082',
              background:'#e9ecef',
              prepare:'#0d8c20',
          },
          dark:{
              primary:colors.green.darken1,
              // primary: '#3f51b5',
              secondary: colors.green.lighten4,
              anchor: colors.indigo.base,
              prepare:'#0d8c20',
          },
            // light: {
            //   primary:'#0d8c20',
            //   // primary: '#3f51b5',
            //   secondary: '#b0bec5',
            //   anchor: '#4B0082',
            //     // primary:colors.red.darken1,
            //     background:'#e9ecef',
            //     prepare:'#0d8c20',
            // },
            // dark:{
            //     primary:colors.red.darken1,
            //     // primary: '#3f51b5',
            //     secondary: colors.red.lighten4,
            //     anchor: colors.red.base,
            //     prepare:'#0d8c20',
            // },
        },
    
      },
      lang:{
          locales:{
            es,
            en,
            kh,
          },
          current:'kh',
      },
    }),
    router,
    store,
    created() {
        this.$vuetify.lang.current = (localStorage.getItem('lang'))?(localStorage.getItem('lang')):'en'
    },
}).$mount('#admin');
