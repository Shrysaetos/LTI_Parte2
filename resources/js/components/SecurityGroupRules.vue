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
        	<tbody>
                <tr v-for='s in securitygroups.security_groups' && 'r in s.security_group_rules' v-if='s.id == securitygroupID'>
                    <td>{{r.direction}}</td>
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