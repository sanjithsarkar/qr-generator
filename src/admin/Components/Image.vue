<template>
  <button type="button" @click="openMediaFrame">

    {{title}}
  </button>
</template>

<script setup>
// import {Picture} from '@element-plus/icons-vue';
import {nextTick, onMounted} from "vue";

const props = defineProps({
  multiple:false,
  title:{
    default:'Add Media'
  },
  action_title:{
    default:'Use This Media'
  },

  size:{
    default:'medium'
  },
})

let mediaFrame = null;
const emit = defineEmits(['onMediaSelected'])

const openMediaFrame = () => {
  if (mediaFrame == null) {
    return
  }
  mediaFrame.open();

}


onMounted(() => {

  if (!typeof window.wp.media === 'function') {
    return
  }

  mediaFrame = window.wp.media({
    title: 'Select or Upload Media Of Your Chosen Persuasion',
    button: {
      text: props.action_title
    },
    library: {
      type: 'image'
    },
    multiple: props.multiple,  // Set to true to allow multiple files to be selected
  });
  listenForMediaChange();

})

const listenForMediaChange = () => {
  mediaFrame.on('select', function () {
    const attachments = mediaFrame.state().get('selection').toJSON()
    emit('onMediaSelected', attachments)
  })
}
</script>