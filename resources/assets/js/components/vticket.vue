
<template>
  <div>
    {{ ticket.id }} "  " {{ ticket.requestedBy_uid }} "  " {{ ticket.title }}
  </div>
</template>

<script>
    export default {
      props: {
            ticket: {
              
            }
      },

      mounted: function () {  },

      methods: {
      }
  }
  
</script>



