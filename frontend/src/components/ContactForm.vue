<template>
  <div>
    <nav
      class="navbar navbar-expand-lg navbar-dark"
      style="margin-top: -62px; background-color: #3498db"
    >
      <div class="container">
        <a class="navbar-brand" href="#">Agenda de Contatos</a>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link to="/" class="nav-link" exact-active-class
                >Home</router-link
              >
            </li>
            <li class="nav-item">
              <router-link to="/contactlist" class="nav-link" exact-active-class
                >Lista de Contatos</router-link
              >
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-4 mb-4">
      <div class="card mx-auto" style="max-width: 600px">
        <div class="card-header text-center bg-primary text-white">
          <h2 class="mb-0">Adicionar Contato</h2>
        </div>

        <div class="card-body">
          <form method="POST" @submit.prevent="saveContact">
            <div class="row">
              <div class="col-md-6">
                <label for="inputName" class="form-label">Nome</label>
                <input
                  type="text"
                  class="form-control"
                  id="inputName"
                  v-model="formData.name"
                  required
                />
              </div>
              <div class="col-md-6">
                <label for="inputEmail" class="form-label">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="inputEmail"
                  v-model="formData.email"
                  required
                />
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-6">
                <label for="inputPhone" class="form-label">Telefone(s)</label>
                <input
                  type="text"
                  class="form-control"
                  id="inputPhone"
                  v-model="phoneInput"
                  @input="restrictToNumbers"
                  @keyup.enter="addPhone"
                  placeholder="Ex: 123456789, 987654321"
                />
                <ul class="list-group mt-2">
                  <li
                    class="list-group-item"
                    v-for="(phone, index) in formData.phones"
                    :key="index"
                  >
                    {{ phone }}
                    <button
                      type="button"
                      class="btn btn-danger btn-sm float-end"
                      @click="removePhone(index)"
                    >
                      Remover
                    </button>
                  </li>
                </ul>
              </div>

              <div class="col-md-6">
                <label for="inputStreet" class="form-label">Logradouro</label>
                <input
                  type="text"
                  class="form-control"
                  id="inputStreet"
                  v-model="formData.street"
                />
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-3">
                <label for="inputCity" class="form-label">Cidade</label>
                <input
                  type="text"
                  class="form-control"
                  id="inputCity"
                  v-model="formData.city"
                />
              </div>
              <div class="col-md-5">
                <label for="inputState" class="form-label">Estado</label>
                <select
                  id="inputState"
                  class="form-select"
                  v-model="formData.state"
                  required
                >
                  <option selected disabled value="estados">Escolha...</option>
                  <option value="SP">São Paulo</option>
                  <option value="RJ">Rio de Janeiro</option>
                  <option value="PE">Pernambuco</option>
                  <option value="MG">Minas Gerais</option>
                  <option value="BH">Bahia</option>
                  <option value="SC">Santa Catarina</option>
                </select>
              </div>
              <div class="col-md-3 offset-md-1" style="margin-left: 10px">
                <label for="inputCEP" class="form-label">CEP</label>
                <input
                  type="text"
                  class="form-control"
                  id="inputCEP"
                  v-model="formData.cep"
                  @blur="fetchAddress"
                />
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <div class="form-check"></div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                  Salvar Contato
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div
      class="card-footer text-muted text-center"
      style="background-color: #5eb7f3; color: white"
    >
      <p style="margin-top: 66px">
        © {{ formData.currentYear }} Deyvson Carlos.
      </p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formData: {
        name: "",
        email: "",
        cep: "",
        city: "",
        street: "",
        phones: [],
        currentYear: new Date().getFullYear(),
      },
      phoneInput: "",
    };
  },
  methods: {
    fetchAddress() {
      if (this.formData.cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${this.formData.cep}/json/`)
          .then((response) => response.json())
          .then((data) => {
            if (!data.erro) {
              this.formData.city = data.localidade;
              this.formData.street = data.logradouro;
            }
          })
          .catch((error) => {
            console.error("Erro ao obter dados do CEP:", error);
          });
      }
    },
    addPhone() {
      const numericPhone = this.phoneInput.replace(/\D/g, "");
      if (numericPhone.length > 0) {
        this.formData.phones.push(numericPhone);
        this.phoneInput = "";
      }
    },
    removePhone(index) {
      this.formData.phones.splice(index, 1);
    },
    restrictToNumbers() {
      this.phoneInput = this.phoneInput.replace(/\D/g, "");
    },

    saveContact() {
  console.log("Dados do formulário:", this.formData);
  fetch("http://localhost:8000/createContact", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(this.formData),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      if (data && data.message === "Contato inserido com sucesso!") {
        alert("Contato salvo com sucesso!");


      } else {
        console.error("Erro ao salvar contato:", data.error || "Erro desconhecido");
      }
    })
    .catch((error) => {
      console.error("Erro ao salvar contato:", error.message);
    });
},

  },
};
</script>

<style>
.nav-item a.custom-link-exact-active,
.nav-item a.custom-link-active {
  text-decoration: none;
  color: gray;
}
</style>
