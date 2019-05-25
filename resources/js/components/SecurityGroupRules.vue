<template>
    <div>
        <div class="jumbotron">
            <h1>Security Groups</h1>
        </div>
        <button class="btn btn-info col-lg-2 control-label" v-on:click.prevent="createSecurityGroup">Create Security Group</button>
        <button type="button" class="btn btn-outline-danger" v-on:click.prevent="goBack">Cancel</button>
    	<table class="table">
        	<thead>
        	    <tr>
        	        <th>Direction</th>
                    <th>Ethertype</th>
                    <th>Protocol</th>
                    <th>Port Range</th>
                    <th>Remote IP Prefix</th>
                    <th>Remote Security Group</th>
                    <th>Description</th>
                    <th>Actions</th>
        	    </tr>
       		</thead>
        	<tbody v-for='s in securitygroups.security_groups' v-if='s.id == securitygroupID'>
                <tr v-for='r in s.security_group_rules'>
                        <td>{{r.direction | capitalize}}</td>
                        <td>{{r.ethertype}}</td>
                        <td v-if='r.protocol == null'>Any</td>
                        <td v-if='r.protocol != null'>{{r.protocol}}}</td>
                        <td v-if='r.port_range_max == null && r.port_range_min == null'>Any</td>
                        <td v-if='r.port_range_max == null && r.port_range_min != null'>{{r.port_range_min}} - Any</td>
                        <td v-if='r.port_range_max != null && r.port_range_min == null'>Any - {{r.port_range_max}}</td>
                        <td v-if='r.port_range_max != null && r.port_range_min != null'>{{r.port_range_min}} - {{r.port_range_max}}</td>
                        <td v-if='r.remote_ip_prefix != null'>{{r.remote_ip_prefix}}</td>
                        <td v-if='r.remote_ip_prefix == null && r.direction == "egress" && r.ethertype == "IPv4"'>0.0.0.0/0</td>
                        <td v-if='r.remote_ip_prefix == null && r.direction == "egress" && r.ethertype == "IPv6"'>::/0</td>
                        <td v-if='r.remote_ip_prefix == null && r.direction == "ingress"'> - </td>
                        <td v-if='r.remote_group_id == null'> - </td>
                        <td v-if='r.remote_group_id != null'>{{s.name}}</td>
                        <td v-if='r.description == null'> - </td>
                        <td v-if='r.description != null'>{{r.description}}</td>
                        <td>
                            <button type="button" class="btn btn-danger" v-on:click.prevent="deleteRule(r)">Delete</button>
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
                securitygroups: [],
                securitygroupID: 0,
            };
        },

        filters: {
            capitalize: function (value) {
                if (!value) return ''
                    value = value.toString()
                    return value.charAt(0).toUpperCase() + value.slice(1)
            }
        },

        methods: {
            getSecurityGroups: function () {
                this.securitygroups = [];
                var vm = this;
                axios.get('api/security_groups')
                    .then(function (response){
                        vm.securitygroups = response.data;
                    })
                    .catch(function (error){
                        vm.securitygroups = 'An error occurred.' + error;
                    });
            },

            getSecurityGroupID: function () {
                this.securitygroupID = this.$route.params.id;
            },

            goBack() {
                this.$router.push('/security_groups');
            },

            deleteRule: function(rule) {
                axios.delete('api/SecurityGroup' + rule.id)
                    .then(response => {
                        this.getSecurityGroups();
                    })
            },

            createRule() {
                this.$router.push('/createRule');
            }
        },
        mounted() {
            this.getSecurityGroups();
            this.getSecurityGroupID();
        }
    };
</script>