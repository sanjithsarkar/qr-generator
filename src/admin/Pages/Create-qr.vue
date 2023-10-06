<script setup>

import {ref} from "vue";
import axios from "axios";
import Swal from 'sweetalert2';
import {useRouter} from 'vue-router';

const form = ref({});
const errors = ref({});
const imageUrl = ref(null);
const router = useRouter();
const imageNotSelected = ref(null);

const onFileSelected = (event) => {
  form.value.image = event.target.files[0];
  imageUrl.value = URL.createObjectURL(form.value.image);
};


const ERROR_MESSAGES = {
  IMAGE_NOT_SELECTED: 'Please select an image.',
  INVALID_IMAGE_FORMAT: 'Invalid image format. Please use JPEG, JPG, PNG, or GIF.',
  MAXIMIZE_IMAGE_SIZE: 'The image size is larger than the maximum permitted (5MB).',
};

// ------------------------------- Insert Data ------------------------------


const insertData = () => {


  let imageError = '';

  const allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

  if (!form.value.image) {
    imageError = ERROR_MESSAGES.IMAGE_NOT_SELECTED;
  }

  if (form.value.image && !allowedImageTypes.includes(form.value.image.type)) {
    imageError = ERROR_MESSAGES.INVALID_IMAGE_FORMAT;
  }

  // Check the image size (you can specify a maximum size in bytes)
  const maxImageSizeInBytes = 5 * 1024 * 1024; // 5MB
  if (form.value.image && form.value.image.size > maxImageSizeInBytes) {
    imageError = ERROR_MESSAGES.MAXIMIZE_IMAGE_SIZE;
  }

// Set error messages and return if there are errors
  if (imageError) {
    imageNotSelected.value = imageError;
    return;
  }


  // If all image validations pass, proceed to send the data
  let formData = new FormData();
  formData.append('image', form.value.image);

  axios.post(window.qr_generator.resturl + 'insert', formData, {
    params: form.value
  })
      .then((res) => {
        if (res.data.status === 'error') {
          errors.value = res.data.errors;
          console.log(errors.value);
        } else if (res.data.status === 'success') {
          Swal.fire(
              'Good job!',
              'Data inserted successfully!',
              'success'
          )
          router.push({path: '/my-qr'});
        }
        form.value = {};
        // form.reset();
      })
      .catch((error) => {
        errors.value = error.response.data.errors;
      });
}


</script>

