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
			<v-menu origin="center center" transition="scale-transition" top offset-y>
				<v-btn dark slot="activator">
					<span>All Notifications</span>
					<v-badge left overlap color="orange">
						<span slot="badge">6</span>
						<v-icon>notifications</v-icon>
					</v-badge>
				</v-btn>
				<notification-view :items="allNotifications"></notification-view>
			</v-menu>
			<v-menu origin="center center" transition="scale-transition" top offset-y>
				<v-btn dark slot="activator">
					<span>Recent Notification</span>
					<v-badge right overlap color="red">
						<span slot="badge">!</span>
						<v-icon>notification_important</v-icon>
					</v-badge>
				</v-btn>
				<notification-view :arrayNotificacoes="arrayTeste"></notification-view>
			</v-menu>
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
        props: ['arrayNotificationReportChannel'],
        data() {
            return {
                bottomNav: 3,
                chat: false,
                showGlobalChat: false,
                showPrivateChat: false,
                showNotifyManagerChat: false,
                currentUser: this.$store.state.user,
		            arrayTeste:[],
                recentNotifications: [
                    { header: 'Today' },
                    {
                        avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
                        title: 'Brunch this weekend?',
                        subtitle: "<span class='text--primary'>Ali Connors</span> &mdash; I'll be in your neighborhood doing errands this weekend. Do you want to hang out?"
                    },
                    { divider: true, inset: true },
                    {
                        avatar: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
                        title: 'Summer BBQ <span class="grey--text text--lighten-1">4</span>',
                        subtitle: "<span class='text--primary'>to Alex, Scott, Jennifer</span> &mdash; Wish I could come, but I'm out of town this weekend."
                    },
                    { divider: true, inset: true },
                    {
                        avatar: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
                        title: 'Oui oui',
                        subtitle: "<span class='text--primary'>Sandra Adams</span> &mdash; Do you have Paris recommendations? Have you ever been?"
                    }
                ],
                allNotifications: [
                    { header: 'Today' },
                    {
                        avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
                        title: 'Brunch this weekend?',
                        subtitle: "<span class='text--primary'>Ali Connors</span> &mdash; I'll be in your neighborhood doing errands this weekend. Do you want to hang out?"
                    },
                    { divider: true, inset: true },
                    {
                        avatar: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
                        title: 'Summer BBQ <span class="grey--text text--lighten-1">4</span>',
                        subtitle: "<span class='text--primary'>to Alex, Scott, Jennifer</span> &mdash; Wish I could come, but I'm out of town this weekend."
                    },
                    { divider: true, inset: true },
                    {
                        avatar: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
                        title: 'Oui oui',
                        subtitle: "<span class='text--primary'>Sandra Adams</span> &mdash; Do you have Paris recommendations? Have you ever been?"
                    }
                ],
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
		        clearRecentNotificationArray(){

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
                        return 'indigo';
                }
            },
            trigger: function (newVal) {
                return newVal;
            }
        },
		    watch:{
            arrayNotificationReportChannel: function (newVal, oldVal) {
               return this.arrayTeste = newVal;
            }
		    },
        components: {
            'notification-view': notificationView,
            'send-message': sendMessage,
        },
    }
</script>