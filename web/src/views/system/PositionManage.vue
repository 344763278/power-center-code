<!-- 职位管理 -->
<template>
    <section>
        <!--工具条-->
        <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
            <el-form :inline="true" :model="filters">
                <el-form-item lable="部门">
                    <el-select v-model="filters.department_id" placeholder="请选择">
                        <el-option label="全部" value="0"></el-option>
                        <el-option v-for="item in department_list" :key="item.department_id" :label="item.name" :value="item.department_id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item lable="名称">
                    <el-input v-model="filters.search_word" placeholder="名称"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" v-on:click="getData">查询</el-button>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" @click="handleAdd">新增职位</el-button>
                </el-form-item>
            </el-form>
        </el-col>

        <!--列表-->
        <el-table :data="datalist" highlight-current-row v-loading="listLoading" style="width: 100%;">
            <el-table-column prop="name" label="职位名称" width="200" >
            </el-table-column>
            <el-table-column prop="department_name" label="所属部门" width="180">
            </el-table-column>
            <el-table-column prop="desc" label="描述" min-width="200">
            </el-table-column>
            <el-table-column prop="create_time" label="添加时间" width="200">
            </el-table-column>
            <el-table-column prop="create_name" label="添加人" width="200">
            </el-table-column>
            <el-table-column label="操作" width="150">
                <template scope="scope">
                    <el-button :plain="true" type="info" size="small" @click="handleEdit(scope.row.position_id)">编辑</el-button>
                    <el-button :plain="true" type="danger" size="small" @click="handleDel(scope.row.position_id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!--工具条-->
        <el-col :span="24" class="toolbar">
            <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="pageSize" :total="total" style="float:right;">
            </el-pagination>
        </el-col>

        <!--编辑界面-->
        <el-dialog title="编辑职位" size="small" v-model="editFormVisible" :close-on-click-modal="false">
            <el-form :model="editForm" label-width="80px" :rules="editFormRules" ref="editForm">
                <el-row :gutter="20">
                    <el-col :span="10">
                        <div class="grid-content bg-purple">
                            <el-form-item label="ID" prop="position_id">
                                <el-input v-model="editForm.position_id" :disabled="true" auto-complete="off"></el-input>
                            </el-form-item>
                            <el-form-item label="所属部门" prop="department_id">
                                <el-select v-model="editForm.department_id" placeholder="请选择">
                                    <el-option v-for="item in department_list" :key="item.department_id" :label="item.name" :value="item.department_id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="职位名称" prop="name">
                                <el-input v-model="editForm.name" :maxlength="10" auto-complete="off"></el-input>
                            </el-form-item>
                            <el-form-item label="描述">
                                <el-input type="textarea" :maxlength="50" v-model="editForm.desc"></el-input>
                            </el-form-item>
                        </div>
                    </el-col>
                    <el-col :span="10">
                        <el-col :span="24" class="system-tree" :key="item.system_id" v-for="(item,index) in system_array">
                            <div class="tree-titel">{{ item.name }}</div>
                            <div class="tree-content">
                                <el-radio-group v-model="select_array[index].role_id">
                                    <ul>
                                        <li v-for="citem in item.role_list">
                                            <el-radio :key="citem.id" :label="citem.role_id">{{citem.name}}</el-radio>
                                        </li>
                                    </ul>
                                </el-radio-group>
                            </div>
                        </el-col>
                    </el-col>
                </el-row>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
            </div>
        </el-dialog>

        <!--新增界面-->
        <el-dialog title="新增职位" size="small" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-row :gutter="24">
                    <el-col :span="12">
                        <div class="grid-content bg-purple">
                            <el-form-item label="所属部门" prop="department_id">
                                <el-select v-model="addForm.department_id" placeholder="请选择">
                                    <el-option v-for="item in department_list" :key="item.department_id" :label="item.name" :value="item.department_id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="职位名称" prop="name">
                                <el-input v-model="addForm.name" :maxlength="10" auto-complete="off"></el-input>
                            </el-form-item>
                            <el-form-item label="描述">
                                <el-input type="textarea" :maxlength="50" v-model="addForm.desc"></el-input>
                            </el-form-item>
                        </div>
                    </el-col>
                    <el-col :span="12">
                        <el-col :span="24" class="system-tree" :key="item.system_id" v-for="(item,index) in system_array">
                            <div class="tree-titel">{{ item.name }}</div>
                            <div class="tree-content">
                                <el-radio-group v-model="select_array[index].role_id">
                                    <ul>
                                        <li v-for="citem in item.role_list">
                                            <el-radio :key="citem.id" :label="citem.role_id">{{citem.name}}</el-radio>
                                        </li>
                                    </ul>
                                </el-radio-group>
                            </div>
                        </el-col>
                    </el-col>
                </el-row>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="addFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="addSubmit" :loading="addLoading">提交</el-button>
            </div>
        </el-dialog>

    </section>
</template>

