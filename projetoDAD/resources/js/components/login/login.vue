<template>
  <v-app id="inspire">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <div v-if="loginTrigger" class="text-xs-center">
              <v-progress-circular
                      :size="50"
                      color="primary"
                      indeterminate
              ></v-progress-circular>
            </div>
            <v-card v-else class="elevation-12" light>
              <v-toolbar dark color="primary">
                <v-toolbar-title>Sign In</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    prepend-icon="person"
                    name="email"
                    label="Email"
                    type="text"
                    v-model="user.email"
                  ></v-text-field>
                  <v-text-field
                    id="password"
                    prepend-icon="lock"
                    name="password"
                    label="Password"
                    type="password"
                    v-model="user.password"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" v-on:click.prevent="checkIsUnlocked">Sign In</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
export default {
  data: function() {
    return {
      user: {
        email: "",
        password: ""
      },
      typeofmsg: "success",
      showMessage: false,
      message: "",
      loginTrigger: false
    };
  },
  methods: {
    checkIsUnlocked: function(){
      let vm = this;
      axios.get("/api/users/"+this.user.email)
              .then(function (response) {
                if (response.data.data[0].blocked === 0){
                  vm.login();
                }else {
                  vm.$toasted.show('User Blocked');
                }
              })
              .catch(function (error) {
                vm.toasted.error('This user does not exist');
              })
              .then(function () {
                // always executed
              });
    },
    login() {
      this.showMessage = false;
      axios
        .post("api/login", this.user)
        .then(response => {
          this.loginTrigger = true;
          this.$store.commit("setToken", response.data.access_token);
          return axios.get("api/users/me");
        })
        .then(response => {
          this.$store.commit("setUser", response.data.data);
          this.$socket.emit('user_enter', response.data.data);
          this.$emit("loginMessage");
          this.$emit("hideMessage");
          this.$router.push("/");
          console.log("Login Button: ", this.$store.state.user);
          this.loginTrigger = false;
        })
        .catch(error => {
          this.$store.commit("clearUserAndToken");
          this.typeofmsg = "error";
          this.message = "Invalid credentials";
          this.$toasted.error('Invalid credentials');
          this.showMessage = true;
          console.log(error);
        });
    }
  }
};
</script>
