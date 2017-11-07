<!-- 模块管理 -->
<template>
    <section>
        <!--工具条-->
        <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
            <el-form :inline="true" :model="filters">
                <el-form-item lable="所属系统">
                    <el-select v-model="filters.system_id" placeholder="所属系统">
                        <el-option label="全部" value="0"></el-option>
                        <el-option v-for="item in system_array" :key="item.system_id" :label="item.name" :value="item.system_id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item lable="状态">
                    <el-select v-model="filters.status" placeholder="状态">
                        <el-option v-for="item in query_status_array" :key="item.id" :label="item.name" :value="item.id"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-input v-model="filters.search_word" placeholder="模块名称"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" v-on:click="getData">查询</el-button>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" @click="handleAdd">新增模块</el-button>
                </el-form-item>
            </el-form>
        </el-col>

        <!--列表-->
        <el-table :data="datalist" highlight-current-row v-loading="listLoading" style="width: 100%;">
            <el-table-column prop="system_name" label="所属系统" width="150">
            </el-table-column>
            <el-table-column prop="p_name" label="所属模块" width="180" :formatter="formatPName">
            </el-table-column>
            <el-table-column prop="name" label="模块名称" min-width="100">
            </el-table-column>
            <el-table-column prop="access_flags" label="链接" min-width="120">
            </el-table-column>
            <el-table-column prop="status" label="状态" width="100" :formatter="formatStatus">
            </el-table-column>
            <el-table-column prop="type" label="模块类型" width="100" :formatter="formatType">
            </el-table-column>
            <el-table-column prop="create_time" label="添加时间" width="150">
            </el-table-column>
            <el-table-column prop="create_name" label="添加人" width="150">
            </el-table-column>
            <el-table-column label="操作" width="150">
                <template scope="scope">
                    <el-button :plain="true" type="info" size="small" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                    <el-button :plain="true" type="danger" size="small" @click="handleDel(scope.row.authority_id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!--工具条-->
        <el-col :span="24" class="toolbar">
            <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="pageSize" :total="total" style="float:right;">
            </el-pagination>
        </el-col>

        <!--编辑界面-->
        <el-dialog title="编辑模块" size="tiny" v-model="editFormVisible" :close-on-click-modal="false">
            <el-form :model="editForm" label-width="80px" :rules="editFormRules" ref="editForm">
                <el-form-item label="ID" prop="authority_id">
                    <el-input v-model="editForm.authority_id" :disabled="true" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="所属系统" prop="system_id">
                    <el-select v-model="editForm.system_id" placeholder="请选择">
                        <el-option v-for="item in system_array" :key="item.system_id" :label="item.name" :value="item.system_id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="所属模块" prop="p_name">
                    <el-select v-model="editForm.p_name" placeholder="请选择">
                        <el-option v-for="item in modul_array" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="模块类型" prop="type">
                    <el-select v-model="editForm.type" placeholder="请选择">
                        <el-option v-for="item in modul_type" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="模块名称" prop="name">
                    <el-input v-model="editForm.name" :maxlength="20" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="链接" prop="access_flags">
                    <el-input v-model="editForm.access_flags" :maxlength="50" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-radio-group v-model="editForm.status">
                        <el-radio class="radio" v-for="item in status_array" :key="item.id" :label="item.id">{{item.name}}</el-radio>
                    </el-radio-group>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
            </div>
        </el-dialog>

        <!--新增界面-->
        <el-dialog title="新增模块" size="tiny" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-form-item label="所属系统" prop="system_id">
                    <el-select v-model="addForm.system_id" placeholder="请选择">
                        <el-option v-for="item in system_array" :key="item.system_id" :label="item.name" :value="item.system_id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="所属模块" prop="pid">
                    <el-select v-model="addForm.pid" placeholder="请选择">
                        <el-option v-for="item in modul_array" :key="item.column_id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="模块类型" prop="type">
                    <el-select v-model="addForm.type" placeholder="请选择">
                        <el-option v-for="item in modul_type" :key="item.id" :label="item.name" :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="模块名称" prop="name">
                    <el-input v-model="addForm.name" :maxlength="20" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="链接" prop="access_flags">
                    <el-input v-model="addForm.access_flags" :maxlength="50" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="状态">
                    <el-radio-group v-model="addForm.status">
                        <el-radio class="radio" v-for="item in status_array" :key="item.id" :label="item.id">{{item.name}}</el-radio>
                    </el-radio-group>
                </el-form-item>
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
    import { getAuthority, addAuthority, editAuthority, removeAuthority, getAllModul, getAllSystem } from '../../api/api';

    const validation = {
        system_id: [
            { required: true, message: '请选择所属系统', trigger: 'blur' }
        ],
        /*pid: [
            { required: true, message: '请选择所属栏目', trigger: 'blur' }
        ],*/
        name: [
            { required: true, message: '请输入模块名称', trigger: 'blur' }
        ],
        access_flags: [
            { required: true, message: '请输入链接地址', trigger: 'blur' }
        ],
        type: [
            { required: true, message: '请选择模块类型', trigger: 'blur' }
        ]
    };

    export default {
        data() {
            return {
                filters: {
                    system_id:'0',
                    search_word: '',
                    status:'0'
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
                    authority_id:'',
                    pid:0,
                    name:"",
                    type:"",
                    system_id:"",
                    access_flags:"",
                    show_flags:""
                },

                addFormVisible: false,//新增界面是否显示
                addLoading: false,
                addFormRules: validation,
                //新增界面数据
                addForm: {
                    pid:0,
                    name:"",
                    type:"",
                    system_id:"",
                    access_flags:"",
                    status:'1',
                    show_flags:""
                },

                system_array:[],
                modul_array:[],
                query_status_array: constant.query.status,
                status_array: constant.use.status,
                modul_type: constant.use.modulType,
            }
        },
        methods: {
            formatPName(row){
                return row.p_name == '' ? '--' : row.p_name;
            },
            formatStatus(row, column) {
                let status_array = constant.use.status,
                    valStr = '未知';
                status_array.map(function(item,index){
                    valStr = (item.id == row.status) ? item.name : valStr;
                });
                return valStr;
            },
            formatType(row , column){
                let  valStr = '未知';
                this.modul_type.map(function(item,index){
                    valStr = (item.id == row.type) ? item.name : valStr;
                });
                return valStr;
            },
            handleCurrentChange(val) {
                this.page = val;
                this.getData();
            },
            getModul(system_id){

                if( system_id == null || system_id == "") return false;

                getAllModul({system_id:system_id, column_id:'0'}).then(res =>{
                    if(res.status){
                        var tem = res.data.lists;
                        tem.push({id:"0",name:"无上级栏目",pid:"0"});
                        this.modul_array = tem;
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            },
            getSystem(){
                getAllSystem({}).then(res =>{
                    if(res.status){
                        this.system_array = res.data.lists; 
                    }else{
                       this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            },
            getData() {
                let para = Object.assign({},this.filters);
                para.page = this.page;
                para.page_size = this.page_size;

                this.listLoading = true;
                getAuthority(para).then(res => {
                    if(res.status){
                        this.total = parseInt(res.data.total || 0,10);
                        this.datalist = res.data.list;
                        console.log(this.datalist)
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                    this.listLoading = false;
                });
            },
            //删除
            handleDel(id) {
                this.$confirm('确认删除该记录吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    let para = { authority_id: id };
                    removeAuthority(para).then(res =>{
                        this.listLoading = false;
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
            handleEdit(index, row) {
                this.editFormVisible = true;
                console.log(row); 
                this.editForm = Object.assign({}, row); 
            },
            //显示新增界面
            handleAdd() {
                this.addFormVisible = true;
                this.addForm = {
                    pid:"",
                    name:"",
                    type:"",
                    system_id: (this.filters.system_id == "0" ? '' : this.filters.system_id),
                    access_flags:"",
                    status:'1',
                    show_flags:""
                };
            },
            //编辑
            editSubmit() {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editLoading = true;
                            let para = Object.assign({}, this.editForm);
                            // console.log(para)

                            editAuthority(para).then((res) => {
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
            //新增
            addSubmit() {
                this.$refs.addForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.addLoading = true;
                            let para = Object.assign({}, this.addForm);
                            addAuthority(para).then((res) => {
                                this.addLoading = false;
                                if(res.status){
                                    this.$message({
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    this.$refs['addForm'].resetFields();
                                    this.addFormVisible = false;
                                    this.getData();
                                }else{
                                    this.$message({
                                        message: res.retinfo,
                                        type: 'error'
                                    });
                                }
                            });
                        });
                    }
                });
            }
        },
        mounted() {
            this.filters.system_id = this.$route.params.System_id;
            this.getData();
            this.getSystem();
        },
        watch:{
            'editForm.system_id'(val){
                this.getModul(val);
            },
            'addForm.system_id'(val){
                this.getModul(val);
            }
        }
    }

</script>

<style scoped>

</style>