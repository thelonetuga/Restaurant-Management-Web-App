<template>
	<div>
		<div class="alert alert-success" v-if="showSuccess">
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
		<item-edit :item="currentItem" :isEdit="isEditing" @item-saved="savedItem" @item-canceled="cancelEdit"
		           v-if="isEditing"></item-edit>
		<item-add v-if="isAdding" @item-canceled="cancelEdit"></item-add>
		<v-container v-if="isListActive" grid-list-md text-xs-center>
			<div class="text-xs-center">
				<v-pagination
								v-model="currentPage"
								:length=lastPage
								@input="makePagination"
								circle
				></v-pagination>
			</div>
			<v-container>
				<router-link tag="span" style="cursor: pointer" to="/items/new">
					<v-btn color="success" v-on:click.prevent="createItem()">Create Item</v-btn>
				</router-link>
			</v-container>
		<v-layout>
			<v-flex xs12>
				<v-card>
					<v-container fluid grid-list-md>
						<v-layout row wrap>
							<v-flex
											v-for="item in items"
											:key="item.name"
											xs4 sm5 md3>
								<v-card light hover :id="item.id">
									<v-img
													:src="'/storage/items/'+item.photo_url"
													height="200px"
									>
									</v-img>
									<v-card-text>
										<p class="headline text-lg-center">{{item.name}}</p>
										<p class="headline text-lg-center">{{item.price}}€</p>
										<p class="headline text-lg-center" v-if="item.type == 'drink'">Drink</p>
										<p class="headline text-lg-center" v-else>Dish</p>
									</v-card-text>
									<v-card-actions>
										<v-spacer></v-spacer>
										<v-btn color="error" v-on:click.prevent="deleteItem(item)">Delete</v-btn>
										<v-btn color="warning" v-on:click.prevent="editItem(item)">Edit</v-btn>
										<v-spacer></v-spacer>
										<v-btn icon @click="show = !show" v-bind:key="item.id">
											<v-icon>{{ show ? 'keyboard_arrow_down' : 'keyboard_arrow_up' }}</v-icon>
										</v-btn>
									</v-card-actions>
									<v-slide-y-transition>
										<v-card-text v-show="show">
											{{item.description}}
										</v-card-text>
									</v-slide-y-transition>
								</v-card>
							</v-flex>
						</v-layout>
					</v-container>
				</v-card>
			</v-flex>
		</v-layout>
			</v-container>
	</div>
</template>
<script>
    import newItem from "./NewItem";
    import editItem from "./EditItem";

    export default {
        data() {
            return {
                show: false,
                items: [],
                currentItem: null,
                showSuccess: false,
                successMessage: '',
                isEditing: false,
                isAdding: false,
                isListActive: true,
                currentPage: 1,
                lastPage: 0,
                pagination: {},
            };
        },
        created() {
            this.fetchItems();
        },
        methods: {
            fetchItems() {
                let vm = this;
                axios.get("/api/items")
                    .then(function (response) {
                        // handle success
                        vm.items = response.data.data;
                        vm.currentPage = response.data.meta.current_page;
                        vm.lastPage = response.data.meta.last_page;
                        vm.pagination = response.data.  meta.links;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            makePagination(page) {
                let vm = this;
                axios.get("/api/items?page=" + page)
                    .then(response => {
                        vm.items = response.data.data;
                        vm.currentPage = response.data.meta.current_page;
                        vm.lastPage = response.data.meta.last_page;
                        vm.pagination = response.data.meta.links;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            editItem(item) {
                this.isEditing = true;
                this.isAdding = false;
                this.isListActive = false;
                this.currentItem = item;
                this.showSuccess = false;
            },
            createItem() {
                this.isAdding = true;
                this.isListActive = false;
                this.isEditing = false;
            },
            deleteItem: function (item) {
                axios.delete("api/delete/items/" + item.id)
                    .then(response => {
                        this.showSuccess = true;
                        this.successMessage = "Item Deleted";
                        this.fetchItems();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            savedItem: function () {
                this.isEditing = false;
                this.isAdding = false;
                this.isListActive = true;
                this.currentItem = null;
                this.showSuccess = true;
                this.successMessage = "Item Saved";
            },
            cancelEdit: function () {
                this.isEditing = false;
                this.isAdding = false;
                this.isListActive = true;
                this.currentItem = null;
                this.showSuccess = false;
            },
            childMessage: function (message) {
                this.isEditing = false;
                this.showSuccess = true;
                this.successMessage = message;
            }
        },
        components: {
            "item-add": newItem,
            "item-edit": editItem
        }
    };
</script>
<style>

</style>