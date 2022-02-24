<template>
  <button type="submit" class="btn btn-sm btn-primary"
          @click="toggle">
    <span class="glyphicon glyphicon-heart"> </span>
   <span v-text="favouritesCount"></span>
  </button>
</template>

<script>
export default {
  props:['reply'],
  data(){
    count:this.reply.favoritesCount,
        active:this.reply.isFavorited
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

