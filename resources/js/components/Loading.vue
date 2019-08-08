<template>
    <div class="text-center">
        <v-progress-circular
        :size="150"
        width="15"
        color="pink darken-2"
        indeterminate
        >Loading...</v-progress-circular>
    </div>
</template>

<script>
    export default {
        name: 'Loading'
    }
</script>
