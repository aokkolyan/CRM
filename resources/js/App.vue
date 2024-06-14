<template>
  <v-app app :style="{
            background: $vuetify.theme.themes[theme].background,
            // fontFamily:$font()
            fontFamily:'Khmer OS Siemreap'
            }">
    <Navbar/>
    
    <v-main>
        <router-view></router-view>
      <Snackbar ref="Snackbar">
      </Snackbar>
    </v-main>
    <Overlay ref="Overlay">
    </Overlay>
  </v-app>
</template>
<script>
import Navbar from "./components/Navbar.vue";
import Snackbar from "@/components/Snackbar";
import Overlay from "@/components/Overlay";
Vue.prototype.$api_url = "https://123.123.123:9999"//declare global variable. use it in vue like 'this.$api_url'
export default {
  name: "App",
  components: {
    Navbar,
    Snackbar,
    Overlay,
  },
   data: () => ({
   
   }),
  mounted() {
    this.$root.Snackbar = this.$refs.Snackbar
    this.$root.Overlay = this.$refs.Overlay
  },
  methods: {
     

  },
  computed:{
    theme(){
      return (this.$vuetify.theme.dark) ? 'dark' : 'light'
    }
  }
};
</script>
