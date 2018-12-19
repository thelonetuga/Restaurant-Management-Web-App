<template>
  <v-app id="dash">
    <h1>ola</h1>
        <router-view :messageReport="messageToReportChannel" :messageGlobal="messageToGlobalChannel" :messageManager="messageToManagerChannel" @logoutMessage="logoutSuccess" @removeDash="removeDash" @removeNav="removeNav"></router-view>
  </v-app>
</template>
<script>


export default {
  props:['messageToGlobalChannel','messageToManagerChannel','messageToReportChannel'],
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
    }
  }
};
</script>
