<template>
 <div>
    <v-toolbar>
      <v-toolbar-title>Tables List</v-toolbar-title>
      <v-divider class="mx-2" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" max-width="500px">
        <v-btn slot="activator" color="primary" dark class="mb-2">New Table</v-btn>
        <v-card>
          <v-card-title>
            <span class="headline">{{ formTitle }}</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedTable.table_number" label="Table Number"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedTable.deleted_at" label="Deleted At" disabled></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedTable.created_at" label="Created At" disabled></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md4>
                  <v-text-field v-model="editedTable.updated_at" label="Updated At" disabled></v-text-field>
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
    <v-data-table :headers="headers" :items="tables" class="elevation-1">
      <template slot="items" slot-scope="props">
        <td>{{ props.item.table_number }}</td>
        <td class="text-xs-left">{{ props.item.deleted_at }}</td>
        <td class="text-xs-left">{{ props.item.created_at }}</td>
        <td class="text-xs-left">{{ props.item.updated_at }}</td>
        <td class="justify-center layout px-0">
          <v-icon small class="mr-2" @click="editItem(props.item)">edit</v-icon>
          <v-icon small @click="deleteItem(props.item)">delete</v-icon>
        </td>
      </template>
      <template slot="no-data">
        <v-btn color="primary" @click="initialize">Reset</v-btn>
      </template>
    </v-data-table>
 </div>
</template>
<script>
export default {
  data: () => ({
    dialog: false,
    headers: [
      {
        text: "Table Number",
        align: "left",
        sortable: false,
        value: "tablenumber"
      },
      { text: "Deleted At", value: "delete" },
      { text: "Created At", value: "create" },
      { text: "Update At", value: "update" },
      { text: "Actions", value: "name", sortable: false }
    ],
    tables: [],
    editedIndex: -1,
    editedTable: {
      table_number: "",
      deleted_at: "",
      created_at: "",
      updated_at: ""
    },
    defaultTable: {
      table_number: "",
      deleted_at: "",
      created_at: "",
      updated_at: ""
    }
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Table" : "Edit Table";
    }
  },
  watch: {
    dialog(val) {
      val || this.close();
    }
  },
  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      let vm = this;
      axios
        .get("api/tables")
        .then(function(response) {
          vm.tables = response.data.data;
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        })
        .then(function() {
          // always executed
        });
    },
    editItem(table, id) {
      this.editedIndex = this.tables.indexOf(table);
      this.editedTable = Object.assign({}, table);
      this.dialog = true;
    },

    deleteItem(table) {
      const index = this.tables.indexOf(table);
      confirm("Are you sure you want to delete this table?") &&
        this.tables.splice(index, 1);
         axios.delete("api/tables/delete/"+table.table_number)
        .then(response => {
          this.$socket.emit('table_deleted', response.data.data);
          this.showSuccess = true;
          this.successMessage = "Table Deleted";
          this.initialize();
        })
        .catch(function(error) {
          // handle error
          console.log(error.response.data.message);
        })
        .then(function() {
          // always executed
        });
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedTable = Object.assign({}, this.defaultTable);
        this.editedIndex = -1;
      }, 300);
    },
    save() {
      if (this.editedIndex > -1) {
        //variavel nao entra no axios
        console.log("Fora do axios", this.editedTable);
        axios
          .patch(
            "api/tables/" + this.tables[this.editedIndex].table_number,
            this.editedTable
          )
          .then(function(response) {
            // handle succes
            this.$socket.emit('table_changed', response.data.data);
            console.log("axios", response.data.data);
          })
          .catch(function(error) {
            // handle error
            console.log(error.response.data.message);
          })
          .then(function() {
            // always executed
          });
           Object.assign(this.tables[this.editedIndex], this.editedTable);
      } else {
        axios
          .post("api/tables/create", this.editedTable)
          .then(response => {
            this.$socket.emit('table_created', response.data);
            Object.assign(this.editedTable, response.data);
          })
          .catch(function(error) {
            // handle error
            console.log(error.response.data.message);
          })
          .then(function() {
            // always executed
          });
        this.tables.push(this.editedTable);
      }
      this.close();
    }
  }
};
</script>
