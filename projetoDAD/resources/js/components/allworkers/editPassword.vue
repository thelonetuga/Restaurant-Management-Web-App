<template>
    <v-card>
        <v-card-title>
            <span class="headline">User Profile</span>
        </v-card-title>
        <v-card-text>
            <v-container grid-list-md>
                <v-layout wrap>
                    <v-flex xs12>
                        <v-text-field v-model="request.old_password" label="Old Password*" type="password" required></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field v-model="request.new_password" :rules="[rules.required, rules.min]" label="New Password*" type="password" required></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                        <v-text-field v-model="confirm_password" :rules="[rules.required, rules.min]" label="Confirm Password*" type="password" required></v-text-field>
                    </v-flex>
                </v-layout>
            </v-container>
            <small>*indicates required field</small>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click="close">Close</v-btn>
            <v-btn color="blue darken-1" flat @click="updatePassword">Save</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        data() {
            return {
                rules: {
                    required: value => !!value || 'Required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                    emailMatch: () => ('The email and password you entered don\'t match')
                },
                request: {
                    old_password: '',
                    new_password: '',
                },
                confirm_password: '',
                current_user: this.$store.state.user,
            };
        },
        methods: {
            updatePassword() {
                if (this.confirm_password === this.request.new_password) {
                    let vm = this;
                    axios
                        .patch("api/user/" + this.current_user.id + "/edit/password", vm.request)
                        .then(response => {
                            Object.assign(vm.current_user, response.data.data);
                            this.$socket.emit('user_changed', response.data.data);
                            this.$store.commit("setUser", vm.current_user);
                            this.$emit('closeDialog');
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error.response.data.message);
                        })
                        .then(function () {
                            // always executed
                        });
                }
                else{
                    this.$toasted.error('something is wrong')
                }
            },
            close() {
                this.$emit('closeDialog');
            }
        },

    }
</script>

<style scoped>

</style>