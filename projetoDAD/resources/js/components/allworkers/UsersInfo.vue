<template>
  <v-app align="center">
    <br>
    <h1>User Profile</h1>
    <br>
    <v-avatar :tile="tile" :size="180" color="grey lighten-4">
      <v-img :src="'/storage/profiles/'+user.photo_url" fluid alt="Image" aspect-ratio="2.75"></v-img>
    </v-avatar>
    <v-container fluid>
      <v-layout row wrap>
        <v-flex xs12 class="text-xs-center" mt-5></v-flex>
        <v-flex xs12 sm6 offset-sm3 mt-3>
          <form>
            <v-switch :label="'Switch to Edit Profile'" v-model="isDisable"></v-switch>
            <v-layout column v-if="isDisable">
              <v-flex>
                <v-text-field
                  name="email"
                  label="Email"
                  id="email"
                  type="email"
                  v-model="user.email"
                  :rules="['Required']"
                >{{user.email}}</v-text-field>
              </v-flex>
              <v-flex>
                <v-text-field
                  name="username"
                  label="Username"
                  id="username"
                  type="username"
                  v-model="user.username"
                  :rules="['Required']"
                >{{user.username}}</v-text-field>
              </v-flex>
              <v-flex>
                <v-text-field
                  name="fullName"
                  label="Full Name"
                  id="fullName"
                  type="fullName"
                  v-model="user.name"
                  :rules="['Required']"
                >{{user.name}}</v-text-field>
              </v-flex>
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
              <v-flex class="text-xs-center" mt-5>
                <v-btn v-on:click.prevent="saveUser()" color="primary" type="submit">Save Profile</v-btn>
              </v-flex>
            </v-layout>
            <v-layout column v-else>
              <v-flex>
                <v-text-field
                  disabled
                  name="email"
                  label="Email"
                  id="email"
                  type="email"
                  v-model="user.email"
                >{{user.email}}</v-text-field>
              </v-flex>
              <v-flex>
                <v-text-field
                  disabled
                  name="username"
                  label="Username"
                  id="username"
                  type="username"
                  v-model="user.username"
                >{{user.username}}</v-text-field>
              </v-flex>
              <v-flex>
                <v-text-field
                  disabled
                  name="fullName"
                  label="Full Name"
                  id="fullName"
                  type="fullName"
                  v-model="user.name"
                >{{user.name}}</v-text-field>
              </v-flex>
            </v-layout>
          </form>
        </v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>
<script>
export default {
  data() {
    return {
      isDisable: false,
      isDragging: false,
      dragCount: 0,
      files: [],
      images: []
    };
  },
  methods: {
    saveUser() {
      axios
        .put("api/users/" + this.user.id, this.user)
        .then(response => {
          Object.assign(this.user, response.data.data);
          this.$socket.emit('user_changed', response.data.data);
          this.$store.commit("setUser", this.user);
        })
        .catch(function(error) {
          // handle error
          console.log(error.response.data.message);
        })
        .then(function() {
          // always executed
        });
      this.isDisable = false;
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
        formData.append("id", this.user.id);
      });
      axios
        .post("/api/users/upload", formData)
        .then(response => {
          // this.$toastr.s("All images uplaoded successfully");
          this.$socket.emit('user_changed', response.data.data);
          this.images = [];
          this.files = [];
        })
        .catch(function(error) {
          // handle error
          console.log(error.response.data.message);
        });
    }
  },
  computed: {
    user: function() {
      return this.$store.state.user;
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

