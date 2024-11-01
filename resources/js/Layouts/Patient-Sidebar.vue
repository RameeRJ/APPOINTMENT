<template>
    <div class="container">
      <button
        class="navbar-toggler"
        type="button"
        @click="toggleSidebar"
        aria-controls="navbarResponsive"
        :aria-expanded="isSidebarOpen"
        aria-label="Toggle navigation"
        v-if="!isSidebarOpen"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="menu" :class="{ 'menu-closed': !isSidebarOpen }">
        <table class="menu-container" border="0">
          <tr class="menu-row">
            <td class="menu-btn menu-icon-dashbord" :class="{ 'menu-active': isActive('/patient/dashboard') }">
              <router-link to="/patient/dashboard" class="non-style-link-menu">
                <div class="menu-item">
                  <i class="fas fa-tachometer-alt"></i>
                  <p class="menu-text">DASHBOARD</p>
                </div>
              </router-link>
            </td>
          </tr>
          <tr class="menu-spacer"><td></td></tr>
          <tr class="menu-row">
            <td class="menu-btn menu-icon-doctor" :class="{ 'menu-active': isActive('/patient/doctors') }">
              <router-link to="/patient/doctors" class="non-style-link-menu">
                <div class="menu-item">
                  <i class="fas fa-user-md"></i>
                  <p class="menu-text">DOCTORS</p>
                </div>
              </router-link>
            </td>
          </tr>
          <tr class="menu-spacer"><td></td></tr>
          <tr class="menu-row">
            <td class="menu-btn menu-icon-schedule" :class="{ 'menu-active': isActive('/patient/schedules') }">
              <router-link to="/patient/schedules" class="non-style-link-menu">
                <div class="menu-item">
                  <i class="fas fa-user-md"></i>
                  <p class="menu-text">SCHEDULES</p>
                </div>
              </router-link>
            </td>
          </tr>
          <tr class="menu-spacer"><td></td></tr>
          <tr class="menu-row">
            <td class="menu-btn menu-icon-appointment" :class="{ 'menu-active': isActive('/patient/appointment') }">
              <router-link to="/patient/appointment" class="non-style-link-menu">
                <div class="menu-item">
                  <i class="fas fa-user-md"></i>
                  <p class="menu-text">APPOINTMENTS</p>
                </div>
              </router-link>
            </td>
          </tr>
          <tr class="menu-spacer"><td></td></tr>
          <tr class="menu-row">
            <td class="menu-btn menu-icon-profile" :class="{ 'menu-active': isActive('/patient/profile') }">
              <router-link to="/patient/profile" class="non-style-link-menu">
                <div class="menu-item">
                  <i class="fas fa-user-md"></i>
                  <p class="menu-text">PROFILE</p>
                </div>
              </router-link>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'patientSidebar',
    data() {
      return {
        defaultProfileImage: '/storage/assets/images/default.jpg',
        isSidebarOpen: true,
        user: null, 
      };
    },
    mounted() {
      
      const storedUser = localStorage.getItem('user');
      if (storedUser) {
        this.user = JSON.parse(storedUser);
      }
    },
  
    methods: {
      toggleSidebar() {
        this.isSidebarOpen = !this.isSidebarOpen; 
      },
      isActive(route) {
        return this.$route.path.startsWith(route);
      },
    },
  }
  </script>
  
  <style scoped>
  @import "/public/css/sidebar.css";
  
  img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    margin-left: -10px;
    margin-top: -26px;
    border: thick;
  }
  
 
  
  .menu-closed {
    transform: translateX(-100%); /* Hide the sidebar */
  }
  
  .menu-container {
    width: 100%;
  }
  
  .menu-item {
    display: flex;
    align-items: center; /* Align items vertically centered */
    height: 45px;
  }
  
  .menu-item i {
    margin-right: 8px; /* Space between icon and text */
    font-size: 1.2em; /* Adjust icon size */
  }
  
  .menu-text {
    margin: 0; /* Remove default margin from paragraph */
  }
  .menu-btn {
    cursor: auto;
  }
  
  /* Responsive Styles */
  @media (max-width: 768px) {
    .menu {
      width: 170px; /* Set width for the menu */
    }
  
    .menu-closed {
      transform: translateX(-100%); /* Hide the sidebar when closed */
    }
  
    .menu-spacer {
      display: none; /* Hide spacer on smaller screens */
    }
    .menu-active {
      width: 172px;
    }
    .profile-subtitle {
      font-size: 11px;
    }
  }
  
  .navbar-toggler {
    display: block; /* Show button */
    margin: 10px; /* Adjust margin as needed */
    background-color: #007bff; /* Button color */
    border: none;
    padding: 10px;
    color: white; /* Icon color */
  }

  </style>
  