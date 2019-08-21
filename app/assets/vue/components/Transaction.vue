<template>
    <div class="card w-100 mt-2">
        <div class="card-body">
            #{{ id }} : {{customerId}} : {{amount}} : {{created}}
        </div>
    </div>
</template>

<script>
    export default {
        name: 'transaction',
        props: ['id', 'customerId', 'amount', 'created'],
    }
</script>
