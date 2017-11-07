<template>
    <ul>
        <node :data.sync="list"></node>
    </ul>
</template>
<script>

import node from '../src/node.vue'

export default {
    name:'tree_ui',
    data(){
        return{
           list:this.data
        }
    },
    props:['data','checkNode'],
    components:{
        node
    }
}
</script>
