import Module from '../../views/admin/Modules/Index.vue'
export default [
    {
        path: '/modules',
        name: 'admin.modules',
        component: Module,
        meta: {
            requiresAuth: true,
            //permission:'dashboard'
        }
    },
]