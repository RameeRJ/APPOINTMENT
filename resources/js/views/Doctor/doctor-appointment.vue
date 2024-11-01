<template>
    <Navbar />
     
     <div class="container-fluid">
       <div class="row">
         <div class="col-md-3 col-lg-2 vh-100 p-0">
           <Doctorsidebar />
         </div>
         <div class="col-md-9 col-lg-10 p-4">
          <div class="add-department-info mb-4">
            <h2 class="heading">All Of Your Appointments</h2>
            <p class="description">View your appointments effortlessly and send prescriptions to patients using our user-friendly interface..</p>
        </div>
        <div class="doctor-list-section">
          <div class="doctor-list-header d-flex justify-content-between align-items-center margin">
            
          </div>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Schedule Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Token</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                <tr v-if="filteredAppointments.length === 0">
                              <td colspan="7" class="text-center">No appointments available</td>
                          </tr>
                          <tr v-for="appointment in filteredAppointments" :key="appointment.id">
                              <td>{{ appointment.name }}</td>
                              <td>{{ appointment.age }}</td>                              
                              <td>{{ appointment.schedule.title }}</td>
                              <td>{{ formatDate(appointment.schedule.date) }}</td>
                              <td>{{ formatTime(appointment.schedule.start_time) }} - {{ formatTime(appointment.schedule.end_time) }}</td>
                              <td>{{ appointment.token }}</td>
                              <td>
                                <button v-if="!appointment.prescriptionSent" class="action" @click="openPrescriptionModal(appointment)">
    <span class="tooltip-text">Send Prescription</span>
</button>
<button class="action" v-else>
    <span class="tooltip-text">Prescription sent successfully</span>

</button>
                     </td>
                 </tr>
            </tbody> 
          </table>
</div>
      </div>
    </div>
    <div v-if="isModalOpen" class="modal-backdrop">
  <div class="modal-content">
    <h3 class="center">Send Prescription</h3>
    <form @submit.prevent="submitPrescription">
      <div class="form-group">
        <label for="prescriptionFile">Upload Prescription File:</label>
        <input
          type="file"
          id="prescriptionFile"
          @change="handleFileUpload"
          class="form-control"  
          accept=".pdf, .jpg, .png" 
        />
      </div>
      <div class="button-group">
        <button type="submit" class="btn btn-add">Submit</button>
        <button type="reset" class="btn btn-add-secondary"  @click="isModalOpen = false">Close</button>
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
  import { ref, computed, onMounted } from 'vue';
  import { useRouter } from "vue-router";
import Swal from "sweetalert2";

   
  
  export default {
  name: "Doctor",
  components: {
    Navbar,
    Doctorsidebar,
  },
  setup() {
    const appointments = ref([]); 
    const filteredAppointmentsByDoctor = ref([]); 
    const searchQuery = ref(""); 
    const router = useRouter();
    const isModalOpen = ref(false);
    const selectedAppointmentId = ref(null); 
    const prescriptionText = ref(""); 
    const prescriptionFile = ref(null); 

    const fetchAppointments = async () => {
    const doctorId = localStorage.getItem('doctor_id'); 
    try {
        const response = await axios.post(`/appointments`); 
        console.log(response.data); 
        appointments.value = response.data.map(appointment => ({
            ...appointment,
            prescriptionSent: !!appointment.prescription, 
        }));
        filteredAppointmentsByDoctor.value = appointments.value.filter(appointment => 
            appointment.schedule && appointment.schedule.doctor_id === Number(doctorId) 
        );
    } catch (error) {
        console.error("Error fetching appointments:", error);
    }
};

    const handleFileUpload = (event) => {
    prescriptionFile.value = event.target.files[0]; 
};

    const openPrescriptionModal = (appointment) => {
      selectedAppointmentId.value = appointment.id; 
      prescriptionText.value = ""; 
      prescriptionFile.value = null; 
      isModalOpen.value = true; 
    };

    const closeModal = () => {
      isModalOpen.value = false; 
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
    const submitPrescription = async () => {
  if (!prescriptionFile.value) {
    Swal.fire("Error", "File is required.", "error");
    return; 
  }

  
  const appointment = appointments.value.find(app => app.id === selectedAppointmentId.value);
  if (appointment) {
    const appointmentDate = new Date(appointment.schedule.date); 
    const currentDate = new Date();

    
    if (appointmentDate > currentDate) {
      Swal.fire("Error", "Cannot send prescription for future appointments.", "error");
      return; 
    }
  }

      const formData = new FormData();
      if (prescriptionFile.value) {
        formData.append("prescription", prescriptionFile.value);
      }
      try {
        
        await axios.post(`/appointments/${selectedAppointmentId.value}/prescription`, formData);
        Swal.fire("Success", "Prescription sent successfully", "success");
        closeModal();
        const appointmentIndex = appointments.value.findIndex(
          (appointment) => appointment.id === selectedAppointmentId.value
        );
        if (appointmentIndex !== -1) {
          appointments.value[appointmentIndex].prescriptionSent = true;
        }
      } catch (error) {
    
    if (error.response && error.response.status === 422) {
      const errors = error.response.data.errors;
      
      const errorMessages = Object.values(errors).flat().join(', '); 
      Swal.fire("Error", errorMessages || "Failed to send prescription", "error");
    } else {
      Swal.fire("Error", "Failed to send prescription", "error");
    }
  }
};

    const formatDate = (dateString) => {
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
      });
    };
    onMounted(() => {
      fetchAppointments();
    });

    

    const filteredAppointments = computed(() => {
      if (!searchQuery.value) {
        return filteredAppointmentsByDoctor.value; 
      }
      return filteredAppointmentsByDoctor.value.filter(appointment => {
        return (
          appointment.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          appointment.schedule.title.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
      });
    });

    return {
      filteredAppointments,
      searchQuery, 
      formatDate,
      formatTime,
      openPrescriptionModal,
      closeModal,
      isModalOpen,
      selectedAppointmentId, 
      prescriptionText, 
      prescriptionFile ,
      submitPrescription,
      handleFileUpload
    };
  },
};
</script>


<style scoped>
@import "/public/css/model.css";

</style>
