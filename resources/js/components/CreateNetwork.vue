<template>
  <div>
    <div class="jumbotron">
      <h1>Create Network</h1>
    </div>
    <div>
        

        <div>
            <h4>Network</h4>
            <div>
                <label>Network Name</label>
                <font v-if="networkName==''" size="3" color="red">*</font>
                <div>
                    <input v-model="networkName" placeholder="Network name">
                </div>
            </div>
            <br>
            <div>
                <ul>
                    <li>
                        <input type="checkbox" id="adminState" v-model="adminState">
                        <label>Enable Admin State</label>
                    </li>
                </ul>
                <ul>
                    <li>
                        <input type="checkbox" id="subnet" v-model="subnet">
                        <label>Create Subnet</label>
                    </li>
                </ul>
            </div>
        </div>
        <br>
        <div v-if="subnet">
            <h4>Subnet</h4>
            <div>
                <label>Subnet Name</label>
                <font v-if="subnetName==''" size="3" color="red">*</font>
                <div>
                    <input v-model="subnetName" placeholder="Subnet name">
                </div>
            </div>   
            <br>
            <div>
                <label>Network Address</label>
                <font v-if="address==''" size="3" color="red">*</font>
                <div>
                    <input v-model="address" placeholder="<ip>/<mask>">
                </div>
            </div>
            <br>
            <div>
                <label>Gateway Ip</label>
                <div>
                    <input v-model="gateway" placeholder="Gateway">
                </div>
            </div>
            <br>
            <ul>
                <li>
                    <input type="checkbox" id="dhcp" v-model="dhcp">
                    <label>Enable DHCP</label>
                </li>
            </ul>  
        </div>
        <br>
        

        <br><br>
      <div>
        <button type="button" :disabled="validateCreate" class="btn btn-outline-success" v-on:click.prevent="createNetwork(networkName)">Create</button>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Cancel</button>
      </div>
      <br><br>

     

    </div>
  </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                info: [],
                idNetwork: '',
                networkName: '',
                adminState: true,
                subnet: true,
                subnetName: '',
                address: '',
                gateway: '',
                dhcp: true,
                
            };
        },
        methods: {
            createNetwork: function (networkName, adminState, subnetName, address, gateway, dhcp) {
                this.info = [];
                this.idNetwork = '';
                var vm = this;

                if (this.subnet == false) {
                    subnetName = 'null';
                    address = 'null';
                    gateway = 'null';
                }
                if (this.gateway == '') {
                    this.gateway = 'null';
                }
                vm.address = vm.address.replace(/[/]/g,']');
                
                axios.post('api/createNetwork/' + networkName + '/' + this.adminState +  '/' + subnetName +  '/' + vm.address + '/' + this.gateway + '/' + this.dhcp)
                .then(function (response){
                    vm.info = response.data;
                })
                .catch(function (error){
                    vm.info = 'An error occurred.' + error;
                });
                this.goBack();


            }, 
            goBack() {
                this.$router.push('/networks');
            },
        },
        computed: {
            validateCreate() {
                if (this.subnet == false) {
                    return this.networkName == '';
                } else{
                    return this.networkName == '' || this.subnetName == '' || this.address == '';
                }
            }
        },
        
        mounted() {
        }
    };
</script>