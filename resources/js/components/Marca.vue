<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de Marca">
                    <template v-slot:conteudo>             
                        <div class="row">
                            <div class="col">
                                <input-container-component titulo="Id da Marca" id="inputId" id-help="idHelp" texto-ajuda="Opcional! Informe o ID do registro">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="Id da Marca" v-model="busca.id">
                                </input-container-component>
                            </div>

                            <div class="col">
                                <input-container-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp" texto-ajuda="Opcional! Informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da Marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn-sm btn btn-primary float-end" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>

                <card-component titulo="Relação de Marca">
                    <template v-slot:conteudo>             
                        <table-component :rows="marcas.data" :titulos="
                        {
                            id: {titulo:'ID', tipo:'text'},
                            nome: {titulo:'Nome', tipo:'text'},
                            imagem: {titulo:'Imagem', tipo:'imagem'},
                            created_at: {titulo:'Data Criação', tipo:'data'},
                        }
                        "></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination" style="cursor: pointer;">
                                            <li v-for="link, key in marcas.links" :key="key" :class="link.active ? 'page-item active' : 'page-item'" @click="paginacao(link)">
                                                <a class="page-link" v-html="link.label"> </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </paginate-component>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn-sm btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#MarcaModal">Adicionar</button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>

        <!-- Modal  Inclusão de Marca-->
        <modal-component id="MarcaModal" titulo="Adicionar Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :message="MenssageTransacao" titulo="Sucesso ao cadastrar marca" v-if="StatusTransacao == 'sucesso'"></alert-component>
                <alert-component tipo="danger" :message="MenssageTransacao" titulo="Erro ao tentar cadastrar a marca" v-if="StatusTransacao == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <div class="col">
                        <input-container-component titulo="Nome da Marca" id="newInputNome" id-help="newNomeHelp" texto-ajuda="Informe o nome da marca">
                            <input type="text" class="form-control" id="newInputNome" aria-describedby="newNomeHelp" placeholder="Nome da Marca" v-model="nomeMarca">
                        </input-container-component>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <div class="col">
                        <input-container-component titulo="Imagem" id="newInputImagem" id-help="newImagemHelp" texto-ajuda="Selecione uma Imagem no formato .PNG">
                            <input type="file" class="form-control-file" id="newInputImagem" aria-describedby="newImagemHelp" @change="carregarImagem($event)">
                        </input-container-component>
                    </div>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" @click="Salvar">Salvar</button>
            </template>
        </modal-component>

        <!-- Modal visualizar Marca-->
        <modal-component id="VisualizarMarcaModal" titulo="Visualizar Marca">
            <template v-slot:conteudo>
                    <div class="row">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                        </input-container-component>

                        <input-container-component titulo="Nome">
                            <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                        </input-container-component>

                        <input-container-component titulo="Imagem">
                            <br><img v-if="$store.state.item.imagem" :src="'/storage/'+$store.state.item.imagem" width="100" height="100">
                        </input-container-component>
                    </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
        
        <!-- Modal  Remoção de Marca-->
        <modal-component id="RemoverMarcaModal" titulo="Excluir Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :message="this.$store.state.transacao" titulo="Sucesso ao remover marca" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" :message="this.$store.state.transacao" titulo="Erro ao tentar remover a marca" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                    <div class="row">
                        <input-container-component titulo="ID">
                            <input type="text" class="form-control" id="excluirMarcaId" :value="$store.state.item.id" disabled>
                        </input-container-component>

                        <input-container-component titulo="Nome">
                            <input type="text" class="form-control" id="excluirMarcaNome" :value="$store.state.item.nome" disabled>
                        </input-container-component>
                    </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-success" @click="excluirMarca()" v-if="$store.state.transacao.status != 'sucesso'"><i class="fa-solid fa-circle-check"></i> Excluir</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Cancelar</button>
            </template>
        </modal-component> 
        
        <!-- Modal  Atualizar Marca-->
        <modal-component id="AtualizarMarcaModal" titulo="Atualizar Marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :message="this.$store.state.transacao" titulo="Sucesso !!!" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" :message="this.$store.state.transacao" titulo="Erro ao tentar atualizar a marca" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                    <div class="row">
                        <input-container-component titulo="Nome">
                            <input type="text" class="form-control" id="atualizarMarcaNome" id-help="atualizarNomeHelp" texto-ajuda="Insira o Nome á ser atualizazdo" v-model="$store.state.item.nome">
                        </input-container-component>
                        <input-container-component titulo="Imagem" id="atualizarInputImagem" id-help="atualizarImagemHelp" texto-ajuda="Selecione uma Imagem no formato .PNG">
                            <input type="file" class="form-control-file" id="atualizarInputImagem" aria-describedby="atualizarImagemHelp" @change="carregarImagem($event)">
                        </input-container-component>
                    </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-success" @click="Atualizar()" v-if="$store.state.transacao.status != 'sucesso'"><i class="fa-solid fa-circle-check"></i> Atualizar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark"></i> Fechar </button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    export default {
        props:['url'],

        data() {
            return{
                nomeMarca: '',
                imagemMarca: [],
                StatusTransacao: '',
                MenssageTransacao: [],
                marcas:{data:[]},
                urlPaginacao: '',
                urlFiltro: '',
                urlBase: this.url+"/api/v1/marca",
                busca: { id: '', nome: ''}
            }
        },

        computed: {
            token(){
                let token = document.cookie.split(';').find(indice =>{
                    return indice.includes('token=') 
                })

                token = 'Bearer '+token.split('=')[1]
                
                return token
            }
        },

        methods: {
            pesquisar(){
                let filtro = ''

                for (let atributo in this.busca) {
                    if (this.busca[atributo]) {
                        if (filtro != '') {
                            filtro += ';'
                        }
                        filtro += atributo+':like:'+this.busca[atributo]                 
                    }
                }

                if (filtro != '') {
                    this.urlPaginacao = 'page=1'
                    this.urlFiltro = '&filtro='+filtro
                }else{
                    this.urlFiltro = ''
                }

                this.Listar()
            },

            carregarImagem(e){
                this.imagemMarca = e.target.files
            },

            Listar(){
                let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro 
                let config = {
                    Headers: {
                        'Accept': 'Application/json',
                        'Authorization': this.token,
                    }
                }

                axios.get(url, config)
                .then( response => {
                    this.marcas = response.data
                })
                .catch(errors => {
                    console.log(errors)
                })
            },

            Salvar(){
                let url = this.urlBase
                let formData = new FormData()
                    formData.append('nome', this.nomeMarca)
                    formData.append('imagem', this.imagemMarca[0])
                let config = {
                    headers: {
                        'Content-type': 'multipart/form-data',
                        'Accept': 'Application/json',
                        'Authorization': this.token,
                    }
                }

                axios.post(url, formData, config)
                    .then( response => {
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'ID do Registro: '+response.data.id   
                        
                        this.Listar()
                        console.log(response);
                    })
                    .catch(errors => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.error
                        
                        console.log(errors.response.data.message);
                    })
            },

            paginacao(p){
                if (p.url != null) {
                    this.urlPaginacao = p.url.split('?')[1]
                    this.Listar()
                }
            },

            excluirMarca(){
                let confirmacao = confirm('Tem certeza que deseja remover este registro?')
                if (confirmacao) {
                    let url = this.urlBase+'/'+this.$store.state.item.id
                    
                    let formData = new FormData()
                    let config = {
                        headers: {
                            'Accept': 'Aplication.json',
                            'Authorization': this.token, 
                        }
                    }

                    formData.append('_method', 'delete')

                    axios.post(url, formData, config)
                    .then(response => {
                        console.log('Registro removido com sucesso')
                        
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = response.data.msg
                        console.log(response);
                        
                        this.Listar()
                    })
                    .catch(erro => {
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = erro.response.data.error
                        console.log(erro);
                    })
                }
            },

            Atualizar(){
                let url = this.urlBase+'/'+this.$store.state.item.id
                let formData = new FormData()
                formData.append('_method', 'put')
                formData.append('nome', this.$store.state.item.nome)
                if (this.carregarImagem[0] != '') {
                    formData.append('imagem', this.imagemMarca[0])
                    console.log(formData);
                }
                
                let config = {
                    headers: {
                        'Content-type': 'multipart/form-data',
                        'Accept': 'Application/json',
                        'Authorization': this.token,
                    }
                }

                axios.post(url, formData, config)
                .then(response => {
                console.log(response)
                atualizarInputImagem.value = ''
                this.$store.state.transacao.status = 'sucesso'
                this.$store.state.transacao.mensagem = 'Registro de Marca Realizado com Sucesso'
                this.Listar()
                })
                .catch(errors => {
                    this.$store.state.transacao.status = 'erro'
                    this.$store.state.transacao.mensagem = errors.response.data.error
                    console.log(errors.response.data.message);
                })
            }
        },

        mounted() {
            this.Listar()
        }
    }
</script>
