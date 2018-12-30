<template>
    <v-app id="inspire">
        <v-navigation-drawer v-model="drawer" fixed clipped app>
            <v-list class="pa-3" dense>
                <br>
                <v-list-tile avatar>
                    <v-list-tile-avatar>
                        <v-avatar :tile="false" :size="60" color="grey lighten-4">
                            <v-img
                                    :src="'/storage/profiles/'+user.photo_url"
                                    fluid
                                    alt="Image"
                                    aspect-ratio="2.75"
                            ></v-img>
                        </v-avatar>
                    </v-list-tile-avatar>
                    <v-list-tile-title v-text="user.name"></v-list-tile-title>
                </v-list-tile>
                <v-list-tile >
                    <v-chip v-if="user.shift_active===0" color="red" text-color="white">
                        <v-avatar>
                            <v-icon>clear</v-icon>
                        </v-avatar>
                        You are: Not Working
                    </v-chip>
                    <v-chip v-else-if="user.shift_active===1" color="green" text-color="white">
                        <v-avatar>
                            <v-icon>check_circle</v-icon>
                        </v-avatar>
                        You are: Working
                    </v-chip>
                </v-list-tile>
                <v-subheader class="mt-3 grey--text text--white-1">MENU</v-subheader>
                <router-link tag="span" style="cursor: pointer" to="/home">
                    <v-list-tile>
                        <v-list-tile-action>
                            <v-icon color="white white-1">settings</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title @click="removeDash" class="white--text text--white-1">Home Page
                        </v-list-tile-title>
                    </v-list-tile>
                </router-link>
                <router-link tag="span" style="cursor: pointer" to="/privatedashboard/cook">
                    <v-list-tile>
                        <v-list-tile-action>
                            <v-icon color="white white-1">settings</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title class="white--text text--white-1">My Dashboard</v-list-tile-title>
                    </v-list-tile>
                </router-link>
                <router-link tag="span" style="cursor: pointer" to="/profile">
                    <v-list-tile>
                        <v-list-tile-action>
                            <v-icon color="white white-1">contacts</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title @click="removeDash" class="white--text text--white-1">User Profile
                        </v-list-tile-title>
                    </v-list-tile>
                </router-link>
                <router-link tag="span" style="cursor: pointer" to="/items">
                    <v-list-tile>
                        <v-list-tile-action>
                            <v-icon color="white white-1">format_list_bulleted</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title
                                @click="removeDash"
                                class="white--text text--white-1"
                        >Items Management
                        </v-list-tile-title>
                    </v-list-tile>
                </router-link>
                <v-list-tile @click="logout">
                    <v-list-tile-action>
                        <v-icon color="white white-1">power_off</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title class="white--text text--white-1">Logout</v-list-tile-title>
                </v-list-tile>
            </v-list>
            <v-textarea
                    outline
                    label="Global Chat"
                    id="textGlobal" class="inputchat"
                    v-model="msgGlobalTextArea" disabled
            >Global Chat
            </v-textarea>
        </v-navigation-drawer>
        <v-toolbar color="blue" dense fixed clipped-left app>
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-icon class="mx-3">fab fa-youtube</v-icon>

            <v-toolbar-title class="mr-5 align-center">
                <span class="title">Cook Manager</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>
        <v-content>
            <v-container fill-height>
                <v-layout justify-center align-center>
                    <v-flex>
                        <switch-Shift></switch-Shift>
                        <list_orders :updateOrderTable="updateOrderTable"></list_orders>
                        <list_pending_orders :updateOrderTable="updateOrderTable"></list_pending_orders>
                        <router-view></router-view>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    import MyOrders from './ListMyOrders'
    import PendingOrders from './PendingOrders'
    import switchShift from "../reuse/shift";

    export default {
        props: ['updateOrderTable'],
        data: () => ({
            drawer: null,
            authenticated: false,
            user: "",
            trigger: "",
            recentNotification: false,
            msgGlobalTextArea: "",
            msgManagerTextArea: "",

        }),
        methods: {
            removeNav() {
                this.$emit("removeNav");
            },
            removeDash() {
                this.$emit("removeDash");
            },
            logout() {
                this.showMessage = false;
                axios
                    .post("api/logout")
                    .then(response => {
                        this.$store.commit("clearUserAndToken");
                        this.user = null;
                        this.$emit("logoutMessage");
                        this.$emit("removeDash");
                        this.$router.push("/");
                        console.log("Logout Button:", this.$store.state.user);
                    })
                    .catch(error => {
                        this.$store.commit("clearUserAndToken");
                        this.typeofmsg = "alert-danger";
                        this.message =
                            "Logout incorrect. But local credentials were discarded";
                        this.showMessage = true;
                        console.log(error);
                    });
            }
        },
        watch:{
            updateOrderTable: function (newVal) {
                return newVal;
            }
        },
        components: {
            'list_orders': MyOrders,
            'list_pending_orders': PendingOrders,
            'switch-Shift': switchShift
        },
        mounted() {
            this.user = this.$store.state.user;
            this.$emit("removeNav");
        }
    };
</script>

<style>

</style>
