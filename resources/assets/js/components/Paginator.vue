<template>
  <ul class="pagination">
    <li v-show="prevUrl" class="page-item">
      <a class="page-link" href="#" aria-label="Previous" @click.prevent="page--">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <li v-show="nextUrl" class="page-item">
      <a class="page-link" href="#" aria-label="Next" @click.prevent ="page++">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>

</template>

<script>
export default {
  name: "Paginator",
  props:['dataSet'],
  data(){
    return{
      page:1,
      prevUrl:false,
      nextUrl:false,
    }
  },

  watch:{
    dataSet(){
      this.page=this.dataSet.current_page;
      this.page=this.dataSet.prev_page_url;
      this.nextUrl=this.dataSet.next_page_url;
    },
    page(){
      this.broadcast().updateUrl();
    }
  },
  computed:{
    shouldPaginate(){
      return  !! this.prevUrl || !! this.nextUrl;
    }
  },
  methods:{
    broadcast(){
      this.$emit('updated', this.page);
      return this;
    },
    updateUrl(){
      history.pushState(null,null,'?page='+this.page);
    }
  }
}
</script>

<style scoped>

</style>