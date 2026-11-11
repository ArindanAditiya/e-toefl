<template>
    <div class="wrapper" v-bind:style="{ 'background-image': 'url(https://siak.imamdev.com/assets/dist/img/bg.jpg)' }">
		<slot/>
	</div>
</template>

<script>
    export default {
        // Default AppName
        data() {
            return { appName : "e-TOEFL" }
        }
    }
</script>