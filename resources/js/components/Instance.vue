<template>
    <div>
        <div class="jumbotron">
            <h1>Instances</h1>
        </div>
        <button class="btn btn-info col-lg-2 control-label" v-on:click.prevent="createInstance">Create Instance</button>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Cancel</button>
    	<table class="table">
        	<thead>
        	    <tr>
        	        <th>Name</th>
        	        <th>Image Name</th>
        	        <th>IP Address</th>
                    <th>Flavor</th>
                    <th>Key Pair</th>
                    <th>Status</th>
                    <th>Availability Zone</th>
                    <th>Task</th>
                    <th>Power State</th>
                    <th>Actions</th>
        	    </tr>
       		</thead>
        	<tbody>
                <tr v-for='i in instances.servers'>
                    <td>{{i.name}}</td>
                    <td v-if='i.image==""'>-</td>
                    <td v-if='i.image!=""'>{{i.image}}</td>
                    <td v-if='i["OS-EXT-STS:vm_state"] == "active"'>{{i.addresses.shared[0].addr}}</td>
                    <td v-if='i["OS-EXT-STS:vm_state"] != "active"'> - </td>
                    <td v-for='f in flavors.flavors' v-if='i.flavor.id == f.id'> <router-link :to="{ name: 'flavor', params: {id: f.id}}">{{f.name}}</router-link></td>
                    <td>{{i.key_name}}</td>
                    <td>{{i["OS-EXT-STS:vm_state"] | capitalize}}</td>
                    <td>{{i["OS-EXT-AZ:availability_zone"]}}</td>
                    <td v-if='i["OS-EXT-STS:task_state"] == null'>None</td>
                    <td v-if='i["OS-EXT-STS:task_state"] != null'>{{i["OS-EXT-STS:task_state"]}}</td>
                    <td>{{i["OS-EXT-STS:power_state"]}}</td>
                    <td>
                        <button class="btn btn-info" v-on:click.prevent="console(i.id)">Console</button>
                        <button type="button" class="btn btn-danger" v-on:click.prevent="deleteInstance(i.id)">Delete</button>
                    </td>
                </tr>
        	</tbody>
    	</table>
    	</div>
        
</template>
<script>
    module.exports = {
        data() {
            return {
                instances: [],
                flavors: [],
                url: '',            };
        },

        filters: {
            capitalize: function (value) {
                if (!value) return ''
                    value = value.toString()
                    return value.charAt(0).toUpperCase() + value.slice(1)
            },
        },

        methods: {
            getInstances: function () {
                this.instances = [];
                var vm = this;
                axios.get('api/instances')
                    .then(function (response){
                        vm.instances = response.data;
                    })
                    .catch(function (error){
                        vm.instances = 'An error occurred.' + error;
                    });
            },

            getFlavors: function () {
                this.flavors = [];
                var vm = this;
                axios.get('api/flavors')
                    .then(function (response){
                        vm.flavors = response.data;
                    })
                    .catch(function (error){
                        vm.flavors = 'An error occurred.' + error;
                    });
            },
            console(instanceID) {
                var vm = this;
                this.getUrl(instanceID);
                setTimeout(function(){
                    window.open(vm.url.console.url);
                }, 3000); 
                

            },

            goBack() {
                this.$router.push('/instances');
            },

            deleteInstance: function(instanceID) {
                axios.delete('api/deleteInstance/' + instanceID)
            },

            createInstance() {
                this.$router.push('/createInstance');
            },

            getUrl: function (instanceID){
                var vm = this;
                axios.post('api/getUrl/' + instanceID)
                    .then(function (response){
                        vm.url = response.data;
                    })
                    .catch(function (error){
                        vm.url = 'An error occurred.' + error;
                    });
            }
        },
        mounted() {
            this.getInstances();
            this.getFlavors();
        }
    };
</script>