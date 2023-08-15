<template>
    <tree :data="cat" draggable cross-tree v-slot="{data, store}" :ondragend="onDragEnd">
        <template v-if="!data.isDragPlaceHolder">
            <div>
                <span class="arrow-item-icon fa fa-angle-right" :class="data.open ? 'open' : ''" v-if="data.children && data.children.length" @click="store.toggleOpen(data)"></span>
                <span v-else class="no-child-item fa fa-circle"></span>

                <span class='i-am-node-name'>{{data.name}}</span>
            </div>
            <div class="btns">
                <a :href="'categories/' + data.id + '/edit'" class="btns-item far fa-edit" title="Редагувати"></a>
                <a href="#" class="btns-item fas fa-trash-alt" title="Видалити" @click="deleteItem(data.id)"></a>
            </div>
        </template>
    </tree>
</template>

<script>
    import {DraggableTree} from 'vue-draggable-nested-tree'

    export default {
        props: {
            categories: {
                default: [],
                type: Array
            }
        },

        components: {'tree': DraggableTree},

        data: function () {
            return {
                tree: []
            }
        },

        methods: {
            onDragEnd: function () {

                setTimeout(() => {
                    let tree = [];

                    for (let i in this.categories) {
                        tree[i] = this.getTree(this.categories[i]);
                    }

                    axios.post('categories/rebuild', {
                        tree: tree
                    })
                        .then(response => {
                            console.log('res',response);
                        });
                },100);

            },

            getTree: function (tree) {
                let elem = {
                    id: tree.id,
                    name: tree.name,
                    parent_id: tree.parent_id,
                    _lft: tree._lft,
                    _rgt: tree._rgt,
                    children: []
                };

                if (!this.isEmptyObj(tree.children)) {
                    for(let j in tree.children){
                        console.log(tree.children[j]);
                        elem.children.push(this.getTree(tree.children[j]));
                    }
                }

                return elem;
            },

            isEmptyObj: function (obj) {
                for(let prop in obj) {
                    if(obj.hasOwnProperty(prop))
                        return false;
                }

                return true;
            },

            deleteItem: function(id){
                if(confirm('Ви впевнені, що хочете видалити цю категорію?')){
                    axios.delete('categories/' + id)
                        .then(response => {
                            if(response.data.status){
                                this.tree = response.data.tree;
                            }
                            else {
                                if(response.data.hasChildren){
                                    if(confirm(response.data.message)){
                                        axios.delete('categories/' + id + '?c=0')
                                            .then(response => {
                                                if(response.data.status){
                                                    this.tree = response.data.tree;
                                                }
                                            });
                                    }
                                }
                            }
                        });
                }
            }
        },

        created: function () {
            this.tree = this.categories;
        },
        computed: {
            cat: function () {
                return this.tree;
            }
        }
    }
</script>
