<template>
  <v-container>
    <v-row>
      <div class="flex-grow-1"></div>
      <v-text-field v-model="search" label="Buscar Producto" hide-details />
      <v-col cols="12" md="12" sm="12" lg="12">
        <v-data-table
          :headers="hTBEquipos"
          :items.sync="arrayEquipos"
          :items-per-page="10"
          :footer-props="{
            'items-per-page-options': [5, 10, 15, 20],
          }"
          :search="search"
          :single-select="false"
          show-select
          v-model="item"
          :dense="true"
          multi-sort
        >
        </v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  data: () => ({
    item: {},
    arrayEquipos: [],
    hTBEquipos: [
      { text: "Código", value: "codigo" },
      { text: "Nombre", value: "nombre" },
      { text: "Descripción", value: "descripcion" },
      { text: "Marca", value: "marca", align: "center" },
    ],

    loader: false,
    modalEquipos: false,
    errorsNombre: [],
    search: "",
    editedEquipo: -1,
    alert: false,
    EquiposCb: true,
    loadData: false,
  }),
  watch: {
    item: function () {
      this.$emit("added", this.item);
    },
  },
  computed: {},
  methods: {
    fetchEquipos() {
      let me = this;
      return axios.get(`/equipos/all`);
    },
    fetchData() {
      let me = this;
      me.loader = true;
      axios
        .all([me.fetchEquipos()])
        .then(
          axios.spread((equipos) => {
            me.arrayEquipos = equipos.data;
            me.loader = false;
          })
        )
        .catch(function (error) {
          console.log(error);
          me.loader = false;
          Vue.swal("Error", "Ocurrio Un Error Intente Nuevamente", "error");
        });
    },
  },
  components: {},
  mounted() {
    let me = this;
    me.fetchData();
    me.$emit("created");
  },
};
</script>
