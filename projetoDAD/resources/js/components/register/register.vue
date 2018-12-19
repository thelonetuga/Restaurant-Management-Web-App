<template>
  <v-app id="inspire">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-1" light>
              <v-toolbar dark color="primary">
                <v-toolbar-title>Register User</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    prepend-icon="email"
                    name="email"
                    label="Email"
                    type="text"
                    v-model="newUser.email"
                  ></v-text-field>
                  <v-text-field
                    id="username"
                    prepend-icon="person"
                    name="username"
                    label="username"
                    type="username"
                    v-model="newUser.username"
                  ></v-text-field>
                  <v-select :items="types" label="Worker Type" v-model="newUser.type"></v-select>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" v-on:click.prevent="register">Register</v-btn>
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
  data() {
    return {
      newUser: {
        username: "",
        email: "",
        type:""
      },
      types:["manager","waiter","cashier","cook"]
    };
  },

  methods: {
    register() {
      axios
        .post("/api/register", this.newUser)
        .then(({ data }) => {
          this.$router.push("/dashboard");
        })
        .catch(({ response }) => {
          alert(response.data.message);
        });
    }
  }
};
</script>
