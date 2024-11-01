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
            <td class="menu-btn menu-icon-dashbord" :class="{ 'menu-active': isActive('/admin/dashboard') }">
              <router-link to="/admin/dashboard" class="non-style-link-menu">
                <div class="menu-item">
                  <i class="fas fa-tachometer-alt"></i>
                  <p class="menu-text">DASHBOARD</p>
                </div>
              </router-link>
            </td>
          </tr>
          <tr class="menu-spacer"><td></td></tr>
          <tr class="menu-row">
            <td class="menu-btn menu-icon-doctor" :class="{ 'menu-active': isActive('/admin/doctors') }">
              <router-link to="/admin/doctors" class="non-style-link-menu">
                <div class="menu-item">
                  <i class="fas fa-user-md"></i>
                  <p class="menu-text">DOCTORS</p>
                </div>
              </router-link>
            </td>
          </tr>
          <tr class="menu-spacer"><td></td></tr>
          <tr class="menu-row">
            <td class="menu-btn menu-icon-department" :class="{ 'menu-active': isActive('/admin/departments') }">
              <router-link to="/admin/departments" class="non-style-link-menu">
                <div class="menu-item">
                  <i class="fas fa-user-md"></i>
                  <p class="menu-text">DEPARTMENTS</p>
                </div>
              </router-link>
            </td>
          </tr>
          <tr class="menu-spacer"><td></td></tr>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AdminSidebar',
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
    transform: translateX(-100%); 
  }
  
  .menu-container {
    width: 100%;
  }
  
  .menu-item {
    display: flex;
    align-items: center; 
    height: 45px;
  }
  
  .menu-item i {
    margin-right: 8px; 
    font-size: 1.2em; 
  }
  
  .menu-text {
    margin: 0; 
  }
  .menu-btn {
    cursor: auto;
  }
  
  
  @media (max-width: 768px) {
    .menu {
      width: 170px; 
    }
  
    .menu-closed {
      transform: translateX(-100%);
    }
  
    .menu-spacer {
      display: none; 
    }
    .menu-active {
      width: 172px;
    }
    .profile-subtitle {
      font-size: 11px;
    }
  }
  
  .navbar-toggler {
    display: block; 
    margin: 10px; 
    background-color: #007bff; 
    border: none;
    padding: 10px;
    color: white; 
  }

  </style>
  