<template>
  <div class="content">
    <div
      class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100"
    >
      <v-overlay :value="loader" :z-index="'99999999'">
        <v-progress-circular
          indeterminate
          size="80"
          color="grey darken-4"
        ></v-progress-circular>
      </v-overlay>
      <v-card>
        <v-card-title
          v-text="
            action == 'add'
              ? 'Formulario de Reserva de Equipos'
              : action == 'upd'
              ? 'Actualización de Reserva'
              : 'Detalle de Reserva de Equipos'
          "
        >
          <div class="flex-grow-1"></div>
          <v-text-field
            v-model="search"
            label="Buscar"
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-container fluid>
          <v-form
            ref="formPrestamo"
            v-model="validForm"
            :lazy-validation="true"
          >
            <v-row>
              <v-col cols="12" md="3">
                <date-picker
                  v-model="prestamoForm.fechaReserva"
                  :editable="false"
                  lang="es"
                  value-type="format"
                  format="YYYY-MM-DD"
                  class="mt-3"
                  style="width: 100% !important"
                  input-class="form-control"
                  disabled
                  placeholder="Fecha de Reserva"
                ></date-picker>
              </v-col>
              <v-col cols="12" md="4"></v-col>
              <v-col cols="12" md="2">Fecha Prestamo</v-col>
              <v-col cols="12" md="3">
                <date-picker
                  v-model="prestamoForm.fechaPrestamo"
                  :editable="false"
                  lang="es"
                  value-type="format"
                  format="YYYY-MM-DD"
                  class="mt-3"
                  style="width: 100% !important"
                  input-class="form-control"
                  placeholder="Fecha Prestamo"
                ></date-picker>
              </v-col>
            </v-row>
            <v-row justify="space-around" align="center">
              <v-col style="width: 350px; flex: 0 1 auto">
                <h2>Hora Inicio:</h2>
                <v-time-picker
                  v-model="prestamoForm.horaInicio"
                  :max="prestamoForm.horaFin"
                ></v-time-picker>
              </v-col>
              <v-col style="width: 350px; flex: 0 1 auto">
                <h2>Hora Finalización:</h2>
                <v-time-picker
                  v-model="prestamoForm.horaFin"
                  :min="prestamoForm.horaInicio"
                ></v-time-picker>
              </v-col>
            </v-row>
            <!--<v-row>
              <v-col cols="12" md="12">
                  
                <v-autocomplete
                  v-model="prestamoForm.empleado"
                  :items="arrayEmpleados"
                  :rules="[(v) => !!v || 'Empleado es Requerido']"
                  required
                  label="Seleccione Empleado"
                  item-value="id"
                  item-text="nombre"
                  :disabled="action == 'detail'"
                  return-object
                ></v-autocomplete>
              </v-col>
            </v-row>-->
            <v-row>
              <v-col cols="12" md="12" lg="12" sm="12">
                <v-col cols="12" md="12">
                  <v-alert
                    :value="noEquipos"
                    type="error"
                    border="left"
                    transition="scale-transition"
                    dismissible
                    elevation="2"
                    >Debe Agregar uno o mas equipos a la reserva</v-alert
                  >
                </v-col>
              </v-col>
              <v-col cols="12" md="12" lg="12" sm="12">
                <v-card outlined>
                  <v-card-title class="mb-4 font-weight-medium subtitle-1">
                    Detalle del Prestamo
                    <div class="flex-grow-1"></div>
                    <v-text-field
                      v-model="searchInDetail"
                      label="Buscar en detalle"
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-card-text>
                    <v-data-table
                      :headers="headerTable"
                      :items="prestamoForm.equipos"
                      no-data-text="No Hay Equipos Agregados"
                      :items-per-page="10"
                      :search="searchInDetail"
                      :footer-props="{
                        'items-per-page-options': [10, 20, 30],
                        showFirstLastPage: true,
                      }"
                    >
                      <template v-slot:top>
                        <v-toolbar flat color="white">
                          <div class="flex-grow-1"></div>
                          <!-- MODAL PARA EQUIPOS  -->
                          <v-dialog
                            v-model="modalEquipos"
                            persistent
                            max-width="1150px"
                            scrollable
                          >
                            <template v-slot:activator="{ on }">
                              <v-btn
                                elevation="10"
                                color="grey darken-3"
                                dark
                                v-if="action != 'detail'"
                                class="mb-2"
                                v-on="on"
                              >
                                Agregar equipo&nbsp;
                                <v-icon>library_add</v-icon>
                              </v-btn>
                            </template>
                            <v-card>
                              <v-card-title
                                class="headline grey lighten-2"
                                primary-titles
                              >
                                <span
                                  class="headline"
                                  v-text="formTitle"
                                ></span>
                              </v-card-title>
                              <v-card-text>
                                <EquipoList
                                  v-on:created="getItemsOfEquiposOfPrestamo()"
                                  @added="onAddedItem"
                                  ref="equipo"
                                />
                              </v-card-text>
                              <v-divider></v-divider>
                              <v-card-actions>
                                <div class="flex-grow-1"></div>
                                <v-btn
                                  color="red darken-1"
                                  text
                                  @click="modalEquipos = false"
                                  >Cerrar</v-btn
                                >
                                <v-btn
                                  color="info darken-1"
                                  @click="getEquipoFromChild()"
                                  text
                                  v-text="'Agregar a prestamo'"
                                ></v-btn>
                              </v-card-actions>
                            </v-card>
                          </v-dialog>
                        </v-toolbar>
                      </template>
                      <!-- FIN DE MODAL PARA PRODUCTOS -->

                      <template v-slot:item="props">
                        <tr :key="props.item.id">
                          <td v-text="props.item.equipo.codigo" />
                          <td v-text="props.item.equipo.nombre" />
                          <td v-text="props.item.equipo.descripcion" />

                          <td class="text-center">
                            <v-btn
                              color="red"
                              class="mx-1"
                              elevation="8"
                              v-if="action != 'detail'"
                              small
                              dark
                              @click="deleteEquipoFromPrestamo(props.item)"
                            >
                              <v-icon>delete</v-icon>
                            </v-btn>
                          </td>
                        </tr>
                      </template>

                      <template v-slot:body.append>
                        <tr class="grey lighten-5">
                          <td colspan="5">
                            <v-row v-if="action != 'detail'">
                              <v-col
                                cols="12"
                                class="d-flex flex-row-reverse"
                                md="12"
                              >
                                <v-btn
                                  :disabled="!validForm"
                                  @click="saveOrUpdate()"
                                  large
                                  color="primary"
                                >
                                  {{
                                    action == "add"
                                      ? "Guardar Reserva"
                                      : action == "upd"
                                      ? "Actualizar Reserva"
                                      : ""
                                  }}&nbsp;
                                  <v-icon>save</v-icon>
                                </v-btn>
                              </v-col>
                            </v-row>
                          </td>
                        </tr>
                      </template>
                    </v-data-table>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </v-form>
        </v-container>
      </v-card>
    </div>
  </div>
