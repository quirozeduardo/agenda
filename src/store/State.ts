import Category from "../objects/types/Category";
import Impact from "../objects/types/Impact";
import Priority from "../objects/types/Priority";
import Status from "../objects/types/Status";
import Task from "../objects/types/Task";
import User from "../objects/types/User";
import {Configuration} from "../objects/types/Configuration";

export default class State {
    public accessToken: string =  localStorage.getItem('access_token') ||  '';
    public userEmail: string = localStorage.getItem('user_email') ||  '';
    public logged: boolean = false;
    public users: User[] = [];
    public loggedUser: User|null = null;
    public categories: Category[] = [];
    public tasks: Task[] = [];
    public impacts: Impact[] = [];
    public priorities: Priority[] = [];
    public statuses: Status[] = [];
    public configurations: Configuration[] = [];
}
