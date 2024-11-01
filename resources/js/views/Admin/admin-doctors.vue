<template>
   <Navbar />
    
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3 col-lg-2 vh-100 p-0">
          <Adminsidebar />
        </div>
        <div class="col-md-9 col-lg-10 p-4">
          <div class="add-department-info mb-4">
            <h2 class="heading">Doctor Management</h2>
            <p class="description">Here you can add new doctors, view the list of current doctors, and manage them as needed.</p>
        </div>
        <div class="doctor-list-section">
          <div class="doctor-list-header d-flex justify-content-between align-items-center margin">
            
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Doctor Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="doctors.length === 0">
                <td colspan="6" class="text-center">No doctors available</td>
              </tr>
              <tr v-for="doctor in doctors" :key="doctor.id">
                <td>{{ doctor.name }}</td>
                <td>{{ doctor.email }}</td>
                <td>{{ doctor.department }}</td>
                <td>{{ doctor.phone }}</td>
                <td>
                      
                  <button class="action" @click="destroyDoctor(doctor.id)">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody> 
          </table>
          <div class="add-doctor-container">
    <button class="add-doctor btn-responsive" @click="showAddDoctorModal = true">
      <i class="fas fa-plus"></i> Add Doctor
    </button>
  </div>
        
        </div>
      </div>
    </div>

    <div v-if="showAddDoctorModal" class="modal-backdrop">
      <div class="modal-content">
        <h3 class="center">Add New Doctor</h3>
        <form @submit.prevent="storeDoctor">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" v-model="form.name" required />
          </div>
          <div class="form-group">
            <label for="email">Email</label>    
            <input type="email" id="email" v-model="form.email" required />
          </div>
          <div class="form-group">
            <label for="department">Department</label>
            <div class="custom-select-wrapper">
              <select id="department" v-model="form.department" required>
                <option value="" disabled>Select Department</option>
                <option v-for="department in departments" :key="department.id" :value="department.name">
                  {{ department.name }}
                </option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" v-model="form.phone" required />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" v-model="form.password" required />
          </div>
          <div class="form-group">
            <label for="confirmpassword">Confirm Password</label>
            <input type="password" id="confirmpassword" v-model="form.password_confirmation" required />
          </div>
          <div class="button-group">
            <button type="submit" class="btn btn-add">Submit</button>
            <button type="reset" class="btn btn-add-secondary" @click="resetForm">Reset</button>
            <button type="close" class="btn btn-add-secondary" @click="showAddDoctorModal = false">Close</button>
          </div>
        </form>
      </div>
    </div>
</div>
</template>
<script>
  import axios from 'axios';
  import Navbar from "../../Layouts/Navbar.vue";
  import Adminsidebar from "../../Layouts/Admin-Sidebar.vue";

import { ref,watch } from 'vue';
import Swal from "sweetalert2";

export default {
  name: "Admin Doctor",
  components: {
    Adminsidebar,
    Navbar,
  },
  setup() {

    const doctors = ref([]);
    const departments = ref([]);
    const error = ref(null);
    const showAddDoctorModal = ref(false);

    const form = ref({
      name: "",
      email: "",
      department: "",
      phone: "",
      password: "",
      confirmpassword: "",
    });

    const fetchDoctors = async () => {
      try {
        const response = await axios.post('/doctors');
        doctors.value = response.data;
      } catch (error) {
        console.error('Error fetching doctors:', error);
        error.value = 'Failed to load doctors. Please try again.';
      }
    };

    const fetchDepartments = async () => {
      try {
        const response = await axios.post('/departments');
        departments.value = response.data;
      } catch (error) {
        console.error('Error fetching departments:', error);
        error.value = 'Failed to load departments. Please try again.';
      }
    };

    const storeDoctor = async () => {
      try {
        await axios.post('/doctors/add', form.value);
        Swal.fire({
          icon: 'success',
          title: 'Doctor registered successfully',
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 2000,
        });
        showAddDoctorModal.value = false;
        resetForm();
        fetchDoctors();
      } catch (error) {
        console.error('Error registering doctor:', error);
        Swal.fire({
          icon: 'error',
          title: 'Registration failed',
          text: error.response?.data?.message || 'An error occurred. Please try again.',
        });
      }
    };

    const destroyDoctor = async (doctorId) => {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      }).then(async (result) => {
        if (result.isConfirmed) {
          try {
            await axios.delete(`/doctors/delete/${doctorId}`);
            fetchDoctors();
            Swal.fire({
      icon: 'success',
      title: 'Doctor removed successfully',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
    });
          } catch (error) {
            console.error('Error removing doctor:', error);
            Swal.fire('Error!', 'Failed to remove the doctor.', 'error');
          }
        }
      });
    };

    const resetForm = () => {
      form.value = {
        name: "",
        email: "",
        department: "",
        phone: "",
        password: "",
        confirmpassword: "",
      };
    };

    watch(showAddDoctorModal, (newValue) => {
      if (newValue === true) fetchDepartments();
    });


    fetchDoctors();

    return {
      doctors,
      form,
      showAddDoctorModal,
      departments,
      error,
      storeDoctor,
      destroyDoctor,
      resetForm,
    };
  },
};
</script>

<style scoped>
@import "/public/css/model.css";

</style>
