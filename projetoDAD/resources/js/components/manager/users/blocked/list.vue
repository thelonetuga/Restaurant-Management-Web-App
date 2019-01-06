<template>
  <v-card>
    <v-toolbar color="red" dark>
      <v-toolbar-side-icon></v-toolbar-side-icon>

      <v-toolbar-title>Blocked Users</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>search</v-icon>
      </v-btn>
    </v-toolbar>

    <v-list two-line subheader>
      <v-list-tile v-for="user in usersBlocked" :key="user.id" avatar @click>
        <v-list-tile-avatar>
          <v-avatar :tile="false" :size="60" color="grey lighten-4">
            <v-img :src="'/storage/profiles/'+user.photo_url" fluid alt="Image" aspect-ratio="2.75"></v-img>
          </v-avatar>
        </v-list-tile-avatar>

        <v-list-tile-content>
          <v-list-tile-title>{{ user.name }}</v-list-tile-title>
          <v-list-tile-sub-title>{{ user.type }}</v-list-tile-sub-title>
        </v-list-tile-content>

        <v-list-tile-action v-if="currentUser.id != user.id">
          <v-btn flat small color="success" @click="unblockUser(user)">Unblock</v-btn>
        </v-list-tile-action>
      </v-list-tile>
    </v-list>
    <div class="text-xs-center">
      <v-pagination v-model="currentPage" :length="lastPage" @input="makePagination" circle></v-pagination>
    </div>
  </v-card>
</template>

<script>
export default {
  props: ["blockuser"],
  data() {
    return {
      usersBlocked: [],
      currentPage: 1,
      lastPage: 0,
      pagination: {},
      currentUser: this.$store.state.user
    };
  },
  methods: {
    fetchBlockedUsers: function() {
      axios
        .get("api/users/blocked")
        .then(response => {
          this.usersBlocked = response.data.data;
          this.currentPage = response.data.meta.current_page;
          this.lastPage = response.data.meta.last_page;
          this.pagination = response.data.meta.links;
        })
        .catch(function(error) {
          // handle error
          console.log(error);
          this.$toasted.error('Something went wrong');
        })
        .then(function() {
          // always executed
        });
    },
    makePagination(page) {
      axios
        .get("api/users/blocked?page=" + page)
        .then(response => {
          this.usersBlocked = response.data.data;
          this.currentPage = response.data.meta.current_page;
          this.lastPage = response.data.meta.last_page;
          this.pagination = response.data.meta.links;
        })
        .catch(function(error) {
          // handle error
          this.$toasted.error('Something went wrong');
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    },
    unblockUser(user) {
      axios
        .patch("api/user/unblock/" + user.id)
        .then(response => {
          this.$socket.emit('user_unblocked', response.data.data);
          this.$emit("user-unblocked", response.data.data);
          this.$toasted.show('User Unblocked');
          this.fetchBlockedUsers();
        })
        .catch(function(error) {
          // handle error
          this.$toasted.error('Something went wrong');
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    }
  },
  watch: {
    blockuser: function(newVal, oldVal) {
      this.usersBlocked.push(newVal);
    }
  },
  created() {
    this.fetchBlockedUsers();
  }
};
</script>

<style scoped>
</style>
