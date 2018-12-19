<template>
  <div>
    <v-toolbar id="mainBar">
      <img src="/storage/icons/vuetify.png" alt="Vuetify.js" height="40">
      <router-link tag="span" style="cursor: pointer" to="/">
        <v-toolbar-title>OLD BUT GOLD</v-toolbar-title>
      </router-link>
      <v-spacer></v-spacer>
      <div class="hidden-sm-and-down">
        <div v-if="authenticated != null">
          <v-btn color="primary" v-on:click.prevent="logout">Log Out</v-btn>
          <router-link tag="span" style="cursor: pointer" to="/items">
            <v-btn flat>Items List</v-btn>
          </router-link>
          <router-link tag="span" style="cursor: pointer" to="/profile">
            <v-btn flat>User Profile</v-btn>
          </router-link>
          <router-link
            @removeDash="showDashboard"
            v-if="ismanager"
            tag="span"
            style="cursor: pointer"
            to="/privatedashboard/manager"
          >
            <v-btn v-on:click.prevent="showDashboard" flat>Private Dashboard</v-btn>
          </router-link>
          <router-link
            @removeDash="showDashboard"
            v-else-if="iscook"
            tag="span"
            style="cursor: pointer"
            to="/privatedashboard/cook"
          >
            <v-btn v-on:click.prevent="showDashboard" flat>Private Dashboard</v-btn>
          </router-link>
          <router-link
            @removeDash="showDashboard"
            v-else-if="iswaiter"
            tag="span"
            style="cursor: pointer"
            to="/privatedashboard/waiter"
          >
            <v-btn v-on:click.prevent="showDashboard" flat>Private Dashboard</v-btn>
          </router-link>
          <router-link
            @removeDash="showDashboard"
            v-else
            tag="span"
            style="cursor: pointer"
            to="/privatedashboard/cashier"
          >
            <v-btn v-on:click.prevent="showDashboard" flat>Private Dashboard</v-btn>
          </router-link>
        </div>
        <div v-else>
          <router-link tag="span" style="cursor: pointer" to="/login">
            <v-btn color="primary" flat>Login</v-btn>
          </router-link>
          <router-link tag="span" style="cursor: pointer" to="/items">
            <v-btn flat>Items List</v-btn>
          </router-link>
        </div>
      </div>
    </v-toolbar>
  </div>
</template>
<script>
export default {
  data() {
    return {
      user: null,
      typeofmsg: "success",
      showMessage: false,
      message: ""
    };
  },
  methods: {
    showDashboard() {
      this.$emit("setDashboard");
    },
    logout() {
      this.showMessage = false;
      axios
        .post("api/logout")
        .then(response => {
          this.$socket.emit("user_exit", this.$store.state.user);
          this.$store.commit("clearUserAndToken");
          this.user = null;
          this.$emit("logoutMessage");
          this.$emit("hideMessage");
          this.$router.push("/");
          console.log("Logout Button: UserResource", this.$store.state.user);
        })
        .catch(error => {
          this.$socket.emit("user_exit", this.$store.state.user);
          this.$store.commit("clearUserAndToken");
          this.typeofmsg = "danger";
          this.message =
            "Logout incorrect. But local credentials were discarded";
          this.showMessage = true;
          console.log(error);
        });
    },
    loginMessage() {
      this.showMessage = true;
      this.typeofmsg = "success";
      this.message = "UserResource authenticated correctly";
    }
  },
  computed: {
    authenticated: function() {
      return this.$store.state.user;
    },
    ismanager: function() {
      return this.$store.state.ismanager;
    },
    iscook: function() {
      return this.$store.state.iscook;
    },
    iswaiter: function() {
      return this.$store.state.iswaiter;
    },
    iscashier: function() {
      return this.$store.state.iscashier;
    }
  },
  mounted() {
    this.user = this.$store.state.user;
  }
};
</script>
