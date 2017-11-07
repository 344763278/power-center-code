<template>
    <section>
        <!--工具条-->
        <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
            <el-form :inline="true" :model="filters">
                <el-form-item>
                    <el-input v-model="filters.search_word" placeholder="系统账号/真实姓名"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" v-on:click="getData">查询</el-button>
                    <el-button :plain="true" type="info" @click="usersyncBoss" >同步BOSS账号</el-button>
                </el-form-item>
            </el-form>
        </el-col>

        <!--列表-->
        <el-table :data="datalist" highlight-current-row v-loading="listLoading" style="width: 100%;">
            <el-table-column prop="username" label="系统账户" width="120">
            </el-table-column>
            <el-table-column prop="real_name" label="真实姓名" width="100" >
            </el-table-column>
            <el-table-column prop="department_name" label="所属部门" width="100">
            </el-table-column>
            <el-table-column prop="position_name" label="职位" >
            </el-table-column>
            <el-table-column prop="is_lock" label="状态" :formatter="formatStatus">
            </el-table-column>
            <el-table-column prop="create_time" label="添加时间" >
            </el-table-column>
            <el-table-column prop="create_name" label="添加人" width="180" >
            </el-table-column>
            <el-table-column label="操作" width="250">
                <template scope="scope">
                    <el-button size="small" @click="handleEdit(scope.row.user_id)">编辑</el-button>
                    <el-button size="small" @click="resetPwd(scope.row.user_id)" >重置密码</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!--工具条-->
        <el-col :span="24" class="toolbar">
            <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="pageSize" :total="total" style="float:right;">
            </el-pagination>
        </el-col>

        <!--编辑界面-->
        <el-dialog title="编辑用户" v-model="editFormVisible" :close-on-click-modal="false">
            <el-form :model="editForm" label-width="80px" :rules="editFormRules" ref="editForm">
                <el-form-item label="所属部门" prop="department_id">
                    <el-select v-model="editForm.department_id" placeholder="请选择">
                        <el-option v-for="item in department_list" :key="item.department_id" :label="item.name" :value="item.department_id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="所属职位" prop="position_id">
                    <el-select v-model="editForm.position_id" placeholder="请选择">
                        <el-option v-for="item in position_list" :key="item.position_id" :label="item.name" :value="item.position_id">
                        </el-option>
                    </el-select>
                </el-form-item>
               <!--  <el-form-item label="所属城市" prop="city_id" >
                   <el-radio-group v-model="editForm.city_id">
                       <el-radio class="radio" v-for="item in city_array" :key="item.id" :label="item.id">{{item.name}}</el-radio>
                   </el-radio-group>
               </el-form-item> -->
                <el-form-item label="用户状态" prop="is_lock" >
                    <el-radio-group v-model="editForm.is_lock">
                        <el-radio class="radio" v-for="item in user_status_array" :key="item.id" :label="item.id">{{item.name}}</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="用户描述" prop="user_desc">
                    <el-input v-model="editForm.user_desc" :maxlength="10" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="手机号码" prop="phone">
                    <el-input v-model="editForm.phone" :maxlength="11" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input v-model="editForm.email" :maxlength="30" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
            </div>
        </el-dialog>
    </section>
</template>

