<template>

</template>

<script>
import Favorite from './favorite.vue';
export default {
  name: "Reply",
  props:['attributes'],

  components:{
    Favorite
  },
  data(){
    return{
      editing:false,
      body:this.attributes.body,
    }
  },

  methods:
      {
        update(){
          axios.patch('/replies/'+this.attributes.id,{
            body:this.body
          });
          this.editing=false;
          flash('updated');
        },
        destroy(){
          axios.delete('/replies/'+ this.attributes.id);
          $(this.$el).fadeOut(300,()=>{
            flash('Your Reply has been deleted');
          });
        }
      }
}
</script>

<style scoped>

</style>