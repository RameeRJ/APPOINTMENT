<template>
    <div class="error-page">
      <h1>404 - Page Not Found</h1>
      <p>The page you are looking for does not exist.</p>
      <router-link to="/">Go back to home</router-link>
    </div>
  </template>
  
  <script>
  export default {
    name: "ErrorPage",
  };
  </script>
  
  <style scoped>
  .error-page {
    text-align: center;
    margin-top: 50px;
  }
  </style>
  