<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Image Upload</div>
                    <div class="card-body">
                       <div class="row">
                          <div class="col-md-3" v-if="image">
                              <img :src="image" class="img-responsive" height="70" width="90">
                           </div>
                          <div class="col-md-6">
                              <input type="file" v-on:change="onImageChange" class="form-control">
                          </div>
                          <div class="col-md-3">
                             <button class="btn btn-success btn-block" v-on:click.prevent="uploadImage()">Upload Image</button>
                          </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      image: null,
    };
  },
  methods: {
    onImageChange(e) {
        this.image = e.target.files[0];
    },
    uploadImage() {

    console.log(this.image)
    //this.$emit("image-upload", this.image);
    }
  }
};
</script>
