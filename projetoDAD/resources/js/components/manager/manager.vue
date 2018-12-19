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
					<v-chip v-if="user.shift_active===0">Not Working</v-chip>
					<v-chip v-else-if="user.shift_active===1">Working</v-chip>
				</v-list-tile>
				<v-subheader class="mt-3 grey--text text--white-1">MENU</v-subheader>
				<router-link tag="span" style="cursor: pointer" to="/home">
					<v-list-tile>
						<v-list-tile-action>
							<v-icon color="white white-1">settings</v-icon>
						</v-list-tile-action>
						<v-list-tile-title @click="removeDash" class="white--text text--white-1">Home Page</v-list-tile-title>
					</v-list-tile>
				</router-link>
				<router-link tag="span" style="cursor: pointer" to="/privatedashboard/manager">
					<v-list-tile>
						<v-list-tile-action>
							<v-icon color="white white-1">settings</v-icon>
						</v-list-tile-action>
						<v-list-tile-title @click="showMainView" class="white--text text--white-1">My Dashboard</v-list-tile-title>
					</v-list-tile>
				</router-link>
				<router-link tag="span" style="cursor: pointer" to="/profile">
					<v-list-tile>
						<v-list-tile-action>
							<v-icon color="white white-1">contacts</v-icon>
						</v-list-tile-action>
						<v-list-tile-title @click="removeDash" class="white--text text--white-1">User Profile</v-list-tile-title>
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
				<router-link tag="span" style="cursor: pointer" to="/privatedashboard/manager/tables">
					<v-list-tile>
						<v-list-tile-action>
							<v-icon color="white white-1">format_list_bulleted</v-icon>
						</v-list-tile-action>
						<v-list-tile-title
										@click="hideMainView"
										class="white--text text--white-1"
						>Tables Management
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
				>Global Chat</v-textarea>
			<v-textarea
							outline
							label="Manager Chat"
							id="textManager" class="inputchat"
							v-model="msgManagerTextArea" disabled
			>Global Chat</v-textarea>
			<v-textarea
							outline
							label="Report Chat"
							id="textReport" class="inputchat"
							v-model="msgReportTextArea" disabled
			>Report Chat</v-textarea>
		</v-navigation-drawer>
		<v-toolbar color="blue" dense fixed clipped-left app>
			<v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
			<v-icon class="mx-3">fab fa-youtube</v-icon>

			<v-toolbar-title class="mr-5 align-center">
				<span class="title">Administrator Manager</span>
			</v-toolbar-title>
			<v-spacer></v-spacer>
		</v-toolbar>
		<v-content>
			<v-container fill-height>
				<v-layout justify-center align-center>
					<v-flex v-if="triggerMainView">
						<switch_shift></switch_shift>
						<br>
						<list_users></list_users>
						<br>
						<v-container grid-list-md>
							<v-layout align-center justify-start row fill-height>
								<v-flex xs12 sm6 offset-sm0>
									<list_blocked_users @user-unblocked="userUnblock" :blockuser="trigger"></list_blocked_users>
								</v-flex>
								<v-flex xs12 sm6 offset-sm0>
									<list_unblocked_users @user-blocked="userBlock" :unblockUser="trigger"></list_unblocked_users>
								</v-flex>
							</v-layout>
						</v-container>
						<br>
						<summary_meals></summary_meals>
					</v-flex>
					<v-flex v-else>
						<router-view></router-view>
					</v-flex>
				</v-layout>
			</v-container>
		</v-content>
	</v-app>
</template>

<script>
    import listTables from "./listTables";
    import ListUsers from "../allworkers/ListUsers";
    import ListBlockedUsers from "../manager/users/blocked/list";
    import ListUnblockedUsers from "../manager/users/unblocked/list";
    import SummaryMeals from "../manager/summary/meals/list";
    import switchShift from "../reuse/shift";

    export default {
        props:['messageGlobal', 'messageManager','messageReport'],
        data: () => ({
            drawer: null,
            authenticated: false,
            user: "",
            trigger: "",
            recentNotification: false,
            msgGlobalTextArea:"",
            msgManagerTextArea:"",
            msgReportTextArea:"",
            triggerMainView: true,

        }),
        methods: {
            hideMainView(){
              this.triggerMainView = false;
            },
            showMainView(){
                this.triggerMainView = true;
            },
            removeNav() {
                this.$emit("removeNav");
            },
            removeDash() {
                this.$emit("removeDash");
            },
            userBlock(event) {
                this.trigger = event;
            },
            userUnblock(event) {
                this.trigger = event;
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
                        console.log("Logout Button: UserResource", this.$store.state.user);
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
        components: {
            list_tables: listTables,
            list_users: ListUsers,
            list_blocked_users: ListBlockedUsers,
            list_unblocked_users: ListUnblockedUsers,
            summary_meals: SummaryMeals,
            switch_shift: switchShift,
        },
        mounted() {
            this.user = this.$store.state.user;
            this.$emit("removeNav");
        },
		    watch: {
            messageGlobal: function (newVal) {
		            return this.msgGlobalTextArea = newVal;
            },
            messageManager: function (newVal) {
                return this.msgManagerTextArea = newVal;
            },
            messageReport: function (newVal) {
                return this.msgReportTextArea = newVal;
            }
		    }
    };
</script>

<style>

</style>
