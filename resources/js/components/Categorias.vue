<template>
  <div class="content">
    <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
      <v-overlay :value="loader" :z-index="'99999999'">
        <v-progress-circular indeterminate size="80" color="grey darken-4"></v-progress-circular>
      </v-overlay>
      <v-card>
        <v-card-title>
          Listado de Categorias de Productos
          <div class="flex-grow-1"></div>
          <v-text-field v-model="search" label="Buscar Categoria" hide-details></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="hTBCategorias"
          :items="arrayCategorias"
          :footer-props="{
            'items-per-page-options': [5,10, 20, 30,40],
            'items-per-page-text' : 'Registros Por Página'
          }"
          :items-per-page="5"
          :search="search"
          multi-sort
          class="elevation-1"
        >
          <!-- Template Para Modal de Actualizar y Agregar Categoria -->

          <template v-slot:top>
            <v-toolbar flat color="white">
              <div class="flex-grow-1"></div>
              <v-dialog v-model="dialog" persistent max-width="700px">
                <template v-slot:activator="{ on }">
                  <v-btn elevation="10" color="grey darken-3" dark class="mb-2" v-on="on">
                    Agregar&nbsp;
                    <v-icon>mdi-plus-box-multiple-outline</v-icon>
                  </v-btn>
                </template>
                <v-card>
                  <v-card-title class="headline grey lighten-2" primary-titles>
                    <span class="headline" v-text="formTitle"></span>
                  </v-card-title>
                  <v-card-text>
                    <v-container>
                      <v-form ref="formCategoria" v-model="validForm" :lazy-validation="true">
                        <v-text-field
                          append-icon="mdi-folder-outline"
                          v-model="categoria.nombre"
                          @keyup="errorsNombre = []"
                          :rules="[v => !!v || 'Nombre Es Requerido']"
                          label="Nombre"
                          required
                          :error-messages="errorsNombre"
                        ></v-text-field>
                        
                      </v-form>
                    </v-container>
                  </v-card-text>
                  <v-divider></v-divider>
                  <v-card-actions>
                    <div class="flex-grow-1"></div>
                    <v-btn color="red darken-1" text @click="cerrarModal">Cerrar</v-btn>
                    <v-btn
                      color="info darken-1"
                      :disabled="!validForm"
                      @click="saveCategoria()"
                      text
                      v-text="btnTitle"
                    ></v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
          </template>
         
          <!-- Template que va en la tabla en la columna de Acciones (Editar,Desactivar) -->
        
          <template v-slot:item.action="{item}" v-slot:activator="{ on }">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-btn
                  color="success"
                  elevation="8"
                  small
                  dark
                  :disabled="item.id < 0"
                  v-on="on"
                  @click="showModalEditar(item)"
                >
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
              </template>
              <span>Actualizar Datos</span>
            </v-tooltip>
            <v-tooltip top >
              <template v-slot:activator="{ on }" >
                <v-btn
                  color="info"
                  class="mx-1"
                  elevation="8"
                  small
                  dark
                  :disabled="item.id < 0"
                  v-on="on"
                  @click="deleteCategoria(item)"
                >
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </template>
              <span>Eliminar Registro</span>
            </v-tooltip>
          </template>
        </v-data-table>
        <v-snackbar v-model="snackbar">
          {{ msjSnackBar }}
          <v-btn color="red" text @click="snackbar = false">Cerrar</v-btn>
        </v-snackbar>
      </v-card>
    </div>
  </div>
