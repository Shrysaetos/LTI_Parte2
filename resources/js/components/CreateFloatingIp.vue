<template>
  <div>
    <div class="jumbotron">
      <h1>Allocate Floating IP</h1>
    </div>
    <div>
        <h4>Floating IP</h4>
        <br>

        <div>
            <label>Network</label>
                <font v-if="networkName==''" size="3" color="red">*</font>
                <div>
                <select v-model='networkName'>
                    <option disable value="">Please select one</option>
                    <option v-for='n in networkInfo.networks'>{{n["name"]}}</option>
                </select>
        </div>
        <br>
        <div>
            <label>Description</label>
            <div>
                <input v-model="description" placeholder="Description">
            </div>
        </div>
        <br>
    </div>
        

      <div>
        <button type="button" :disabled="validateCreate" class="btn btn-outline-success" v-on:click.prevent='createFloatingIp()'>Create</button>
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
                networkInfo: [],
                network: '',
                networkId: '',
                networkName: '',
                description: '',
                validation: false,
            };
        },
        methods: {  
            createFloatingIp: function () {
                this.info = [];
                this.getNetworkId();
                var vm = this;
                if (vm.description == '') {
                    vm.description = 'null';
                }
                
                axios.post('api/createFloatingIp/' + vm.description + '/' + vm.networkId)
                .then(function (response){
                    vm.info = response.data;
                })
                .catch(function (error){
                    vm.info = 'An error occurred.' + error;
                });
                //this.$router.push('/keypairs');
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
            goBack() {
                this.$router.push('/floatingips');
            },
        },
        computed: {
            validateCreate() {
              return this.networkName =='';
            }
        },
        mounted() {
            this.getNetworks();
        }
    };
</script>