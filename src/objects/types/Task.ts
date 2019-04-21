export default class Task {
    private _id: number = 0;
    private _name: string = '';
    private _description: string = '';
    private _status: string = '';
    private _assignedUser: string = '';
    private _assignedByUser: string = '';
    private _impact: string = '';
    private _category: string = '';
    private _priority: string = '';
    private _comments: string = '';
    private _timeToSolve: string = '';
    private _created_at: string = '';
    private _update_at: string = '';
    private _deleted_at: string = '';


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

    get status(): string {
        return this._status;
    }

    set status(value: string) {
        this._status = value;
    }

    get assignedUser(): string {
        return this._assignedUser;
    }

    set assignedUser(value: string) {
        this._assignedUser = value;
    }

    get assignedByUser(): string {
        return this._assignedByUser;
    }

    set assignedByUser(value: string) {
        this._assignedByUser = value;
    }

    get impact(): string {
        return this._impact;
    }

    set impact(value: string) {
        this._impact = value;
    }

    get category(): string {
        return this._category;
    }

    set category(value: string) {
        this._category = value;
    }

    get priority(): string {
        return this._priority;
    }

    set priority(value: string) {
        this._priority = value;
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
}
