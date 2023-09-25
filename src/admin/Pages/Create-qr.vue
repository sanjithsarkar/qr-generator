<script setup>

import {ref} from "vue";
import axios from "axios";

const form = ref({});

const imageUrl = ref(null);

const onFileSelected = (event) => {
  form.image = event.target.files[0];
  imageUrl.value = URL.createObjectURL(form.image);
};
const insertData = () => {

  let formData = new FormData();
  formData.append('image', form.value.image);

  axios.post(window.qr_generator.resturl +'insert', form.value)
      .then((res) => {
        alert("Data inserted successfully");
        form.value = {};
        // form.reset();
      })
      .catch((error) => {
        console.error("Error:", error);
      });
}


const getData = () => {
  axios.get(window.qr_generator.resturl + 'getdata/' + 1)
}


</script>

<template>

  <div class="grid grid-rows-1 mt-4">
    <div class="row-span-full flex justify-center">
      <button @click="getData" class="bg-blue-600 p-2 rounded text-white">Get Data</button>
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pr-4">

    <div class="col-span-full md:col-span-3">
      <form @submit.prevent="insertData" enctype="multipart/form-data">
        <div>
          <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">QR name</label>
          <input type="text" id="first_name" v-model="form.qr_name"
                 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                 placeholder="John">
        </div>


        <!--      <div class="max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow mt-6">-->
        <!--        <div class="grid grid-rows-1">-->
        <!--          <div class="grid grid-cols-3 gap-3">-->
        <!--            <div class="col-span-1 shadow bg-gray-200">-->
        <!--              1-->
        <!--            </div>-->

        <!--            <div class="col-span-1 shadow bg-gray-200">-->
        <!--              1-->
        <!--            </div>-->

        <!--            <div class="col-span-1 shadow bg-gray-200">-->
        <!--              1-->
        <!--            </div>-->
        <!--          </div>-->
        <!--        </div>-->
        <!--      </div>-->

        <div class="text-left mt-3 mb-2">
          <h3 class="text-lg">VCard Information</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="col-span-1">
            <label class="block mb-2 text-sm font-medium text-gray-900">Image</label>
            <input type="file" @change="onFileSelected"
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
          </div>
          <div class="col-span-1 shadow-xl flex justify-center">
            <img class="h-[50px] max-w-xl rounded-full w-96 h-96" :src="imageUrl" v-if="imageUrl" style="height: 55px; width: 65px;" alt="Not Found">
          </div>
        </div>

        <div class="grid grid-rows-1 mt-3">
          <div class=" grid grid-col-full md:grid-cols-3 gap-3">
            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
              <input type="text" v-model="form.name" placeholder="Name"
                     class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
            </div>

            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Surname</label>
              <input type="text" v-model="form.surname" placeholder="Surname"
                     class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
            </div>

            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Title</label>
              <input type="text" v-model="form.title" placeholder="Title"
                     class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
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
            </div>

            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
              <input type="email" v-model="form.email" placeholder="Email"
                     class="block w-full text-sm text-gray-900 border border-gray-300 bg-gray-50">
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
          </div>
        </div>

        <div class="grid grid-rows-1 mt-4">
          <div class="row-span-full flex justify-center">
            <button type="submit" class="bg-blue-600 p-2 rounded text-white">Next</button>
          </div>
        </div>
      </form>
    </div>

    <div class="col-span-full md:col-span-1 bg-blue-600">2</div>

  </div>
</template>