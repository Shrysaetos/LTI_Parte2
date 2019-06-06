<template>
    <div>
        <div class="jumbotron">
            <h1>Flavors</h1>
        </div>
        <button type="button" class="btn btn-outline-danger col-lg-2 control-label" v-on:click.prevent="goBack">Cancel</button>
        <br><br>
    	<table class="table">
        	<thead>
        	    <tr>
        	        <th>Name</th>
        	        <th>VCPUS</th>
        	        <th>RAM</th>
                    <th>Total Disk</th>
                    <th>Public</th>
        	    </tr>
       		</thead>
        	<tbody>
                <tr v-for='f in flavors.flavors'>
                    <td>{{f.name}}</td>
                    <td>{{f.vcpus}}</td>
                    <td>{{f.ram}}MB</td>
                    <td>{{f.disk}}G</td>
                    <td v-if='f["os-flavor-access:is_public"] == true'>Yes</td>
                    <td v-if='f["os-flavor-access:is_public"] != true'>No</td>
                </tr>
        	</tbody>
    	</table>
    	</div>
</template>
<script>
    module.exports = {
        data() {
            return {
                flavors: [],
            };
        },
        methods: {
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

            goBack() {
                this.$router.push('/currentUser');
            },

            deleteFlavor: function(flavor) {
                axios.delete('api/flavors' + flavor.id)
                    .then(response => {
                        this.getFlavors();
                    })
            },

            createFlavor() {
                this.$router.push('/createFlavor');
            }
        },
        mounted() {
            this.getFlavors();
        }
    };
</script>