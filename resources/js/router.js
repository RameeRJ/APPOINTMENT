import { createRouter, createWebHistory } from 'vue-router';

const routes = [

     {
        path: '/authentication',
        name: 'authentication',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/Auth/auth.vue'
          ),    
      },
      {
        path: '/',
        name: 'Index',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/index.vue'
          ),    
      },

      {
        path: '/:pathMatch(.*)*',
        name: 'PageNotFound',
        component: () =>
          import(/* webpackChunkName: "not-found" */ './views/ErrorPage.vue'),
      },



      {
        path: '/admin/dashboard',
        name: 'admin dashboard',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/Admin/admin-dashboard.vue'
          ),    
          meta: { requiresAuth: true, userType: 'admin'},
      },
      {
        path: '/admin/doctors',
        name: 'admin doctors',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/Admin/admin-doctors.vue'
          ),    
          meta: { requiresAuth: true, userType: 'admin'},
      },
      {
        path: '/admin/departments',
        name: 'admin departments',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/Admin/admin-departments.vue'
          ),    
          meta: { requiresAuth: true, userType: 'admin'},
      },
      
      


      
      {
        path: '/doctor/dashboard',
        name: 'doctor dashboard',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/Doctor/doctor-dashboard.vue'
          ),    
          meta: { requiresAuth: true, userType: 'doctor'},
      },
      {
        path: '/doctor/schedule',
        name: 'doctor schedule',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/Doctor/doctor-schedule.vue'
          ),    
          meta: { requiresAuth: true, userType: 'doctor'},
      },
      {
        path: '/doctor/appointment',
        name: 'doctor appointment',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/Doctor/doctor-appointment.vue'
          ),    
          meta: { requiresAuth: true, userType: 'doctor'},
      },




      {
        path: '/patient/dashboard',
        name: 'patient dashboard',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/patient/patient-dashboard.vue'
          ),    meta: { requiresAuth: true, userType: 'patient'},  
      },
      {
        path: '/patient/doctors',
        name: 'patient doctors',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/patient/patient-doctors.vue'
          ),  
          meta: { requiresAuth: true, userType: 'patient'},  
      },
      {
        path: '/patient/schedules',
        name: 'patient schedule',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/patient/patient-schedules.vue'
          ),    
          meta: { requiresAuth: true, userType: 'patient'},
      },
      {
        path: '/patient/appointment',
        name: 'patient appointment',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/patient/patient-appointment.vue'
          ),    
          meta: { requiresAuth: true, userType: 'patient'},
      },
      {
        path: '/patient/profile',
        name: 'patient profile',
        component: () =>
          import(
            /* webpackChunkName: "home" */ './views/patient/patient-profile.vue'
          ),    
          meta: { requiresAuth: true, userType: 'patient'},
      },
     
      




];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('isAuthenticated'); 
  const userType = localStorage.getItem('userType'); 

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next({ name: 'authentication' }); 
    } else if (to.meta.userType && to.meta.userType !== userType) {
      next({ name: 'authentication' }); 
    } else {
      next(); 
    }
  } else {
    next();
  }
});

export default router;
