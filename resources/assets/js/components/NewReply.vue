<template>
  <div v-if="signedIn">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <div class="form-group">
          <textarea id="body" v-model="body" class="form-control" placeholder="Reply here" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-default" @click.prevent="addReply">POST</button>

      </div>




    </div>
  </div>
  <p class="text-center" v-else>
    Please <a href="/login"> Sign in</a>to participate in the discussion</p>
</template>

<script>
import 'at.js';
import 'jquery.caret';
export default {
  props: ['endpoint'],
  name: "NewReply",
  data() {
    return {
      body: '',
    }
  },
  computed: {
    signedIn() {
      return window.App.signedIn;
    }
  },

  mounted(){
    $('#body').atwho({
      at:"@",
      delay:750,
      callbacks:{
        remoteFilter:function (query, callback){
          console.log('Called');
          $.getJSON("/api/users",{name:query}, function(usernames){
            callback(usernames)
          });
        }
      }
    });
  },
  methods: {
    addReply() {
      axios.post(this.endpoint, {body: this.body})
          .then(response => {
            this.body = '';
             flash('Your Reply has been saved');
            this.$emit('created', response.data);
          }).catch(error=>{
            // console.log(error.response);
            flash(error.response.data,'danger');
      });

    }
  }
}
</script>

<style scoped>

</style>