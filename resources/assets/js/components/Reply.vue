<template>
  <div :id="'reply-'+id" class="panel panel-default">

    <div class="panel-heading">
      <div class="level">
        <h5 class="flex">
          <a :href="'/profiles/'+data.owner.name"
             v-text="data.owner.name"></a> said
          <span v-text="ago"></span>
        </h5>
        <div v-if="signedIn">
          <favorite :reply="data"></favorite>
        </div>


      </div>

    </div>

    <div class="card-body">
      <div v-if="editing">
        <form @submit="update">
          <div class="form-group">
            <textarea class="form-control" v-model="body" required></textarea>
          </div>
          <button type="button" class="btn btn-xs btn-link" @click="editing=false">CANCEL</button>
          <button type="submit" class="btn btn-xs btn-primary mr-1">UPDATE</button>
        </form>
      </div>
      <div v-else v-html="body">

      </div>
      <div class="panel-footer level" v-if="canUpdate">
        <button class="btn btn-xs mr-1" @click="editing=true">EDIT</button>
        <button class="btn btn-xs btn-danger mr-1" @click="destroy">DELETE</button>
      </div>


    </div>
  </div>

</template>

<script>
import Favorite from './favorite.vue';
import moment from 'moment';

export default {
  name: "Reply",
  props: ['data'],

  components: {
    Favorite
  },
  data() {
    return {
      editing: false,
      id: this.data.id,
      body: this.data.body,
    }
  },
  computed: {
    ago() {
      return moment(this.data.created_at).fromNow() + '......';
    },
    signedIn() {
      return window.App.signedIn;
    },
    canUpdate() {
      return this.authorize(user => this.data.user_id === user.id)
      // return  this.data.user_id==window.App.user.id;
    }
  },
  methods:
      {
        update() {
          axios.patch('/replies/' + this.data.id, {
            body: this.body
          })
              .catch(error => {
                flash(error.response.data, 'danger')
              });
          this.editing = false;
          flash('updated');
        },
        destroy() {
          axios.delete('/replies/' + this.data.id);
          this.$emit('deleted', this.data.id);
          flash('Reply deleted');
        }
      }
}
</script>

<style scoped>

</style>