</template>
<script>
import EquipoList from "./EquipoList.vue";
export default {
  name: "PrestamosForm",
  //props: ["prestamo", "action"],
  props: ["user"],
  data() {
    return {
      search: "",
      loader: false,
      fecha: null,
      prestamoForm: {
        id: null,
        correlativo: "",
        fechaReserva: new Date(
          Date.now() - new Date().getTimezoneOffset() * 60000
        )
          .toISOString()
          .substr(0, 10),
        fechaPrestamo: new Date(
          Date.now() - new Date().getTimezoneOffset() * 60000
        )
          .toISOString()
          .substr(0, 10),
        horaInicio: null,
        horaFin: null,
        estado: "R",
        user: null,
        equipos: [],
      },
      action: "add",
      headerTable: [
        { text: "Código", value: "equipo.codigo", align: "left" },
        { text: "Nombre", value: "equipo.nombre", align: "left" },
        { text: "Descripción", value: "equipo.descripcion", align: "left" },
        { text: "Marca", value: "equipo.marca", align: "center" },
        { text: "Acción", value: "action", sortable: false, align: "center" },
      ],
      modalEquipos: false,
      validForm: true,
      noEquipos: false,
      accion: "",
      searchInDetail: "",
      formatted: "",
      loader: false,
      formTitle: "Agregar Items a Prestamo",
      date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
        .toISOString()
        .substr(0, 10),
    };
  },
  computed: {},
  methods: {
    onAddedItem(value) {
      let me = this;
      if (value.length > 0) {
        me.formTitle = "Agregar Items a Prestamo";
      }
    },
    getItemsOfEquiposOfPrestamo() {
      let me = this,
        equipos = [];
      me.prestamoForm.equipos.map((detalle) => equipos.push(detalle.equipo));
      me.$refs.equipo.item = equipos;
    },
    getEquipoFromChild() {
      let me = this;
      me.$refs.equipo.item.forEach(function (equipo) {
        if (!me.containsObject(equipo, me.prestamoForm.equipos)) {
          me.prestamoForm.equipos.push({
            id: null,
            equipo: equipo,
          });
        }
      });
      me.modalEquipos = false;
    },
    containsObject(obj, list) {
      return list.some(
        (item) => JSON.stringify(item.equipo) === JSON.stringify(obj)
      );
    },
    //quitar un item del detalle
    deleteEquipoFromPrestamo(item) {
      let me = this,
        index;
      index = me.prestamoForm.equipos.indexOf(item);
      me.prestamoForm.equipos.splice(index, 1);
    },
    //guardar la reserva
    saveOrUpdate() {
      let me = this;
      if (me.$refs.formPrestamo.validate()) {
        if (me.prestamoForm.equipos.length == 0) {
          me.noEquipos = true;
          me.validForm = false;
        } else {
          me.prestamoForm.user = this.user;
          me.loader = true;
          axios
            .post(`/prestamos/save`, me.prestamoForm)
            .then(function (response) {
              if (response.status == 201) {
                Vue.swal("Ok", "Reserva registrada con exito", "succes");
                //redireccionar a otra parte
              } else {
                Vue.swal(
                  "Error",
                  "No se puede eliminar, ya tiene prestamos registrados",
                  "error"
                );
              }
              me.loader = false;
            })
            .catch(function (error) {
              Vue.swal(
                  "Error",
                  "No se puede eliminar, ya tiene prestamos registrados",
                  "error"
                );
            });
        }
      }
    },
  },
  components: {
    EquipoList,
  },
  mounted() {
    console.log(this.user); 
  },
};
</script>
<style scope>
.centered-input >>> input {
  text-align: center;
}
</style>
