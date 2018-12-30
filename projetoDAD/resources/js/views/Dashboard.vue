<template>
  <v-app id="dash">
    <h1>ola</h1>
        <router-view :updateMealTable="updateMealTable" :updateInvoiceTable="updateInvoiceTable" :updateOrderTable="updateOrderTable" :messageReport="messageToReportChannel" :messageGlobal="messageToGlobalChannel" :messageManager="messageToManagerChannel" @logoutMessage="logoutSuccess" @removeDash="removeDash" @removeNav="removeNav"></router-view>
  </v-app>
</template>
<script>


export default {
  props:['messageToGlobalChannel','messageToManagerChannel','messageToReportChannel','updateOrderTable', 'updateInvoiceTable', 'updateMealTable'],
  data: () => ({}),
  methods: {
    logoutSuccess: function() {
      this.$store.commit("setManager");
    },
    removeDash: function() {
      this.$emit("removeDashboard");
    },
    removeNav: function(){
        this.$emit('setDashboard');
    }
  },
  computed: {
  },
  watch: {
    messageToGlobalChannel: function (newVal) {
      return newVal;
    },
    messageToManagerChannel: function (newVal) {
      return newVal;
    },
    messageToReportChannel: function (newVal) {
      return newVal;
    },
    updateOrderTable: function (newVal) {
      console.log("Dashboard:"+ newVal.changedOrder.id +" has changed");
      return newVal;
    },
    updateInvoiceTable:function (newVal) {
      console.log("Dashboard:"+ newVal.changedInvoice.id +" has changed");
      return newVal;
    },
    updateMealTable:function (newVal) {
      console.log("Dashboard:"+ newVal.changedMeal.id +" has changed");
      return newVal;
    }
  }
};
</script>
