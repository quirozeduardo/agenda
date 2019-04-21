import Vue from 'vue';
import Router from 'vue-router';
import Login from './components/auth/Login.vue';
import Register from './components/auth/Register.vue';
import store from "./store/store";
import Configuration from "./views/ConfigurationView.vue";
import Impact from "./views/configuration/ImpactView.vue";
import Category from "./views/configuration/CategoryView.vue";
import Priority from "./views/configuration/PriorityView.vue";
import Status from "./views/configuration/StatusView.vue";
import HomeView from "./views/HomeView.vue";

Vue.use(Router);

const router = new Router({
  // mode: 'history',
  //base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'default',
      redirect: '/home'
    },
    { path: "*",
      redirect: '/home'
    },
    {
      path: '/home',
      name: 'home',
      component: HomeView,
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
      path: '/logout',
      name: 'logout'
    },
    {
      path: '/configuration',
      name: 'configuration',
      component: Configuration,
      meta: {
        requiresAuth: true
      },
      children: [
        {
          path: 'category',
          name: 'category',
          component: Category,
          meta: {
            requiresAuth: true
          },
        },
        {
          path: 'impact',
          name: 'impact',
          component: Impact,
          meta: {
            requiresAuth: true
          },
        },
        {
          path: 'priority',
          name: 'priority',
          component: Priority,
          meta: {
            requiresAuth: true
          },
        },
        {
          path: 'status',
          name: 'status',
          component: Status,
          meta: {
            requiresAuth: true
          },
        }
      ]
    },
  ],

});
router.beforeEach(async (to, from, next) => {
  console.log(to);
  await store.dispatch('loggedIn');
  let logged = store.state.logged;
  if (to.meta.requiresAuth === true) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!logged) {
      next('/login');
    } else {
      next();
    }
  }
  else if (to.name === 'logout') {
    await store.dispatch('logout');
    next('/login');
  }
  else if (to.name === 'login' || to.name === 'register') {
    if (logged) {
      next('/home');
    }else {
      next();
    }
  }
  else {
    next(); // make sure to always call next()!
  }
});
export default router;
