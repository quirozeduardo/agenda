export default class Category {
    private _id: number = 0;
    private _name: string = '';
    private _description: string = '';
    private _importance: number = 1;
    private _color: string = '';

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

    get importance(): number {
        return this._importance;
    }

    set importance(value: number) {
        this._importance = value;
    }

    get color(): string {
        return this._color;
    }

    set color(value: string) {
        this._color = value;
    }
}
