<template>
    <div :class="estilo" role="alert">
        <b> {{ titulo }} </b>
        <hr>
        <p>
            <span v-if="message.mensagem">
                {{ message.mensagem }}
            </span>

            <!-- <ul v-if="message.dados">
                <li v-for="e, key in message.dados" :key="key" style="list-style: none;">
                    {{ e[0] }}
                </li>
            </ul> -->
        </p>
    </div>
</template>

<script>
    export default {
        props:['tipo', 'titulo', 'message'],
        computed:{
            estilo(){
                return 'alert alert-'+this.tipo
            }
        }
    }
</script>
