<template>
  <div>
    <template>
      <v-toolbar flat>
        <v-toolbar-title>List of Orders:  {{table()}}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn v-on:click.prevent="swapTables()" v-if="isPending" color="info">Get Prepared</v-btn>
        <v-btn v-on:click.prevent="swapTables()"  v-if="isPrepared" color="info">Get Pending/Confirmed</v-btn>
      </v-toolbar>
    </template>
    <pending v-if="isPending"></pending>
    <prepared v-if="isPrepared"></prepared>
  </div>
</template>


<script>
    import Pending from "./tables/pending";
    import Prepared from "./tables/prepared";

    export default {
        data() {
            return {
                isPending: false,
                isPrepared: false,
            };
        },
        created() {
            this.isPending = true;
        },
        methods: {
            swapTables(){
                if(this.isPrepared){
                    this.isPrepared = false;
                    this.isPending = true;
                }else{
                    this.isPrepared = true;
                    this.isPending = false;
                }
            },table(){
                if(this.isPending){
                    return "Pending/Confirmed";
                }else{
                    return "Prepared";
                }
            },
        },
        components: {
            pending: Pending,
            prepared: Prepared,
        },
    }
    ;
</script>