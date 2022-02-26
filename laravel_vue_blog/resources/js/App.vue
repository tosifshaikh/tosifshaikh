<template>
<div>
  
       <router-view >

    </router-view>
   
 
  
</div>
</template>
<script>

export default {
    name: "App",
    
    mounted() {
         this.$nextTick(()=>{
          var script = document.createElement('script');
          script.setAttribute('src','/js/vendor-all.min.js');
          
          document.head.appendChild(script);
          var script = document.createElement('script');
          script.setAttribute('src','/js/bootstrap.min.js');
          document.head.appendChild(script);
          var script = document.createElement('script');
          script.setAttribute('src','/js/pcoded.min.js');
          document.head.appendChild(script); 
       }); 
    },
}
</script>