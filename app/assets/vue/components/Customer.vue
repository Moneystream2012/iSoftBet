<template>
    <div class="card w-100 mt-2">
        <a v-bind:href="'/transactions/' + id">
            <div class="card-body">
                #{{ id }} : {{ name }} ({{ cnp }})
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        name: 'customer',
        props: ['id', 'name','cnp'],
    }
</script>
