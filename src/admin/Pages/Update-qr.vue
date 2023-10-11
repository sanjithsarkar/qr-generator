<script setup>

import {onMounted, ref} from "vue";
import axios from "axios";
import {useRoute} from "vue-router";
import Swal from 'sweetalert2';
import Image from '../Components/Image.vue';

const form = ref({});
const errors = ref(null);

const id = useRoute().params.id;
const getData = () => {
  axios.get(window.qr_generator.resturl + 'get/data/' + id)
      .then((res) => {
        if (res.data.errors){
          errors.value = res.data.errors;
        }else {
          form.value = res.data.data.data;
        }
      })
      .catch((error) => {
        console.error('Error fetching data:', error);
      });
}

onMounted(() => {
  getData();
})

const imageUrl = ref(null);

const onMediaSelected = (attachments) => {
  const attachmentUrl = attachments[0].url;
  imageUrl.value = attachmentUrl;
  form.value.imageUrl = attachmentUrl;
}

const updateData = () => {

  axios.post(window.qr_generator.resturl + 'update/' + id, form.value)
      .then((res) => {
        if (res.data.errors){
          errors.value = res.data.errors;
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
}


</script>

<template>

  <div class="grid grid-cols-1 md:grid-cols-7 gap-4 pr-4">

    <div class="col-span-full md:col-span-5">

      <div class="grid grid-rows-1 mt-4">
        <div class="row-span-full flex justify-center">
          <h3 class="text-lg bg-black px-4 py-2 rounded text-white">Update Data</h3>
        </div>
      </div>

      <div class="flex justify-center py-3">
        <span class="text-red-700 font-semibold" v-if="errors">{{ errors }}</span>
      </div>

      <form @submit.prevent="updateData" enctype="multipart/form-data">
        <div>
          <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">QR name</label>
          <input type="text" id="first_name" v-model="form.qr_name"
                 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                 placeholder="John">
        </div>

        <div class="text-left mt-3 mb-2">
          <h3 class="text-lg">VCard Information</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="col-span-1">
            <label class="block mb-2 text-sm font-medium text-gray-900">Image</label>
            <Image @on-media-selected="onMediaSelected"
                   class="block bg-blue-600 font-semibold text-white rounded px-2 py-2"/>
          </div>
          <div class="col-span-1 shadow-xl flex justify-center">
            <img class="h-[50px] max-w-xl rounded-full" :src="imageUrl" v-if="imageUrl" height="50" width="60"
                 alt="Not Found">
            <img v-else="" :src="form.image" class="flex justify-center items-center rounded-full" alt="Image"
                 height="50" width="60">
          </div>
        </div>

        <div class="grid grid-rows-1 mt-3">
          <div class=" grid grid-col-full md:grid-cols-3 gap-3">
            <div class="col-span-1">
              <label for="success" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
              <input type="text" v-model="form.name"
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
              <input type="number" v-model="form.mobile"
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
            <button type="submit" class="bg-green-700 px-4 py-2 rounded text-white">Update</button>
          </div>
        </div>
      </form>
    </div>

    <div class="col-span-full md:col-span-2">
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

                        <img class="h-[50px] max-w-xl rounded-full" :src="imageUrl" v-if="imageUrl" height="50"
                             width="60" alt="Not Found">
                        <img v-else="" :src="form.image" class="flex justify-center items-center rounded-full"
                             alt="Image" height="50" width="60">

                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-5">
                <div
                    class="col-span-5 flex justify-center items-center mt-4 mx-5 rounded border-collapse border border-gray-300">
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
