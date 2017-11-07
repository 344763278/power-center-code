<template>
    <el-form ref="modifyForm" :model="modifyForm" label-width="80px" :rules="modifyFormRules" style="margin:20px;width:400px;min-width:400px;">
        <el-form-item label="原始密码" prop="olPwd">
            <el-input type="password" v-model="modifyForm.olPwd"></el-input>
        </el-form-item>
        <el-form-item label="新密码" prop="newPwd">
            <el-input type="password" :maxlength="18" v-model="modifyForm.newPwd"></el-input>
        </el-form-item>
        <el-form-item label="确认密码" prop="newPwd2">
            <el-input type="password" :maxlength="18" v-model="modifyForm.newPwd2"></el-input>
        </el-form-item>
        <el-form-item label="     ">
            <el-button type="primary" style="width: 320px;" @click="ModifyPwd" >确认修改</el-button>
        </el-form-item>
    </el-form>
</template>

<script>

    import util from '../common/js/util'
    import constant from '../common/js/constant'
    import { editPwd } from '../api/api'

    export default {
        data() {
            return {
                modifyForm: {
                    olPwd: '',
                    newPwd: '',
                    newPwd2: ''
                },
                modifyFormRules:{
                    olPwd:[
                        { required: true, message: '请输入旧密码', trigger: 'blur' }
                    ],
                    newPwd:[
                        { required: true, message: '请输入新密码', trigger: 'blur' }
                    ],
                    newPwd2:[
                        { required: true, message: '请再次确认密码', trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            CheckPwd(){
                console.log(this.modifyForm);
            },
            ModifyPwd(){
                var para = {
                    username:sessionStorage.getItem('user_id'),
                    old_password:this.modifyForm.olPwd,
                    new_password:this.modifyForm.newPwd
                }
                if(this.modifyForm.newPwd != this.modifyForm.newPwd2){
                    this.$message({
                        message: '两次密码不一致',
                        type: 'error'
                    });
                    return false;
                }
                if(/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,18}$/.test(para.new_password) == false){
                    this.$message({
                        message: '密码由6-18字母和数字组成，不能是纯数字或纯英文',
                        type: 'error'
                    });
                    return false;
                }
                if(para.old_password == para.new_password){
                    this.$message({
                        message: '新密码不能与旧密码相同',
                        type: 'error'
                    });
                    return false;
                }
                editPwd(para).then(res => {
                    if(res.status){
                        this.$message({
                            message: '修改成功，即将重新登陆。',
                            type: 'success'
                        });
                        var that = this;
                        window.setTimeout(function(){
                            that.$router.push({ path: '/login' });
                        },1000);
                    }else{
                        this.$message({
                            message: res.retinfo,
                            type: 'error'
                        });
                    }
                });
            }
        }
    }

</script>