<template>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pr-4">

    <div class="col-span-full md:col-span-3">

      <div class="grid grid-rows-1 mt-4">
        <div class="row-span-full flex justify-center">
          <h3 class="text-lg bg-black px-4 py-2 rounded text-white">Insert Data</h3>
        </div>
      </div>

      <form @submit.prevent="insertData" enctype="multipart/form-data">
        <div>
          <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">QR name</label>
          <input type="text" id="first_name" v-model="form.qr_name"
                 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                 placeholder="John">
          <small class="text-red-700 text-sm" v-if="errors"> {{ errors[0] }} </small>
        </div>

        <div class="text-left mt-3 mb-2">
          <h3 class="text-lg">VCard Information</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="col-span-1">
            <label class="block mb-2 text-sm font-medium text-gray-900">Image</label>
            <input type="file" @change="onFileSelected"
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">

            <small class="text-red-700 text-sm" v-if="imageNotSelected"> {{ imageNotSelected }}</small>

          </div>
          <div class="col-span-1 shadow-xl flex justify-center">
            <img class="h-[50px] max-w-xl rounded-full w-96 h-96" :src="imageUrl" v-if="imageUrl"
                 style="height: 55px; width: 65px;" alt="Not Found">
            <span v-else="" class="flex justify-center items-center">View Image</span>
          </div>
        </div>

        <div class="grid grid-rows-1 mt-3">
          <div class=" grid grid-col-full md:grid-cols-3 gap-3">
            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
              <input type="text" v-model="form.name" placeholder="Name"
                     class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
              <small class="text-red-700 text-sm" v-if="errors"> {{ errors[1] }} </small>
            </div>

            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Surname</label>
              <input type="text" v-model="form.surname" placeholder="Surname"
                     class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
              <small class="text-red-700 text-sm" v-if="errors"> {{ errors[2] }} </small>
            </div>

            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
              <input type="text" v-model="form.title" placeholder="Title"
                     class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
              <small class="text-red-700 text-sm" v-if="errors"> {{ errors[3] }} </small>
            </div>
          </div>
        </div>


        <div class="text-left mt-3 mb-2">
          <h3 class="text-lg">Contact Information</h3>
        </div>
        <div class="grid grid-rows-1 mt-3">
          <div class=" grid grid-col-full md:grid-cols-2 gap-3">
            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Mobile</label>
              <input type="number" v-model="form.mobile" placeholder="Mobile Number"
                     class="block w-full text-sm text-gray-900 border border-gray-3 bg-gray-50">
              <small class="text-red-700 text-sm" v-if="errors"> {{ errors[4] }} </small>
            </div>

            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
              <input type="email" v-model="form.email" placeholder="Email"
                     class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
              <small class="text-red-700 text-sm" v-if="errors"> {{ errors[5] }} </small>
            </div>
          </div>
        </div>

        <div class="text-left mt-3 mb-2">
          <h3 class="text-lg">Address</h3>
        </div>

        <div class="grid grid-rows-1 gap-4">
          <div class="row-span-full">
            <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Address</label>
            <input type="text" v-model="form.address" placeholder="Address"
                   class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
            <small class="text-red-700 text-sm" v-if="errors"> {{ errors[6] }} </small>
          </div>
        </div>

        <div class="grid grid-rows-1 mt-4">
          <div class="row-span-full flex justify-center">
            <button type="submit" class="bg-green-700 py-2 px-4 rounded text-white text-sm">Insert</button>
          </div>
        </div>
      </form>
    </div>


    <div class="col-span-full md:col-span-1 gap-4">
      <div class="py-8">
        <div class="max-w-screen-md mx-auto">
          <div class="bg-white shadow-lg rounded-lg">
            <div class="bg-black text-white py-4 px-6 rounded-t-lg">
              <h3 class="text-2xl text-white flex justify-center font-semibold">Display Input Data</h3>
            </div>
            <div class="py-10">
              <div class="grid grid-cols-5">
                <div class="col-span-5 flex justify-center items-center mx-5 border-collapse border border-gray-300">
                  <div class="flex justify-center">
                    <div class="w-full py-2">
                      <div class=" rounded-full p-2 mx-auto">

                        <img class="h-[50px] max-w-xl rounded-full w-96 h-96" :src="imageUrl" v-if="imageUrl"
                             style="height: 55px; width: 65px;" alt="Not Found">

                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-5">
                <div
                    class="col-span-5 flex justify-center items-center mt-4 mx-5 border-collapse border border-gray-300">
                  <div class="w-full bg-gray-100">
                    <table class="w-full table-fixed my-4">
                      <tbody>
                      <tr class="bg-gray-100">
                        <td class="w-2/3 py-2 px-4 font-semibold pl-12">QR Name</td>
                        <td class="w-2/3 py-2">{{ form.qr_name }}</td>
                      </tr>
                      <tr class="bg-gray-50">
                        <td class="w-1/2 py-2 px-4 font-semibold pl-12">Name</td>
                        <td class="w-3/4 py-2">{{ form.name }}</td>
                      </tr>
                      <tr class="bg-gray-100">
                        <td class="w-1/2 py-2 px-4 font-semibold pl-12">Surname</td>
                        <td class="w-3/4 py-2">{{ form.surname }}</td>
                      </tr>
                      <tr class="bg-gray-50">
                        <td class="w-1/2 py-2 px-4 font-semibold pl-12">Title</td>
                        <td class="w-3/4 py-2">{{ form.title }}</td>
                      </tr>
                      <tr class="bg-gray-50">
                        <td class="w-1/2 py-2 px-4 font-semibold pl-12">Mobile</td>
                        <td class="w-3/4 py-2">{{ form.mobile }}</td>
                      </tr>
                      <tr class="bg-gray-50">
                        <td class="w-1/2 py-2 px-4 font-semibold pl-12">Email</td>
                        <td class="w-3/4 py-2">{{ form.email }}</td>
                      </tr>
                      <tr class="bg-gray-50">
                        <td class="w-1/2 py-2 px-4 font-semibold pl-12">Address</td>
                        <td class="w-3/4 py-2">{{ form.address }}</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>