</template>
<script>
export default {
  data() {
     return {
      arrayCategorias: [],
      hTBCategorias: [
        { text: "Nombre", value: "nombre" },
        { text: "Acciones", value: "action", sortable: false, align: "center" }
      ],
      loader: false,
      search: "",
      dialog: false,
      categoria: {
        id: null,
        nombre: ""
      },
      validForm: true,
      snackbar: false,
      msjSnackBar: "",
      errorsNombre: [],
      editedCategoria: -1
    };
  },
  computed: {
    formTitle() {
      return this.categoria.id === null
        ? "Agregar Categoria"
        : "Actualizar Categoria";
    },
    btnTitle() {
      return this.categoria.id === null ? "Guardar" : "Actualizar";
    }
  },
  methods: {
    
    fetchCategorias() {
      let me = this;
      me.loader = true;
      axios.get(`/categorias/all`)
        .then(function(response) {
          me.arrayCategorias = response.data;
          me.loader = false;
        })
        .catch(function(error) {
          me.loader = false;
          console.log(error);
        });
      
     //me.arrayCategorias = [{"id":"1","nombre":"Hardware"},{"id":"2","nombre":"Accesorios"}];
     me.loader = false;
    },
    
    setMessageToSnackBar(msj, estado) {
      let me = this;
      me.snackbar = estado;
      me.msjSnackBar = msj;
    },
    cerrarModal() {
      let me = this;
      me.dialog = false;
      setTimeout(() => {
        me.categoria = {
          id: null,
          nombre: ""
        };
        me.resetValidation();
      }, 300);
    },
    resetValidation() {
      let me = this;
      me.errorsNombre = [];
      me.$refs.formCategoria.resetValidation();
    },
    showModalEditar(categoria) {
      let me = this;
      me.editedCategoria = me.arrayCategorias.indexOf(categoria);
      me.categoria = Object.assign({}, categoria);
      me.dialog = true;
    },
    saveCategoria() {
      let me = this;
      if (me.$refs.formCategoria.validate()) {
        let accion = me.categoria.id == null ? "add" : "upd";
        me.loader = true;
        if(accion=="add"){
           axios.post('/categorias/save', me.categoria)
            .then(function(response) {
                    me.verificarAccionDato(response.data, response.status, accion);
                    me.cerrarModal();
                })
                .catch(function(error){
                    if (error.response.status == 409) {
                        me.setMessageToSnackBar("Categoria ya existe",true);
                        me.errorsNombre = ["Nombre de categoría existente"];
                    }
                    else {
                        Vue.swal("Error","Ocurrió un error intente de nuevo","error");
                    }
                })          
        }else{
            //para actualizar
            axios.put('/categorias/update',me.categoria)
               .then(function(response) {
                    me.verificarAccionDato(response.data, response.status, accion);
                    me.cerrarModal();
            })
          .catch(function(error) {
            if (error.response.status == 409) {
                me.setMessageToSnackBar("Categoria ya existe",true);
                me.errorsNombre = ["Nombre de categoría existente"];
            }
            else {
                Vue.swal("Error","Ocurrió un error intente de nuevo","error");
            }
          });
        }
      
      }
    },
    deleteCategoria(categoria) {
      let me = this;
      me.editedCategoria = me.arrayCategorias.indexOf(categoria);
      
      const Toast = Vue.swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        });
        //personalizando nueva confirmacion
        Vue.swal.fire({
        title: 'Eliminar Categoría',
        text: "Una vez realizada la acción no se podra revertir !",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: "No"
        }).then((result) => {
        if (result.value) {
            me.loader = true;
            axios.post('/categorias/delete', categoria)
            .then(function(response) {
              me.verificarAccionDato(response.data, response.status, "del");
              me.loader = false;
            })
          }
        });
    },
     verificarAccionDato(categoria, statusCode, accion) {
      let me = this;
      
      const Toast = Vue.swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        }); 
      switch (accion) {
        case "add":
          //Agrego al array de categorias el objecto que devuelve el Backend
          //me.arrayCategorias.unshift(categoria);
          this.fetchCategorias(); 
          Toast.fire({
            icon: 'success',
            title: 'Categoría Registrada con Exito'
           });
          me.loader = false;
          break;
        case "upd":
          //Actualizo al array de categorias el objecto que devuelve el Backend ya con los datos actualizados
          //Object.assign(me.arrayCategorias[me.editedCategoria], categoria);
          this.fetchCategorias(); 
          Toast.fire({
            icon: 'success',
            title: 'Categoría Actualizada con Exito'
           }); 
            
          me.loader = false;
          break;
        case "del":
          if (statusCode == 200) {
            try {
              //Se elimina del array de Categorias Activos si todo esta bien en el backend
              me.arrayCategorias.splice(me.editedCategoria, 1);
              //Se Lanza mensaje Final
              Toast.fire({
                icon: 'success',
                title: 'Categoría Eliminada...!!!'
              });
            } catch (error) {
              console.log(error);
            }
          } else {
             Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error, intente de nuevo'
              });
          }
          break;
      }
    }
  },
  mounted() {
    let me = this;
    me.fetchCategorias();
  }
};
</script>