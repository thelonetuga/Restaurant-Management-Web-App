<template>
    <v-app dark>
        <div id="app">
            <navbar
                    @logoutMessage="logoutSuccess"
                    @hideMessage="hideSuccess"
                    @setDashboard="setDash"
                    v-if="showNav"
            ></navbar>
            <router-view :messageToReportChannel="msgReportTextArea" :messageToGlobalChannel="msgGlobalTextArea" :messageToManagerChannel="msgManagerTextArea" @loginMessage="loginSuccess" @hideMessage="hideSuccess" @removeDashboard="removeDash"  @setDashboard="setDash"></router-view>
            <notificationBar :arrayNotificationReportChannel="notificationMsgTextArea" v-if="isLogged"></notificationBar>
        </div>
    </v-app>
</template>
<script>
    import navbar from "./components/reuse/NavBar.vue";
    import managerDashboard from "./components/manager/manager";
    import notificationBar from "./components/reuse/notificationBar";
    export default {
        data: () => ({
            typeofmsg: "success",
            showMessage: false,
            alert: true,
            message: "",
            showNav: true,
            msgManagerTextArea: "",
            msgGlobalTextArea:"",
            msgReportTextArea:"",
            notificationMsgTextArea:"",
        }),
        methods: {
            setDash: function() {
                this.showNav = false;
                this.$router.push('/privatedashboard/'+this.$store.state.user.type)
            },
            removeDash: function() {
                this.showNav = true;
            },
            loginSuccess: function() {
                this.$toasted.show('User authenticated correctly');
            },
            logoutSuccess: function() {
                this.$toasted.show('User has logged out correctly');
                this.$store.commit("setManager");
                this.showNav = true;
            },
            hideSuccess: function() {
                var vm = this;
                setTimeout(function() {
                    vm.alert = false;
                }, 4000);
            }
        },
        computed: {
            ismanager: function() {
                return this.$store.state.ismanager;
            },
            isLogged: function () {
                return this.$store.state.user;
            }
        },
        components: {
            navbar,
            managerDashboard,
            notificationBar,
        },
        sockets:{
            connect(){
                console.log('socket connected (socket ID = '+this.$socket.id+')');
            },
            msg_from_server(dataFromServer){
                console.log("Global Channel: ",dataFromServer);
                this.msgGlobalTextArea = dataFromServer + '\n' + this.msgGlobalTextArea;
            },
            msg_from_server_manager(dataFromServer){
                console.log("Manager Channel: ",dataFromServer);
                this.msgManagerTextArea = dataFromServer + '\n' + this.msgManagerTextArea ;
            },
            msg_from_server_report(dataFromServer){
                console.log("Report Channel: ",dataFromServer);
                this.msgReportTextArea = dataFromServer + '\n' + this.msgReportTextArea ;
            },
            notification_from_server_manager(dataFromServer){
                console.log("Notification Box UserInfo: ",dataFromServer);
               // this.notificationMsgTextArea = dataFromServer + '\n' + this.notificationMsgTextArea ;
                this.notificationMsgTextArea = dataFromServer;
            }
        },
        mounted() {
            console.log("App: ",this.msgGlobalTextArea);
            this.$store.commit("getItems");
            this.$store.commit("loadTokenAndUserFromSession");
        }
    };
</script>

