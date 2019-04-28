export default class User {
    private _id: number = 0;
    private _name: string = '';
    private _userName: string = '';
    private _lastName: string = '';
    private _email: string = '';
    private _created_at: string = '';
    private _updated_at: string = '';
    private _deleted_at: string|null = '';

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

    get userName(): string {
        return this._userName;
    }

    set userName(value: string) {
        this._userName = value;
    }

    get lastName(): string {
        return this._lastName;
    }

    set lastName(value: string) {
        this._lastName = value;
    }

    get email(): string {
        return this._email;
    }

    set email(value: string) {
        this._email = value;
    }

    get created_at(): string {
        return this._created_at;
    }

    set created_at(value: string) {
        this._created_at = value;
    }

    get updated_at(): string {
        return this._updated_at;
    }

    set updated_at(value: string) {
        this._updated_at = value;
    }

    get deleted_at(): string | null {
        return this._deleted_at;
    }

    set deleted_at(value: string | null) {
        this._deleted_at = value;
    }
}
