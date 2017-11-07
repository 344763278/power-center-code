<template>
    <li>
        <div>
             {{model.setCheckedKeys}}
        </div>
        <ul>
            <items v-for='cel in model.children' :model='cel'></items>
        </ul>
    </li>
</template>
<script type="text/javascript">
    export default {
        name: 'items',
        props: ['model'],
        data() {
            return {
            }
        },
        methods: {
          
        }
    }
</script>