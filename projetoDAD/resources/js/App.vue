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
               // this.notificationMsgTextArea = dataFromServer + '\n' + this.notificationMsgTextArea ;
                this.notificationMsgTextArea = dataFromServer;
            },
            order_changed(dataFromServer){
                this.orderChanged = dataFromServer;
                console.log("App: "+ dataFromServer.changedOrder.id +" has changed");
            },
            order_created(dataFromServer){
                this.orderChanged = dataFromServer;
                console.log("App: "+ dataFromServer.changedOrder.id +" has created");
            },
            order_deleted(dataFromServer){
                this.orderChanged = dataFromServer;
                console.log("App: "+ dataFromServer.changedOrder.id +" has deleted");
            },
            item_changed(dataFromServer){
                this.itemChanged = dataFromServer;
                console.log("App(Item): "+ dataFromServer.changedItem.id +" has changed");
            },
            item_created(dataFromServer){
                this.itemChanged = dataFromServer;
                console.log("App(Item): "+ dataFromServer.changedItem.id +" has created");
            },
            item_deleted(dataFromServer){
                this.itemChanged = dataFromServer;
                console.log("App(Item): "+ dataFromServer.changedItem.id +" has deleted");
            },
            meal_changed(dataFromServer){
                this.mealChanged = dataFromServer;
                console.log("App(Meal): "+ dataFromServer.changedMeal.id +" has changed");
            },
            meal_created(dataFromServer){
                this.mealChanged = dataFromServer;
                console.log("App(Meal): "+ dataFromServer.changedMeal.id +" has created");
            },
            meal_deleted(dataFromServer){
                this.mealChanged = dataFromServer;
                console.log("App(Meal): "+ dataFromServer.changedMeal.id +" has deleted");
            },
            invoice_changed(dataFromServer){
                this.invoiceChanged = dataFromServer;
                console.log("App(Invoice): "+ dataFromServer.changedInvoice.id +" has changed");
            },
            invoice_created(dataFromServer){
                this.invoiceChanged = dataFromServer;
                console.log("App(Invoice): "+ dataFromServer.changedInvoice.id +" has created");
            },
            invoice_deleted(dataFromServer){
                this.invoiceChanged = dataFromServer;
                console.log("App(Invoice): "+ dataFromServer.changedInvoice.id +" has deleted");
            },
            table_changed(dataFromServer){
                this.tableChanged = dataFromServer;
                console.log("App(Table): "+ dataFromServer.changedInvoice.id +" has changed");
            },
            table_created(dataFromServer){
                this.tableChanged = dataFromServer;
                console.log("App(Table): "+ dataFromServer.changedTable.id +" has created");
            },
            table_deleted(dataFromServer){
                this.tableChanged = dataFromServer;
                console.log("App(Table): "+ dataFromServer.changedTable.id +" has deleted");
            },
        },
        mounted() {
            this.$store.commit("getItems");
            this.$store.commit("loadTokenAndUserFromSession");
        }
    };
</script>

