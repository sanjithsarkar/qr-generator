<script setup>
import {ref, onMounted, computed} from 'vue';
import axios from "axios";
import VueQrcode from 'vue-qrcode';

const tableData = ref({});

const getData = () => {
  axios.get(window.qr_generator.resturl +'get/data')
      .then((res) => {
        tableData.value =  res.data;
      })
}


const deleteData = (id) => {
  axios.delete(window.qr_generator.resturl +'delete/' + id)
      .then((res) => {
        alert("Deleted Successfully!!");
        getData();
      })
}

const getViewUrl = (id)=>{
  return window.qr_generator.site_url +'/qrcode/'+id;
}

// const qrCodeUrl = (id) => {
//     return window.qr_generator.site_url +'/qrcode/'+id;
//   };


onMounted(() => {
  getData();
})
</script>

<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
      <tr>

<!--        <th scope="col" class="px-6 py-3">-->
<!--          QR name-->
<!--        </th>-->
<!--        <th scope="col" class="px-6 py-3">-->
<!--          <div class="flex items-center">-->
<!--            Name-->
<!--            <a href="#"><svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">-->
<!--              <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>-->
<!--            </svg></a>-->
<!--          </div>-->
<!--        </th>-->
<!--        <th scope="col" class="px-6 py-3">-->
<!--          <div class="flex items-center">-->
<!--            Surname-->
<!--            <a href="#"><svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">-->
<!--              <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>-->
<!--            </svg></a>-->
<!--          </div>-->
<!--        </th>-->
<!--        <th scope="col" class="px-6 py-3">-->
<!--          <div class="flex items-center">-->
<!--            Title-->
<!--            <a href="#"><svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">-->
<!--              <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>-->
<!--            </svg></a>-->
<!--          </div>-->
<!--        </th>-->

<!--        <th scope="col" class="px-6 py-3">-->
<!--          <div class="flex items-center">-->
<!--            Email-->
<!--            <a href="#"><svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">-->
<!--              <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>-->
<!--            </svg></a>-->
<!--          </div>-->
<!--        </th>-->

<!--        <th scope="col" class="px-6 py-3">-->
<!--          <div class="flex items-center">-->
<!--            Mobile-->
<!--            <a href="#"><svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">-->
<!--              <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>-->
<!--            </svg></a>-->
<!--          </div>-->
<!--        </th>-->

<!--        <th scope="col" class="px-6 py-3">-->
<!--          <div class="flex items-center">-->
<!--            Address-->
<!--            <a href="#"><svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">-->
<!--              <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>-->
<!--            </svg></a>-->
<!--          </div>-->
<!--        </th>-->

        <th scope="col" class="px-6 py-3">
          <div class="flex items-center justify-center">
            QR Code
            <a href="#"><svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
              <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
            </svg></a>
          </div>
        </th>

        <th scope="col" class="px-6 py-3">
          <div class="flex justify-center items-center">
            View
            <a href="#"><svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
              <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
            </svg></a>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-center items-center">
            Action
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="(item, index) in tableData" :key="item.id">
<!--        <td class="px-6 py-4">-->
<!--          {{ ++index }}-->
<!--        </td>-->
<!--        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">-->
<!--          {{ item.qr_name }}-->
<!--        </th>-->
<!--        <td class="px-6 py-4">-->
<!--          {{ item.name }}-->
<!--        </td>-->
<!--        <td class="px-6 py-4">-->
<!--          {{ item.surname }}-->
<!--        </td>-->
<!--        <td class="px-6 py-4">-->
<!--          {{ item.title }}-->
<!--        </td>-->
<!--        <td class="px-6 py-4">-->
<!--          {{ item.email }}-->
<!--        </td>-->
<!--        <td class="px-6 py-4">-->
<!--          {{ item.mobile }}-->
<!--        </td>-->
<!--        <td class="px-6 py-4">-->
<!--          {{ item.address }}-->
<!--        </td>-->

        <td class="px-6 py-4">
          <div class="flex justify-center">
            <VueQrcode :value="getViewUrl(item.id)" :size="150"></VueQrcode>
          </div>
        </td>

        <td class="px-6 py-4">
          <div class="flex justify-center">
            <a :href="getViewUrl(item.id)" target="_blank">{{getViewUrl(item.id)}}</a>
          </div>
        </td>

        <td class="px-6 py-4 text-right">
          <div class="flex justify-center">
            <router-link :to="`/update-qr/${item.id}`" class="p-1 px-2 rounded bg-blue-600 text-white font-medium hover:underline hover:text-white mr-3">Edit</router-link>
            <button @click="deleteData(item.id)" class="p-1 px-2 rounded bg-red-600 text-white font-medium hover:text-white">Delete</button>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

</template>

