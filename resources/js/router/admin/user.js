import Dashboard from '../../views/admin/Dashboards/Dashboard.vue'
import User from '../../views/admin/Users/Index.vue'
import UserResetPassword from '../../views/admin/Users/ResetPassword.vue'
import Role from '../../views/admin/Roles/Index.vue'
import Permission from '../../views/admin/Roles/Permission.vue'
import Branch from '../../views/admin/Branches/Index.vue'
import School from '../../views/admin/Schools/Index.vue'
import Status from '../../views/admin/Statuses/Index.vue'
import Stage from '../../views/admin/Stages/Index.vue'
import Source from '../../views/admin/Sources/Index.vue'
import Category from '../../views/admin/Categories/Index.vue'
import Product from '../../views/admin/Products/Index.vue'
import Lead from '../../views/admin/Leads/Index.vue'
import MyReferral from '../../views/admin/MyReferrals/Index.vue'
import PaymentStatus from '../../views/admin/PaymentStatuses/Index.vue'
import ReferralStatus from '../../views/admin/ReferralStatuses/Index.vue'
import MyReferralShow from '../../views/admin/MyReferrals/Show.vue'
import Referral from '../../views/admin/Referrals/Index.vue'
import ReferralShow from '../../views/admin/Referrals/Show.vue'
import Commission from '../../views/admin/Commissions/Index.vue'
import AllReferral from '../../views/admin/Commissions/AllReferral.vue'
import WeeklyReport from '../../views/admin/WeeklyReports/Index.vue'
import WeeklyReportCreate from '../../views/admin/WeeklyReports/Create.vue'
import WeeklyReportShow from '../../views/admin/WeeklyReports/Show.vue'
import AllReferralShow from '../../views/admin/Commissions/AllReferralShow.vue'
import CommissionPayment from '../../views/admin/Commissions/AllPayment.vue'
import GoogleMap from '../../views/admin/Maps/Index.vue'
import GoogleMapView from '../../views/admin/Maps/Map.vue'
import Profile from '../../views/admin/Users/Profile.vue'
import Program from '../../views/admin/Programs/Index.vue'
export default [
    {
        path: '',
        name: 'dashboard',
        component: Dashboard,
        meta: {
          requiresAuth: true,
          permission:'dashboard'
        }
    },
    
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
          requiresAuth: true,
          permission:'dashboard'
        }
    },
    {
        path: '/users',
        name: 'admin.users',
        component: User,
        meta: {
            requiresAuth: true,
            permission:'user'
        }
    },
    {
        path: '/profile',
        name: 'admin.profile',
        component: Profile,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/users/resetPassword',
        name: 'admin.users.resetPassword',
        component: UserResetPassword,
        meta: {
            requiresAuth: true,
            permission:'user'
        }
    },
    {
        path: '/roles',
        name: 'admin.roles',
        component: Role,
        meta: {
            requiresAuth: true,
            permission:'role'
        }
    },
    {
        path: '/permission/:role',
        name: 'admin.permission',
        component: Permission,
        meta: {
            requiresAuth: true,
            permission:'permission'
        }
    },
    {
        path: '/branches',
        name: 'admin.branches',
        component: Branch,
        meta: {
            requiresAuth: true,
            permission: 'branch',
        }
    },
    {
        path: '/schools',
        name: 'admin.schools',
        component: School,
        meta: {
            requiresAuth: true,
            permission: 'School',
        }
    },
    {
        path: '/statuses',
        name: 'admin.statuses',
        component: Status,
        meta: {
            requiresAuth: true,
            permission: 'Status',
        }
    },
    {
        path: '/stages',
        name: 'admin.stages',
        component: Stage,
        meta: {
            requiresAuth: true,
            permission: 'Stage',
        }
    },
    {
        path: '/sources',
        name: 'admin.sources',
        component: Source,
        meta: {
            requiresAuth: true,
            permission: 'Source',
        }
    },
    {
        path: '/categories',
        name: 'admin.categories',
        component: Category,
        meta: {
            requiresAuth: true,
            permission: 'Category',
        }
    },
    {
        path: '/products',
        name: 'admin.products',
        component: Product,
        meta: {
            requiresAuth: true,
            permission: 'Product',
        }
    },
    {
        path: '/leads',
        name: 'admin.leads',
        component: Lead,
        meta: {
            requiresAuth: true,
            permission: 'Lead',
        }
    },
 
    {
        path: '/referralStatuses',
        name: 'admin.referralStatuses',
        component: ReferralStatus,
        meta: {
            requiresAuth: true,
            permission: 'ReferralStatus',
        }
    },
    {
        path: '/paymentStatuses',
        name: 'admin.paymentStatuses',
        component: PaymentStatus,
        meta: {
            requiresAuth: true,
            permission: 'PaymentStatus',
        }
    },
    {
        path: '/myReferrals',
        name: 'admin.myReferrals',
        component: MyReferral,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/myReferrals/:referral_id',
        name: 'admin.myReferrals.show',
        component: MyReferralShow,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/referrals',
        name: 'admin.referrals',
        component: Referral,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/referrals/:referral_id',
        name: 'admin.referrals.show',
        component: ReferralShow,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/commissions',
        name: 'admin.commissions',
        component: Commission,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/allReferrals',
        name: 'admin.allReferrals',
        component: AllReferral,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/allReferrals/:user_id',
        name: 'admin.allReferrals.show',
        component: AllReferralShow,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/weeklyReportReferrals',
        name: 'admin.weeklyReports',
        component: WeeklyReport,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/weeklyReportReferrals/create',
        name: 'admin.weeklyReports.create',
        component: WeeklyReportCreate,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/weeklyReportReferrals/:weekly_report_referral_id',
        name: 'admin.weeklyReports.show',
        component: WeeklyReportShow,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/commissionPayments',
        name: 'admin.commissionPayments',
        component: CommissionPayment,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/mapLocations',
        name: 'admin.mapLocation',
        component: GoogleMap,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/mapLocations/map',
        name: 'admin.map',
        component: GoogleMapView,
        meta: {
            requiresAuth: true,
        }
    },
    {
        path: '/programs',
        name: 'admin.programs',
        component: Program,
        meta: {
            requiresAuth: true,
        }
    },
   
]
