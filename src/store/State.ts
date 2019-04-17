import Category from "../objects/types/Category";
import ImpactObject from "../objects/types/Impact";
import PriorityObject from "../objects/types/Priority";
import StatusObject from "../objects/types/Status";
import Task from "../objects/types/Task";

export default class State {
    public accessToken: string =  localStorage.getItem('access_token') ||  '';
    public userEmail: string = localStorage.getItem('user_email') ||  '';
    public logged: boolean = false;
    public categories: Category[] = [];
    public tasks: Task[] = [];
    public impacts: ImpactObject[] = [];
    public priorities: PriorityObject[] = [];
    public statuses: StatusObject[] = [];
}
