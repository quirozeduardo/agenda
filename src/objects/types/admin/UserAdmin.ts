import User from "../User";
import {Department} from "./Department";
import {UserType} from "./UserType";

export class UserAdmin extends User{
    private _departments: Department[] = [];
    private _userTypes: UserType[] = [];
    private _password: string = '';

    get departments(): Department[] {
        return this._departments;
    }

    set departments(value: Department[]) {
        this._departments = value;
    }


    get userTypes(): UserType[] {
        return this._userTypes;
    }

    set userTypes(value: UserType[]) {
        this._userTypes = value;
    }

    get password(): string {
        return this._password;
    }

    set password(value: string) {
        this._password = value;
    }
}
