<template>
  <Navbar />
  
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 col-lg-2 vh-100 p-0">
        <Adminsidebar />
      </div>
      <div class="col-md-9 col-lg-10 p-4">
    
        <div class="add-department-info mb-4">
          <h2 class="heading">Department Management</h2>
          <p class="description">Here you can add new departments, view the list of current departments, and manage them as needed.</p>
        </div>

        <div class="department-list-section">
          <div class="doctor-list-header d-flex justify-content-between align-items-center margin">
          </div>

          <!-- Department List Table -->
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Sl. No</th>
                <th>Department Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="departments.length === 0">
                <td colspan="6" class="text-center">No departments available</td>
              </tr>
              <tr v-for="(department, index) in departments" :key="department.id">
                <td>{{ index + 1 }}</td>
                <td>{{ department.name }}</td>
                <td>
                  <button class="action" @click="destroyDepartment(department.id)">
                    <i class="fas fa-trash-alt"></i> 
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="add-doctor-container">
            <button class="add-doctor btn-responsive" @click="showAddDepartmentModal = true">
              <i class="fas fa-plus"></i> Add Department
            </button>
          </div>
        </div>

        <!-- Add Department Modal -->
        <div v-if="showAddDepartmentModal" class="modal-backdrop">
          <div class="modal-content">
            <h3>Add New Department</h3>
            <form @submit.prevent="storeDepartment">
              <div class="form-group">
                <label for="departmentName">Department Name</label>
                <input type="text" v-model="form.name" id="departmentName" class="form-control" required />
              </div>
              <div class="btn-group">
                <button type="submit" class="btn add1">Add</button>
                <button type="button" class="btn add2" @click="showAddDepartmentModal = false">Close</button>
              </div>
            </form>
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
   import { ref } from 'vue';
   import Swal from "sweetalert2";

          export default {
            name: "Departments",
            components: {
              Adminsidebar,
              Navbar,
            },
            setup() {
    const departments= ref([]);
    const error = ref(null);
    const showAddDepartmentModal = ref(false);

    const form = ref({
      name: "",
    });
    
    const fetchDepartments = async () => {
      try {
        const response = await axios.post('/departments'); 
        departments.value = response.data; 
      } catch (error) {
        console.error('Error fetching department:', error);
        error.value = 'Failed to load department. Please try again.'; 
      }
    };


    const storeDepartment = async () => {
      try {
        await axios.post('/departments/add', form.value); 
        Swal.fire({
          icon: 'success',
          title: 'Department registered successfully',
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 2000,
          padding: '2em',
        });
        showAddDepartmentModal.value = false; 
        resetForm(); 
        fetchDepartments(); 
      } catch (error) {
        console.error('Error registering department:', error);
        Swal.fire({
          icon: 'error',
          title: 'Registration failed',
          text: error.response?.data?.message || 'An error occurred. Please try again.',
        });
      }
    };
    const resetForm = () => {
      form.value = {
        name: "",
      };
    };


    const destroyDepartment = async (departmentId) => {
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
        await axios.delete(`/departments/delete/${departmentId}`); 
        Swal.fire({
          toast:true,
          position: 'top-end',
          icon: 'success',
          title: 'Department has been deleted.',
          showConfirmButton: false,
          timer: 2000
        });
        fetchDepartments();
      } catch (error) {
        console.error('Error removing department:', error);
        Swal.fire({
          icon: 'error',
          title: 'Failed!',
          text: 'Failed to delete department. Please try again.',
        });
      }
    }
  });
};
fetchDepartments();
return {
      departments,
      error,
      showAddDepartmentModal,
      form,
      fetchDepartments,
      storeDepartment,
      resetForm,
      destroyDepartment,
    };
  },
};
</script>
  
  <style scoped>
  @import "/public/css/table.css";
 


  </style>