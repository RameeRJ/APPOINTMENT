<template>
    <Navbar />
     
     <div class="container-fluid">
       <div class="row">
         <div class="col-md-3 col-lg-2 vh-100 p-0">
           <Patientsidebar />
         </div>
         <div class="col-md-9 col-lg-10 p-4">
          <div class="add-department-info mb-4">
            <h2 class="heading">Available Schedule</h2>
            <p class="description">Browse through the available schedules and doctors.<br> Patients can view schedule details and book appointments based on the schedules availability..</p>
        </div>
        <div class="doctor-list-section">
          <div class="doctor-list-header d-flex justify-content-between align-items-center margin">
            <input
        type="text"
        class="form-control"
        v-model="searchQuery"
        placeholder="Search Schedule or Doctor's Name"
      />
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Schedule Name</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>

              </tr>
            </thead>
            <tbody>
              <tr v-if="filteredSchedules.length === 0">
      <td colspan="5" class="text-center">No schedules available</td>
    </tr>
    <tr v-for="schedule in filteredSchedules" :key="schedule.id">
      <template v-if="schedule.doctor">
        <td>{{ schedule.title }}</td>
        <td>{{ schedule.doctor.name }}</td>
        <td>{{ formatDate(schedule.date) }}</td>
        <td>{{ formatTime(schedule.start_time) }} - {{ formatTime(schedule.end_time) }}</td>
        <td>
          <button class="action" @click="openBookingModal(schedule)">
            <span class="tooltip-text">Book Appointment</span>
          </button>
        </td>
      </template>
    </tr>
            </tbody>
          </table>
        </div>
        </div>
        </div>
        <div v-if="bookAppointmentModel" class="modal-backdrop">
      <div class="modal-content">
      
        <h3 class="center">Book Your Appointent</h3>
        <form @submit.prevent="bookAppointment">
          <div class="form-group">
            <label for="name">name</label>
            <input type="text" id="name" v-model="form.name" required />
          </div>
          <div class="form-group">
            <label for="age">Age</label>
            <input type="number" id="age" v-model="form.age" required />
          </div>
          <div class="form-group">
            <label for="place">Place</label>
            <input type="text" id="place" v-model="form.place" required />
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" v-model="form.phone" required />
          </div>

          <div class="button-group">
            <button type="submit" class="btn btn-add">Submit</button>
            <button type="reset" class="btn btn-add-secondary" @click="resetForm">Close</button>
          </div>
        </form>
      </div>
    </div>
        </div>
        </template>

<script>
import axios from 'axios';
 import Navbar from "../../Layouts/Navbar.vue";
 import Patientsidebar from "../../Layouts/Patient-Sidebar.vue";
 import { useRoute , useRouter } from "vue-router";
 import { ref, onMounted,computed } from "vue";
import Swal from "sweetalert2";


  
 
 export default {
 name: "Doctor",
 components: {
   Navbar,
   Patientsidebar,
 },
 setup() {
    const schedules = ref([]);
    const searchQuery = ref("");
    const bookAppointmentModel = ref(false);
    const selectedSchedule = ref(null);
    const router = useRouter();
    const form = ref({
      name: "",
      age: "",
      place: "",
      phone:"",
    });




    const fetchSchedules = async () => {
      try {
        const response = await axios.post("/patient/schedules");
        schedules.value = response.data.schedules

      } catch (error) {
        console.error("Error fetching schedules:", error);
      }
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


    

const openBookingModal = (schedule) => {
      selectedSchedule.value = schedule;
      bookAppointmentModel.value = true;
    };

    const resetForm = () => {
      form.value.age = "";
      form.value.place = "";
      form.value.phone = "";
      bookAppointmentModel.value = false;
    };

    const bookAppointment = async () => {
  try {

    const checkResponse = await axios.post("/patient/check-appointment-limit", {
      schedule_id: selectedSchedule.value.id,
    });

    if (checkResponse.data.bookingsCount >= 2) {
      Swal.fire({
        icon: 'warning',
        title: 'Booking limit reached',
        text: 'You have already booked 2 appointments for this schedule.',
      });
      return;
    }

    
    const response = await axios.post("/patient/appointment", {
      schedule_id: selectedSchedule.value.id, 
      name:form.value.name,
      age: form.value.age,
      place: form.value.place,
      phone: form.value.phone
    });
    Swal.fire({
      icon: 'success',
      title: 'Appointment booked successfully!',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000,
    });

    if (response.status === 201) {
    
      router.push("/patient/appointment");
    }
  } catch (error) {
    console.error("Error booking appointment:", error);
    Swal.fire({
      icon: 'error',
      title: 'Booking failed',
      text: error.response?.data?.error || 'An error occurred while booking. Please try again.',
    });
  }
};
onMounted(() => {
  fetchSchedules(); 
});

    

const filteredSchedules = computed(() => {
  if (!searchQuery.value) {
    return schedules.value;
  }

  const query = searchQuery.value.toLowerCase();
  
  return schedules.value.filter(schedule => {
    const doctorName = schedule.doctor?.name?.toLowerCase() || ''; // Handle missing doctor
    const scheduleTitle = schedule.title.toLowerCase();

    return doctorName.includes(query) || scheduleTitle.includes(query);
  });
});

    return {
      schedules,
      formatTime,
      filteredSchedules,
      searchQuery,
      formatDate,
      bookAppointmentModel,
      form,
      openBookingModal,
      bookAppointment,
      resetForm,
    };
  },
};
</script>

<style scoped>
@import "/public/css/model.css";

</style>
