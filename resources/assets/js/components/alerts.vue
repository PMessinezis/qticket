/* 

	Expects a string property named alertsText


*/

<template>
<div class='alertsWrap' v-show='alertsText'>
	<div v-if='alertsText' class='alertsText'>  
		<div>{{alertsText}}</div> 
	</div>
</div>

</template>


<script >

	export default {
  	props: ['alertsText'],
 	name:'vue-alerts',
	}

</script>


<style scoped lang="scss">

.alertsWrap{
	position:relative;
	display:block;
	width:100%;
}

.alertsText{
	display:inline-flex;
	position:absolute;
	flex-direction: row;
	white-space: nowrap;
	justify-content:left;
	color:red;
	left:100%;
	animation: mymove 60s 1s infinite linear;
}

@keyframes mymove{
    0% {left: 100%;}   
    100% { transform:translateX(-100%);left:0%;}
}

	
</style>
