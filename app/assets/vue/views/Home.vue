<template>
    <div>
        <div class="row col">
            <h1>Welcome on-board</h1>
        </div>

        <div>
            <p>This is the homepage of my [Symfony4 + Vue.js] transactions stuff..</p>
        </div>

        <div>
            <img :src="url">
        </div>

        <div class="row col">
            <p>Hope our efforts always give expected result :)</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'home',
        data () {
            return {
                url: "https://media.giphy.com/media/LdOyjZ7io5Msw/giphy.gif"
            }
        }
    }
</script>
