<template>
  <Navbar />
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 col-lg-2 vh-100 p-0">
        <Patientsidebar />
      </div>
      <div class="col-md-9 col-lg-10 p-4">
        <div class="add-department-info mb-4">
          <h2 class="heading">Profile Management</h2>
          <p class="description">Manage your profile effortlessly by viewing and updating your details. <br /> Keep your information up-to-date with the option to edit your profile anytime.</p>
        </div>


        <div class="profile-details">
          <div class="profile-pic-container">
            <img :src="previewImage || '/images/default.jpg'" alt="Profile Preview" class="profile-preview" />
          </div>
          <div class="form-group">
            <label>Name:</label>
            <p class="form-control-static">{{ formData.name }}</p>
          </div>
          <div class="form-group">
            <label>Email:</label>
            <p class="form-control-static">{{ formData.email }}</p>
          </div>
          <div class="form-group">
            <label>Phone:</label>
            <p class="form-control-static">{{ formData.phone }}</p>
          </div>
          <button class="btn btn-add" @click="openEditProfileModal">Edit Profile</button>
        </div>

        <!-- Edit Profile Modal -->
        <div v-if="EditProfileModel" class="modal-backdrop">
          <div class="modal-content">
            <h3 class="center">Edit your profile</h3>
            <form @submit.prevent="editProfile">
              <div class="profile-pic-container">
                <img :src="previewImage || '/images/default.jpg'" alt="Profile Preview" class="profile-preview" />
              </div>
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" v-model="formData.name" required />
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" v-model="formData.email" required />
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" v-model="formData.phone" required />
              </div>
              <div class="form-group">
                <label for="profilePicture">Profile Picture</label>
                <input type="file" class="form-control" id="profilePicture"   accept="image/*" @change="onFileChange"  />
                
              </div>
              <div class="button-group">
                <button type="submit" class="btn btn-add">Submit</button>
                <button type="reset" class="btn btn-add-secondary" @click="resetForm">Reset</button>
                <button type="close" class="btn btn-add-secondary"  @click="EditProfileModel = false">Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import axios from 'axios';
import Navbar from "../../Layouts/Navbar.vue";
import Patientsidebar from "../../Layouts/Patient-Sidebar.vue";
import { ref } from 'vue';
import Swal from "sweetalert2";

export default {
  name: "ProfileManagement",
  components: {
    Navbar,
    Patientsidebar,
  },
  setup() {
    const EditProfileModel = ref(false);
    const formData = ref({
      name: '',
      email: '',
      phone: '',
      profile_picture: null, // This can start as null
    });
    const previewImage = ref(''); // Default image path
    const error = ref(null);

    const fetchUserDetails = async () => {
      try {
        const response = await axios.post('/patient/user-details');
        formData.value = response.data; 
        // Only set preview image if profile_picture exists
        previewImage.value = response.data.profile_picture 
          ? `/storage/${response.data.profile_picture}` 
          : '/images/default.jpg';
      } catch (error) {
        console.error('Error fetching user details:', error);
      }
    };

    // Fetch user details when the component mounts
    fetchUserDetails();

    const openEditProfileModal = () => {
      EditProfileModel.value = true;
    };

    const onFileChange = (event) => {
      const file = event.target.files[0];
      formData.value.profile_picture = file ? file : null;

      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          previewImage.value = e.target.result; 
        };
        reader.readAsDataURL(file); 
      } else {
        // Reset to default image if no file is chosen
        previewImage.value = '/images/default.jpg';
      }
    };

    const editProfile = async () => {
      try {
        const formDataObj = new FormData();
        formDataObj.append('name', formData.value.name);
        formDataObj.append('email', formData.value.email);
        formDataObj.append('phone', formData.value.phone);
        if (formData.value.profile_picture) {
          formDataObj.append('profile_picture', formData.value.profile_picture);
        }

        await axios.post('/patient/update-profile', formDataObj, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        Swal.fire({
          icon: 'success',
          title: 'Profile Edited successfully',
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 2000,
        }).then(() => {
          window.location.reload();
        });

      } catch (error) {
        console.error('Error updating profile:', error);
        Swal.fire({
          icon: 'error',
          title: 'Editing Profile Failed',
          text: error.response?.data?.message || 'An error occurred. Please try again.',
        });
      }
    };

    const resetForm = () => {
      formData.value = {
        name: '',
        email: '',
        phone: '',
        profile_picture: null, // Reset to null
      };
      previewImage.value = '/images/default.jpg'; // Reset to default image
    };

    return {
      EditProfileModel,
      formData,
      previewImage,
      onFileChange,
      editProfile,
      resetForm,
      openEditProfileModal,
    };
  },

};
</script>

<style scoped>
@import "/public/css/model.css";

.profile-details {
  display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-top: 21px;
    border: 3px solid;
    width: 322px;
    height: 522px;
    margin-left: 281px;
}

.center {
  margin: 0;
}

/* Ensure the profile-pic-container also centers the image */
.profile-pic-container {
  display: flex;
  justify-content: center; /* Center the image horizontally */
  margin-bottom: 20px; /* Maintain the margin at the bottom */
}


.profile-preview {
  display: block;
  width: 150px;
  height: 150px;
  border-radius: 21%;
  object-fit: cover;
  margin: auto;
  padding: 14px;
  margin-top: 10px;
}


.profile-details-container {
  display: flex;
  flex-direction: column; 
  align-items: center; 
  text-align: center; 
}

.profile-info {
  margin-top: 20px; 
}


</style>