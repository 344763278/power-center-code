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
                <el-form-item>
                    <el-input v-model="filters.search_word" placeholder="角色名"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" v-on:click="getData">查询</el-button>
                </el-form-item>
                <el-form-item>
                    <el-button :plain="true" type="info" @click="handleAdd">新增角色</el-button>
                </el-form-item>
            </el-form>
        </el-col>

        <!--列表-->
        <el-table :data="datalist" highlight-current-row v-loading="listLoading" style="width: 100%;">
            <el-table-column prop="name" label="角色名称"  >
            </el-table-column>
            <el-table-column prop="system_name" label="所属系统"  >
            </el-table-column>
            <el-table-column prop="status" label="状态" width="120" :formatter="formatStatus">
            </el-table-column>
            <el-table-column prop="desc" label="描述"  min-width="150" >
            </el-table-column>
            <el-table-column prop="create_time" label="添加时间" >
            </el-table-column>
            <el-table-column prop="create_name" label="添加人" >
            </el-table-column>
            <el-table-column label="操作" width="150">
                <template scope="scope">
                    <el-button size="small" @click="handleEdit(scope.$index, scope.row)">编辑</el-button>
                    <el-button :plain="true" type="danger" size="small" @click="handleDel(scope.row.role_id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!--工具条-->
        <el-col :span="24" class="toolbar">
            <el-pagination layout="prev, pager, next" @current-change="handleCurrentChange" :page-size="pageSize" :total="total" style="float:right;">
            </el-pagination>
        </el-col>

        <!--编辑界面-->
        <el-dialog title="编辑角色" v-model="editFormVisible" :close-on-click-modal="false">
            <el-form :model="editForm" label-width="80px" :rules="editFormRules" ref="editForm">
                <el-row :gutter="20">
                  <el-col :span="10">
                    <div class="grid-content bg-purple">
                        <el-form-item label="角色名称" prop="name">
                            <el-input v-model="editForm.name" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="所属系统" prop="system_id">
                            <el-select v-model="editForm.system_id" placeholder="请选择">
                                <el-option v-for="item in system_array" :key="item.system_id" :label="item.name" :value="item.system_id">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="描述">
                            <el-input type="textarea" v-model="editForm.desc"></el-input>
                        </el-form-item>
                        <el-form-item label="状态">
                            <el-radio-group v-model="editForm.status">
                                <el-radio class="radio" v-for="item in status_array" :key="item.id" :label="item.id">{{item.name}}</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </div>
                  </el-col>
                  <el-col :span="10">
                    <div class="grid-content bg-purple trre-box">
                        <el-tree
                            :data="authority_tree"
                            show-checkbox=""
                            default-expand-all=""
                            ref="editTrre"
                            node-key="id">
                        </el-tree>
                        <!-- <tree :data="authority_tree"></tree> -->
                    </div>
                  </el-col>
                </el-row>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click.native="editFormVisible = false">取消</el-button>

                <!-- <el-button @click.native="getTest">测试</el-button>
                <el-button @click.native="setTest">测试2</el-button>-->
                
                <el-button type="primary" @click.native="editSubmit" :loading="editLoading">提交</el-button>
            </div>
        </el-dialog>

        <!--新增界面-->
        <el-dialog title="新增角色" v-model="addFormVisible" :close-on-click-modal="false">
            <el-form :model="addForm" label-width="80px" :rules="addFormRules" ref="addForm">
                <el-row :gutter="20">
                  <el-col :span="10">
                    <div class="grid-content bg-purple">
                        <el-form-item label="角色名称" prop="name">
                            <el-input v-model="addForm.name" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="所属系统" prop="system_id">
                            <el-select v-model="addForm.system_id" placeholder="请选择">
                                <el-option v-for="item in system_array" :key="item.system_id" :label="item.name" :value="item.system_id">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="描述">
                            <el-input type="textarea" v-model="addForm.desc"></el-input>
                        </el-form-item>
                        <el-form-item label="状态">
                            <el-radio-group v-model="addForm.status">
                                <el-radio class="radio" v-for="item in status_array" :key="item.id" :label="item.id">{{item.name}}</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </div>
                  </el-col>
                  <el-col :span="10">
                    <div class="grid-content bg-purple trre-box">
                        <el-tree
                            :data="authority_tree"
                            default-expand-all
                            show-checkbox
                            :default-checked-keys="[]"
                            ref='addTrre'
                            node-key="id">
                        </el-tree>
                    </div>
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

