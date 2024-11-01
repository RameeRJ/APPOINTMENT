<template>
    <Navbar />
     
     <div class="container-fluid">
       <div class="row">
         <div class="col-md-3 col-lg-2 vh-100 p-0">
           <Doctorsidebar />
         </div>
         <div class="col-md-9 col-lg-10 p-4">
          <div class="add-department-info mb-4">
            <h2 class="heading">Schedule Management</h2>
            <p class="description">Here you can add new schedules, view the list of current schedules, and manage them as needed.</p>
        </div>
        <div class="doctor-list-section">
          <div class="doctor-list-header d-flex justify-content-between align-items-center margin">
            
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Schedule Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>No.of Booking Available</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="schedules.length === 0">
                <td colspan="6" class="text-center">No schedules available</td>
              </tr>
              <tr v-for="schedule in schedules" :key="schedule.id">
                <td>{{ schedule.title }}</td>
                <td>{{ formatDate(schedule.date) }}</td>
                <td>{{ formatTime(schedule.start_time) }} - {{ formatTime(schedule.end_time) }}</td>
                <td>{{ schedule.max_bookings }}</td>
                <td>     
                  <button class="action" @click="destroySchedule(schedule.id)">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody> 
          </table>
          <div class="add-doctor-container">
    <button class="add-doctor btn-responsive" @click="showAddScheduleModal = true">
      <i class="fas fa-plus"></i> Add Schedule
    </button>
  </div>
</div>
      </div>
    </div>
    <div v-if="showAddScheduleModal" class="modal-backdrop">
      <div class="modal-content">
        <h3 class="center">Add New Schedule</h3>
        <form @submit.prevent="storeSchedule">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" v-model="form.title" required />
          </div>
          <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" v-model="form.date" required />
          </div>
          <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" id="start_time" v-model="form.start_time" required />
          </div>
          <div class="form-group">
            <label for="end_time">End Time</label>
            <input type="time" id="end_time" v-model="form.end_time" required />
          </div>
          <div class="form-group">
            <label for="max_bookings">Max Bookings</label>
            <input type="number" id="max_bookings" v-model="form.max_bookings" required />
          </div>
          <div class="button-group">
            <button type="submit" class="btn btn-add">Submit</button>
            <button type="reset" class="btn btn-add-secondary" @click="resetForm">Reset</button>
            <button type="close" class="btn btn-add-secondary" @click="showAddScheduleModal = false">Close</button>
          </div>
        </form>
      </div>
    </div>
   </div>
</template>
 
<script>
 import axios from 'axios';
  import Navbar from "../../Layouts/Navbar.vue";
  import Doctorsidebar from "../../Layouts/Doctor-Sidebar.vue";
  import { ref, onMounted } from 'vue';
import Swal from "sweetalert2";

   
  
  export default {
  name: "Doctor",
  components: {
    Navbar,
    Doctorsidebar,
  },
  setup(){

  const schedules = ref([]); 
    const showAddScheduleModal = ref(false); 
    const error = ref(null);

    const form = ref({
      title: '',
      date: '',
      start_time: '',
      end_time: '',
      max_bookings: '',
    });


    const fetchSchedules = async () => {
      const doctorId = localStorage.getItem('doctor_id'); 
      try {
        const response = await axios.post(`/schedules/${doctorId}`);
        console.log("Schedules fetched:", response.data);
        schedules.value = response.data.schedules;
      } catch (error) {
        console.error("Error fetching schedules:", error);
        error.value = 'Failed to load doctors. Please try again.';
      }
    };


    const storeSchedule = async () => {
      const doctorId = localStorage.getItem('doctor_id'); 
      if (!doctorId) {
        console.error("Doctor ID is not found in local storage.");
        return;
      }

      try {
        form.value.doctor_id = doctorId;
        const response = await axios.post('/add', form.value); 
        
        schedules.value.push(response.data.schedule);
        Swal.fire({
          icon: 'success',
          title: 'Schedule added successfully',
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 2000,
        });
        showAddScheduleModal.value = false;
        resetForm();

        fetchSchedules();
      } catch (error) {
        console.error("Error creating schedule:", error);
        Swal.fire({
          icon: 'error',
          title: 'Registration failed',
          text: error.response?.data?.message || 'An error occurred. Please try again.',
        });
      }
    };



    const destroySchedule = async (id) => {
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
            await axios.delete(`/schedules/${id}`);
            fetchSchedules();
    schedules.value = schedules.value.filter(schedule => schedule.id !== id);

    Swal.fire({
      icon: 'success',
      title: 'Schedule removed successfully',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
    });
  } catch (error) {
    console.error("Error removing schedule:", error);
    Swal.fire('Error', 'Failed to remove schedule. Please try again.', 'error');
  }
}
      });
    };



    const resetForm = () => {
      form.value = {
        title: '',
        date: '',
        start_time: '',
        end_time: '',
        max_bookings: '',
      };
      showAddScheduleModal.value = false; 
    };



    const formatTime = (time) => {
      const [hours, minutes] = time.split(':');
      const date = new Date();
      date.setHours(hours);
      date.setMinutes(minutes);
      return date.toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
      });
    };



    const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    day: 'numeric',    
    month: 'long',     
    year: 'numeric'    
  });
};



   
    onMounted(fetchSchedules);

    return {
      schedules,
      form,
      showAddScheduleModal,
      storeSchedule,
      resetForm,
      error,
      destroySchedule,
      formatTime,
      formatDate
    };
  },
};     


</script>


<style scoped>
@import "/public/css/model.css";

</style>
