import Impact from "./Impact";
import Status from "./Status";
import Category from "./Category";
import Priority from "./Priority";
import {Department} from "./admin/Department";
import User from "./User";

export default class Task {
    private _id: number = 0;
    private _name: string = '';
    private _description: string = '';
    private _status: Status|null = null;
    private _assignedUser: User|null = null;
    private _assignedByUser: User|null = null;
    private _impact: Impact|null = null;
    private _category: Category|null = null;
    private _priority: Priority|null = null;
    private _department: Department|null = null;
    private _comments: string = '';
    private _timeToSolve: string = '';
    private _created_at: string = '';
    private _update_at: string = '';
    private _deleted_at: string = '';


    get status(): Status | null {
        return this._status;
    }

    set status(value: Status | null) {
        this._status = value;
    }

    get category(): Category | null {
        return this._category;
    }

    set category(value: Category | null) {
        this._category = value;
    }

    get id(): number {
        return this._id;
    }

    set id(value: number) {
        this._id = value;
    }

    get name(): string {
        return this._name;
    }

    set name(value: string) {
        this._name = value;
    }

    get description(): string {
        return this._description;
    }

    set description(value: string) {
        this._description = value;
    }


    get comments(): string {
        return this._comments;
    }

    set comments(value: string) {
        this._comments = value;
    }

    get timeToSolve(): string {
        return this._timeToSolve;
    }

    set timeToSolve(value: string) {
        this._timeToSolve = value;
    }

    get created_at(): string {
        return this._created_at;
    }

    set created_at(value: string) {
        this._created_at = value;
    }

    get update_at(): string {
        return this._update_at;
    }

    set update_at(value: string) {
        this._update_at = value;
    }

    get deleted_at(): string {
        return this._deleted_at;
    }

    set deleted_at(value: string) {
        this._deleted_at = value;
    }

    get assignedUser(): User | null {
        return this._assignedUser;
    }

    set assignedUser(value: User | null) {
        this._assignedUser = value;
    }

    get assignedByUser(): User | null {
        return this._assignedByUser;
    }

    set assignedByUser(value: User | null) {
        this._assignedByUser = value;
    }

    get impact(): Impact | null {
        return this._impact;
    }

    set impact(value: Impact | null) {
        this._impact = value;
    }

    get priority(): Priority | null {
        return this._priority;
    }

    set priority(value: Priority | null) {
        this._priority = value;
    }

    get department(): Department | null {
        return this._department;
    }

    set department(value: Department | null) {
        this._department = value;
    }
}
