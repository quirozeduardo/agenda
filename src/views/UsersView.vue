<template>
    <v-container>
        <v-flex>
            <v-toolbar flat color="white">
                <v-toolbar-title>Users Table</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn color="primary" dark class="mb-2" v-on:click="openDialog()">New User</v-btn>
            </v-toolbar>
            <v-data-table :headers="headers" :items="this.$store.state.usersAdmin" item-key="name">
                <template v-slot:items="props">
                    <tr @click="props.expanded = !props.expanded" bgcolor="#B2EBF2">
                        <td class="text-xs-center">{{ props.item.id }}</td>
                        <td class="text-xs-center">{{ props.item.name }}</td>
                        <td class="text-xs-center">{{ props.item.lastName}}</td>
                        <td class="text-xs-center">{{ props.item.email }}</td>
                        <td class="text-xs-center">
                            <v-chip v-for="department in props.item.departments" small color="primary" :key="department.id" text-color="white">{{department.name}}</v-chip>
                        </td>
                        <td class="text-xs-center">
                            <v-chip v-for="userType in props.item.userTypes" small color="secondary" :key="userType.id" text-color="white">{{userType.name}}</v-chip>
                        </td>
                        <td class="justify-center layout px-0">
                            <v-icon small class="mr-2" @click="editItem(props.item)">
                                fa-edit
                            </v-icon>
                            <v-icon v-if="props.item.deleted_at == null" small class="mr-2" @click="openDialogDeleteItem(props.item)" color="red">
                                fa-trash
                            </v-icon>
                            <v-icon v-else small class="mr-2" @click="restoreItem(props.item)" color="teal">
                                fas fa-trash-restore
                            </v-icon>
                        </td>
                    </tr>
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
                                <v-text-field label="Last Name" v-model="lastNameInsert"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-text-field label="Email" v-model="emailInsert"></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-combobox v-model="selectDepartments" item-text="name" :items="this.$store.state.departmentsAdmin" label="Select departments" multiple
                                ></v-combobox>
                            </v-flex>
                            <v-flex xs12>
                                <v-combobox v-model="selectUserTypes" item-text="name" :items="this.$store.state.userTypeAdmin" label="Select User Types" multiple
                                ></v-combobox>
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
    import {Department} from "../objects/types/admin/Department";
    import {UserType} from "../objects/types/admin/UserType";
    import {UserAdmin} from "../objects/types/admin/UserAdmin";
    import User from "../objects/types/User";

    @Component
    export default class Users extends Vue {
        private headers= [
            { text: 'Id', value: 'id', align: 'center' },
            { text: 'Name', value: 'name', align: 'center' },
            { text: 'Last Name', value: 'lastName', align: 'center' },
            { text: 'Email', value: 'impact', align: 'center' },
            { text: 'Departments', value: 'priority', align: 'center' },
            { text: 'User Type', value: 'userTypes', align: 'center' },
            { text: 'Actions', value: 'actions', align: 'center' },
        ];
        private update: boolean =  false;
        private item: UserAdmin|any = null;
        private dialog: boolean =  false;
        private dialogDelete: boolean =  false;

        private search: string = '';
        private selectDepartments: Department[] = [];
        private selectUserTypes: UserType[] = [];

        private nameInsert: string = '';
        private lastNameInsert: string = '';
        private emailInsert: string = '';

        private saveItem(): void {
            console.log(this.selectDepartments);
            if (this.nameInsert.trim().length > 0) {
                if(this.update === true) {
                    this.updateItem(this.item);
                }else{
                    this.storeItem();
                }
            }

            this.dialog = false;
        }
        private openDialog(item: UserAdmin|null = null): void {
            this.dialog = true;
            if (item === null) {
                this.nameInsert = '';
                this.update = false;
            }else {
                this.nameInsert = item.name;
                this.lastNameInsert = item.lastName;
                this.emailInsert = item.email;
                this.selectDepartments = item.departments;
                this.selectUserTypes = item.userTypes;
                this.update = true;
            }
        }
        private editItem(item: UserAdmin): void {
            this.item = item;
            this.openDialog(item);
        }
        private async storeItem(): Promise<void> {
            let item = new UserAdmin();
            item.name = this.nameInsert;0
            //this.$store.dispatch('storeTask',item);
            console.log(item);
        }
        private async updateItem(item: UserAdmin): Promise<void> {
            let itemUpdate = new UserAdmin();
            itemUpdate.id = this.item.id;
            itemUpdate.name = this.nameInsert;
            itemUpdate.lastName = this.lastNameInsert;
            itemUpdate.email = this.emailInsert;
            itemUpdate.departments = this.selectDepartments;
            itemUpdate.userTypes = this.selectUserTypes;
            itemUpdate.userName = item.userName;
            itemUpdate.deleted_at = item.deleted_at;
            itemUpdate.updated_at = item.updated_at;
            itemUpdate.created_at = item.created_at;
            this.$store.dispatch('updateUserAdmin',itemUpdate);
        }
        private deleteItem(item: UserAdmin): void {
            this.dialogDelete = false;
            this.$store.dispatch('deleteUser',item);
        }
        private restoreItem(item: UserAdmin): void {
            let itemRestore = new UserAdmin();
            itemRestore.id = item.id;
            itemRestore.name = item.name;
            itemRestore.lastName = item.lastName;
            itemRestore.email = item.email;
            itemRestore.userName = item.userName;
            itemRestore.deleted_at = null;
            itemRestore.updated_at = item.updated_at;
            itemRestore.created_at = item.created_at;
            itemRestore.userTypes = item.userTypes;
            itemRestore.departments = item.departments;
            this.dialogDelete = false;
            this.$store.dispatch('updateUserAdmin',itemRestore);
        }
        openDialogDeleteItem(item: UserAdmin) {
            this.item = item;
            this.dialogDelete = true;
        }

        private async mounted() {
            await this.$store.dispatch('retrieveDepartmentsAdmin');
            await this.$store.dispatch('retrieveUserTypesAdmin');
            await this.$store.dispatch('retrieveUsersAdmin');
        }
    }
</script>

<style scoped lang="scss">

</style>
