<template>
    <Navbar />
     
     <div class="container-fluid">
       <div class="row">
         
         <div class="col-md-3 col-lg-2 vh-100 p-0">
           <PatientSidebar />
         </div>
         <div class="col-md-9 col-lg-10 p-4">
          <div class="dashboard-header">
            <h2>
              <span class="welcome-message">Welcome {{ user.name }} </span>
              <span class="c">{{ currentDate }}</span>
            </h2>
            <br>
    <p class="welcome-message">As a patient, you can easily book appointments with your preferred doctor, view your upcoming appointments, and access your prescriptions, ensuring smooth and efficient healthcare management...</p>
    
          </div>
          <div class="status-section mb-4">
            <h3 class="center">Status Summary</h3>
            <div class="row">
            <div class="col-md-4">
              <div class="status-card">
                  <router-link to="/patient/doctors" class="no-underline">
                  <h5 class="card-label">Number Of Doctors</h5>
                  <p class="card-count">{{ numberOfDoctors }}</p>
                </router-link>
              
              </div>
            </div>
            <div class="col-md-4">
              <div class="status-card">
               
                  <router-link to="/patient/schedules" class="no-underline">
                  <h5 class="card-label">Number of Schedules</h5>
                  <p class="card-count">{{ numberOfSchedules }}</p>
                  </router-link>
               
              </div>
            </div>
            <div class="col-md-4">
              <div class="status-card">
               
                  <router-link to="/patient/appointment" class="no-underline">
                  <h5 class="card-label">Number of Appointments</h5>
                  <p class="card-count">{{ numberOfAppointments }}</p>
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
import { ref, onMounted } from 'vue';
import PatientSidebar from '../../Layouts/Patient-Sidebar.vue';


export default {
    name: 'AdminHome',
    components: {
      Navbar,
      PatientSidebar,
      
    },

    data() {
      return {
      numberOfDoctors: 0,
      numberOfSchedules: 0,
      numberOfAppointments: 0,
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
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      this.user = JSON.parse(storedUser);
      }

  },  

  setup() {
    const schedules = ref([]); 
    const appointments = ref([]); 
    const doctors = ref([]);
    const numberOfSchedules = ref(0); 
    const numberOfAppointments = ref(0);
    const filteredAppointmentsByDoctor = ref([]);
    const numberOfDoctors = ref(0);
    const error = ref(null); 
    

    const fetchSchedules = async () => {
      try {
        const response = await axios.post(`/patient/schedules/`);
        console.log("Schedules fetched:", response.data);
        schedules.value = response.data.schedules;


        numberOfSchedules.value = response.data.schedules.length;

      } catch (error) {
        console.error("Error fetching schedules:", error);
        error.value = 'Failed to load schedules. Please try again.';
      }
    };

    const fetchAppointments = async () => {
      const userId = localStorage.getItem('user_id'); 
      try {
        const response = await axios.post(`/appointments/${userId}`);
        console.log("appointments fetched:", response.data);
        appointments.value = response.data;

        numberOfAppointments.value = response.data.length;
      } catch (error) {
        console.error("Error fetching appointments:", error);
        error.value = 'Failed to load doctors. Please try again.';
      }
    };


const fetchDoctors = async () => {
  try {
    const response = await axios.post(`/doctors`); 
    doctors.value = response.data; 

    numberOfDoctors.value = response.data.length;
  } catch (error) {
    console.error("Error fetching appointments:", error);
  }
};


    onMounted(() => {
      fetchSchedules();
      fetchAppointments();
      fetchDoctors();
    });

    return {
      schedules,
      numberOfSchedules,
      error,
      fetchAppointments,
      numberOfAppointments,
      numberOfDoctors,
     fetchDoctors,
      };
  }
};


</script>
  
<style scoped>

@import "/public/css/dashboard.css";

</style>