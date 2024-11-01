<template>
    <Navbar />
     
     <div class="container-fluid">
       <div class="row">
         <div class="col-md-3 col-lg-2 vh-100 p-0">
           <Patientsidebar />
         </div>
         <div class="col-md-9 col-lg-10 p-4">
          <div class="add-department-info mb-4">
            <h2 class="heading">Available Doctors</h2>
            <p class="description">Browse through the list of doctors and their departments.<br> Patients can view doctor details and book appointments based on their availability.</p>
        </div>
      <div class="doctor-list-section">
          <div class="doctor-list-header d-flex justify-content-between align-items-center margin">
            <input
        type="text"
        class="form-control"
        v-model="searchQuery"
        placeholder="Search Doctor or Department"
      />
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Doctor Name</th>
                <th>Phone</th>
                <th>Department</th>

              </tr>
            </thead>
            <tbody>
                <tr v-if="filteredDoctors.length === 0">
      <td colspan="6" class="text-center">No Doctor available</td>
    </tr>
              <tr v-for="doctor in filteredDoctors" :key="doctor.id">
                <td>{{ doctor.name }}</td>
                <td>{{ doctor.phone }}</td>
                <td>{{ doctor.department }}</td>
              </tr>
            </tbody> 
          </table>
        </div>
        </div>
        </div>
        </div>
        </template>

<script>
import axios from 'axios';
 import Navbar from "../../Layouts/Navbar.vue";
 import Patientsidebar from "../../Layouts/Patient-Sidebar.vue";
 import { ref, computed } from 'vue';


  
 
 export default {
 name: "Doctor",
 components: {
   Navbar,
   Patientsidebar,
 },
  setup() {
          const doctors = ref([]);
          const searchQuery = ref("");
          const error = ref(null);


          const fetchDoctors = async ()=> {
            try {
              const response = await axios.post("/doctors");
              doctors.value = response.data;
            } catch (error) {
              console.error('error fetchind ',error);
              error.value = 'failed to load doctors';
            }
          };

          fetchDoctors();

          const filteredDoctors = computed(() => {
      if (!searchQuery.value) {
        return doctors.value;
      }
      return doctors.value.filter(doctor => {
        return (
          doctor.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          doctor.department.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
      });
    });

return {
            doctors,
            searchQuery,
            filteredDoctors,
            error,
            
          }

        }
};
</script>

<style scoped>
@import "/public/css/model.css";

</style>
