<template>
  <v-app>
    <v-content shrink>
      <v-container fluid fill-height>
        <v-layout align-center>
          <v-container>
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
                            class="d-flex transition-fast-in-fast-out blue darken-2 v-card--reveal display-3 white--text"
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
                  <div class="font-weight-light grey--text title mb-2" v-if="item.type === 'drink'">Drink</div>
                  <div class="font-weight-light grey--text title mb-2" v-else>Dish</div>
                  <h3 class="display-1 font-weight-light blue--text mb-2">{{item.name}}</h3>
                  <div class="font-weight-light  grey--text  title mb-2">
                    {{item.description}}
                  </div>
                </v-card-text>
              </v-card>
            </v-hover>
          </v-container>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar dark color="primary">
                <v-toolbar-title>Edit Item</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    prepend-icon="person"
                    name="name"
                    label="Name"
                    type="text"
                    v-model="item.name"
                  ></v-text-field>
                  <v-text-field
                    prepend-icon="lock"
                    name="price"
                    label="Price"
                    type="text"
                    v-model="item.price"
                  ></v-text-field>
                  <v-text-field
                    prepend-icon="lock"
                    name="description"
                    label="Description"
                    type="text"
                    v-model="item.description"
                  ></v-text-field>
                  <v-select :items="types" label="Item Types" v-model="item.type"></v-select>
                </v-form>
              </v-card-text>
              <div
                class="uploader"
                @dragenter="OnDragEnter"
                @dragleave="OnDragLeave"
                @dragover.prevent
                @drop="onDrop"
                :class="{ dragging: isDragging }"
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
              </div>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn v-on:click.prevent="saveItem()">Save Item</v-btn>
                <v-btn v-on:click.prevent="cancelEdit()">Cancel Edit</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
</template>
<script>
export default {
  props: ["item"],
  data() {
    return {
      types: ["Drink", "Dish"],
      isDragging: false,
      dragCount: 0,
      files: [],
      images: []
    };
  },
  methods: {
    cancelEdit() {
      this.$emit("item-canceled");
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
        formData.append("id", this.item.id);
      });
      axios
        .post("/api/items/upload", formData)
        .then(response => {
          // this.$toastr.s("All images uplaoded successfully");
          this.$socket.emit('item_changed', response.data.data);
          this.images = [];
          this.files = [];
        })
        .catch(function(error) {
          // handle error
          console.log(error.response.data.message);
        });
    },
    saveItem() {
      axios
        .patch("api/items/" + this.item.id, this.item)
        .then(response => {
          Object.assign(this.item, response.data.data);
          this.$socket.emit('item_changed', response.data.data);
          this.$emit("item-saved", this.item);
        })
        .catch(function(error) {
          // handle error
          console.log(error.response.data.message);
        })
        .then(function() {
          // always executed
        });
    }
  }
};
</script>
<style lang="scss" scoped>
.uploader {
  width: 100%;
  background: #2196f3;
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
