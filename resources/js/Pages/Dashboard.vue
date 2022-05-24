<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
import { onMounted, ref } from '@vue/runtime-core';
import { Inertia } from '@inertiajs/inertia';
var data = ref({
    
    'newMessage': ''
});

var props = defineProps({
    Tuser: Object,
    messages : Array,
})

var mUsers = ref({});

onMounted(() =>{
    Echo.join('chatroom')
    .here((users) => {
        console.log(users)
        mUsers.value = users
    })
    .listen('.App\\Events\\NewMesage', (e) =>{
        console.log(e.message)
        props.messages.push(e.message);
    })
    .joining((user) => {
        console.log(user.name);
    })
    .leaving((user) => {
        console.log(user.name);
    })
    .error((error) => {
        console.error(error);
    });
});

function send()
{
    if(data.value.newMessage)
    {
        Inertia.post("/message/new",{'message': data.value.newMessage})
    }
} 

function time_ago(time) {

  switch (typeof time) {
    case 'number':
      break;
    case 'string':
      time = +new Date(time);
      break;
    case 'object':
      if (time.constructor === Date) time = time.getTime();
      break;
    default:
      time = +new Date();
  }
  var time_formats = [
    [60, 'seconds', 1], // 60
    [120, '1 minute ago', '1 minute from now'], // 60*2
    [3600, 'minutes', 60], // 60*60, 60
    [7200, '1 hour ago', '1 hour from now'], // 60*60*2
    [86400, 'hours', 3600], // 60*60*24, 60*60
    [172800, 'Yesterday', 'Tomorrow'], // 60*60*24*2
    [604800, 'days', 86400], // 60*60*24*7, 60*60*24
    [1209600, 'Last week', 'Next week'], // 60*60*24*7*4*2
    [2419200, 'weeks', 604800], // 60*60*24*7*4, 60*60*24*7
    [4838400, 'Last month', 'Next month'], // 60*60*24*7*4*2
    [29030400, 'months', 2419200], // 60*60*24*7*4*12, 60*60*24*7*4
    [58060800, 'Last year', 'Next year'], // 60*60*24*7*4*12*2
    [2903040000, 'years', 29030400], // 60*60*24*7*4*12*100, 60*60*24*7*4*12
    [5806080000, 'Last century', 'Next century'], // 60*60*24*7*4*12*100*2
    [58060800000, 'centuries', 2903040000] // 60*60*24*7*4*12*100*20, 60*60*24*7*4*12*100
  ];
  var seconds = (+new Date() - time) / 1000,
    token = 'ago',
    list_choice = 1;

  if (seconds == 0) {
    return 'Just now'
  }
  if (seconds < 0) {
    seconds = Math.abs(seconds);
    token = 'from now';
    list_choice = 2;
  }
  var i = 0,
    format;
  while (format = time_formats[i++])
    if (seconds < format[0]) {
      if (typeof format[2] == 'string')
        return format[list_choice];
      else
        return Math.floor(seconds / format[2]) + ' ' + format[1] + ' ' + token;
    }
  return time;
}
</script>

<template>
    <AppLayout :users="mUsers" title="Dashboard">
        <div class="flex flex-col-reverse relative">
            <div style="height:calc(100vh - 130px)" class="w-full flex flex-col-reverse bg-gray-200 overflow-y-scroll">
                <div v-for="message in messages.slice().reverse()" :key="message" :class="{'flex flex-row-reverse': Tuser.id == message.user.id}" class="m-2">
                    <div class="flex flex-col px-2 py-1 rounded-lg shadow-md" :class="{'bg-gray-100 text-gray-500': Tuser.id == message.user.id, 'bg-gray-700 text-white': Tuser.id != message.user.id}">
                        <div :class="{'flex flex-row-reverse': Tuser.id == message.user.id}" class="flex justify-between w-full">
                            <div :class="{'hidden': Tuser.id == message.user.id}" class="font-dosis text-lg">
                                {{message.user.name}}
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-dosis tracking-wide">{{message.message}}</span>
                            <div class="text-gray-400 text-xs text-right">
                                {{time_ago(message.created_at)}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-1 my-1 w-full sm:w-1/2 bg-gray-200">
            <div class="px-1">
                <input @keydown.enter="send" v-model="data.newMessage" type="text" placeholder="type your message here" class="w-full bg-white h-14 border border-gray-300 focus:ring-0 focus:border-gray-300 pr-14 rounded-3xl relative">
                <div @click="send" class="absolute top-1 rounded-full right-2 bg-gray-100 px-2 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-blue-500" width="24" height="24" viewBox="0 0 24 24">\
                        <path d="M22 12l-20 12 5-12-5-12z"/>
                    </svg>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
 @import url('https://fonts.googleapis.com/css2?family=Dosis:wght@500&display=swap');

 .font-dosis{
       
           font-family: 'Dosis', sans-serif;
 }
 /* .font-nunito{

           font-family: 'Nunito', sans-serif;

 } */
</style>