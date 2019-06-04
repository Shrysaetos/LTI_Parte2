
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
var Vue = require('vue');
Vue.use(require('vue-resource'));

//import Vue       from 'vue';
import lineClamp from 'vue-line-clamp';
Vue.use(lineClamp, {
	importCss: true
});

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import Vuex from 'vuex';
Vue.use(Vuex);

import Toasted from 'vue-toasted';

Vue.use(Toasted, {
    position: 'top-right',
    duration: 5000,
    type: 'info',
});

const LoginComponent = Vue.component('login-component', require('./components/Login.vue').default);

//Instances
const CreateInstanceComponent = Vue.component('createInstance-component', require('./components/CreateInstance.vue').default);
const InstancesComponent = Vue.component('instances-component', require('./components/Instance.vue').default);

//Networks
const NetworksComponent = Vue.component('networks-component', require('./components/Network.vue').default);
const SubnetsComponent = Vue.component('subnets-component', require('./components/Subnet.vue').default);
	//create
const CreateNetworkComponent = Vue.component('createNetwork-component', require('./components/CreateNetwork.vue').default);
//Flavors
const FlavorsComponent = Vue.component('flavors-component', require('./components/Flavor.vue').default);
const FlavorComponent = Vue.component('flavor-component', require('./components/IndividualFlavor.vue').default);

//Volumes
const CreateVolumeComponent = Vue.component('createVolume-component', require('./components/CreateVolume.vue').default);
const VolumesComponent = Vue.component('volumes-component', require('./components/Volume.vue').default);

//Images
const ImagesComponent = Vue.component('images-component', require('./components/Image.vue').default);
	//create
const CreateImageComponent = Vue.component('createimage-component', require('./components/CreateImage.vue').default);

//Key Pairs
const KeypairsComponent = Vue.component('keypairs-component', require('./components/Keypair.vue').default);
	//create
const CreateKeypairComponent = Vue.component('createkeypair-component', require('./components/CreateKeyPair.vue').default);

//Security Groups
const SecurityGroupsComponent = Vue.component('security-groups-component', require('./components/SecurityGroup.vue').default);
const SGRulesComponent = Vue.component('s-g-rules-component', require('./components/SecurityGroupRules.vue').default);

//Floating IPs
const FloatingIPsComponent = Vue.component('floatingips-component', require('./components/FloatingIP.vue').default);

const CurrentUserComponent = Vue.component('currentUser-component', require('./components/CurrentUser.vue').default);
const CreateUserComponent = Vue.component('createUser-component', require('./components/CreateUser.vue').default);



const routes = [

	{
		path: '/',
		redirect: {
			name: "login"
		}
	},
	{
		path: "/login",
		name: "login",
		component: LoginComponent
	},
	{
		path: "/createVolume",
		name: "createVolume",
		component: CreateVolumeComponent
	},
	{
		path: "/volumes",
		name: "volumes",
		component: VolumesComponent
	},
	{
		path: "/createInstance",
		name: "createInstance",
		component: CreateInstanceComponent
	},
	{
		path: "/instances",
		name: "instances",
		component: InstancesComponent
	},
	{
		path: "/flavors",
		name: "flavors",
		component: FlavorsComponent
	},
	{
		path: "/flavor/:id",
		name: "flavor",
		component: FlavorComponent
	},
	{
		path: "/images",
		name: "images",
		component: ImagesComponent
	},
	{
		path: "/createImage",
		name: "createImage",
		component: CreateImageComponent
	},
	{
		path: "/keypairs",
		name: "keypairs",
		component: KeypairsComponent
	},
	{
		path: "/createKeypair",
		name: "createKeypair",
		component: CreateKeypairComponent
	},
	{
		path: "/networks",
		name: "networks",
		component: NetworksComponent
	},
	{
		path: "/createNetwork",
		name: "createNetwork",
		component: CreateNetworkComponent
	},
	{
		path: "/subnets/:id",
		name: "subnets",
		component: SubnetsComponent
	},
	{
		path: "/security_groups",
		name: "security-groups",
		component: SecurityGroupsComponent
	},
	{
		path: "/security_groups/:id",
		name: "security_groups_rules",
		component: SGRulesComponent
	},
	{
		path: "/floatingips",
		name: "floatingips",
		component: FloatingIPsComponent
	},
	{
		path: "/currentUser",
		name: "currentUser",
		component: CurrentUserComponent
	},
	{
		path: "/createUser",
		name: "createUser",
		component: CreateUserComponent
	}


];


//Vue.component('server', require('./components/Servers.vue').default);

Vue.http.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

import server from './components/CurrentUser.vue';



const router = new VueRouter({
    routes: routes
});

/*
const app = new Vue({
    router,
    el: '#app',
    data: {
    	lines: 2
  	}
});*/

const app = new Vue({
	router,
    el: '#app',
    components :  { server }
});
