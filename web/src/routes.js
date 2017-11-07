import Login from './views/Login.vue'
import NotFound from './views/404.vue'
import Home from './views/Home.vue'
import Main from './views/Main.vue'

import PermissionsManage from './views/system/PermissionsManage.vue'
import DepartmentManage from './views/system/DepartmentManage.vue'
import RoleManage from './views/system/RoleManage.vue'
import UserMnage from './views/system/UserMnage.vue'
import PositionManage from './views/system/PositionManage.vue'
import System from './views/system/System.vue'
import ModifyPwd from './views/ModifyPwd.vue'

var RoytesArray = new Map();
RoytesArray.set('PermissionsManage',PermissionsManage);
RoytesArray.set('PermissionsManage',PermissionsManage);
RoytesArray.set('DepartmentManage',DepartmentManage);
RoytesArray.set('RoleManage',RoleManage);
RoytesArray.set('UserMnage',UserMnage);
RoytesArray.set('PositionManage',PositionManage);
RoytesArray.set('System',System);
RoytesArray.set('ModifyPwd',ModifyPwd);

let routes = [
    {
        path: '/login',
        component: Login,
        name: '',
        leaf: false,
        hidden: true
    },
    {
        path: '/404',
        component: NotFound,
        name: '',
        leaf: false,
        hidden: true
    },
    {
        path: '/',
        component: Home,
        name: '欢迎页',
        hidden: true,
        leaf: false,
        children: [
            { path: '/Main', component: Main, name: '主页', hidden: true }
        ]
    },
    /*{
        path: '/',
        component: Home,
        name: '系统设置',
        leaf: false,
        children: [
            { path: '/System', component: System, name: '系统管理' },
            { path: '/PermissionsManage', component: PermissionsManage, name: '模块列表' },
            { path: '/RoleManage', component: RoleManage, name: '角色管理' },
            { path: '/DepartmentManage', component: DepartmentManage, name: '部门管理' },
            { path: '/PositionManage', component: PositionManage, name: '职位管理' },
            { path: '/UserMnage', component: UserMnage, name: '用户管理' }
        ]
    },
    {
        path: '/',
        component: Home,
        name: '修改密码',
        leaf: true,
        children: [
            { path: '/ModifyPwd', component: ModifyPwd, name: '修改密码' }
        ]
    },*/
    {
        path: '*',
        hidden: true,
        leaf: false,
        redirect: { path: '/404' }
    }
];

let menu = JSON.parse(sessionStorage.getItem('menu'));

if(Array.isArray(menu)){

    var routes_array = [];
    //根部
    menu.map(function(item,index){

        //只有菜单权限才添加到导航中
        if(item.type != '2')
            return;

        var tree = {
            path: "/",
            component: Home,
            name: item.name,
            leaf: false
        };

        if(item.sub_menu != null && item.sub_menu.length > 0){
            //叶子
            tree.children = [];
            item.sub_menu.map(function(citem, cindex){
                var component = (citem.access_flags || '').substring(1);
                var leaf = {
                    path: citem.access_flags,
                    component: RoytesArray.get(component),
                    name: citem.name
                }
                tree.children.push(leaf);
            });
        }else{
            tree.leaf = true;
            var component = (item.access_flags || '').substring(1);
            tree.children = [{
                path: item.access_flags,
                component: RoytesArray.get(component),
                name: item.name
            }];
        }
        routes.push(tree);
    });
}

export default routes;