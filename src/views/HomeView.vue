<template>
    <v-container>
        <v-flex d-flex>
            <v-select class="ma-2" :items="statusOptions" :value="-1" v-on:change="changedFilters" v-model="filterStatusModel" item-text="name" item-value="id" label="Status">
            </v-select>
            <v-select class="ma-2" :items="priorityOptions" :value="-1" v-on:change="changedFilters" v-model="filterPriorityModel" item-text="name" item-value="id" label="Priority">
            </v-select>
            <v-select class="ma-2" :items="impactOptions" :value="-1" v-on:change="changedFilters" v-model="filterImpactModel" item-text="name" item-value="id" label="Impact">
            </v-select>
            <v-select class="ma-2" :items="categoryOptions" :value="-1" v-on:change="changedFilters" v-model="filterCategoryModel" item-text="name" item-value="id" label="Category">
            </v-select>
        </v-flex>
        <v-flex>
            <v-toolbar flat color="white">
                <v-toolbar-title>Task Table</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn color="primary" dark class="mb-2" v-on:click="openDialog()">New Task</v-btn>
            </v-toolbar>
            <v-data-table :headers="headers" :items="this.$store.state.tasks" item-key="name">
                <template v-slot:items="props">
                    <tr @click="props.expanded = !props.expanded" bgcolor="#B2EBF2">
                        <td class="text-xs-center">{{ props.item.id }}</td>
                        <td class="text-xs-center">{{ props.item.name }}</td>
                        <td class="text-xs-center">{{ props.item.status }}</td>
                        <td class="text-xs-center">{{ props.item.impact }}</td>
                        <td class="text-xs-center">{{ props.item.priority }}</td>
                        <td class="justify-center layout px-0">
                            <v-icon small class="mr-2" @click="editItem(props.item)">
                                fa-edit
                            </v-icon>
                            <v-icon small class="mr-2" @click="openDialogDeleteItem(props.item)" color="red">
                                fa-trash
                            </v-icon>
                            <v-icon small @click="quickResolve(props.item)" color="green">
                                far fa-check-circle
                            </v-icon>
                        </td>
                    </tr>
                </template>
                <template v-slot:expand="props">
                    <v-card flat>
                        <v-card-text>{{ props.item.comments }}</v-card-text>
                    </v-card>
                </template>
            </v-data-table>
        </v-flex>
        <v-dialog v-model="dialog" max-width="500px">
            <v-card>
                <v-card-title>
                    <span class="headline">Task</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field label="Name*" v-model="nameInsert" required></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Description" v-model="descriptionInsert"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-select v-model="userAsignedModel" :items="this.$store.state.users" item-text="name" item-value="id" label="Assigned to*">
                                </v-select>
                            </v-flex>
                            <v-flex xs12>
                                <v-select v-model="statusModel" :items="this.$store.state.statuses" item-text="name" item-value="id" label="Status*">
                                </v-select>
                            </v-flex>
                            <v-flex xs12>
                                <v-select v-model="priorityModel" :items="this.$store.state.priorities" item-text="name" item-value="id" label="Priority*">
                                </v-select>
                            </v-flex>
                            <v-flex xs12>
                                <v-select v-model="impactModel" :items="this.$store.state.impacts" item-text="name" item-value="id" label="Impact*">
                                </v-select>
                            </v-flex>
                            <v-flex xs12>
                                <v-select v-model="categoryModel" :items="this.$store.state.categories" item-text="name" item-value="id" label="Category*">
                                </v-select>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Comments" v-model="commentsInsert"></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialog = false">Close</v-btn>
                    <v-btn color="blue darken-1" flat @click="saveItem()">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="290">
            <v-card>
                <v-card-title class="headline">Delete</v-card-title>

                <v-card-text>
                    Are you sure you want to delete it?
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="blue darken-1" flat="flat" @click="dialogDelete = false">
                        Close
                    </v-btn>

                    <v-btn color="red darken-1" flat="flat" @click="deleteItem(item);">
                        Delete
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-container>

