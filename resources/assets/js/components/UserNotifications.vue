<template>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="">
      <span class="glyphicon glyphicon-bell"></span>
    </a>

    <ul class="dropdown-menu">
      <li v-for="notification in notifications">
        <a href="notification.data.link" @click.prevent="marrkAsRead(notification)">notification.data.messsage</a>
      </li>

    </ul>
  </li>
</template>

<script>
export default {
  name: "UserNotifications",
  data() {
    return {notifications: false}
  },
  created() {
    axios.get("/profiles/" + window.App.user.namr + "/notifications")
        .then(response => this.notifications = response.data);
  },

  methods:{
    markAsRead(notification){
      axios.delete('/profiles/'+ window.App.user.name + '/notifications'+ notification.id)
    }
  }
}
</script>

<style scoped>

</style>