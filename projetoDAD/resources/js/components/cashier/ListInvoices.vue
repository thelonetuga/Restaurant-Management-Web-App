<template>
  <div>
    <v-toolbar>
      <v-toolbar-title>Pending Invoices</v-toolbar-title>
      <v-divider class="mx-2" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px">
        <v-card>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedInvoices.nif" label="Costumer NIF"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedInvoices.name" label="Costumer Name"></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
            <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-data-table :headers="headers" :items="pendingInvoices" class="elevation-1">
      <template slot="items" slot-scope="props">
        <td class="text-left">{{ props.item.state }}</td>
        <td class="text-left">{{ props.item.meals.table_number }}</td>
        <td class="text-left">{{ props.item.name }}</td>
        <td class="text-left">{{ props.item.nif }}</td>
        <td class="text-left">{{ props.item.waiter_name }}</td>
        <td class="text-left">{{ props.item.meals.total_price_preview }} €</td>
        <td class="text-left">
          <v-icon small @click="editInvoice(props.item)">edit</v-icon>
        </td>
      </template>
    </v-data-table>
    <detailinvoices></detailinvoices>
  </div>
</template>
<script>
import ListDetailInvoices from "../cashier/ListDetailInvoices";

export default {
  props:['updateInvoiceTable'],
  data() {
    return {
      dialog: false,
      headers: [
        {
          text: "State",
          align: "left",
          sortable: false,
          value: "state"
        },
        { text: "Table Number", value: "table_number" },
        { text: "Costumer Name", value: "name" },
        { text: "NIF", value: "nif" },
        { text: "Responsible Waiter", value: "responsible_waiter_id" },
        { text: "Total Price (€)", value: "total_price_preview" },
        { text: "Actions", value: "name", sortable: false }
      ],
      pendingInvoices: [],
      editedIndex: -1,
      editedInvoices: {
        nif: "",
        name: ""
      },
      defaultTable: {
        nif: "",
        name: "",
      }
    };
  },
  methods: {
    fetchPendingInvoices: function() {
      let vm = this;
      axios
        .get("api/invoices/pending")
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
    editInvoice(invoice, id) {
      this.editedIndex = this.pendingInvoices.indexOf(invoice);
      this.editedInvoices = Object.assign({}, invoice);
      this.dialog = true;
    },
    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedInvoices = Object.assign({}, this.defaultTable);
        this.editedIndex = -1;
      }, 300);
    },
    save() {
      if (this.editedIndex > -1) {
        //variavel nao entra no axios
        console.log("Fora do axios", this.editedInvoices);
        axios
          .patch(
            "api/invoices/pending/" + this.pendingInvoices[this.editedIndex].id,
            this.editedInvoices
          )
          .then(function(response) {
            // handle succes
            this.$socket.emit('invoice_changed', response.data.data);
            console.log("axios", response.data.data);
          })
          .catch(function(error) {
            // handle error
            console.log(error.response.data.message);
          })
          .then(function() {
            // always executed
          });
        Object.assign(
          this.pendingInvoices[this.editedIndex],
          this.editedInvoices
        );
      } else {
      }
      this.close();
    }
  },
  components: {
    detailinvoices: ListDetailInvoices
  },
  computed: {},
  watch: {
    dialog(val) {
      val || this.close();
    },
    updateInvoiceTable: function (newVal) {
      this.$toasted.show('Invoice "' + newVal.changedInvoice.id + 'has changed');
      this.fetchPendingInvoices();
    }
  },
  created() {
    this.fetchPendingInvoices();
  }
};
</script>
