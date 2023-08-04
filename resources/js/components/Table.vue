<template>
    <div>
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr >
                    <th v-for="titulo, key in titulos" :key="key" scope="col">{{titulo.titulo}}</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, key in dados" :key="key">
                    <td v-for="row, chave in obj" :key="chave">
                        <span v-if="titulos[chave].tipo == 'text'">
                            {{ row }}
                        </span>
                        <span v-if="titulos[chave].tipo == 'imagem'">
                            <img :src="'/storage/'+row" width="40" height="40">
                        </span>
                        <span v-if="titulos[chave].tipo == 'data'">
                            {{ formatarData(row) }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-secondary" href="#"  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#VisualizarMarcaModal" @click="setStore(obj)"><i class="fa-solid fa-eye"></i> Visualizar</a></li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#AtualizarMarcaModal" @click="setStore(obj)"><i class="fa-solid fa-pen-to-square text-success"></i> Editar</a></li>
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#RemoverMarcaModal" @click="setStore(obj)"><i class="fa-solid fa-trash-can text-danger"></i> Excluir</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props:['rows', 'titulos'],

        methods: {
            setStore(obj){
                this.$store.state.item = obj
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.mensagem = ''
            },

            formatarData(data){
                if (!data) return ''
                data = data.split('T')
                let dia = data[0]
                let tempo = data[1]

                dia = dia.split('-')
                dia = dia[2]+'/'+dia[1]+'/'+dia[0]

                tempo = tempo.split('.')
                tempo = tempo[0].split(':')
                tempo = tempo[2]+':'+tempo[1]+':'+tempo[0]

                return dia + ' ' + tempo
            },
        },

        computed:{
            
            dados(){
                let titulos = Object.keys(this.titulos)
                let dados = []
                this.rows.map((item, chave) => {
                    let itemFiltrado = {}
                    titulos.forEach(element => {
                        itemFiltrado[element] = item[element]
                    });
                    dados.push(itemFiltrado)
                })
                return dados
            },
        }
    }
</script>
