<template>
    <v-container>
        <v-toolbar flat color="white">
            <v-toolbar-title>Expandable Table</v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>
        <v-data-table :headers="headers" :items="this.$store.state.tasks" item-key="name">
            <template v-slot:items="props">
                <tr @click="props.expanded = !props.expanded" bgcolor="#B2EBF2">
                    <td class="text-xs-center">{{ props.item.id }}</td>
                    <td class="text-xs-center">{{ props.item.name }}</td>
                    <td class="text-xs-center">{{ props.item.status }}</td>
                    <td class="text-xs-center">{{ props.item.impact }}</td>
                    <td class="text-xs-center">{{ props.item.priority }}</td>
                </tr>
            </template>
            <template v-slot:expand="props">
                <v-card flat>
                    <v-card-text>{{ props.item.comments }}</v-card-text>
                </v-card>
            </template>
        </v-data-table>
    </v-container>

</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';
    @Component
    export default class HomeView extends Vue {
        private headers= [
            { text: '#Act', value: 'id', align: 'center' },
            { text: 'Name', value: 'name', align: 'center' },
            { text: 'Status', value: 'status', align: 'center' },
            { text: 'Impact', value: 'impact', align: 'center' },
            { text: 'Priority', value: 'priority', align: 'center' },
        ];
        private mounted():void {
            this.$store.dispatch('retrieveTasks');
        }
    }
</script>

<style scoped lang="scss">

</style>
