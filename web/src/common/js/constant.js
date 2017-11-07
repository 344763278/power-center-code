export default {

    query: {
        status: [
            { id: '0', name: '全部' },
            { id: '1', name: '开启' },
            { id: '2', name: '关闭' }
        ],
        userStatus: [
            { id: '0', name: '全部' },
            { id: '1', name: '正常' },
            { id: '2', name: '锁定' }
        ]
    },

    use: {
        status: [
            { id: '1', name: '开启' },
            { id: '2', name: '锁定' }
        ],
        userStatus: [
            { id: '1', name: '正常' },
            { id: '2', name: '禁用' }
        ],
        modulType: [
            { id: '1', name: '权限' },
            { id: '2', name: '菜单' }
        ],
        cityArray: [
            { id: '1101', name: '北京' },
            { id: '4403', name: '深圳' }
        ]
    },
    pageSize: 20,

    resArray: [
        { retcode: '0', status: true, retinfo: '正确' }
    ],

    paramsHead: {
        msgtype: "request",
        remark: "",
        version: "0.01"
    },

    //当前系统标识符
    current_system_id: '1',

    //特殊字符串的验证
    generalStr: (rule, value, callback) => {
        if (value == '') {
            callback(new Error('内容不能为空'));
        }
        callback();
    },

    arrayRemove(array, val) {
        let temArray = [];
        array.forEach((m, index) => {
            if (m != val) {
                temArray.push(m);
            }
        });
        return temArray;
    }
}