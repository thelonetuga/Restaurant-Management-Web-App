<template>
	<v-bottom-nav
					:active.sync="bottomNav"
					:color="color"
					:value="true"
					dense fixed clipped-left app
	>
		<div class="text-xs-center">
			<v-dialog v-model="showPrivateChat" persistent max-width="600px">
				<v-btn dark slot="activator">
					<span>Private Chat</span>
					<v-icon>message</v-icon>
				</v-btn>
				<send-message @chatClose="closeDialogPrivate" :privateChat="showPrivateChat"></send-message>
			</v-dialog>
			<v-menu origin="center center" transition="scale-transition" open-on-hover top offset-y >
				<v-btn dark slot="activator">
					<span>Recent Notification</span>
					<v-badge right overlap color="red">
						<span slot="badge" v-model="counter">{{counter}}</span>
						<v-icon>notifications</v-icon>
					</v-badge>
				</v-btn>
				<notification-view @clean="reduceNotifications" :arrayNotificacoes="recentNotifications"></notification-view>
			</v-menu>
			<v-chip v-if="currentUser.shift_active===0" color="red" text-color="white">
				<v-avatar>
					<v-icon>clear</v-icon>
				</v-avatar>
				You are: Not Working
			</v-chip>
			<v-chip v-else-if="currentUser.shift_active===1" color="green" text-color="white">
				<v-avatar>
					<v-icon>check_circle</v-icon>
				</v-avatar>
				You are: Working
			</v-chip>
				<v-dialog v-model="showGlobalChat" persistent max-width="600px">
					<v-btn ml-1 dark slot="activator">
						<span>Global Chat</span>
						<v-icon>message</v-icon>
					</v-btn>
					<send-message @chatClose="closeDialogGlobal" :globalChat="showGlobalChat"></send-message>
				</v-dialog>
				<v-dialog v-model="showNotifyManagerChat" persistent max-width="600px">
					<v-btn dark slot="activator">
						<span>Report a Problem</span>
						<v-icon>message</v-icon>
					</v-btn>
					<send-message @chatClose="closeDialogReport" :sendNotifyManager="showNotifyManagerChat"></send-message>
				</v-dialog>
		</div>
	</v-bottom-nav>
</template>
<script>
    import notificationView from './notificationView';
    import sendMessage from './chat';

    export default {
        props: ['arrayNotificationReportChannel', 'updateOrderTable', 'updateItemList', 'updateTable','updateInvoiceTable', 'updateMealTable'],
        data() {
            return {
                bottomNav: 1,
                chat: false,
                showGlobalChat: false,
                showPrivateChat: false,
                showNotifyManagerChat: false,
                currentUser: this.$store.state.user,
                recentNotifications: [],
		            counter: 0,
            }
        },
        methods: {
            closeDialogPrivate() {
                this.showPrivateChat = false;
            },
		        closeDialogGlobal() {
                this.showGlobalChat = false;
            },
            closeDialogReport() {
                this.showNotifyManagerChat = false;
            },
            reduceNotifications(val){
                this.counter = this.counter + val;
            }
        },
        computed: {
            color() {
                switch (this.bottomNav) {
                    case 0:
                        return 'blue-grey';
                    case 1:
                        return 'teal';
                    case 2:
                        return 'brown';
                    case 3:
                        return 'light-blue darken-3';
		                case 4:
		                    return 'indigo';
                }
            },
            trigger: function (newVal) {
                return newVal;
            },
        },
		    watch:{
            arrayNotificationReportChannel: function (newVal, oldVal) {
                this.bottomNav = 2;
                this.reduceNotifications(1);
                return this.recentNotifications = newVal;
            },
            updateOrderTable: function (newVal) {
                this.bottomNav = 2;
                this.recentNotifications = newVal;
                this.reduceNotifications(1);
            },
            updateItemList: function (newVal) {
                this.bottomNav = 2;
                this.recentNotifications = newVal;
                this.reduceNotifications(1);
            },
            updateTable: function (newVal) {
                this.bottomNav = 2;
                this.recentNotifications = newVal;
                this.reduceNotifications(1);
            },
            updateInvoiceTable: function (newVal) {
                this.bottomNav = 2;
                this.recentNotifications = newVal;
                this.reduceNotifications(1);
            },
            updateMealTable: function (newVal) {
                this.bottomNav = 2;
                this.recentNotifications = newVal;
                this.reduceNotifications(1);
            }
		    },
        components: {
            'notification-view': notificationView,
            'send-message': sendMessage,
        },
    }
</script>