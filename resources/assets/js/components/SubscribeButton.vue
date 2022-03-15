<template>
  <button  @click.prevent="subscribe()">Subscribe</button>
</template>

<script>
export default {
  name: "SubscribeButton",
  props:['active'],

  computed:{
    classes(){
      return ['btn', this.active ?'btn-primary' :' btn-default'];
    }
  },
  methods:{
    subscribe(){
      axios[( this.active ? 'delete' : 'post' )] (location.pathname +'/subscriptions');
      // flash('Subscribed');
    }
  }
}
</script>

<style scoped>

</style>