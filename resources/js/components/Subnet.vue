<template>
    <div>
        <div class="jumbotron">
            <h1>Subnets</h1>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>CIDR</th>
                    <th>IP Version</th>
                    <th>Gateway IP</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for='s in subnets.subnets' v-if='s.network_id == networkID'>
                    <td>{{s.name}}</td>
                    <td>{{s.cidr}}</td>
                    <td>IPv{{s.ip_version}}</td>
                    <td>{{s.gateway_ip}}</td>
                    <td>
                        <button class="btn btn-info" v-on:click.prevent="editKeypair">Edit</button>
                        <button type="button" class="btn btn-danger" v-on:click.prevent="deleteKeypair(k)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Back</button>
        </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                subnets: [],
                networkID: 0,
            };
        },
        methods: {
            getSubnets: function () {
                this.subnets = [];
                var vm = this;
                axios.get('api/subnets')
                    .then(function (response){
                        vm.subnets = response.data;
                    })
                    .catch(function (error){
                        vm.subnets = 'An error occurred.' + error;
                    });
            },

            getNetworkID: function () {
                    this.networkID = this.$route.params.id;
            },

            goBack() {
                this.$router.push('/networks');
            },
        },
        mounted() {
            this.getSubnets();
            this.getNetworkID();
        }
    };
</script>