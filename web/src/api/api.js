import axios from 'axios';
import constant from '../common/js/constant';

const openApi = ['authlogin', 'authregistersystem'];
// const base = 'http://dev-amc.huishoubao.com.cn';
const base = 'http://api-amc.huishoubao.com.cn';

//发送请求
let sen_axaj = (apiName, apiUrl, params) => {
    let requestParams = {
        head: constant.paramsHead,
        params: {}
    };
    requestParams.head.interface = apiName;
    let url = base + apiUrl;

    //排除登陆接口
    if (!openApi.includes(apiName)) {
        let user_id = sessionStorage.getItem('user_id');
        let token = sessionStorage.getItem('token');
        if (!user_id || !token) {
            console.log('登陆超时');
            return false;
        }
        //添加统一参数
        params.login_user_id = user_id;
        params.login_token = token;
        params.login_system_id = constant.current_system_id;
    }
    requestParams.params = params;
    return axios.post(url, requestParams).then(res => res);
}

//结果处理
let res_factory = res => {
    if (res.status != 200 || res.data.body == null) return {
        status: false,
        retcode: '',
        retinfo: '系统异常'
    };
    let data = constant.resArray,
        resBody = res.data.body;
    //默认是错误的处理方式
    resBody.status = false;
    data.map(function(item, index) {
        if (item.retcode == resBody.retcode) {
            resBody.status = item.status;
            resBody.retinfo = item.retinfo;
        }
    });
    if (resBody.retcode == '10005') {
        window.location.href = "/#/login";
    }
    return resBody;
}

axios.interceptors.request.use(function(config) {
    return config;
}, function(err) {
    console.log(err);
    return Promise.reject(err);
});

axios.interceptors.response.use(function(res) {
    res = res_factory(res);
    return res;
}, function(err) {
    console.log(err);
    return Promise.reject(err);
});

/* 公共 API */
const requestLogin = params => { return sen_axaj('authlogin', '/authlogin', params); };
const regSystem = params => { return sen_axaj('authregistersystem', '/authregistersystem', params); };
const getLoginUser = params => { return sen_axaj('logininfo', '/logininfo', params); };
export const login = params => {

    //系统标识符
    params.system_id = constant.current_system_id;

    return requestLogin(params).then(data => {
        if (!data.status) {
            return data;
        }
        sessionStorage.setItem('user_id', data.data.user_id);
        sessionStorage.setItem('token', data.data.login_token);

        let params = {
            token: data.data.login_token,
            user_id: data.data.user_id,
            system_id: constant.current_system_id
        }
        return regSystem(params);
    }).then(data => {
        if (!data.status) {
            return data;
        }
        let params = {};
        return getLoginUser(params);
    });
}

export const getAllModul = params => { return sen_axaj('columnselect', '/columnselect', params); };
export const getAllSystem = params => { return sen_axaj('systemselect', '/systemselect', params); };

/* 系统 API */
export const getSystem = params => { return sen_axaj('systemlists', '/systemlists', params); };
export const addSystem = params => { return sen_axaj('systemadd', '/systemadd', params); };
export const editSystem = params => { return sen_axaj('systemedit', '/systemedit', params); };
export const getSystemInfo = params => { return sen_axaj('systeminfo', '/systeminfo', params); };
export const removeSystem = params => { return sen_axaj('systemdel', '/systemdel', params); };

//修改密码
export const editPwd = params => { return sen_axaj('editpassword', '/editpassword', params); };
export const resetPwd = params => { return sen_axaj('resetpassword','/resetpassword',params); };
export const usersyncBoss = params => { return sen_axaj('usersyncboss','/usersyncboss',params); };

/* 部门 API */
export const getAllDepartment = params => { return sen_axaj('departmentselect', '/departmentselect', params); };
export const getDepartment = params => { return sen_axaj('departmentlists', '/departmentlists', params); };
export const removeDepartment = params => { return sen_axaj('departmentdel', '/departmentdel', params); };
export const addDepartment = params => { return sen_axaj('departmentadd', '/departmentadd', params); };
export const editDepartment = params => { return sen_axaj('departmentedit', '/departmentedit', params); };

/* 职位 API */
export const getPositionByDID = params => { return sen_axaj('positionselect', '/positionselect', params); };
export const getPosition = params => { return sen_axaj('positionlists', '/positionlists', params); };
export const addPosition = params => { return sen_axaj('positionadd', '/positionadd', params); };
export const editPosition = params => { return sen_axaj('positionedit', '/positionedit', params); };
export const removePosition = params => { return sen_axaj('positiondel', '/positiondel', params); };
export const getPositionInfo = params => { return sen_axaj('positioninfo', '/positioninfo', params); };

/* 权限 API */
export const getAuthority = params => { return sen_axaj('authoritylists', '/authoritylists', params); };
export const addAuthority = params => { return sen_axaj('authorityadd', '/authorityadd', params); };
export const editAuthority = params => { return sen_axaj('authorityedit', '/authorityedit', params); };
export const removeAuthority = params => { return sen_axaj('authoritydel', '/authoritydel', params); };
export const getAuthorityTrre = params => { return sen_axaj('authoritytree', '/authoritytree', params); };

/* 角色 API */
export const getRole = params => { return sen_axaj('rolelists', '/rolelists', params); };
export const addRole = params => { return sen_axaj('roleadd', '/roleadd', params); };
export const eidtRole = params => { return sen_axaj('roleedit', '/roleedit', params); };
export const removeRole = params => { return sen_axaj('roledel', '/roledel', params); };
export const getRoleInfo = params => { return sen_axaj('roleinfo', '/roleinfo', params); };

/* 用户 API */
export const getUserList = params => { return sen_axaj('userlists', '/userlists', params); };
//export const removeUser = params => { return sen_axaj('userinfo', '/userinfo', params); };
export const getUserInfo = params => { return sen_axaj('userinfo', '/userinfo', params); };
export const editUser = params => { return sen_axaj('useredit', '/useredit', params); };
//export const addUser = params => { return sen_axaj('userinfo', '/userinfo', params); };