<script>
    import util from '../../common/js/util'
    import constant from '../../common/js/constant'
    import { getUserList, getUserInfo, editUser, getAllDepartment, getPositionByDID, resetPwd, usersyncBoss } from '../../api/api';

    const validation = {
        department_id:[
            { required: true, message: '请选择所属部门', trigger: 'blur' }
        ],
        position_id:[
            { required: true, message: '请选择所属职位', trigger: 'blur' }
        ],
        email:[
            { type: 'email', message: '请输入正确的邮箱地址', trigger: 'blur' }
        ]
    };

    export default {
        data() {
            return {
                filters: {
                    search_word: ''
                },
                datalist: [],
                total: 0,
                page: 1,
                listLoading: false,

                editFormVisible: false,//编辑界面是否显示
                editLoading: false,
                editFormRules: validation,
                pageSize:constant.pageSize,
                //编辑界面数据
                editForm: {
                    user_id:"",
                    real_name:"",
                    department_id:"",
                    position_id:"",
                    city_id:"",
                    user_desc:"",
                    phone:"",
                    email:""
                },
                city_array:constant.use.cityArray,
                department_list:[],
                position_list:[],
                user_status_array:constant.use.userStatus
            }
        },
        methods: {
            formatStatus(row){
                var strVal = '--';
                this.user_status_array.map(function(item,index){
                    strVal =  (item.id == row.is_lock) ? item.name : strVal;
                });
                return strVal;
            },
            handleCurrentChange(val){
                this.page = val;
                this.getData();
            },
            getDepartment(){
                getAllDepartment({}).then(res =>{
                    if(res.status){
                        this.department_list = res.data.lists;
                    }else{
                       this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            },
            getPosition(){
                var para = {
                    department_id: this.editForm.department_id
                };
                getPositionByDID(para).then(res =>{
                    if(res.status){
                        this.position_list = res.data.lists;
                    }else{
                       this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            },
            getData() {
                let para = {
                    page: this.page,
                    name: this.filters.name,
                    search_word: this.filters.search_word
                };
                this.listLoading = true;
                getUserList(para).then((res) => {
                    if(res.status){
                        this.datalist = res.data.list;
                        this.total = parseInt(res.data.total || 0,10);
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                    this.listLoading = false;
                });
            },
            //显示编辑界面
            handleEdit(id) {
                getUserInfo({user_id: id}).then(res =>{
                    if(res.status){
                        let o = res.data;
                        this.editForm = {
                            user_id:o.user_id,
                            real_name:o.real_name,
                            department_id:o.department_id,
                            position_id:o.position_id,
                            city_id:'1',
                            user_desc:o.user_desc,
                            phone:o.phone,
                            email:o.email,
                            is_lock:o.is_lock
                        }
                        this.editFormVisible = true;
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            },
            //编辑
            editSubmit() {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            let para = Object.assign({}, this.editForm);
                            if(/^([0-9]){7,11}$/.test(para.phone) == false){
                                this.$message({
                                    message: '请输入正确的手机号码',
                                    type: 'error'
                                });
                                return false;
                            }
                            this.editLoading = true;
                            editUser(para).then((res) => {
                                if(res.status){
                                    this.$message({
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    this.$refs['editForm'].resetFields();
                                    this.editFormVisible = false;
                                    this.getData();
                                }else{
                                    this.$message({
                                        message: res.retinfo,
                                        type: 'error'
                                    });
                                }
                                this.editLoading = false;
                            });
                        });
                    }
                });
            },
            //重置密码
            resetPwd(user_id){

                this.$confirm('确认重置该账号的密码吗？', '提示', {}).then(() => {
                    var para = {
                        user_id: user_id
                    }
                    resetPwd(para).then(res => {
                        if(res.status){
                            var msg = '重置密码成功，新密码：'+res.data.password;
                            this.$alert(msg, '重置密码', {
                                confirmButtonText: '确定',
                                callback: action => {
                                    console.log('重置密码成功');
                                }
                            });
                        }else{
                            this.$message({
                                message: res.retinfo,
                                type: 'error'
                            });
                        }
                    });
                });
            },
            //同步BOSS账号
            usersyncBoss(){
                usersyncBoss({}).then(res => {
                    if(res.status){
                        this.$message({
                            message: '同步成功',
                            type: 'success'
                        });
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            }
        },
        mounted() {
            this.getData();
            this.getDepartment();
        },
        watch:{
            'editForm.department_id'(val){
                this.getPosition();
            }
        }
    }

</script>

<style scoped>

</style>