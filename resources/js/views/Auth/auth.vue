<template>
    <div class="auth-container">
      <div class="auth-box">
        <div class="auth-left">
          <div v-if="isLogin">
            <h1>Login</h1>
            <p class="p">Please login to your account</p>
            <form @submit.prevent="handleLogin" class="auth-form">
              
              <input
                v-model="loginData.email"
                type="email"
                placeholder="Email Address"
                class="auth-input"
              />
              <span class="text-danger" v-if="loginErrors.email">{{ loginErrors.email[0] }}</span>
  
              
              <input
                v-model="loginData.password"
                type="password"
                placeholder="Password"
                class="auth-input" 
              />
              <span class="text-danger" v-if="loginErrors.password">{{ loginErrors.password[0] }}</span>
  
              
              <button type="submit" class="auth-btn">Sign In</button>
            </form>
          </div>
  
          <div v-else>
            <h1>Register</h1>
            
            <form @submit.prevent="handleSignUp" class="auth-form">
              
           
                <span class="text-danger" v-if="signUpErrors.name">{{ signUpErrors.name[0] }}</span>
                <input
                v-model="signUpData.name"
                type="text"
                placeholder="Full Name"
                class="auth-input"
                />
            
  
              
              <span class="text-danger" v-if="signUpErrors.email">{{ signUpErrors.email[0] }}</span>
              <input
                v-model="signUpData.email"
                type="email"
                placeholder="Email Address"
                class="auth-input"
              />
  
              
              <span class="text-danger" v-if="signUpErrors.phone">{{ signUpErrors.phone[0] }}</span>
              <input
                v-model="signUpData.phone"
                type="phone"
                placeholder="Phone Number"
                class="auth-input"
              />
           
  
              
              <span class="text-danger" v-if="signUpErrors.password">{{ signUpErrors.password[0] }}</span>
              <input
                v-model="signUpData.password"
                type="password"
                placeholder="Password"
                class="auth-input"
              />
  
              
              <span class="text-danger" v-if="signUpErrors.password_confirmation">{{ signUpErrors.password_confirmation[0] }}</span>
              <input
                v-model="signUpData.password_confirmation"
                type="password"
                placeholder="Confirm Password"
                class="auth-input"
              />
  
              
              <button type="submit" class="auth-btn">Sign Up</button>
            </form>
          </div>
        </div>
  
        <div class="auth-right">
          <h2 v-if="isLogin">Welcome to login</h2>
          <h2 v-else>Welcome to sign up</h2>
          <p v-if="isLogin">Don't have an account?</p>
          <p v-else>Already have an account?</p>
          <button @click="toggleAuth" class="auth-toggle-btn">
            {{ isLogin ? 'Sign Up' : 'Sign In' }}
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  import Swal from "sweetalert2";
  
  export default {
    data() {
      return {
        isLogin: true, 
        loginData: {
          email: "",
          password: "",
        },
        signUpData: {
          name: "",
          email: "",
          phone: "",
          password: "",
          password_confirmation: "",
        },
        loginErrors: {},
        signUpErrors: {},
      };
    },
    methods: {
      toggleAuth() {
        this.isLogin = !this.isLogin;
      },
      async handleLogin() {
        try {
          this.clearErrors(); 
  
          const response = await axios.post("/login", this.loginData);
          let doctorId = null;

          const user = response.data.user;
        localStorage.setItem('userType', user.user_type); 
        localStorage.setItem('user', JSON.stringify(user)); 
        localStorage.setItem('isAuthenticated', true);


    if (response.data.user.user_type === "doctor") {
      try {
        const doctorResponse = await axios.post("/get-doctorid", {
          email: this.loginData.email,
        });
        doctorId = doctorResponse.data.doctor_id;
        localStorage.setItem("doctor_id", doctorId); 
      } catch (err) {
        if (err.response.status !== 404) {
          throw err; 
        }
      }
    }
  
          
          Swal.fire({
            icon: "success",
            title: "Login Successful!",
            showConfirmButton: false,
            timer: 1500,
            toast: true,
            position: "top-end",
          });
  
          
          const userType = response.data.user.user_type;
          
          this.$router.push(userType === "admin" ? "/admin/dashboard" : userType === "doctor" ? "/doctor/dashboard" : "/patient/dashboard");
        } catch (error) {
          if (error.response && error.response.data.errors) {
            this.loginErrors = error.response.data.errors; 
          } else {
            Swal.fire("Error", "Login failed. Please try again.", "error");
          }
        }
      },
      async handleSignUp() {
        try {
          this.clearErrors(); 
  
          const response = await axios.post("/register", this.signUpData);
  
          
          Swal.fire({
            icon: "success",
            title: "Registered successfully!",
            showConfirmButton: false,
            timer: 1500,
            toast: true,
            position: "top-end",
          });
  
          
          setTimeout(() => {
            this.isLogin = true; 
            this.$router.push("/authentication"); 
        }, 1500);
        } catch (error) {
          if (error.response && error.response.data.errors) {
            this.signUpErrors = error.response.data.errors; 
          } else {
            Swal.fire("Error", "Registration failed. Please try again.", "error");
          }
        }
      },
      clearErrors() {
        this.loginErrors = {};
        this.signUpErrors = {};
      },
    },
  };
  </script>
  
  <style scoped>
  @import "/public/css/auth.css";
  </style>
  