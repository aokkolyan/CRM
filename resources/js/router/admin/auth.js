import Login from '../../views/admin/auth/Login.vue';
import Logout from '../../views/admin/auth/Logout.vue';
import Register from '../../views/admin/auth/Register.vue';
import DashboardAdmin from '../../views/admin/Dashboards/Dashboard.vue';

export default [
      {
        path: "/",
        component: DashboardAdmin,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/logout',
        name: 'admin.logout',
        component: Logout,
        meta: {
          requiresAuth: true,
        }
    },
    {
        path: '/login',
        name: 'admin.login',
        component: Login,
        meta: {
          requiresVisitor: true,
        }
    },
    {
      path: '/register',
      name: 'admin.register',
      component: Register,
      meta: {
        requiresVisitor: true,
      }
  }
]