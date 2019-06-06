<template>
  <div>
    <div class="jumbotron">
      <h1>Create Volume</h1>
    </div>
    <div>
        <h4>Create a volume</h4>
        <br>

        <div>
            <label>Name</label>
            <div>
                <input v-model="name" placeholder="Volume name">
            </div>
        </div>
        <br>
        <div>
            <label>Description</label>
            <div>
                <input v-model="description" placeholder="Description">
            </div>
        </div>
        <br>
        <div>
            <label>Size (GiB)</label>
            <font v-if="size==''" size="3" color="red">*</font>
            <div>
                <input type="number" v-model="size" :min="0">
            </div>
        </div>
        <br>
        <div>
            <label>Source</label>
            <div>
                <select v-model="source">
                    <option>Empty volume</option>
                    <option>Image</option>
                </select>
            </div>
        </div>
        <br>
        <div v-if="source === 'Image'">
            <ul>
                <li>
                    <label>Image </label>
                    <div>
                        <select v-model="image">
                            <option disabled value="">Please select one</option>
                            <option v-for='s in imageInfo.images'>{{s["name"]}}</option>
                        </select>
                    </div>
                </li>
            </ul>
        </div>

    </div>
    <br>
      <div>
        <button type="button" class="btn btn-outline-success" :disabled="size<=0" v-on:click.prevent='createVolume(source, name, description, size, image)'>Create</button>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Cancel</button>
      </div>

    </div>
  </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                info: [],
                imageInfo: [],
                name: '',
                source: 'Empty volume',
                image: '',
                imageId: '',
                description: '',
                size: '1',
            };
        },
        methods: {
            createVolume: function (source, name, description, size, image) {
                this.info = [];
                var vm = this;
                this.getImageId();
                if (this.source == 'Empty volume') {
                    vm.imageId = 'NO_IMAGE';
                }
                if (this.imageId == '') {
                    vm.imageId = 'NO_IMAGE';
                }
                if (this.name == ''){
                    name = 'null';
                } else {
                    name = '"' + name + '"';
                }
                if (this.description == ''){
                    description = 'null';
                } else {
                    description = '"' + description + '"';
                }
                axios.post('api/createVolume/' + name + '/' + description + '/' + size + '/' + vm.imageId)
                .then(function (response){
                    vm.info = response.data;
                })
                .catch(function (error){
                    vm.info = 'An error occurred.' + error;
                });
            },    
            listImages: function () {
                this.imageInfo = [];
                var vm = this;
                axios.get('api/listImages')
                .then(function (response){
                    vm.imageInfo = response.data;
                })
                .catch(function (error){
                    vm.imageInfo = 'An error occurred.' + error;
                });
            },
            getImageId: function () {
                var vm = this
                if (vm.image == '') {
                    this.typeofmsg = "alert-danger";
                    this.message = "Please Select a image";
                    this.showMessage = true;
                }else{
                    for (var i = vm.imageInfo.images.length - 1; i >= 0; i--) {
                        if(vm.imageInfo.images[i].name == vm.image){
                            vm.imageId = vm.imageInfo.images[i].id;
                        }
                    }
                    this.showMessage = false;
                }
                
            },                 
            goBack() {
                this.$router.push('/volumes');
            },
        },
        mounted() {
            this.listImages();
        }
    };
</script>