</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator';
    import Status from "../objects/types/Status";
    import Priority from "../objects/types/Priority";
    import Impact from "../objects/types/Impact";
    import Category from "../objects/types/Category";
    import User from "../objects/types/User";
    import Task from "../objects/types/Task";
    @Component
    export default class HomeView extends Vue {
        private statusOptions: Status[] = [];
        private priorityOptions: Priority[] = [];
        private impactOptions: Impact[] = [];
        private categoryOptions: Category[] = [];

        private filterStatusModel = -1;
        private filterPriorityModel = -1;
        private filterImpactModel = -1;
        private filterCategoryModel = -1;

        private dialog: boolean = false;
        private dialogDelete: boolean = false;

        private nameInsert: string = '';
        private descriptionInsert: string = '';
        private commentsInsert: string = '';

        private userAsignedModel: number = 0;
        private statusModel: number = 0;
        private priorityModel: number = 0;
        private impactModel: number = 0;
        private categoryModel: number = 0;

        private update = false;
        private item: Status|any = null;

        private headers= [
            { text: '#Act', value: 'id', align: 'center' },
            { text: 'Name', value: 'name', align: 'center' },
            { text: 'Status', value: 'status', align: 'center' },
            { text: 'Impact', value: 'impact', align: 'center' },
            { text: 'Priority', value: 'priority', align: 'center' },
            { text: 'Actions', value: 'actions', align: 'center' },
        ];

        private quickResolve(item: Task) {

        }

        private async changedFilters(): Promise<void> {
            let filters = {
                filterStatus: this.filterStatusModel,
                filterPriority: this.filterPriorityModel,
                filterImpact: this.filterImpactModel,
                filterCategory: this.filterCategoryModel,
            };
            this.$store.dispatch('retrieveTasks', filters);
        }
        private async mounted(): Promise<void> {
            await this.$store.dispatch('retrieveStatuses');
            await this.$store.dispatch('retrievePriorities');
            await this.$store.dispatch('retrieveImpacts');
            await this.$store.dispatch('retrieveCategories');
            await this.$store.dispatch('retrieveUsers');

            this.statusOptions = this.$store.getters.getStatuses;
            this.priorityOptions = this.$store.getters.getPriorities;
            this.impactOptions = this.$store.getters.getImpacts;
            this.categoryOptions = this.$store.getters.getCategories;

            let statusAll = new Status();
            statusAll.id = -1;
            statusAll.name = 'All';

            let priorityAll = new Priority();
            priorityAll.id = -1;
            priorityAll.name = 'All';

            let impactAll = new Impact();
            impactAll.id = -1;
            impactAll.name = 'All';

            let categoryAll = new Category();
            categoryAll.id = -1;
            categoryAll.name = 'All';

            this.statusOptions.unshift(statusAll);
            this.priorityOptions.unshift(priorityAll);
            this.impactOptions.unshift(impactAll);
            this.categoryOptions.unshift(categoryAll);

            let filters = {
                filterStatus: this.filterStatusModel,
                filterPriority: this.filterPriorityModel,
                filterImpact: this.filterImpactModel,
                filterCategory: this.filterCategoryModel,
            };
            this.$store.dispatch('retrieveTasks', filters);

        }


        private saveItem(): void {
            if (this.nameInsert.trim().length > 0 &&
            Number(this.categoryModel) != 0 &&
                Number(this.priorityModel) != 0 &&
                Number(this.impactModel) != 0 &&
                Number(this.statusModel) != 0 &&
                Number(this.userAsignedModel) != 0) {
                if(this.update === true) {
                    this.updateItem(this.item);
                }else{
                    this.storeItem();
                }
            }

            this.dialog = false;
            console.log(this.statusModel);
        }
        private openDialog(item: Task|null = null): void {
            this.dialog = true;
            if (item === null) {
                this.nameInsert = '';
                this.descriptionInsert = '';
                this.commentsInsert = '';
                this.userAsignedModel = 0;
                this.statusModel = 0;
                this.priorityModel = 0;
                this.impactModel = 0;
                this.categoryModel = 0;
                this.update = false;
            }else {
                this.nameInsert = item.name;
                this.descriptionInsert = item.description;
                this.commentsInsert = item.comments;
                this.userAsignedModel = Number(item.assignedUser);
                this.statusModel = Number(item.status);
                this.priorityModel = Number(item.priority);
                this.impactModel = Number(item.impact);
                this.categoryModel = Number(item.category);
                this.update = true;
            }
        }
        private editItem(item: Task): void {
            this.item = item;
            this.openDialog(item);
        }
        private storeItem(): void {
            let item = new Task();
            item.name = this.nameInsert;
            item.description = this.descriptionInsert;
            item.impact = String(this.impactModel);
            item.status = String(this.statusModel);
            item.category = String(this.categoryModel);
            item.priority = String(this.priorityModel);
            item.assignedUser = String(this.userAsignedModel);
            item.assignedByUser = String(this.$store.state.loggedUser.id);
            item.comments = this.commentsInsert;
            this.$store.dispatch('storeTask',item);
            console.log(item);
        }
        private updateItem(item: Task): void {
            let itemUpdate = new Task();
            itemUpdate.id = this.item.id;
            itemUpdate.name = this.nameInsert;
            itemUpdate.description = this.descriptionInsert;
            itemUpdate.impact = String(this.impactModel);
            itemUpdate.status = String(this.statusModel);
            itemUpdate.category = String(this.categoryModel);
            itemUpdate.priority = String(this.priorityModel);
            itemUpdate.assignedUser = String(this.userAsignedModel);
            itemUpdate.assignedByUser = String(this.$store.state.loggedUser);
            itemUpdate.comments = this.commentsInsert;
            this.$store.dispatch('updateTask',itemUpdate);
        }
        private deleteItem(item: Status): void {
            this.dialogDelete = false;
            this.$store.dispatch('deleteTask',item);
        }
        openDialogDeleteItem(item: Status) {
            this.item = item;
            this.dialogDelete = true;
        }
    }
</script>

<style scoped lang="scss">

</style>
