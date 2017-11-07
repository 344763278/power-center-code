<!-- 部门管理 -->
<template>
    <section>
        <!--工具条-->
        <el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
            <el-form :inline="true" :model="filters">
                <el-form-item lable="名称">
                    <el-input v-model="filters.search_word" placeholder="部门名称"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" v-on:click="getData">查询</el-button>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" @click="handleAdd">新增部门</el-button>
                </el-form-item>
            </el-form>
        </el-col>

        <!--列表-->
        <el-table :data="datalist" highlight-current-row v-loading="listLoading" style="width: 100%;">
            <el-table-column prop="name" label="部门名称" width="120">
            </el-table-column>
            <el-table-column prop="desc" label="描述" min-width="300" >
            </el-table-column>
            <el-table-column prop="create_time" label="添加时间" width="200">
            </el-table-column>
            <el-table-column prop="create_name" label="添加人" min-width="100">
            </el-table-column>
            <el-table-column label="操作" width="250">
                <template scope="scope">
                    <!-- <el-button size="small" @click="queryLower(scope.row.department_id)" >查看职位</el-button> -->
                    <el-button :plain="true" type="info" size="small" @click="handleEdit(scope.$index)">编辑</el-button>
                    <el-button :plain="true" type="danger" size="small" @click="handleDel(scope.row.department_id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!--工具条-->
        <el-col :span="24" class="toolbar">
            <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="pageSize" :total="total" style="float:right;">
            </el-pagination>
        </el-col>

        <!--编辑界面-->
        <el-dialog title="编辑部门" size="tiny" v-model="editFormVisible" :close-on-click-modal="false">
            <el-form :model="editForm" label-width="80px" :rules="editFormRules" ref="editForm">
                <el-form-item label="ID" prop="department_id">
                    <el-input v-model="editForm.department_id" :disabled="true" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="部门名称" prop="name">
                    <el-input v-model="editForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="描述">
                    <el-input type="textarea" :maxlength="10" v-model="editForm.desc"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editFormVisible = false">取消</el-button>
                <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
            </div>
        </el-dialog>

        <!--新增界面-->
        <el-dialog title="新增部门" size="tiny" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-form-item label="部门名称" prop="name">
                    <el-input v-model="addForm.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="描述">
                    <el-input type="textarea" :maxlength="10" v-model="addForm.desc"></el-input>
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
    import { getDepartment, removeDepartment, addDepartment, editDepartment} from '../../api/api';

    const validation = {
        name: [
            { required: true, message: '请输入部门名称', trigger: 'blur' }
        ]
    };

    export default {
        data() {
            return {
                filters: {
                    search_word: ''
                },

                datalist:[],
                total: 0,
                page: 1,
                pageSize:constant.pageSize,
                listLoading: false,

                editFormVisible: false,//编辑界面是否显示
                editLoading: false,
                editFormRules: validation,
                //编辑界面数据
                editForm: {
                    department_id: 0,
                    name: '',
                    desc:''
                },

                addFormVisible: false,//新增界面是否显示
                addLoading: false,
                addFormRules: validation,
                //新增界面数据
                addForm: {
                    name: '',
                    desc:''
                }
            }
        },
        methods: {
            handleCurrentChange(val) {
                this.page = val;
                this.getData();
            },
            /*queryLower(id){
                this.$router.push({ path: '/PositionManage/'+id });
            },*/
            getData() {
                let para = {
                    page: this.page,
                    page_size: this.pageSize,
                    search_word: this.filters.search_word
                };
                this.listLoading = true;

                getDepartment(para).then((res) => {
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
            //删除
            handleDel(id) {
                this.$confirm('确认删除该记录吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    var para = {
                        department_id: id
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
            handleEdit(index) {
                this.editFormVisible = true;
                this.editForm = Object.assign({},this.datalist[index]);
            },
            //显示新增界面
            handleAdd() {
                this.addFormVisible = true;
                this.addForm = {
                    name: '',
                    desc:''
                };
            },
            //编辑
            editSubmit() {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editLoading = true;
                            let para = Object.assign({}, this.editForm);
                            editDepartment(para).then( res => {
                                this.editLoading = false;
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
                            addDepartment(para).then(res => {
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
            this.getData();
        }
    }

</script>

<style scoped>

</style>