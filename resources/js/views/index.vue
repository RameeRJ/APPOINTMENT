<template>
    <body>
    <div class="full-height">
      <section class="left">
        <h1 class="h1">
          <span class="bold-text">THE </span>
        <span class="style-text">FUTURE</span>
        <span class="style-text"> HEALTH </span>
        <span class="bold-text">IS HERE!</span>
        </h1>
        <router-link to="/authentication">
        <button class="appointment-button" >Appointment</button>
        </router-link>
      </section>

        
  
    </div>
    </body>
  </template>
  
  <script>
  export default {
    name: 'Home',
  };
  </script>
  
  <style scoped>
  @import "/public/css/index.css";
  @import "/public/css/main.css";
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Playfair+Display:wght@700&display=swap');

  .h1 {
  color: aliceblue;
  display: table-caption;
  margin: 51px;
  font-size: 92px;
  padding-top: 3px;
}
.bold-text {
  font-weight: normal; /* Default weight */
  
}

.style-text {
  font-family: 'YourDesiredFontFamily', sans-serif; /* Replace with your desired font */
  font-weight: bold; /* Make FUTURE bolder */
  
}
.appointment-button {
  padding: 11px 31px;
    font-size: 18px;
    background-color: transparent;
    color: white;
    border: 2px solid white;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-left: 141px; /* Keep your margin */
}

.appointment-button:hover {
  background-color: rgba(255, 255, 255, 0.1); /* Slight white transparency on hover */
  border-color: #ffffff; /* Keep the white border on hover */
}

  </style>
  