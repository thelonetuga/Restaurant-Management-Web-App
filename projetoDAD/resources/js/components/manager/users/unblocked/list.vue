<template>
	<v-card>
		<v-toolbar color="dark-green" dark>
			<v-toolbar-side-icon></v-toolbar-side-icon>

			<v-toolbar-title>Unblocked Users</v-toolbar-title>

			<v-spacer></v-spacer>

			<v-btn icon>
				<v-icon>search</v-icon>
			</v-btn>
		</v-toolbar>

		<v-list two-line subheader>

			<v-list-tile
							v-for="user in usersUnblocked"
							:key="user.id"
							avatar
							@click=""
			>
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

				<v-list-tile-content>
					<v-list-tile-title>{{ user.name }}</v-list-tile-title>
					<v-list-tile-sub-title>{{ user.type }}</v-list-tile-sub-title>
				</v-list-tile-content>

				<v-list-tile-action  v-if="currentUser.id != user.id">
					<v-btn flat small color="error" @click="blockUser(user)">Block</v-btn>
				</v-list-tile-action>
			</v-list-tile>
		</v-list>
		<div class="text-xs-center">
			<v-pagination
							v-model="currentPage"
							:length=lastPage
							@input="makePagination"
							circle
			></v-pagination>
		</div>
	</v-card>
</template>

<script>
    export default {
        data() {
            return {
                usersUnblocked: [],
                currentPage: 1,
                lastPage: 0,
                pagination: {},
                currentUser: this.$store.state.user,
            }
        },
        methods: {
            fetchUnblockedUsers: function () {
                axios.get("api/users/unblocked")
                    .then(response => {
                        this.usersUnblocked = response.data.data;
                        this.currentPage = response.data.meta.current_page;
                        this.lastPage = response.data.meta.last_page;
                        this.pagination = response.data.meta.links;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            makePagination(page) {
                axios.get("api/users/unblocked?page=" + page)
                    .then(response => {
                        this.usersUnblocked = response.data.data;
                        this.currentPage = response.data.meta.current_page;
                        this.lastPage = response.data.meta.last_page;
                        this.pagination = response.data.meta.links;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            blockUser(user){
                axios.patch("api/user/block/" + user.id)
                    .then(response => {
                        this.$socket.emit('user_blocked', response.data.data);
                        this.fetchUnblockedUsers();
                        this.$emit("user-blocked", response.data.data);
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
        },
		    watch:{
            unblockuser: function (newVal, oldVal) {
                this.usersUnblocked.push(newVal);
            }
		    },
        created() {
            this.fetchUnblockedUsers();
        }
    }
</script>

<style scoped>

</style>