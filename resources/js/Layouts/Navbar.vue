<template>
  <div>
    <!-- Alternate Navigation -->
    <nav class="navbar">
      <div class="navbar-container">
        <!-- Brand Logo and Name -->
        <div class="brand-logo-container">
          <span class="navbar-brand">D</span>
          <img src="/public/images/logo.png" alt="DocBooker Logo" class="brand-logo" />
          <span class="navbar-brand">B</span>
        </div>
        <!-- Profile Image and Logout Button -->
        <div class="profile-logout-container">
          <div class="profile-container">
            <img :src="profileImage" alt="user" class="profile-image" />
            
          </div>
          <button @click="logout" class="logout-btn">Logout</button>
        </div>
      </div>
    </nav>
    <div class="dashboard-content">
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  name: 'Navbar',
  data() {
    return {
      defaultProfileImage: '/images/default.jpg',
      user: null,
      profileImage: '',
    };
  },
  mounted() {
    this.fetchUserProfile();
  },

  methods: {
    async logout() {
      try {
        const result = await Swal.fire({
          title: 'Are you sure?',
          text: 'You will be logged out!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, log me out!',
          cancelButtonText: 'Cancel',
          padding: '1em',
        });

        if (result.isConfirmed) {
          await axios.post('/logout');
          localStorage.clear(); 
          this.$router.push({ name: 'Index' });
          Swal.fire({
            icon: 'success',
            title: 'Logged out successfully',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            padding: '1em',
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Logout Failed',
          text: 'There was an error logging you out. Please try again.',
          padding: '1em',
        });
      }
    },
    async fetchUserProfile() {
  try {
    const response = await axios.post('/patient/user-details');
    this.user = response.data; 
    
    this.profileImage = this.user.profile_picture
      ? `/storage/${this.user.profile_picture}`
      : this.defaultProfileImage;
  } catch (error) {
    console.error('Error fetching user details:', error);
    this.profileImage = this.defaultProfileImage; 
  }
},
  },
  
};
</script>

<style scoped>
@import "/public/css/navbar.css";


</style>
