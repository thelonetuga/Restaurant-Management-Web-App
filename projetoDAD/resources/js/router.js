window.Vue = require("vue");
import VueRouter from 'vue-router';
import store from './vuex.js';
import ItemsList from "./components/items/ItemsList.vue";
import UsersList from "./components/allworkers/ListUsers.vue";
import Register from "./components/register/register.vue";
import Login from "./components/login/login.vue";
import PrivateDashboard from "./views/Dashboard.vue";
import ManagerDashboard from "./components/manager/manager.vue";
import Home from "./views/Home.vue";
import UserProfile from "./components/allworkers/UsersInfo.vue";
import MainDashboard from "./components/allworkers/MainDashboard.vue";
import Confirmation from "./components/password/confirmation.vue";
import ListInvoices from "./components/cashier/ListInvoices.vue";
import listTables from "./components/manager/listTables.vue";
import CookOrders from "./components/cooks/ListMyOrders.vue";
import CreateMeal from "./components/waiters/CreateMeal.vue";
import MyMeals from "./components/waiters/MyMeals.vue";
import WaiterOrders from "./components/waiters/MyOrders.vue";
import CookDashboard from "./components/cooks/dashCook";
import WaiterDashboard from "./components/waiters/dashWaiter";
import CashierDashboard from "./components/cashier/dashCashier";


Vue.use(VueRouter);
const router = new VueRouter({
    routes: [{
        path: '/',
        name: 'home',
        component: Home
    }, {
        path: '/home',
        component: Home
    }, {
        path: '/items',
        name: 'items',
        component: ItemsList
    }, {
        path: '/login',
        name: 'login',
        component: Login
    }, {
        path: '/profile',
        name: 'profile',
        component: UserProfile,
        meta: {
            middleware: true
        }
    }, {
        path: '/password/confirmation',
        name: 'passwordConfirmation',
        component: Confirmation,
    }, {
        path: '/privatedashboard',
        name: 'privatedashboard',
        component: PrivateDashboard,
        props: true,
        meta: {
            middleware: true
        },
        children: [
            {
                path: 'manager',
                name: 'manager',
                component: ManagerDashboard,
                props: true,
                meta: {
                    middleware: true
                },
                children: [{
                    path: 'tables',
                    name: 'tables',
                    component: listTables
                },
                    {
                        path: 'listusers',
                        name: 'listusers',
                        component: UsersList,
                    }, {
                        path: 'register',
                        name: 'register',
                        component: Register,
                    },
                ],
            },
            {
                path: 'cook',
                name: 'cook',
                component: CookDashboard,
                props: true,
                meta: {
                    middleware: true
                },
                children:[
                    {
                        path: '/orders/my',
                        name: 'cookOrders',
                        component: CookOrders,
                        meta: {
                            middlewareAuth: true
                        }
                    },
                ]
            },
            {
                path: 'cashier',
                name: 'cashier',
                component: CashierDashboard,
                props: true,
                meta: {
                    middleware: true
                },
                children:[
                    {
                        path: 'invoices/list',
                        name: 'listInvoices',
                        component: ListInvoices,
                        meta: {
                            middleware: true
                        }
                    }
                ]
            },
            {
                path: 'waiter',
                name: 'waiter',
                component: WaiterDashboard,
                props: true,
                meta: {
                    middleware: true
                },
                children:[
                    {
                        path: '/meal/new',
                        name: 'newMeal',
                        component: CreateMeal,
                        meta: {
                            middlewareAuth: true
                        }
                    },
                    {
                        path: '/meals/my',
                        name: 'myMeals',
                        component: MyMeals,
                        meta: {
                            middlewareAuth: true
                        }
                },
                {
                    path: '/orders/my',
                    name: 'waiterOrders',
                    component: WaiterOrders,
                    meta: {
                        middlewareAuth: true
                    }
                },
                ]
            }],
        }, {
            path: '/mainDashboard',
            name: 'mainDashboard',
            component: MainDashboard,
            meta: {
                middleware: true
            }
        }
    ]
});
router.beforeEach((to, from, next) => {
    if (to.meta.middleware && (store.state.user == null)) {
        next('/login');
        return;
    }
    next();
});
export default router;
