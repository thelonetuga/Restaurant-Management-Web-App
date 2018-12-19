<template>
	<v-card v-if=" isManager && isPrivateChat">
		<v-card-title>
			<span class="headline">Manager Chat</span>
		</v-card-title>
		<v-card-text>
			<v-text-field
							label="Write Something to Manager Chat"
							placeholder="Hi!"
							outline
							type="text" id="inputManager" class="inputchat"
							v-model="msgManagerText"
			></v-text-field>
		</v-card-text>
		<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="blue darken-1" flat @click="sendDialog">Close</v-btn>
			<v-btn color="blue darken-1" flat @click="sendManagerMsg">Send</v-btn>
		</v-card-actions>
	</v-card>
	<v-card v-else-if="isnotifyManager">
		<v-card-title>
			<span class="headline">Report Chat</span>
		</v-card-title>
		<v-card-text>
			<v-text-field
							label="Send a report to a manager"
							placeholder="Hi!"
							outline
							type="text" id="inputError" class="inputchat"
							v-model="msgManagerText"
			></v-text-field>
		</v-card-text>
		<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="blue darken-1" flat @click="sendDialog">Close</v-btn>
			<v-btn color="blue darken-1" flat @click="sendReportMsg">Send</v-btn>
		</v-card-actions>
	</v-card>
	<v-card v-else-if=" isCook && isPrivateChat">
		<v-card-title>
			<span class="headline">Cook Chat</span>
		</v-card-title>
		<v-card-text>
			<v-text-field
							label="Write Something to Manager Chat"
							placeholder="Hi!"
							outline
							type="text" id="inputCook" class="inputchat"
							v-model="msgManagerText"
			></v-text-field>
		</v-card-text>
		<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="blue darken-1" flat @click="sendDialog">Close</v-btn>
			<v-btn color="blue darken-1" flat @click="">Send</v-btn>
		</v-card-actions>
	</v-card>
	<v-card v-else-if=" isWaiter && isPrivateChat">
		<v-card-title>
			<span class="headline">Waiter Chat</span>
		</v-card-title>
		<v-card-text>
			<v-text-field
							label="Write Something to Manager Chat"
							placeholder="Hi!"
							outline
							type="text" id="inputWaiter" class="inputchat"
							v-model="msgManagerText"
			></v-text-field>
		</v-card-text>
		<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="blue darken-1" flat @click="sendDialog">Close</v-btn>
			<v-btn color="blue darken-1" flat @click="">Send</v-btn>
		</v-card-actions>
	</v-card>
	<v-card v-else-if=" isCashier && isPrivateChat">
		<v-card-title>
			<span class="headline">Cashier Chat</span>
		</v-card-title>
		<v-card-text>
			<v-text-field
							label="Write Something to Manager Chat"
							placeholder="Hi!"
							outline
							type="text" id="inputCashier" class="inputchat"
							v-model="msgManagerText"
			></v-text-field>
		</v-card-text>
		<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="blue darken-1" flat @click="sendDialog">Close</v-btn>
			<v-btn color="blue darken-1" flat @click="">Send</v-btn>
		</v-card-actions>
	</v-card>
	<v-card v-else-if="isGlobalChat">
		<v-card-title>
			<span class="headline">Global Chat</span>
		</v-card-title>
		<v-card-text>
			<v-text-field
							label="Write Something to Global Chat"
							placeholder="Hi!"
							outline
							type="text" id="inputGlobal" class="inputchat"
							v-model="msgGlobalText"
			></v-text-field>
		</v-card-text>
		<v-card-actions>
			<v-spacer></v-spacer>
			<v-btn color="blue darken-1" flat @click="sendDialog">Close</v-btn>
			<v-btn color="blue darken-1" flat @click="sendGlobalMsg">Send</v-btn>
		</v-card-actions>
	</v-card>
</template>

<script>
    export default {
        props: ['globalChat', 'privateChat','sendNotifyManager'],
        data: () => ({
            msgGlobalText: "",
            msgManagerText: "",
            isGlobalChat: false,
            isPrivateChat: false,
            isnotifyManager: false,
        }),
        methods: {
            sendDialog() {
                this.$emit("chatClose");
            },
            sendGlobalMsg: function () {
                this.sendDialog();
                if (this.$store.state.user === null) {
                    this.$socket.emit('msg_from_client', this.msgGlobalText);
                } else {
                    this.$socket.emit('msg_from_client', this.msgGlobalText,
                        this.$store.state.user);
                }
                this.msgGlobalText = "";
            },
            sendManagerMsg: function () {
                this.sendDialog();
                if (this.$store.state.user === null) {
                    this.$toasted.error('User is not logged in');
                } else {
                    this.$socket.emit('msg_from_worker_manager',
                        this.msgManagerText, this.$store.state.user);
                }
                this.msgManagerText = "";
            },
            sendReportMsg: function () {
                this.sendDialog();
                if (this.$store.state.user === null) {
                    this.$toasted.error('User is not logged in');
                } else {
                    this.$socket.emit('msg_from_worker_report_manager',
                        this.msgManagerText, this.$store.state.user);
                }
                this.msgManagerText = "";
            }
        },
        computed: {
            isManager: function () {
                return this.$store.state.ismanager;
            },
            isCook: function () {
                return this.$store.state.iscook;
            },
            isCashier: function () {
                return this.$store.state.iscashier;
            },
		        isWaiter: function () {
                return this.$store.state.iswaiter;
            }
        },
        watch: {
            globalChat: function (newVal) {
                this.isGlobalChat = newVal;
                this.isPrivateChat = false;
                this.isnotifyManager = false;
            },
            privateChat: function (newVal) {
                this.isPrivateChat = newVal;
                this.isGlobalChat = false;
                this.isnotifyManager = false;
            },
            sendNotifyManager: function (newVal) {
		            this.isnotifyManager = newVal;
                this.isGlobalChat = false;
                this.isPrivateChat = false;
            }
        }
    }
</script>

<style scoped>

</style>