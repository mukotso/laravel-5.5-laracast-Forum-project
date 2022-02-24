<template>

</template>

<script>
export default {
  name: "Reply",
props:['attributes'],
  data(){
    return{
      editing:false,
      body:this.attributes.body,
    }
  },

  methods:
      {
        update(){
          axios.patch('/reply'+this.attributes.id,{
            body:this.body

          });
          this.editing=false;
          flash('updated');
        },
        destroy(){
          axios.delete('/reply/'+ this.attribute.id);
          $(this.$el).fadeOut(300,()=>{
            flash('Your Reply has been deleted');
          });
        }
      }
}
</script>

<style scoped>

</style>