<template>
	<div>
		<v-toolbar flat>
			<v-toolbar-title>List of Workers</v-toolbar-title>
			<v-divider class="mx-2" inset vertical></v-divider>
			<v-spacer></v-spacer>
			<v-layout row align-center style="max-width: 650px">
				<v-text-field
								v-model="search"
								append-icon="search"
								label="Search"
								single-line
								hide-details
				></v-text-field>
			</v-layout>
			<v-spacer></v-spacer>
			<v-dialog v-model="dialog" fullscreen>
				<v-btn slot="activator" color="primary" dark class="mb-2">New User</v-btn>
				<v-card>
					<v-card-title>
						<span class="headline">{{ formTitle }}</span>
					</v-card-title>
					<v-card-text>
						<v-container grid-list-md>
							<v-layout wrap>
								<v-flex xs12 sm6 md4>
									<v-text-field v-model="editedUser.name" label="Fullname "></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 md4>
									<v-text-field v-model="editedUser.username" label="Username"></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 md4>
									<v-text-field v-model="editedUser.email" label="Email"></v-text-field>
								</v-flex>
								<v-flex xs12 sm6 d-flex>
									<v-select :items="types" v-model="editedUser.type" label="Type" solo></v-select>
								</v-flex>
							</v-layout>
							<v-layout>
								<v-flex
												v-if="this.editedIndex != -1"
												class="uploader"
												@dragenter="OnDragEnter"
												@dragleave="OnDragLeave"
												@dragover.prevent
												@drop="onDrop"
												:class="{ dragging: isDragging }"
												full
								>
									<div class="upload-control" v-show="images.length">
										<label for="file">Select a file</label>
										<button @click="upload">Upload</button>
									</div>
									<div v-show="!images.length">
										<i class="fa fa-cloud-upload"></i>
										<p>Drag your images here</p>
										<div>OR</div>
										<div class="file-input">
											<label for="file">Select a file</label>
											<input type="file" id="file" @change="onInputChange" multiple>
										</div>
									</div>
									<div class="images-preview" v-show="images.length">
										<div class="img-wrapper" v-for="(image, index) in images" :key="index">
											<img :src="image" :alt="`Image Uplaoder ${index}`">
											<div class="details">
												<span class="name" v-text="files[index].name"></span>
												<span class="size" v-text="getFileSize(files[index].size)"></span>
											</div>
										</div>
									</div>
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
		<v-data-table :headers="headers" :items="users" :search="search" class="elevation-1">
			<template slot="items" slot-scope="props">
				<td>{{ props.item.name }}</td>
				<td class="text-xs-left">{{ props.item.username }}</td>
				<td class="text-xs-left">{{ props.item.email }}</td>
				<td class="text-xs-left">{{ props.item.type }}</td>
				<td class="text-xs-left">{{ props.item.deleted_at }}</td>
				<td class="text-xs-left">
					<v-avatar :tile="tile" :size="size" color="grey lighten-4">
						<v-img
										:src="'/storage/profiles/'+props.item.photo_url"
										fluid
										alt="Image"
										aspect-ratio="2.75"
						></v-img>
					</v-avatar>
				</td>
				<td class="justify-center layout px-0">
					<v-icon small class="mr-2" @click="editUser(props.item)">edit</v-icon>
					<v-icon small @click="deleteUser(props.item)">delete</v-icon>
				</td>
			</template>
			<template slot="no-data">
				<v-btn color="primary" @click="initialize">Reset</v-btn>
			</template>
			<v-alert slot="no-results" :value="true" color="error" icon="warning">
				Your search for "{{ search }}" found no results.
			</v-alert>
		</v-data-table>
		<v-slider v-model="size" min="50" max="100" step="1"></v-slider>
	</div>
</template>

