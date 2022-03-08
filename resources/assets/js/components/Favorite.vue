<template>
  <button type="submit" class="btn btn-sm"
          :class="classes"
          @click="toggle">
   <span v-text="count"></span> Likes
  </button>
</template>

<script>
export default {
  props:['reply'],
  data(){
    return{
      count:this.reply.favoritesCount,
       active:this.reply.isFavorited
    }
  },
  computed:{
    classes(){
      return ['btn',this.active ? 'btn-primary':'btn-default']
    }
  },
  methods:{
    toggle(){
      if(this.active){
        this.destroy();
          }else{
        this.create();
      }
    },

    create(){
      axios.post('/replies/'+this.reply.id+'/favorites');
      this.active=true;
      this.count++;
    },
    destroy(){
      axios.delete('/replies/'+this.reply.id+'/favorites')
    }
  }
}
</script>

