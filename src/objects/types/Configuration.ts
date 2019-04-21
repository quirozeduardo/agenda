export class Configuration {
    private _id: number = 0;
    private _key: string = '';
    private _value: string = '';


    get id(): number {
        return this._id;
    }

    set id(value: number) {
        this._id = value;
    }

    get key(): string {
        return this._key;
    }

    set key(value: string) {
        this._key = value;
    }

    get value(): string {
        return this._value;
    }

    set value(value: string) {
        this._value = value;
    }
}
