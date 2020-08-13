<template>
  <div>
  	<!-- Send notification button -->
    <button
      :disabled="loading"
      type="button"
      class="btn btn-success btn-send" @click="sendNotification"
    >
      Notify
    </button>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: ['friend'],

  data: () => ({
  	loading: false
  }),

  methods: {
    /**
     * Send a request to the server for a push notification.
     */
    sendNotification () {
      this.loading = true
      
      console.log('---', this.friend)

      axios.post('/notifications/'+this.friend.id)
        .catch(error => console.log(error))
        .then(() => { this.loading = false })
    }
  }
  
}

</script>
