<template>
  <Navbar />
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 col-lg-2 vh-100 p-0">
        <Doctorsidebar />
      </div>
      <div class="col-md-9 col-lg-10 p-4">
        <div class="dashboard-header">
          <h2>
            <span class="welcome-message">Welcome {{ user.name }}</span>
            <span class="c">{{ currentDate }}</span>
          </h2>
          <br />
          <p class="welcome-message">
            As a doctor, you are responsible for managing your appointments,
            patients, and schedules. You can also handle prescriptions,
            ensuring accurate and efficient patient care.
          </p>
        </div>

        <!-- Status Summary Section -->
        <div class="status-section mb-4">
          <h3 class="center">Status Summary</h3>
          <div class="row">
            <div class="col-md-6">
              <div class="status-card">
                <router-link to="/doctor/schedule" class="no-underline">
                  <h5 class="card-label">Number of Schedules</h5>
                  <p class="card-count">{{ numberOfSchedules }}</p>
                </router-link>
              </div>
            </div>
            <div class="col-md-6">
              <div class="status-card">
                <router-link to="/doctor/appointment" class="no-underline">
                  <h5 class="card-label">Number of Appointments</h5>
                  <p class="card-count">{{ numberOfAppointments }}</p>
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <!-- Analytics Section with Chart -->
        <div class="analytics-section d-flex justify-content-center align-items-center">
          <div class="chart-container">
            <canvas id="appointmentsChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Navbar from "../../Layouts/Navbar.vue";
import Doctorsidebar from "../../Layouts/Doctor-Sidebar.vue";
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";

export default {
  name: "AdminHome",
  components: {
    Navbar,
    Doctorsidebar,
  },
  data() {
    return {
      numberOfSchedules: 0,
      numberOfAppointments: 0,
      numberOfPatients: 0,
      user: {},
    };
  },
  computed: {
    currentDate() {
      const options = { year: "numeric", month: "long", day: "numeric" };
      return new Date().toLocaleDateString(undefined, options);
    },
  },
  mounted() {
    const storedUser = localStorage.getItem("user");
    if (storedUser) {
      this.user = JSON.parse(storedUser);
    }
  },
  setup() {
    const schedules = ref([]);
    const numberOfSchedules = ref(0);
    const numberOfAppointments = ref(0);
    const filteredAppointmentsByDoctor = ref([]);
    const error = ref(null);

    const fetchSchedules = async () => {
      const doctorId = localStorage.getItem("doctor_id");
      try {
        const response = await axios.post(`/schedules/${doctorId}`);
        schedules.value = response.data.schedules;
        numberOfSchedules.value = response.data.schedules.length;
      } catch (error) {
        console.error("Error fetching schedules:", error);
        error.value = "Failed to load schedules. Please try again.";
      }
    };


    const fetchAppointments = async () => {
      const doctorId = localStorage.getItem("doctor_id");
      try {
        const response = await axios.post(`/appointments`);
        const appointments = response.data;
        filteredAppointmentsByDoctor.value = appointments.filter(
          (appointment) =>
            appointment.schedule &&
            appointment.schedule.doctor_id === Number(doctorId)
        );
        numberOfAppointments.value = filteredAppointmentsByDoctor.value.length;
      } catch (error) {
        console.error("Error fetching appointments:", error);
      }
    };

    


    const createChart = () => {
      const ctx = document.getElementById("appointmentsChart").getContext("2d");

      const scheduleTitles = schedules.value.map((schedule) => schedule.title);
      const appointmentCounts = schedules.value.map(
        (schedule) => schedule.appointments_count
      );

      new Chart(ctx, {
        type: "bar", 
        data: {
          labels: scheduleTitles, 
          datasets: [
            {
              label: "Number of Appointments",
              data: appointmentCounts,
              backgroundColor: '#001d66',
              borderColor: "blue",
              borderWidth: 1,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              min: 0,
              stepSize: 1,
              max: Math.max(...appointmentCounts) + 5,
              title: {
                display: true,
                text: "Number of Appointments",
              },
            },
            x: {
              title: {
                display: true,
                text: "Schedules",
              },
            },
          },
          responsive: true,
          maintainAspectRatio: false, 
          plugins: {
            legend: {
              display: false, 
            },
          },
        },
      });
    };

    const fetchAppointmentsBySchedule = async () => {
      const doctorId = localStorage.getItem("doctor_id");
      try {
        const response = await axios.post(
          `/appointments-by-schedule/${doctorId}`
        );
        schedules.value = response.data;
        createChart();
      } catch (error) {
        console.error("Error fetching analytics:", error);
      }
    };

    onMounted(() => {
      fetchSchedules();
      fetchAppointments();
      fetchAppointmentsBySchedule();
    });

    return {
      schedules,
      numberOfSchedules,
      error,
      numberOfAppointments,
    };
  },
};
</script>

  
<style scoped>

@import "/public/css/dashboard.css";

.analytics-section {
  margin-top: 20px;
}

.chart-container {
  width: 70%;
  height: 250px;
  background-color: #ffffff;
  border: 1px solid #001d4b;
  border-radius: 10px;
  padding: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  position: relative;
}

.chart-title {
  position: absolute;
  top: -20px;
  left: 10px;
  font-size: 16px;
  font-weight: bold;
  color: #001d66;
}

canvas {
  margin-top: 20px;
}

canvas {
  margin-top: 40px;
}





</style>