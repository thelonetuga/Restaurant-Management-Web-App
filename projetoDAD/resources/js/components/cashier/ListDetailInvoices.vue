<template>
  <div mr-3>
    <br>
    <v-toolbar>
      <v-toolbar-title>Detail Invoices</v-toolbar-title>
      <v-divider class="mx-2" inset vertical></v-divider>
    </v-toolbar>
    <v-data-table :headers="headers" hide-actions :items="pendingInvoices" class="elevation-1">
      <template slot="items" slot-scope="props">
        <td class="text-left">{{ props.item.date }}</td>
        <td class="text-left">{{ props.item.meals.table_number }}</td>
        <td class="text-left">{{ props.item.waiter_name }}</td>
        <td class="text-left">{{ props.item.meals.total_price_preview }} €</td>
        <td class="text-left">{{ props.item.quantity }}</td>
        <td class="text-left">{{ props.item.unit_price }}</td>
        <td class="text-left">{{ props.item.sub_total_price }}</td>
        <td class="text-left">{{ props.item.item_name }}</td>
      </template>
    </v-data-table>
    <div class="text-xs-center">
      <v-pagination v-model="currentPage" :length="lastPage" @input="makePagination" circle></v-pagination>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      pendingInvoices: [],
      currentPage: 1,
      lastPage: 0,
      pagination: {},
      headers: [
        {
          text: "Date",
          align: "left",
          sortable: false,
          value: "date"
        },
        { text: "Table Number", value: "table_number" },
        { text: "Responsible Waiter", value: "responsible_waiter_id" },
        { text: "Total Price (€)", value: "total_price_preview" },
        { text: "Quantity", value: "quantity" },
        { text: "Unit Price (€)", value: "unit_price" },
        { text: "Sub Total Price (€)", value: "sub_total_price" },
        { text: "Item Consumed ", value: "name" }
      ]
    };
  },
  methods: {
    fetchPendingInvoices: function() {
      let vm = this;
      axios
        .get("api/invoices/all")
        .then(response => {
          vm.pendingInvoices = response.data.data;
          //console.log(response.data.data);
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    },
    makePagination(page) {
      axios
        .get("api/invoices/all?page=" + page)
        .then(response => {
          this.pendingInvoices = response.data.data;
          this.currentPage = response.data.meta.current_page;
          this.lastPage = response.data.meta.last_page;
          this.pagination = response.data.meta.links;
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    }
  },
  created() {
    this.fetchPendingInvoices();
    this.makePagination();
  }
};
</script>
