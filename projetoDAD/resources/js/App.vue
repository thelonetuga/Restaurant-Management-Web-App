<template>
    <v-app dark>
        <div id="app">
            <navbar
                    @logoutMessage="logoutSuccess"
                    @hideMessage="hideSuccess"
                    @setDashboard="setDash"
                    v-if="showNav"
            ></navbar>
            <router-view :updateMealTable="mealChanged" :updateInvoiceTable="invoiceChanged" :updateTable="tableChanged" :updateItemList="itemChanged" :updateOrderTable="orderChanged" :messageToReportChannel="msgReportTextArea" :messageToGlobalChannel="msgGlobalTextArea" :messageToManagerChannel="msgManagerTextArea" @loginMessage="loginSuccess" @hideMessage="hideSuccess" @removeDashboard="removeDash"  @setDashboard="setDash"></router-view>
            <notificationBar :updateMealTable="mealChanged" :updateInvoiceTable="invoiceChanged" :updateTable="tableChanged" :updateItemList="itemChanged" :updateOrderTable="orderChanged" :arrayNotificationReportChannel="notificationMsgTextArea" v-if="isLogged"></notificationBar>
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
            orderChanged: "",
            itemChanged:"",
            mealChanged:"",
            invoiceChanged:"",
            tableChanged:"",
            userChanged:"",
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
                this.$toasted.success('User authenticated correctly');
            },
            logoutSuccess: function() {
                this.$toasted.success('User has logged out correctly');
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
               //this.notificationMsgTextArea = dataFromServer + '\n' + this.notificationMsgTextArea ;
                this.notificationMsgTextArea = dataFromServer;
            },
            order_changed(dataFromServer){
                this.orderChanged = dataFromServer;
            },
            order_created(dataFromServer){
                this.orderChanged = dataFromServer
            },
            order_deleted(dataFromServer){
                this.orderChanged = dataFromServer;
            },
            item_changed(dataFromServer){
                this.itemChanged = dataFromServer;
            },
            item_created(dataFromServer){
                this.itemChanged = dataFromServer;
            },
            item_deleted(dataFromServer){
                this.itemChanged = dataFromServer;
            },
            meal_changed(dataFromServer){
                this.mealChanged = dataFromServer;
            },
            meal_created(dataFromServer){
                this.mealChanged = dataFromServer;
            },
            meal_deleted(dataFromServer){
                this.mealChanged = dataFromServer;
            },
            invoice_changed(dataFromServer){
                this.invoiceChanged = dataFromServer;
            },
            invoice_created(dataFromServer){
                this.invoiceChanged = dataFromServer;
            },
            invoice_deleted(dataFromServer){
                this.invoiceChanged = dataFromServer;
            },
            table_changed(dataFromServer){
                this.tableChanged = dataFromServer;
            },
            table_created(dataFromServer){
                this.tableChanged = dataFromServer;
            },
            table_deleted(dataFromServer){
                this.tableChanged = dataFromServer;
            },
        },
        mounted() {
            this.$store.commit("getItems");
            this.$store.commit("loadTokenAndUserFromSession");
        }
    };
</script>

