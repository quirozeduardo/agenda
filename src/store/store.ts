import Vue from 'vue';
import Vuex from 'vuex';
import UserRegister from "../objects/UserRegister";
import UrlMaster from "../UrlMaster";
import UserLogin from "../objects/UserLogin";
import router from "../router";
import Category from "../objects/types/Category";
import ImpactObject from "../objects/types/Impact";
import State from "./State";
import Impact from "../objects/types/Impact";
import Priority from "../objects/types/Priority";
import Status from "../objects/types/Status";
import Task from "../objects/types/Task";

Vue.use(Vuex);

export default new Vuex.Store({
  state: new State(),
  mutations: {
        SET_ACCESS_TOKEN(state, token: string){
        state.accessToken = token;
        },
        SET_USER_EMAIL(state, email: string){
        state.userEmail = email;
        },
        SET_LOGGED(state, value: boolean){
        state.logged = value;
        },

        //CATEGORIES
        SET_CATEGORIES(state, data: Category[]) {
        state.categories = data;
        },
        ADD_CATEGORY(state, data: Category) {
        state.categories.push(data);
        },
        REMOVE_CATEGORY(state, id: number) {
            let result = state.categories.filter((item: Category) => { return item.id === id});
            if (result.length > 0) {
                let index = state.categories.indexOf(result[0]);
                state.categories.splice(index, 1);
            }
        },
        UPDATE_CATEGORY(state, data: Category) {
          let result = state.categories.filter((item: Category) => { return item.id === data.id});
          if (result.length > 0) {
              let index = state.categories.indexOf(result[0]);
              state.categories[index].name = data.name;
              state.categories[index].description = data.description;
          }
        },

        //IMPACTS
        SET_IMPACTS(state, data: Impact[]) {
          state.impacts = data;
        },
        ADD_IMPACT(state, data: Impact) {
          state.impacts.push(data);
        },
        REMOVE_IMPACT(state, id: number) {
          let result = state.impacts.filter((item: Impact) => { return item.id === id});
          if (result.length > 0) {
              let index = state.impacts.indexOf(result[0]);
              state.impacts.splice(index, 1);
          }
        },
        UPDATE_IMPACT(state, data: Impact) {
          let result = state.impacts.filter((item: Impact) => { return item.id === data.id});
          if (result.length > 0) {
              let index = state.impacts.indexOf(result[0]);
              state.impacts[index].name = data.name;
              state.impacts[index].description = data.description;
          }
        },

        //PRIORITIES
        SET_PRIORITIES(state, data: Priority[]) {
          state.priorities = data;
        },
        ADD_PRIORITY(state, data: Priority) {
          state.priorities.push(data);
        },
        REMOVE_PRIORITY(state, id: number) {
          let result = state.priorities.filter((item: Priority) => { return item.id === id});
          if (result.length > 0) {
              let index = state.priorities.indexOf(result[0]);
              state.priorities.splice(index, 1);
          }
        },
        UPDATE_PRIORITY(state, data: Priority) {
          let result = state.priorities.filter((item: Priority) => { return item.id === data.id});
          if (result.length > 0) {
              let index = state.priorities.indexOf(result[0]);
              state.priorities[index].name = data.name;
              state.priorities[index].description = data.description;
          }
        },

        //STATUSES
        SET_STATUSES(state, data: Status[]) {
          state.statuses = data;
        },
        ADD_STATUS(state, data: Status) {
          state.statuses.push(data);
        },
        REMOVE_STATUS(state, id: number) {
          let result = state.statuses.filter((item: Status) => { return item.id === id});
          if (result.length > 0) {
              let index = state.statuses.indexOf(result[0]);
              state.statuses.splice(index, 1);
          }
        },
        UPDATE_STATUS(state, data: Status) {
          let result = state.statuses.filter((item: Status) => { return item.id === data.id});
          if (result.length > 0) {
              let index = state.statuses.indexOf(result[0]);
              state.statuses[index].name = data.name;
              state.statuses[index].description = data.description;
          }
        },

      //TASKS
        SET_TASKS(state, data: Task[]){
            state.tasks = data;
        }
  },
  actions: {
        async registerUser(context, data: UserRegister): Promise<boolean> {
          let success = false;
          try {
            let response = await Vue.axios.post(UrlMaster.URL_AUTH,{
              action: 'store',
              section: 'register',
              data: {
                name: data.name,
                lastName: data.lastName,
                userName: data.userName,
                email: data.email,
                password: data.password
              }
            });
            if (response.data.response === 'success') {
              success = true;
              let userLogin = new UserLogin();
              userLogin.userName = data.userName;
              userLogin.email = data.email;
              userLogin.password = data.password;
              await context.dispatch('login', userLogin);
            }
          }catch (e) {
            success = false;
          }
          return  success;
        },
        async login(context, data: UserLogin): Promise<string|null> {
          let token = null;
          try {
            let response = await Vue.axios.post(UrlMaster.URL_AUTH,{
              action: 'auth',
              section: 'login',
              data: {
                email: data.email,
                password: data.password
              }
            });
            if (response.data.response !== null) {
              token = response.data.response;
              localStorage.setItem('access_token',token);
              localStorage.setItem('user_email',data.email);
              context.commit('SET_ACCESS_TOKEN', token);
              context.commit('SET_USER_EMAIL', data.email);
              context.commit('SET_LOGGED', true);
            }
          }catch (e) {

          }
          return token;
        },
        async loggedIn(context): Promise<boolean> {
          try {
            let response = await Vue.axios.post(UrlMaster.URL_AUTH,{
              action: 'auth',
              section: 'verifyRememberToken',
              data: {
                token: context.state.accessToken,
                email: context.state.userEmail,
              }
            });
            if (response.data.response === true) {
              context.commit('SET_LOGGED', true);
              return true;
            }
          }catch (e) {

          }
          context.commit('SET_LOGGED', false);
          return false;
        },
        async logout(context): Promise<void> {
          try {
            let response = await Vue.axios.post(UrlMaster.URL_AUTH,{
              action: 'auth',
              section: 'logout',
              data: {
                email: context.state.userEmail,
              }
            });
            localStorage.setItem('access_token','');
            localStorage.setItem('user_email','');
            context.commit('SET_ACCESS_TOKEN', '');
            context.commit('SET_USER_EMAIL', '');
            context.commit('SET_LOGGED', false);
          }catch (e) {

          }
        },

        async storeCategory(context, data: Category):Promise<void> {
          try {
            let response = await Vue.axios.post(UrlMaster.URL_STORE_DATA,{
              action: 'store',
              section: 'storeCategory',
              data: data
            });
            let responseData = response.data.response;
            if (responseData !== null) {
              let category = new Category();
              category.id = Number(responseData.id);
              category.name = responseData.name;
              category.description = responseData.description;
              context.commit('ADD_CATEGORY',category);
            }
          }catch (e) {

          }
        },
        async retrieveCategories(context): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_RETRIEVE_DATA,{
            action: 'retrieve',
            section: 'retrieveCategories',
            data: null
          });
          let categories = [];
          let responseData = response.data.response;
          for (let i = 0; i < responseData.length; i++) {
            let object = responseData[i];
            let category = new Category();
            category.id = Number(object.id);
            category.name = object.name;
            category.description = object.description;
            categories.push(category);
          }
          context.commit('SET_CATEGORIES',categories);
          },
        async updateCategory(context, data: Category): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_UPDATE_DATA,{
              action: 'update',
              section: 'updateCategory',
              data: data
          });
          if (response.data.response === true) {
              context.commit('UPDATE_CATEGORY',data);
          }
        },
        async deleteCategory(context, data: Category): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_DELETE_DATA,{
              action: 'delete',
              section: 'deleteCategory',
              data: data
          });
          if (response.data.response === true) {
              context.commit('REMOVE_CATEGORY',data.id);
          }
        },

        async storeImpact(context, data: Impact):Promise<void> {
          try {
              let response = await Vue.axios.post(UrlMaster.URL_STORE_DATA,{
                  action: 'store',
                  section: 'storeImpact',
                  data: data
              });
              let responseData = response.data.response;
              if (responseData !== null) {
                  let object = new Impact();
                  object.id = Number(responseData.id);
                  object.name = responseData.name;
                  object.description = responseData.description;
                  context.commit('ADD_IMPACT',object);
              }
          }catch (e) {

          }
        },
        async retrieveImpacts(context): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_RETRIEVE_DATA,{
              action: 'retrieve',
              section: 'retrieveImpacts',
              data: null
          });
          let objects = [];
          let responseData = response.data.response;
          for (let i = 0; i < responseData.length; i++) {
              let object = responseData[i];
              let obj = new Impact();
              obj.id = Number(object.id);
              obj.name = object.name;
              obj.description = object.description;
              objects.push(obj);
          }
          context.commit('SET_IMPACTS',objects);
        },
        async updateImpact(context, data: Impact): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_UPDATE_DATA,{
              action: 'update',
              section: 'updateImpact',
              data: data
          });
          if (response.data.response === true) {
              context.commit('UPDATE_IMPACT',data);
          }
        },
        async deleteImpact(context, data: Impact): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_DELETE_DATA,{
              action: 'delete',
              section: 'deleteImpact',
              data: data
          });
          if (response.data.response === true) {
              context.commit('REMOVE_IMPACT',data.id);
          }
        },

        async storePriority(context, data: Priority):Promise<void> {
          try {
              let response = await Vue.axios.post(UrlMaster.URL_STORE_DATA,{
                  action: 'store',
                  section: 'storePriority',
                  data: data
              });
              let responseData = response.data.response;
              if (responseData !== null) {
                  let object = new Priority();
                  object.id = Number(responseData.id);
                  object.name = responseData.name;
                  object.description = responseData.description;
                  context.commit('ADD_PRIORITY',object);
              }
          }catch (e) {

          }
        },
        async retrievePriorities(context): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_RETRIEVE_DATA,{
              action: 'retrieve',
              section: 'retrievePriorities',
              data: null
          });
          let objects = [];
          let responseData = response.data.response;
          for (let i = 0; i < responseData.length; i++) {
              let object = responseData[i];
              let obj = new Priority();
              obj.id = Number(object.id);
              obj.name = object.name;
              obj.description = object.description;
              objects.push(obj);
          }
          context.commit('SET_PRIORITIES',objects);
        },
        async updatePriority(context, data: Priority): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_UPDATE_DATA,{
              action: 'update',
              section: 'updatePriority',
              data: data
          });
          if (response.data.response === true) {
              context.commit('UPDATE_PRIORITY',data);
          }
        },
        async deletePriority(context, data: Priority): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_DELETE_DATA,{
              action: 'delete',
              section: 'deletePriority',
              data: data
          });
          if (response.data.response === true) {
              context.commit('REMOVE_PRIORITY',data.id);
          }
        },

        async storeStatus(context, data: Status):Promise<void> {
          try {
              let response = await Vue.axios.post(UrlMaster.URL_STORE_DATA,{
                  action: 'store',
                  section: 'storeStatus',
                  data: data
              });
              let responseData = response.data.response;
              if (responseData !== null) {
                  let object = new Status();
                  object.id = Number(responseData.id);
                  object.name = responseData.name;
                  object.description = responseData.description;
                  context.commit('ADD_STATUS',object);
              }
          }catch (e) {

          }
        },
        async retrieveStatuses(context): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_RETRIEVE_DATA,{
              action: 'retrieve',
              section: 'retrieveStatuses',
              data: null
          });
          let objects = [];
          let responseData = response.data.response;
          for (let i = 0; i < responseData.length; i++) {
              let object = responseData[i];
              let obj = new Status();
              obj.id = Number(object.id);
              obj.name = object.name;
              obj.description = object.description;
              objects.push(obj);
          }
          context.commit('SET_STATUSES',objects);
        },
        async updateStatus(context, data: Status): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_UPDATE_DATA,{
              action: 'update',
              section: 'updateStatus',
              data: data
          });
          if (response.data.response === true) {
              context.commit('UPDATE_STATUS',data);
          }
        },
        async deleteStatus(context, data: Status): Promise<void> {
          let response = await Vue.axios.post(UrlMaster.URL_DELETE_DATA,{
              action: 'delete',
              section: 'deleteStatus',
              data: data
          });
          if (response.data.response === true) {
              context.commit('REMOVE_STATUS',data.id);
          }
        },
        async retrieveTasks(context): Promise<void> {
            let response = await Vue.axios.post(UrlMaster.URL_RETRIEVE_DATA,{
                action: 'retrieve',
                section: 'retrieveTasks',
                data: null
            });
            let objects = [];
            let responseData = response.data.response;
            for (let i = 0; i < responseData.length; i++) {
                let object = responseData[i];
                let obj = new Task();
                obj.id = Number(object.id);
                obj.name = object.name;
                obj.description = object.description;
                obj.status = object.status;
                obj.assignedUser = object.assigned_user;
                obj.assignedByUser = object.assigned_by;
                obj.impact = object.impact;
                obj.category = object.category;
                obj.priority = object.priority;
                obj.comments = object.comments;
                obj.timeToSolve = object.time_to_solve;
                obj.created_at = object.created_at;
                obj.update_at = object.update_at;
                obj.deleted_at = object.deleted_at;

                objects.push(obj);
            }
            context.commit('SET_TASKS',objects);
        }
    },
});

