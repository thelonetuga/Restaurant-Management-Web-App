<template>
  <v-app>
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar dark color="success">
                <v-toolbar-title>Add Item</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field prepend-icon="person" name="name" label="Name" type="text" v-model="item.name"></v-text-field>
                  <v-text-field prepend-icon="lock" name="price" label="Price" type="text" v-model="item.price"></v-text-field>
                  <v-text-field prepend-icon="lock" name="description" label="Description" type="text" v-model="item.description"></v-text-field>
                  <v-select :items="types" label="Item Types" v-model="item.type"></v-select>
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
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn  v-on:click.prevent="addItem()">Add Item</v-btn>
                <v-btn v-on:click.prevent="cancel()">Cancel</v-btn>
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
  data() {
    return {
      item: {
        name: "",
        price: "",
        type: "",
        description: ""
      },
      types: ["Drink", "Dish"],
      isAdding: false,
      isDragging: false,
      dragCount: 0,
      files: [],
      images: []
    };
  },
  methods: {
    addItem() {
      const fd = new FormData();
      this.files.forEach(file => {
        fd.append("images", file, file.name);
      });
      fd.append('name', this.item.name);
      fd.append('price', this.item.price);
      fd.append('type', this.item.type);
      fd.append('description', this.item.description);

      axios
        .post("api/items/create",fd)
        .then(response => {
          Object.assign(this.item, response.data.data);
          this.$emit("item-saved", this.item);
        })
        .catch(function(error) {
          // handle error
          console.log(error.response.data.message);
        })
        .then(function() {
          // always executed
        });
    },
    cancel() {
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
  },
};
</script>
<style lang="scss" scoped>
  .uploader {
    width: 100%;
    background: #4faf50;
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