<script>
    import util from '../../common/js/util'
    import constant from '../../common/js/constant'
    import { getPosition, addPosition, editPosition, getAllDepartment, removePosition, getAllSystem, getPositionInfo } from '../../api/api';

    const validation = {
        name: [
            { required: true, message: '请输入职位名称', trigger: 'blur' }
        ]
    }

    export default {
        data() {
            return {
                filters: {
                    search_word: '',
                    department_id:'0'
                },

                datalist:[],
                total: 0,
                page: 1,
                pageSize:constant.pageSize,
                listLoading: false,

                editFormVisible: false,//编辑界面是否显示
                editLoading: false,
                editFormRules:validation,
                //编辑界面数据
                editForm: {
                    position_id: '',
                    department_id:'',
                    pid:'',
                    name: '',
                    desc:'',
                    roles:[]
                },

                addFormVisible: false,//新增界面是否显示
                addLoading: false,
                addFormRules: validation,
                //新增界面数据
                addForm: {
                    pid:"",
                    department_id:'',
                    name:"",
                    desc:"",
                    roles:[]
                },
                department_list:[],
                system_array:[],
                select_array:[]
            }
        },
        methods: {
            getSelectRole(){
                let data = [];
                this.select_array.map(function(item,index){
                    if(item.role_id != ''){
                        data.push(item);
                    }
                });
                return data;
            },
            handleCurrentChange(val) {
                this.page = val;
                this.getData();
            },
            getSystem(){
                getAllSystem({}).then(res =>{
                    if(res.status){
                        var endVal = [];
                        (res.data.lists || []).map(function(item, index){
                            endVal.push({
                                system_id:item.system_id,
                                role_id:''
                            });
                        });
                        this.select_array = endVal;
                        this.system_array = res.data.lists;
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            },
            getAllDepartment(){
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
            getData() {
                let para = {
                    page: this.page,
                    page_size: this.pageSize,
                    search_word: this.filters.search_word,
                    department_id: this.filters.department_id
                };
                this.listLoading = true;
                getPosition(para).then((res) => {
                    if(res.status){
                        this.total = parseInt(res.data.total || 0,10);
                        this.datalist = res.data.list;
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                    this.listLoading = false;
                });
            },
            getSystem_Role(keys){
                let system_role = [];
                (keys || []).map(function(item,index){
                    let strArray = item.split('$');
                    system_role.push({
                        system_id: strArray[0],
                        role_id: strArray[1]
                    });
                });
                return system_role;
            },
            //删除
            handleDel(id) {
                this.$confirm('确认删除该记录吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    var para = {
                        position_id: id
                    };
                    removeDepartment(para).then(res =>{
                        if(res.status){
                            this.$message({
                                message: '删除成功',
                                type: 'success'
                            });
                            this.getData();
                        }else{
                            this.$message({
                                message: res.retinfo,
                                type: 'error'
                            });
                        }
                    });
                }).catch(() => {

                });
            },
            //显示编辑界面
            handleEdit(id) {
                getPositionInfo({position_id: id}).then(res => {
                    if(res.status){
                        this.editForm = {
                            position_id: res.data.position_id,
                            name: res.data.name,
                            desc: res.data.desc,
                            department_id: res.data.department_id
                        }
                        this.select_array.map(function(item,index){
                            item.role_id = "";
                            res.data.system_role.map(function(c_item,c_index){
                                if(item.system_id == c_item.system_id){
                                    item.role_id = c_item.role_id;
                                }
                            });
                        });
                        console.log(this.select_array);
                        this.editFormVisible = true;
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            },
            //显示新增界面
            handleAdd() {
                this.addFormVisible = true;
                this.addForm = {
                    name: '',
                    department_id:'',
                    desc:''
                };
                let temArray = this.select_array;
                temArray.map(function(item,index){
                    item.role_id = '';
                });
                this.select_array = temArray;
            },
            //编辑
            editSubmit() {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editLoading = true;
                            let para = Object.assign({}, this.editForm);
                            //para.department_id = this.filters.department_id;
                            para.system_role = this.getSelectRole();
                            if(para.system_role.length == 0){
                                this.$message({
                                    message: '请选择改职位对应的角色',
                                    type: 'error'
                                });
                            }
                            editPosition(para).then( res => {
                                if( res.status ){
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
            //新增
            addSubmit() {
                this.$refs.addForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.addLoading = true;
                            let para = Object.assign({}, this.addForm);
                            para.system_role = this.getSelectRole();
                            if(para.system_role.length == 0){
                                this.$message({
                                    message: '请选择改职位对应的角色',
                                    type: 'error'
                                });
                            }
                            addPosition(para).then(res => {
                                if(res.status){
                                    this.$refs['addForm'].resetFields();
                                    this.addFormVisible = false;
                                    this.getData();
                                }else{
                                    this.$message({
                                        message: res.retinfo,
                                        type: 'error'
                                    });
                                }
                                this.addLoading = false;
                            });
                        });
                    }
                });
            }
        },
        mounted() {
            this.filters.department_id = (this.$route.params.Id == null ? '0' : this.$route.params.Id);
            this.getData();
            this.getSystem();
            this.getAllDepartment();
        }
    }
</script>

<style scoped>
    .system_tree{}

    .tree-titel{
        line-height: 30px;
        font-weight: bold;
    }

    .tree-content ul li{
        list-style: none;
        width: 100%;
        margin:0;
        padding: 0;
        line-height: 25px;
    }
</style>