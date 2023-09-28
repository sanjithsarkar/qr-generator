<script setup>
import {ref, onMounted, computed, onUnmounted } from 'vue';
import axios from "axios";
import VueQrcode from 'vue-qrcode';
import Swal from 'sweetalert2';

const tableData = ref({});

//----------------------- Display All Data ---------------------------
const getData = () => {
  axios.get(window.qr_generator.resturl + 'get/data')
      .then((res) => {
        tableData.value = res.data;
      })
}


// ---------------------- Delete data by id --------------------
const deleteData = (id) => {
  Swal.fire({
    title: 'Confirm Deletion',
    text: 'Are you sure you want to delete the selected data?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!',
  }).then((result) => {
    if (result.isConfirmed) {
      axios.delete(window.qr_generator.resturl + 'delete/' + id)
          .then((res) => {
            getData();
          })
          .catch((error) => {
            console.error("Error:", error);
          });
    }
  });
}


// ------------------------ Data view url ---------------------------
const getViewUrl = (id) => {
  return window.qr_generator.site_url + '/qrcode/' + id;
}


// ---------------- Download QR Code ---------------------

const qrCodeSvg = ref(null);

onMounted(() => {
  qrCodeSvg.value = document.querySelector('svg');
});

// Clean up the resources when the component is unmounted
onUnmounted(() => {
  qrCodeSvg.value = null;
});

const downloadQRCode = (id) => {

  const link = document.createElement('a');
  link.href = getViewUrl(id);
  link.download = 'qrcode.png';
  link.style.display = 'none';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

}


// ---------------- Copy Link ---------------------

const copyQRLink = (id) => {

  const qrLink = getViewUrl(id);

  navigator.clipboard.writeText(qrLink)
      .then(() => {
        Swal.fire({
          icon: 'success',
          title: 'Link Copied',
          text: 'The link has been copied to the clipboard.',
        });
      })
      .catch((error) => {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Unable to copy QR link to clipboard.',
        });
      });
}

// ---------------  QR Resize ---------------

const isCrease = ref(false);
const qrSize = ref(80);
const qrWidth = ref(70);
const toggleSize = () => {
  isCrease.value = !isCrease.value;
  if (isCrease.value) {
    qrSize.value = 300;
    qrWidth.value = 200;
  } else {
    qrSize.value = 80;
    qrWidth.value = 70;
  }
}

// ---------------- Mount ---------------------
onMounted(() => {
  getData();
})

</script>

<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg gap-4 pr-4">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 divide-y divide-gray-200">
      <thead class="text-xs text-white uppercase bg-neutral-800 ">
      <tr>

        <th scope="col" class="px-4 py-1">
          <div class="flex items-center justify-center">
            QR Card Name
            <a href="#">
              <svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                   viewBox="0 0 24 24">
                <path
                    d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
              </svg>
            </a>
          </div>
        </th>

        <th scope="col" class="px-6 py-3">
          <div class="flex items-center justify-center">
            QR Code
            <a href="#">
              <svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                   viewBox="0 0 24 24">
                <path
                    d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
              </svg>
            </a>
          </div>
        </th>

        <th scope="col" class="px-6 py-3">
          <div class="flex justify-center items-center">
            View
            <a href="#">
              <svg class="w-3 h-3 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                   viewBox="0 0 24 24">
                <path
                    d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
              </svg>
            </a>
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
      <tr class="bg-white border-b hover:bg-gray-100" v-for="(item, index) in tableData"
          :key="item.id">

        <td class="px-6 py-4">
          <div class="flex justify-center">
            {{ item.name }}
          </div>
        </td>

        <td class="px-6 py-4">
          <div class="flex flex-col items-center justify-center">
            <!-- VueQrcode -->
            <div>
              <button @click="toggleSize">
                <VueQrcode :value="getViewUrl(item.id)" :size="qrSize" :width="qrWidth" class="mb-4"/>
              </button>
            </div>
          </div>
        </td>

        <td class="px-6 py-4">
          <div class="flex flex-col items-center justify-center">
            <a :href="getViewUrl(item.id)" target="_blank" class="mb-6" id="copyLink">{{ getViewUrl(item.id) }}</a>
          </div>
        </td>

        <td class="px-6 py-4 text-right">

          <!---------------- Edit ------------->
          <div class="flex justify-center">
            <div class="group relative bg-blue-600 text-white px-3 py-2 font-medium rounded overflow-hidden transition-transform transform hover:scale-105 mr-3">
              <router-link :to="`/update-qr/${item.id}`">
                <svg class="w-5 h-5 text-white pt-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 1v3m5-3v3m5-3v3M1 7h7m1.506 3.429 2.065 2.065M19 7h-2M2 3h16a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm6 13H6v-2l5.227-5.292a1.46 1.46 0 0 1 2.065 2.065L8 16Z"/>
                </svg>
              </router-link>

              <div class="group-hover:opacity-100 opacity-0 transition-opacity absolute inset-0 flex items-center justify-center bg-black text-white text-center">
                <router-link :to="`/update-qr/${item.id}`" class="hover:text-white text-sm">
                  Edit
                </router-link>
              </div>
            </div>


            <!---------------- Delete ------------->

            <div class="group relative bg-red-600 text-white pl-2 py-1 font-medium rounded overflow-hidden transition-transform transform hover:scale-105 mr-3">

              <button @click="deleteData(item.id)"
                      class="px-2 py-1 rounded text-white font-medium hover:text-white mr-1 mt-1">
                <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 20 16">
                  <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                        d="M8 8v1h4V8m4 7H4a1 1 0 0 1-1-1V5h14v9a1 1 0 0 1-1 1ZM2 1h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Z"/>
                </svg>

              </button>

              <div class="group-hover:opacity-100 opacity-0 transition-opacity text-sm absolute inset-0 flex items-center justify-center bg-black text-white text-center">
                <button @click="deleteData(item.id)" class="text-sm">
                  Delete
                </button>
              </div>
            </div>



            <!---------------- Download QR ------------->
            <button @click="downloadQRCode(item.id)"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-2 mr-3 rounded">
              <svg class="w-4 h-4 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                   viewBox="0 0 16 18">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
              </svg>
            </button>


            <!---------------- Copy Link ------------->
            <button type="button" @click="copyQRLink(item.id)"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
              Copy Link
            </button>

          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

</template>
