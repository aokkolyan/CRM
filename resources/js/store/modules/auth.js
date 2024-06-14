
import axios from 'axios';
export default {
    state: {
        user: JSON.parse(localStorage.getItem('user')) || null,
        permission: localStorage.getItem('permissions') || null,
        menu: JSON.parse(localStorage.getItem('menu')) || null,
    },
    getters: {
        loggedIn(state) {
            return state.user !== null
        },
        usingDefaultPassword(state) {
            return (state.user.default_password)
        }
    },
    mutations: {
        retrieveUser(state, data) {
            state.user = data.user;
            state.permission=data.permission;
            state.menu=data.menu;
        },
        destroyToken(state) {
            state.menu = null
            state.user = null
            state.permission = null
        },
        hasResetPassword(state){
            state.user.default_password=false
        }
       
    },
    actions: {
        hasResetPassword(context){
            let user=JSON.parse(localStorage.getItem('user'));
            user.default_password=false;
            localStorage.setItem('user', JSON.stringify(user))
            context.commit('hasResetPassword')

        },
        canAccess(state,to){
            var permission=state.menu;
            for (const element of permission) {
                //console.log(element.link);
                if(element.link==to.fullPath){
                    //console.log('can access');
                    return true;
                }
            };
            
            //console.log('can not access');
            return false;
        },
        destroyToken(context) {
            if (context.getters.loggedIn) {
                
                context.commit('destroyToken')
                localStorage.removeItem('user')
            }
        },
        retrieveToken(context, credentials) {
            
            // this.listToTree(response.data.data.menu);
            return new Promise((resolve, reject) => {
                axios.post("/api/user/login", {
                user_name: credentials.user_name,
                password: credentials.password,
                })
                .then(response => {
                    if(response.data.status==200){
                        const data = response.data.data
                        localStorage.setItem('user', JSON.stringify(response.data.data.user))
                        localStorage.setItem('permissions',JSON.stringify(response.data.data.user.permissions))
                        localStorage.setItem('menu',JSON.stringify(response.data.data.menu))
                        context.commit('retrieveUser', data)
                        
                        resolve(response)
                    }else{
                        resolve(response)
                    }
                  
                })
                .catch(error => {
                    reject(error)
                })
            })
        },
        
        retrieveUser(context) {
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
        axios.get('/api/users')
            .then(response => {
                context.commit('retrieveUser', response.data)
            })
            .catch(error => {
            //console.log(error)
        })
    },
   
  }
}