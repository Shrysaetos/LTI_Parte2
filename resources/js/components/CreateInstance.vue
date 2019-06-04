<template> 
  <div>
    <div class="jumbotron">
      <h1>Create Instance</h1>
    </div>
    <br>
    <div>
        <div class="alert" :class="typeofmsg" v-if="showMessage">             
            <strong>{{ message }}</strong>
        </div>
        <h4>Details</h4>
        <div>
            <ul>
                <li>
                    <label>Name</label>
                    <font v-if="name==''" size="3" color="red">*</font>
                    <div>
                        <input v-model="name" placeholder="Instance name" required >
                    </div>
                </li>
            </ul>
            
        </div>
        <div>
            <ul>
                <li>
                    <label>Description</label>
                    <div>
                        <input v-model="description" placeholder="Description">
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <ul>
                <li>
                    <label>Availability Zone</label>
                    <font v-if="zone==''" size="3" color="red">*</font>
                    <div>
                        <select v-model="zone">
                            <option disabled value="">Please select one</option>
                            <option v-for='t in zonesInfo.availabilityZoneInfo'>{{t["zoneName"]}}</option>
                        </select>
                    </div>
                </li>
            </ul>
        </div>
        <br>
    </div>
    <div>
        <h4>Source</h4>
        <div>
            <ul>
                <li>
                    <label>Select Source Type </label>
                    <font v-if="source==''" size="3" color="red">*</font>
                    <div>
                        <select v-model="source">
                            <option>Image</option>
                            <option>Volume</option>
                        </select>
                    </div>
                    <br>
                    <div v-if ="source=='Image'">
                        <ul>
                            <li>
                                <label>Image </label>
                                <font v-if="image==''" size="3" color="red">*</font>
                                <div>
                                    <select v-model="image">
                                        <option disabled value="">Please select one</option>
                                        <option v-for='s in imageInfo.images'>{{s["name"]}}</option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                        
                        <ul>
                            <li>
                                <label>Volume size</label>
                                <font v-if="size==''" size="3" color="red">*</font>
                                <div>
                                    <input type="number" v-model="size" :min="0">
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-if="source=='Volume'">
                        <ul>
                            <li>
                                <label>Volume</label>
                                <font v-if="volume==''" size="3" color="red">*</font>
                                <div>
                                    <select v-model="volume">
                                        <option disabled value="">Please select one</option>
                                        <option v-for='v in volumeInfo.volumes' v-if="v['name'] != ''">{{v["name"]}}</option>
                                        <option v-for='v in volumeInfo.volumes' v-if="v['name'] == ''">{{v["id"]}}</option>

                                    </select>
                                </div>
                            </li>
                        </ul>
                        
                    </div>
                </li>
            </ul>
            
        </div>
    </div>
    <div>
        <h4>Flavor</h4>
        <div>
            <ul>
                <li>
                    <label>Flavor</label>
                    <font v-if="flavor==''" size="3" color="red">*</font>
                    <div>
                        <select v-model='flavor'>
                            <option disable value="">Please select one</option>
                            <option v-for='f in flavorInfo.flavors'>{{f["name"]}}</option>
                        </select>

                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div>
        <h4>Network</h4>
        <div>
            <ul>
                <li>
                    <label>Network</label>
                    <font v-if="networkName==''" size="3" color="red">*</font>
                    <div>
                        <select v-model='networkName'>
                            <option disable value="">Please select one</option>
                            <option v-for='n in networkInfo.networks'>{{n["name"]}}</option>
                        </select>

                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div>
        <h4>Key par</h4>
        <div>
            <ul>
                <li>
                    <label>Key par</label>
                    <font v-if="keypair==''" size="3" color="red">*</font>
                    <div>
                        <select v-model='keypair'>
                            <option disable value="">Please select one</option>
                            <option v-for='k in keyPairInfo.keypairs'>{{k.keypair.name}}</option>
                        </select>

                    </div>
                </li>
            </ul>
        </div>
    </div>

  
    <br><br>
    <div>
        <button type="button" class="btn btn-outline-success" :disabled="validateCreate" v-on:click.prevent='createInstance(name, description, zone, size, networkName, keypair, source, volume)'>Create Instance</button>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Cancel</button>
    </div>



    <br>
    <h4>DEBUG</h4>
    <p>Teste imagem: {{image}} - {{imageId}}</p>
    <p>Teste network: {{networkName}} - {{networkId}}</p>
    <p>Teste flavors: {{flavor}} - {{flavorId}}</p>
    <p>Teste Volumes: {{volume}} --- {{volumeId}}</p>
    <button type="button" class="btn btn-outline-success" v-on:click.prevent='getVolumeId'>Test Volume</button>
    <br>

  </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                info: [],
                imageInfo: [],
                imageId: '',
                zonesInfo: [],
                flavorInfo: [],
                networkInfo: [],
                keyPairInfo: [],
                volumeInfo: [],
                name: '',
                description: '',
                zone: '',
                image: '',
                size: '1',
                flavor: '',
                flavorId: '',
                network: '',
                keypair: '',
                networkId: '',
                networkName: '',
                typeofmsg: "alert-success",
                showMessage: false,
                message: "",
                source: "Image",
                volume: '',
                volumeId: '',

                
            };
        },
        methods: {
            listImages: function () {
                this.imageInfo = [];
                var vm = this;
                axios.get('api/images')
                .then(function (response){
                    vm.imageInfo = response.data;
                })
                .catch(function (error){
                    vm.imageInfo = 'An error occurred.' + error;
                });
            },  
            getZones: function () {
                this.zonesInfo = [];
                var vm = this;
                axios.get('api/zones')
                .then(function (response){
                    vm.zonesInfo = response.data;
                })
                .catch(function (error){
                    vm.zonesInfo = 'An error occurred.' + error;
                });
            },  
            getFlavors: function () {
                this.flavorInfo = [];
                var vm = this;
                axios.get('api/flavors')
                .then(function (response){
                    vm.flavorInfo = response.data;
                })
                .catch(function (error){
                    vm.flavorInfo = 'An error occurred.' + error;
                });
            }, 
            getNetworks: function () {
                this.networkInfo = [];
                var vm = this;
                axios.get('api/networks')
                    .then(function (response){
                        vm.networkInfo = response.data;
                    })
                    .catch(function (error){
                        vm.networkInfo = 'An error occurred.' + error;
                    });
            },  
            getKeyPair: function () {
                this.keyPairInfo = [];
                var vm = this;
                axios.get('api/keypairs')
                    .then(function (response){
                        vm.keyPairInfo = response.data;
                    })
                    .catch(function (error){
                        vm.keyPairInfo = 'An error occurred.' + error;
                    });
            },
            getVolumes: function () {
                this.volumeInfo = [];
                var vm = this;
                    axios.get('api/volumes')
                    .then(function (response){
                      vm.volumeInfo = response.data;
                    })
                    .catch(function (error){
                      vm.volumeInfo = 'An error occurred.' + error;
                    });
            },  
            createInstance: function (name, description, zone, size, networkName, keypair, source, volume) {
                this.instanceInfo = [];
                var vm = this;
                this.getImageId();
                this.getNetworkId();
                this.getFlavorId();
                this.getVolumeId();
                //Verificação variaveis (precisa de ser melhorado)
                if (this.name == '') {
                    name = 'null';
                }else{
                    name = '"' + name + '"';
                }if (this.description == ''){
                    description = 'null';
                } else {
                    description = '"' + description + '"';
                } if (this.zone == ''){
                    this.typeofmsg = "alert-danger";
                    this.message = "Please Select a zone";
                    this.showMessage = true;
                } else {
                    this.showMessage = false;
                    zone = '"' + zone + '"';
                } if (this.keypair == '') {
                    this.typeofmsg = "alert-danger";
                    this.message = "Please Select a key pair";
                    this.showMessage = true;
                } else {
                    this.showMessage = false;
                    keypair = '"' + keypair + '"';
                }

                vm.imageId = '"' + vm.imageId + '"';
                size = '"' + size + '"';
                vm.flavorId = '"' + vm.flavorId + '"';
                vm.networkId = '"' + vm.networkId + '"';
                networkName = '"' + networkName + '"';

                if (this.source == 'Image') {
                    vm.volumeId = 'NULL';
                } else{ //source == 'Volume'{
                    vm.imageId = 'NULL';
                }


                axios.post('api/createInstance/' + name + '/' + description + '/' + zone + '/' + vm.imageId + '/' + size + '/' + vm.flavorId + '/' + vm.networkId + '/' + networkName + '/' + keypair + '/' + vm.volumeId )
                    .then(function (response){
                        vm.instanceInfo = response.data;
                    })
                    .catch(function (error){
                        vm.instanceInfo = 'An error occurred.' + error;
                    });

                    //this.goBack();
            },
            getImageId: function () {
                var vm = this;
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
            getNetworkId: function () {
                var vm = this;

                if (vm.networkName == ''){
                    this.typeofmsg = "alert-danger";
                    this.message = "Please Select a network";
                    this.showMessage = true;
                } else {
                    for (var i = vm.networkInfo.networks.length - 1; i >= 0; i--) {
                        if(vm.networkInfo.networks[i].name == vm.networkName){
                            vm.networkId = vm.networkInfo.networks[i].id;
                        }
                    }
                    this.showMessage = false;
                }
            },
            getFlavorId: function () {
                var vm = this;

                if (vm.flavor == ''){
                    this.typeofmsg = "alert-danger";
                    this.message = "Please Select a flavor";
                    this.showMessage = true;
                }else{
                    for (var i = vm.flavorInfo.flavors.length - 1; i >= 0; i--) {
                        if(vm.flavorInfo.flavors[i].name == vm.flavor){
                            vm.flavorId = vm.flavorInfo.flavors[i].id;
                        }
                    }
                    this.showMessage = false;
                }
            },
            getVolumeId: function () {
                var vm = this;

                for (var i = vm.volumeInfo.volumes.length - 1; i >= 0; i--) {
                    if(vm.volumeInfo.volumes[i].name == vm.volume){
                        vm.volumeId = vm.volumeInfo.volumes[i].id;
                    }
                    if (i==1 && vm.volumeId == '') {
                        vm.volumeId = vm.volume;
                    }
                }
            },

                         
            goBack() {
                this.$router.push('/instances');
            },
        },
        computed: {
            validateCreate() {
              return this.name == '' || this.zone == '' || this.size == '' || this.flavor == ''/* || this.networkName == ''*/ || this.keypair == '';
            }
        },
        mounted() {
            this.listImages();
            this.getZones();
            this.getFlavors();
            this.getNetworks();
            this.getKeyPair();
            this.getVolumes();
        }
    };
    /*
    
    */
</script>