<template>
    
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';
    import Task from "../objects/types/Task";

    @Component
    export default class TaskList extends Vue {
        @Prop() private tasks!: Task;
    }
</script>

<style lang="scss">

</style>