<style>
 .trre-box{
    max-height: 600px;
    overflow-y: scroll;
 }
</style>

<script>
    import util from '../../common/js/util'
    import constant from '../../common/js/constant'
    import tree from '../../component/tree'
    import { getRole, addRole, eidtRole, removeRole, getAuthorityTrre, getAllSystem, getRoleInfo } from '../../api/api';

    const validation = {
        name: [
            { required: true, message: '请输入角色名', trigger: 'blur' }
        ],
        system_id:[
            { required: true, message: '请选择所属系统', trigger: 'blur' }
        ]
    }

    export default {
        data() {
            return {
                filters: {
                    search_word: '',
                    system_id:''
                },
                datalist: [],
                total: 0,
                page: 1,
                pageSize:constant.pageSize,
                listLoading: false,

                editFormVisible: false,//编辑界面是否显示
                editLoading: false,
                editFormRules: validation,
                //编辑界面数据
                editForm: { 
                    name: '',
                    system_id:'',
                    status: '',
                    role_id:'',
                    desc:'',
                    authority_id:[],
                    isRenderEditTree:false
                },

                addFormVisible: false,//新增界面是否显示
                addLoading: false,
                addFormRules: validation,
                //新增界面数据
                addForm: {
                    name: '',
                    system_id:'',
                    status: '1'
                },

                status_array: constant.use.status,
                system_array:[],
                authority_tree:[],

                isRenderEditTree: false,

                _test:[]
            }
        },
        methods: {
            formatStatus(row, column) {
                let status_array = constant.use.status,
                    valStr = '未知';
                status_array.map(function(item,index){
                    valStr = (item.id == row.status) ? item.name : valStr;
                });
                return valStr;
            },
            handleCurrentChange(val) {
                this.page = val;
                this.getData();
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
            getAuthorityTrre(system_id){
                if(system_id == "") return false;
                var para = {
                    system_id:system_id
                };
                if(system_id == null || system_id == '') return false;
                getAuthorityTrre(para).then(res => {
                    if(res.status){
                        this.authority_tree = Array.isArray(res.data) ? res.data : [];
                        console.log('load data...');
                    }else{
                       this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            },
            getSelectTree(){
                var role_array = [];
                if(this.editFormVisible){
                    role_array = this.$refs.editTrre.getCheckedKeys();
                }else if(this.addFormVisible){
                    role_array = this.$refs.addTrre.getCheckedKeys();
                }
                if(role_array.length == 0) return role_array;
                //加入父节点
                this.authority_tree.map(function(item, index){
                    (item.children || []).map(function(citem, cindex){
                        if(role_array.includes(citem.id) && !role_array.includes(citem.pid)){
                            role_array.push(citem.pid);
                        }
                    });
                });
                return role_array;
            },
            getData() {
                let para = Object.assign({},this.filters);
                para.page = this.page;
                para.page_size = this.page_size;
                this.listLoading = true;
                getRole(para).then((res) => {
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
                    this.listLoading = true;
                    let para = { role_id: id };
                    removeRole(para).then(res =>{
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
                        this.listLoading = false;
                    });

                }).catch(() => {

                });
            },
            //显示编辑界面
            handleEdit(index, row) {
                var para = {
                    role_id: row.role_id
                }
                getRoleInfo(para).then(res => {
                    if(res.status){
                        let info = res.data;

                        this.editForm.name = info.name;
                        this.editForm.system_id = info.system_id;
                        this.editForm.status = info.status;
                        this.editForm.role_id = info.role_id;
                        this.editForm.desc = info.desc;
                        this.editForm.authority_id = info.authority_id;
                        this.editForm.isRenderEditTree = false;
                        //this.editForm = res.data;
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
            handleAdd: function () {
                this.addFormVisible = true;
                this.addForm = {
                    name: '',
                    system_id: ( this.filters.system_id == '0' ? '' : this.filters.system_id ) ,
                    desc:'',
                    status: '1'
                };
            },
            
            //移除父级节点
            removeParentNodRemove(data,rols){
     
                var removeArray = [];
                var recursionFun = (list) => {
                    list.forEach((m) => {
                        
                        let id = parseInt(m.id || 0,10);
                        let pid = parseInt(m.pid || 0,10);
                        if(rols.includes(id) && pid != 0){
                            removeArray.push(pid);
                        }

                        if(Array.isArray(m.children) && m.children.length > 0){
                            recursionFun(m.children);
                        }
                    });
                };

                recursionFun(data);
                removeArray = [...new Set(removeArray)];

                removeArray.forEach((m) => {
                    rols = constant.arrayRemove(rols,m);
                });
                return rols;
            },


            //添加父级别节点
            removeParentNodAdd(data, rols){
                
                var temRols = [];
                var recursionFun = (list) => {
                    list.forEach((m) => {

                        let pids = m.pids;
                        let id = m.id;

                        if(rols.includes(id)){
                            temRols.push(pids+"#"+id);
                        }
                        if(Array.isArray(m.children) && m.children.length > 0){
                            recursionFun(m.children);
                        }
                    });
                };
                recursionFun(data); 

                console.log('解析前：',temRols);

                let rolsArray = [];
                temRols.forEach((item) => {
                    let array = item.split('#');
                    array.forEach((v) => {
                        if(v != ''){
                            rolsArray.push(parseInt(v,10));
                        }
                    });
                });

                rolsArray = [...new Set(rolsArray)];

                return rolsArray;
            },

            //渲染编辑界面的权限树
            RenderEditTree(){ 

                if(this.editForm.isRenderEditTree) return; 

                var _trre = this.authority_tree,
                    _prot = this.editForm.authority_id;

                if( !Array.isArray(_trre) || _trre.length == 0 ||
                    !Array.isArray(_prot) || _prot.length == 0){
                    return [];
                }

                let selectNode = this.removeParentNodRemove(_trre,_prot);

                this.$nextTick(() =>{
                    this.$refs.editTrre.setCheckedKeys(selectNode);
                    this.editForm.isRenderEditTree = true;
                    console.log('渲染属性节点：',selectNode);
                });  
                return selectNode;
            },
            /*
            getTest(){
                let selectedNode = this.$refs.editTrre.getCheckedKeys();
                let ids = this.removeParentNodAdd(this.authority_tree,selectedNode);
                console.log('所选节点数据：', ids);
            },

            setTest(){
                let tem = [89, 90, 92, 93, 98, 99, 100, 111, 76, 78, 80, 83];
                let ttt = this.removeParentNodRemove(this.authority_tree,tem);
                console.log('需要移除的数据：',ttt);

                this.$refs.editTrre.setCheckedKeys(ttt);
            },*/
            //编辑
            editSubmit() {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.$confirm('确认提交吗？', '提示', {}).then(() => {
                            this.editLoading = true;
                            let para = Object.assign({}, this.editForm);
                            let selectedNode = this.$refs.editTrre.getCheckedKeys();
                            para.authority_id =  this.removeParentNodRemove(this.authority_tree,selectedNode);
                            console.log('提交修改：', para.authority_id);
                            eidtRole(para).then(res =>{
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
                            para.authority_id = this.getSelectTree();
                            addRole(para).then(res => {
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
            this.filters.system_id = this.$route.params.System_id;
            this.getSystem();
            this.getData();
        },
        watch:{
            'editForm.system_id'(val){
                if(val != ''){
                    this.getAuthorityTrre(val);
                }
            },
            'addForm.system_id'(val){
                if(val != ''){
                    this.getAuthorityTrre(val);
                }
            },
            //角色数据发生改变
            'authority_tree'(val){ 
                this.RenderEditTree();
            },
            //编辑角色发生改变
            'editForm.role_id'(val){ 
                this.RenderEditTree();
            },
            'editFormVisible'(val){
                //关闭窗口重置渲染状态
                if(val === false){
                    this.isRenderEditTree = false;
                }
            }
        }
    }
</script>