<script>
    export default {
        data: () => ({
            dialog: false,
            size: 60,
            tile: false,
            isDragging: false,
            search: '',
            dragCount: 0,
            files: [],
            types: ["cook", "manager", "cashier", "waiter"],
            images: [],
            headers: [
                {
                    text: "Fullname",
                    align: "left",
                    sortable: false,
                    value: "fullname"
                },
                {text: "Username", value: "username"},
                {text: "Email", value: "email"},
                {text: "Type", value: "type"},
                {text: "Delete", value: "delete"},
                {text: "Photo", value: "photo"},
                {text: "Actions", value: "name", sortable: false}
            ],
            users: [],
            editedIndex: -1,
            editedUser: {
                fullname: "",
                username: "",
                email: "",
                type: "",
                photo_url: ""
            },
            defaultUser: {
                fullname: "",
                username: "",
                email: "",
                type: "",
                photo_url: ""
            }
        }),
        computed: {
            formTitle() {
                return this.editedIndex === -1 ? "New UserResource" : "Edit UserResource";
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
                    .get("api/users")
                    .then(function (response) {
                        vm.users = response.data.data;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            editUser(user) {
                this.editedIndex = this.users.indexOf(user);
                this.editedUser = Object.assign({}, user);
                this.dialog = true;
            },
            deleteUser(user) {
                const index = this.users.indexOf(user);
                confirm("Are you sure you want to delete this user?") &&
                this.users.splice(index, 1);
                console.log(user.id);
                axios.delete("api/users/delete/" + user.id)
                    .then(response => {
                        this.$socket.emit('user_deleted', response.data.data);
                        console.log('user deleted');
                        this.showSuccess = true;
                        this.successMessage = "UserResource Deleted";
                        this.initialize();
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error.response.data.message);
                    })
                    .then(function () {
                        // always executed
                    });
            },
            close() {
                this.dialog = false;
                setTimeout(() => {
                    this.editedUser = Object.assign({}, this.defaultUser);
                    this.editedIndex = -1;
                }, 300);
            },
            OnDragEnter(e) {
                e.preventDefault();

                this.dragCount++;
                this.isDragging = true;
                return false;
            },
            OnDragLeave(e) {
                e.preventDefault();
                this.dragCount--;
                if (this.dragCount <= 0) this.isDragging = false;
            },
            onInputChange(e) {
                const files = e.target.files;
                Array.from(files).forEach(file => this.addImage(file));
            },
            onDrop(e) {
                e.preventDefault();
                e.stopPropagation();
                this.isDragging = false;
                const files = e.dataTransfer.files;
                Array.from(files).forEach(file => this.addImage(file));
            },
            addImage(file) {
                if (!file.type.match("image.*")) {
                    this.$toastr.e(`${file.name} is not an image`);
                    return;
                }
                this.files.push(file);
                const img = new Image(),
                    reader = new FileReader();
                reader.onload = e => this.images.push(e.target.result);
                reader.readAsDataURL(file);
            },
            getFileSize(size) {
                const fSExt = ["Bytes", "KB", "MB", "GB"];
                let i = 0;

                while (size > 900) {
                    size /= 1024;
                    i++;
                }
                return `${Math.round(size * 100) / 100} ${fSExt[i]}`;
            },
            upload() {
                const formData = new FormData();
                this.files.forEach(file => {
                    formData.append("images", file, file.name);
                    formData.append("id", this.editedUser.id);
                });
                axios
                    .post("/api/users/upload", formData)
                    .then(response => {
                        // this.$toastr.s("All images uplaoded successfully");
                        this.$socket.emit('user_changed', response.data.data);
                        Object.assign(this.users[this.editedIndex], this.editedUser);
                        this.initialize();
                        this.images = [];
                        this.files = [];
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error.response.data.message);
                    });
            },
            save() {
                if (this.editedIndex > -1) {
                    axios
                        .put("api/users/" + this.editedUser.id, this.editedUser)
                        .then(response => {
                            Object.assign(this.editedUser, response.data.data);
                            this.$socket.emit('user_changed', response.data.data);
                        })
                        .catch(function (error) {
                            // handle error
                            console.log(error.response.data.message);
                        })
                        .then(function () {
                            // always executed
                        });
                    Object.assign(this.users[this.editedIndex], this.editedUser);
                } else {
                    axios.post("api/users/create", this.editedUser)
                        .then(response => {
                            //Object.assign(this.editedUser, response.data.data);
                            this.$socket.emit('user_created', response.data.data);
                            console.log(response);
                            console.log(JSON.stringify({email: response.data.email}));
                            let email = {email: response.data.email};
                            return axios.post('api/password/create', email);
                        })
                        .catch(function (error) {
                            console.log(error.response.data.message);
                        })
                        .then(function () {
                            // always executed
                        });
                    this.users.push(this.editedUser);
                }
                this.close();
            }
        }
    };
</script>
<style lang="scss" scoped>
	.uploader {
		width: 100%;
		background: #7fbff3;
		color: #fff;
		padding: 40px 15px;
		text-align: center;
		border-radius: 10px;
		border: 3px dashed #fff;
		font-size: 20px;
		position: relative;

		&.dragging {
			background: #fff;
			color: #2196f3;
			border: 3px dashed #2196f3;

			.file-input label {
				background: #2196f3;
				color: #fff;
			}
		}

		i {
			font-size: 85px;
		}

		.file-input {
			width: 200px;
			margin: auto;
			height: 68px;
			position: relative;

			label,
			input {
				background: #fff;
				color: #2196f3;
				width: 100%;
				position: absolute;
				left: 0;
				top: 0;
				padding: 10px;
				border-radius: 4px;
				margin-top: 7px;
				cursor: pointer;
			}

			input {
				opacity: 0;
				z-index: -2;
			}
		}

		.images-preview {
			display: flex;
			flex-wrap: wrap;
			margin-top: 20px;

			.img-wrapper {
				width: 160px;
				display: flex;
				flex-direction: column;
				margin: 10px;
				height: 150px;
				justify-content: space-between;
				background: #fff;
				box-shadow: 5px 5px 20px #3e3737;

				img {
					max-height: 105px;
				}
			}

			.details {
				font-size: 12px;
				background: #fff;
				color: #000;
				display: flex;
				flex-direction: column;
				align-items: self-start;
				padding: 3px 6px;

				.name {
					overflow: hidden;
					height: 18px;
				}
			}
		}

		.upload-control {
			position: absolute;
			width: 100%;
			background: #fff;
			top: 0;
			left: 0;
			border-top-left-radius: 7px;
			border-top-right-radius: 7px;
			padding: 10px;
			padding-bottom: 4px;
			text-align: right;

			button,
			label {
				background: #2196f3;
				border: 2px solid #03a9f4;
				border-radius: 3px;
				color: #fff;
				font-size: 15px;
				cursor: pointer;
			}

			label {
				padding: 2px 5px;
				margin-right: 10px;
			}
		}
	}
</style>

