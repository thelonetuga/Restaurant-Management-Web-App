<template>
	<div>
		<div class="alert alert-success" v-if="showSuccess">
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>
		<item-edit :item="currentItem" :isEdit="isEditing" @item-saved="savedItem" @item-canceled="cancelEdit"
		           v-if="isEditing"></item-edit>
		<item-add v-if="isAdding" @item-canceled="cancelEdit" @item-saved="savedItem"></item-add>
		<v-container v-if="isListActive" grid-list-md text-xs-center>
			<div class="text-xs-center">
				<v-pagination
								v-model="currentPage"
								:length=lastPage
								@input="makePagination"
								circle
				></v-pagination>
			</div>
			<v-container v-if="currentUser != null && currentUser.type === 'manager'">
				<router-link tag="span" style="cursor: pointer" to="/items/new">
					<v-btn color="success" v-on:click.prevent="createItem()">Create Item</v-btn>
				</router-link>
			</v-container>
			<v-container
							fluid grid-list-md
			>
				<v-layout row wrap>
					<v-flex
									v-for="item in items" :key="item.name"
									xs4>
						<v-hover>
							<v-card
											slot-scope="{ hover }"
											class="mx-auto"
											color="grey lighten-4"
											max-width="600"
							>
								<v-img
												:aspect-ratio="16/9"
												height="200px"
												:src="'/storage/items/'+item.photo_url"
								>
									<v-expand-transition>
										<div
														v-if="hover"
														class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal display-3 white--text"
														style="height: 100%;"
										>
											{{item.price}}€
										</div>
									</v-expand-transition>
								</v-img>
								<v-card-text
												class="pt-4"
												style="position: relative;"
								>
									<v-flex xs1 v-if="currentUser != null && currentUser.type === 'manager'">
										<v-btn
														absolute
														color="orange"
														class="white--text"
														fab
														dark
														top
														v-on:click.prevent="editItem(item)"
										>
											<v-icon>edit</v-icon>
										</v-btn>
										<v-btn
														absolute
														color="red"
														class="white--text"
														fab
														right
														top
														v-on:click.prevent="deleteItem(item)"
										>
											<v-icon>remove_circle_outline</v-icon>
										</v-btn>
									</v-flex>
									<div class="font-weight-light grey--text title mb-2" v-if="item.type === 'drink'">Drink</div>
									<div class="font-weight-light grey--text title mb-2" v-else>Dish</div>
									<h3 class="display-1 font-weight-light orange--text mb-2">{{item.name}}</h3>
									<div class="font-weight-light  grey--text  title mb-2">
										{{item.description}}
									</div>
								</v-card-text>
							</v-card>
						</v-hover>
					</v-flex>
				</v-layout>
			</v-container>
		</v-container>
	</div>
</template>
<script>
    import newItem from "./NewItem";
    import editItem from "./EditItem";

    export default {
        props: ['updateItemList'],
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
                currentUser: ""
            };
        },
        created() {
            this.fetchItems();
            this.currentUser = this.$store.state.user;
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
                this.$socket.emit('item_deleted', item);
                axios.delete("api/items/delete/" + item.id)
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
                this.showSuccess = false;
                this.$toasted.success('Item Created/Saved');
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
        watch: {
            updateItemList: function (newVal) {
                this.fetchItems();
            }
        },
        components: {
            "item-add": newItem,
            "item-edit": editItem
        }
    };
</script>
<style>
	.v-card--reveal {
		align-items: center;
		bottom: 0;
		justify-content: center;
		opacity: .5;
		position: absolute;
		width: 100%;
	}
</style>