<template>
    <div>
        <div class="jumbotron">
            <h1>Floating IPs</h1>
        </div>
        <button class="btn btn-info col-lg-2 control-label" v-on:click.prevent="createFloatingIP">Create Floatig IP</button>
        <button type="button" class="btn btn-outline-danger col-lg-2" v-on:click.prevent="goBack">Cancel</button>
    	<table class="table">
        	<thead>
        	    <tr>
        	        <th>IP Address</th>
                    <th>Description</th>
                    <th>Fixed IP Address</th>
                    <th>Status</th>
                    <th>Actions</th>
        	    </tr>
       		</thead>
        	<tbody>
                <tr v-for='f in floatingips.floatingips'>
                    <td>{{f.floating_ip_address}}</td>
                    <td>{{f.description}}</td>
                    <td v-if='fixed_ip_address == null'> - </td>
                    <td v-if='fixed_ip_address != null'>{{f.fixed_ip_address}}</td>
                    <td>{{f.status}}</td>
                    <td>
                        <button class="btn btn-info" v-on:click.prevent="editFloatingIP(f)">Edit
                        </button>
                        <button type="button" class="btn btn-danger" v-on:click.prevent="deleteFloatingIP(f.id)">Delete</button>
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
                floatingips: [],
            };
        },
        methods: {
            getFloatingIPs: function () {
                this.floatingips = [];
                var vm = this;
                axios.get('api/floatingips')
                    .then(function (response){
                        vm.floatingips = response.data;
                    })
                    .catch(function (error){
                        vm.floatingips = 'An error occurred.' + error;
                    });
            },

            goBack() {
                this.$router.push('/currentUser');
            },

            editFloatingIP: function (id) {
                this.$router.push('/floatingips/'+ id);
            },

            deleteFloatingIP: function(id) {
                axios.delete('api/deleteFloatingIP/' + id)
                    .then(response => {
                        this.getFloatingIPs();
                    })
            },

            createFloatingIP() {
                this.$router.push('/createFloatingIp');
            }
        },
        mounted() {
            this.getFloatingIPs();
        }
    };
</script>