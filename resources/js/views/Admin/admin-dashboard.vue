<template>
   <Navbar />
    
    <div class="container-fluid">
      <div class="row">
        
        <div class="col-md-3 col-lg-2 vh-100 p-0">
          <Adminsidebar />
        </div>
        
        
        <div class="col-md-9 col-lg-10 p-4">
          <div class="dashboard-header">
            <h2>
              <span class="welcome-message">Welcome {{ user.name }} </span>
              <span class="c">{{ currentDate }}</span>
            </h2>
            <br>
    <p class="welcome-message">As an admin, you are responsible for adding and managing doctors and departments, ensuring all information is accurate and up-to-date for effective operations.</p>
    
          </div>
          <div class="status-section mb-4">
            <h3 class="center">Status Summary
            </h3>
            <div class="row">
            <div class="col-md-6">
              <div class="status-card">
                  <router-link to="/admin/doctors" class="no-underline">
                  <h5 class="card-label">Number of Doctors</h5>
                  <p class="card-count">{{ numberOfDoctors }}</p>
                </router-link>
              
              </div>
            </div>
            <div class="col-md-6">
              <div class="status-card">
               
                  <router-link to="/admin/departments" class="no-underline">
                  <h5 class="card-label">Number of Departments</h5>
                  <p class="card-count">{{ numberOfDepartments }}</p>
                  </router-link>
               
              </div>
            </div>
          </div>
          
        </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import Navbar from "../../Layouts/Navbar.vue";
  import Adminsidebar from "../../Layouts/Admin-Sidebar.vue";
  
  
  export default {
    name: 'AdminHome',
    components: {
      Navbar,
      Adminsidebar
      
    },
    data() {
      return {
        numberOfDoctors: 0,
        numberOfDepartments: 0, 
        user: {}
      };
    },
    computed: {
    currentDate() {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date().toLocaleDateString(undefined, options);
    }
  },
    mounted() {
      this.fetchStats();
      this.fetchdepartment();
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        this.user = JSON.parse(storedUser);
        }
      
    },
    methods: {
      fetchStats() {
       
        axios.post('/doctors')
          .then(response => {
            this.numberOfDoctors = response.data.length;
          })
          .catch(error => {
            console.error('Error fetching doctors:', error);
          });   
      },
  
      fetchdepartment() {
        axios.post('/departments')
        .then(response => {
          this.numberOfDepartments = response.data.length;
        })
        .catch(error => {
          console.error('Error fetching departments:', error);
        });
      },

    },
  };
  </script>
  
  <style scoped>
  
  @import "/public/css/dashboard.css";

  
  
  </style>
  