import Vue from "vue";
import VueRouter from "vue-router";

import Dashboard from './pages/Dashboard';
import Items from './pages/Items';
import Auth from './pages/Auth';

import Menu from './components/Menu';

Vue.use(VueRouter);

Vue.component(Dashboard);
Vue.component(Auth);
Vue.component(Menu);

const router = new VueRouter({
    linkActiveClass: 'active',
    routes: [
        {path: '/auth', component: Auth, name: "auth"},
        {path: '/', redirect: '/dashboard'},
        {
            path: '/dashboard',
            components: {
                default: Dashboard,
                menu: Menu
            },
            name: "dashboard"
        },
        {
            path: '/items',
            components: {
                default: Items,
                menu: Menu
            },
            name: "items"
        },
    ],
    mode: "history"
});

// router.beforeEach(function (to, from, next) {
//     if (store.getters.token === null && from.name !== "auth") {
//         location.href = '/auth';
//     } else {
//         next();
//     }
// });

export default router;