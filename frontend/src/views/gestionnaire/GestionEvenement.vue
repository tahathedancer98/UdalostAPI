<template>
  <div>
    <div id="header-wrapper" class="ui stackable six column grid">
      <div id="header" class="container">
        <!--MENU ORDINATEUR-->
        <div id="menu">
          <ul>
            <li class="active">
              <a @click="evenement" class="column" accesskey="2" title=""
                >Administration</a
              >
            </li>
            <li>
              <a @click="seDeconnecter" class="column" accesskey="5" title=""
                >Se déconnecter</a
              >
            </li>
          </ul>
        </div>
        <!--FIN MENU ORDINATEUR-->

        <!--MENU PORTABLE-->
        <template class="menuGeneral">
          <b-navbar toggleable type="dark" id="navPortable">
            <b-navbar-toggle
              target="navbar-toggle-collapse"
              class="menuPortable">
              <b-icon variant="dark" icon="grid3x3-gap-fill"></b-icon>
            </b-navbar-toggle>

            <b-collapse id="navbar-toggle-collapse" is-nav>
              <b-navbar-nav class="">
                <b-nav-item @click="evenement" class="active"> Évenement </b-nav-item>
                <b-nav-item @click="seDeconnecter"> Se déconnecter </b-nav-item>
              </b-navbar-nav>
            </b-collapse>
          </b-navbar>
        </template>
        <!--FIN MENU PORTABLE-->

      </div>
    </div>

    <div
      class="ui secondary pointing menu ui stackable two column grid"
      id="submenu"
    >
      <a class="active item column" @click="evenement">
        Événements
      </a>
      <a class="item column" @click="compte">
        Utilisateurs
      </a>
    </div>
    <div id="wrapper">
        <div
          class="ui stackable two column grid"
          style="margin:0px !important; width:100%; padding:0px !important; padding-left:8.2%; padding-right:8.2%; margin-bottom:3%;"
        >
          <div class="ui search column" style="margin:0px !important; float:right; width:100%; padding:0px !important;">
            <div class="recherche">
              <div>
                <input type="radio" id="rechercheTitre" name="recherche" value="titre" checked @click='changerTextDeRecherche();'>
                <label for="rechercheTitre">Titre</label>
                <input type="radio" id="rechercheVille" name="recherche" value="ville" @click='changerTextDeRecherche();'>
                <label for="rechercheVille">Ville</label>
                <input type="radio" id="recherchePays" name="recherche" value="pays" @click='changerTextDeRecherche();'>
                <label for="recherchePays">Pays</label>
                <button @click="rechercher();"><i class="search icon"></i></button>
                <button @click="afficherEvenement();"><i class="">Afficher tous les évenements</i></button>
              </div>
            </div> 
                <div class="ui icon input" id="chercheur">
                  <input id="textDeRecherche" class="prompt" type="text" placeholder="Rechercher un évenement par titre..."/>
                </div>
          </div>
        </div>
      <!-- Affichage de tous les évenements  -->
      <div class="ui link cards stackable five column grid" id="cardsEvenement">
        <a id="cardDiv" class="card column" v-for="(ev, i) in this.listeEvenements">
          <div id="iconB">
              <a>
                <i class="large red trash alternate icon" @click="suppEvenement(ev.id)"></i>
              </a>
          </div>
          <div class="image" id="imageCard">
            <img src="../../assets/images/iconCalendar.png" />
          </div>
          <div class="content">
              <div class="header">{{ ev.titre }}</div>
              <div class="meta">
                <a>Aresse : {{ ev.adresse }} {{ ev.codePostal }},  {{ ev.ville }}, {{ ev.pays }}
                </a>
              </div>
              <div class="description">
                {{ ev.description }}
              </div>
            </div>
            <div class="extra content" id="content">
              <span class="right floated" id="date">
                {{ ev.date }} {{ ev.heure }}
              </span>
            </div>
        </a>
      </div>
      <!--FIN TABLE-->

      <!-- Affichage d'un évenement trouvé-->
      <div class="ui link cards stackable five column grid" id="cardsEvenementRecherche" style='justify-content:center;text-align:center;' >
        <a id="cardDivRecherche" class="card column">
          <div id="iconB">
            <button class='large red trash alternate icon' @click="suppEvenement();">SUPP</button>
          </div>
          <div class="image" id="imageCard">
            <img src="../../assets/images/iconCalendar.png" />
          </div>
            <div class="content">
              <div class="header">{{ this.eventRecherche.titre }}</div>
              <div class="meta">
                <a>Aresse : {{ this.eventRecherche.adresse }}, {{ this.eventRecherche.codePostal }}, {{ this.eventRecherche.ville }}, {{ this.eventRecherche.pays }}
                </a>
              </div>
              <div class="description">
                {{ this.eventRecherche.description }}
              </div>
            </div>
            <div class="extra content" id="content">
              <span class="right floated" id="date">
                {{ this.eventRecherche.date }} {{ this.eventRecherche.heure }}
              </span>
            </div>
        </a>
      </div>

      <!-- Affichage d'un évenement non trouvé-->
      <div class="ui link cards stackable five column grid" id="cardsEvenementRechercheNonTrouvee" style='justify-content:center;text-align:center;'>
        <a id="cardDivRecherche" class="card column">
          <div class="image" id="imageCard">
            <img src="../../assets/images/iconCalendar.png" />
          </div>
            <div class="content">
              <div class="header">
                L'évenement n'existe pas malheureusement !
              </div>
              <div class="meta">
                <a>
                  ---------
                </a>
              </div>
            </div>
        </a>
      </div>

      </div>

    <div id="copyright" class="container">
      <p>
        &copy; IUT Nancy-Charlemagne. Tous droits réservés | Licence Pro 1
      </p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      listeEvenements : [],
      eventRecherche: []
    };
  },
  mounted() { 
   this.afficherEvenement();
  },

  methods: {
    afficherEvenement() {
      document.getElementById('cardsEvenementRecherche').style.display='none';
      document.getElementById('cardsEvenementRechercheNonTrouvee').style.display='none';
      document.getElementById('cardsEvenement').style.display='none';
      api_back({
        url: `http://localhost:8080/evenements/`,
        method: "GET",
      })
        .then(
          (response) => {
            this.listeEvenements = [];
            this.eventRecherche = [];

            if (response.data.evenements.length > 0) {
              
              console.log(response.data.evenements.length);

              for (var i = 0;i < response.data.evenements.length;i++) {
                this.listeEvenements[i] = response.data.evenements[i].evenement;
              }

              this.trierEvenements();
              document.getElementById('cardsEvenement').style.display='flex';
              console.log(this.listeEvenements);

            } else {
              console.log("Il y a pas des évenements");
              this.listeEvenements = [];
              document.getElementById("messageVideE").style.display = "block";
            }
          },
          function(err) {
            //throw new Error("end of pagination");
            console.log("error");
          }
        )
        .catch((error) => {
          alert("Il n'y a pas d'évenements");
        });

    },
    suppEvenement(id) {
      console.log('okkk');
      if(confirm('Voulez-vous supprimer cet événement ?')) {
        api_back({
          url: `http://localhost:8080/evenements/` + id,
          method: "DELETE",
        })
          .then((response) => {
            console.log("L'évenement est bien supprimé");
            location.reload();
            // this.$router.push("/gestionEvenement");
          })
          .catch((error) => {
            console.log("Error ========>", error);
          });
      }
    },
    supprEvenement() {
      console.log('okkk');
      if(confirm('Voulez-vous supprimer cet événement ?')) {
        api_back({
          url: `http://localhost:8080/evenements/` + this.eventRecherche.id,
          method: "DELETE",
        })
          .then((response) => {
            console.log("L'évenement est bien supprimé");
            location.reload();
            // this.$router.push("/gestionEvenement");
          })
          .catch((error) => {
            console.log("Error ========>", error);
          });
      }
    },
    trierEvenements() {
        this.listeEvenements.sort(function(a,b){
          if ( a.date < b.date){
              return -1;
          }
          if ( a.date > b.date){
              return 1;
          }
          return 0;
        });
    },
    rechercher(){
      var textSaisie = document.getElementById('textDeRecherche');
      
      var results = [];
      var condition=null;
      if(textSaisie.value == ''){
        alert('veuillez remplir le champ de text pour rechercher !');
      }else{
        this.eventRecherche = [];

        document.getElementById('cardsEvenement').style.display='none';
        document.getElementById('cardsEvenementRecherche').style.display='none';
        document.getElementById('cardsEvenementRechercheNonTrouvee').style.display='none';

        console.log('great!');
        if(document.getElementById('rechercheTitre').checked){

          for(var event of this.listeEvenements){
            if(event.titre == textSaisie.value){
              this.eventRecherche = event;
              console.log(this.eventRecherche);
              condition=true;
            }
          }
          
        }else if(document.getElementById('rechercheVille').checked){

          for(var event of this.listeEvenements){
            if(event.ville == textSaisie.value){
              this.eventRecherche = event;
              condition=true;
              console.log(this.eventRecherche);
            }
          }

        }else if(document.getElementById('recherchePays').checked){

          for(var event of this.listeEvenements){
            if(event.email == textSaisie.value){
              this.eventRecherche = event;
              condition=true;
              console.log(this.eventRecherche);
            }
          }
        }
        if(condition == true){
          document.getElementById('cardsEvenementRecherche').style.display='flex';
          document.getElementById('cardsEvenementRechercheNonTrouvee').style.display ='none';
        }else{
          document.getElementById('cardsEvenementRecherche').style.display='none';
          document.getElementById('cardsEvenementRechercheNonTrouvee').style.display ='flex';
        }
      }
    },
    seDeconnecter() {
      this.$router.push("/");
    },

    compte() {
      this.$router.push("/gestionCompte");
    },

    evenement() {
      this.$router.push("/gestionEvenement");
    },

  }

};
</script>

<style lang="scss" scoped>
@import url("https://fonts.googleapis.com/css2?family=Raleway:wght@300&display=swap");
@import "node_modules/bootstrap/scss/bootstrap.scss";
@import "node_modules/bootstrap-vue/src/index.scss";
@import "https://cdn.jsdelivr.net/leaflet/1/leaflet.css";


#iconB{
  margin-left:90%; 
  cursor:pointer; 
  background: white !important;

  &:hover{
    background: white !important;
  }
}

#map-example-container {
  height: 60vh;
}

* {
  font-family: "Raleway", sans-serif;
}

a {
  cursor: pointer;
}

html,
body {
  height: 100%;
}

body {
  margin: 0px;
  padding: 0px;
  background: #111111;
  font-family: "Didact Gothic", sans-serif;
  font-size: 12pt;
  font-weight: 200;
  color: #fff;
}

h1,
h2,
h3 {
  margin: 0;
  padding: 0;
}

p,
ol,
ul {
  margin-top: 0;
}

ol,
ul {
  padding: 0;
  list-style: none;
}

p {
  line-height: 180%;
}

a {
  color: #ee0000;
}

a:hover {
  text-decoration: none;
}

.container {
  width: 100%;
}

/*********************************************************************************/
/* Image Style                                                                   */
/*********************************************************************************/

.image {
  display: inline-block;
}

.image img {
  display: block;
  width: 100%;
}

.image-full {
  display: block;
  width: 100%;
  margin: 0 0 2em 0;
}

.image-left {
  float: left;
  margin: 0 2em 2em 0;
}

.image-centered {
  display: block;
  margin: 0 0 2em 0;
}

.image-centered img {
  margin: 0 auto;
  width: auto;
}

.fa-1x {
  font-size: 1.5rem;
}
.navbar-toggler.toggler-example {
  cursor: pointer;
}

/*********************************************************************************/
/* Button Style                                                                  */
/*********************************************************************************/

.button {
  display: inline-block;
  padding: 1em 2em 1em 2em;
  letter-spacing: 0.1em;
  margin-top: 2em;
  padding: 1.4em 3em;
  border: 2px solid rgba(255, 255, 255, 1);
  border-radius: 6px;
  text-decoration: none;
  font-weight: 700;
  font-size: 1em;
  color: rgb(255, 255, 255);

  &:hover {
    background-color: #484877ff;
    border: 2px solid #484877ff;
    color: white;
    transition: 1s;
  }
}

.button-alt {
  width: 100%;

  color: rgba(0, 0, 0, 0.8);
}

.boxA,
.boxB,
.boxC {
  width: 100%;
}
/*********************************************************************************/
/* Heading Titles                                                                */
/*********************************************************************************/

.title {
  margin-bottom: 3em;
  color: white;
}

.title h2 {
  font-size: 2.7em;
}

.title .byline {
  font-size: 1.3em;
  color: rgba(255, 255, 255, 0.356);
}

.bylineTitle {
  font-size: 1.3em;
  color: rgba(255, 255, 255, 0.356);
}

/*********************************************************************************/
/* Header                                                                        */
/*********************************************************************************/

#wrapper {
  width: 100% !important;
  padding: 4em 0em 7em 0em;
  background: #fff;
}

#wrapper2 {
  width: 100% !important;
  background: #fff;
}

/*********************************************************************************/
/* Header                                                                        */
/*********************************************************************************/

#header-wrapper {
  position: relative;
  background: rgb(48, 48, 78);
  background-size: cover;
}

#header {
  position: relative;
  padding: 5em 0em;
}

/*********************************************************************************/
/* Logo                                                                          */
/*********************************************************************************/

#logo {
  & img {
    width: 12%;
    margin-top: -2%;
    margin-left: 5%;
  }
}
/*********************************************************************************/
/* Menu                                                                          */
/*********************************************************************************/

#menu {
  position: absolute;
  top: 4.5em;
  right: 0;
  margin-right: 0%;
  display: "";
}

#menu ul {
  display: inline-block;
}

#menu li {
  display: block;
  float: left;
  text-align: center;
}

#menu li a,
#menu li span {
  padding: 1em 1.5em;
  letter-spacing: 1px;
  text-decoration: none;
  text-transform: uppercase;
  font-size: 0.8em;
  color: rgba(255, 255, 255, 0.8);

  &:hover {
    border-bottom: 2px solid rgba(255, 255, 255, 1);
  }
}

#menu .active a {
  border: 2px solid rgba(255, 255, 255, 1);
  border-radius: 6px;
  color: rgba(255, 255, 255, 1);

  &:hover {
    background-color: #484877ff;
    border: 2px solid #484877ff;
    transition: 1s;
  }
}

/*********************************************************************************/
/* Banner                                                                        */
/*********************************************************************************/

#banner {
  padding-top: 10em;
  text-align: center;
}

#banner .title h2 {
  font-size: 4em;
}

#banner .title .byline {
  font-size: 2em;
}

#banner .button {
  margin-top: 2em;
  padding: 1.4em 3em;
  border: 2px solid rgba(255, 255, 255, 1);
  border-radius: 6px;
  font-weight: 700;
  font-size: 1em;

  &:hover {
    border: 2px solid #a6cff4;
    background-color: #a6cff4;
  }
}

#copyright {
  width: 100%;
  padding: 5em 0em;
}

#copyright p {
  text-align: center;
  font-size: 1em;
  color: rgba(0, 0, 0, 0.699);
}

#copyright a {
  text-decoration: none;
  color: rgba(0, 0, 0, 0.699);
}

/*********************************************************************************/
/* Extra                                                                         */
/*********************************************************************************/

#three-column {
  text-align: center;
  color: rgba(0, 0, 0, 0.6);
}

#three-column .fa {
  display: block;
  color: rgba(0, 0, 0, 1);
  font-size: 2em;
}

#three-column .title h2 {
  font-weight: bold;
  color: rgba(0, 0, 0, 0.8);
}

#three-column .title .byline {
  text-align: center;
  color: rgba(0, 0, 0, 0.5);
}

// NAV MOBILE

#navPortable {
  display: none;
  float: left;
}

.menuPortable {
  background-color: rgb(255, 255, 255);
}

@media screen and (max-width: 900px) {
  #navPortable {
    display: inline-block;
  }

  #menu {
    display: none;
    top: 0px;
  }

  #header {
    padding: 0px;

    #logo {
      & img {
        float: right;
        width: 18%;
        margin-top: 2%;
        margin-right: 5%;
      }
    }
  }

  #header-wrapper {
    padding: 5%;
  }
}

// CARD EVENEMENT

#cardsEvenement {
  text-align: center;
  justify-content: center;
  display: flex;

  & #cardDiv {
    display: inline-block;
    background-color: #00000007;
    padding: 0% !important;
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);

    #content {
      background-color: rgb(0, 0, 0);
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0% !important;
      color: white;

      span {
        margin-left: 5%;
      }

      &:hover {
        background-color: #3f3f3f;
      }
    }

    &:hover {
      background-color: #0000000e;
    }
  }
}

#icons {
  float: right;
  margin-right: 8.2%;
  border: none;

  & a {
    float: right;
    border: none;
    border-radius: 0%;
    font-size: 100%;
    background-color: #88b2d6 !important;
    color: white !important;
    width: 100%;
    border-radius: 100px;

    &:nth-child(1) > i {
      color: white;
    }

    &:hover {
      background-color: #65839e !important;
    }
  }
}

#chercheur {
  width: 100%;
  color: black;

  & input {
    width: 100%;
    background-color: #00000015;

    &::placeholder {
      color: rgb(78, 78, 78);

      &:hover {
        background-color: #a6cff4;
      }
    }
  }
}

#submenu {
  display: flex;
  justify-content: center;

  & a {
    display: flex;
    justify-content: center;

    &:hover {
      color: #484877ff;
    }
  }
}

#imageCard {
  background-color: white;
  display: flex;
  justify-content: center;

  img {
    width: 50%;
    padding: 4%;
  }
}

//modal

$base: #0f1923;
$white: #ece8e1;
$height: 7vh;
$transition: 0.3s ease-out all;

.modal {
  visibility: hidden;
  opacity: 0;
  z-index: 50;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.719);
  height: 100vh;
  transition: all 0.4s;
}

.modal:target {
  visibility: visible;
  opacity: 1;
}

.modal__contentModal {
  border-radius: 10px;
  position: relative;
  width: 100%;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;

  & > .form {
    border-radius: 10px;
    box-shadow: 10px $base;
    background-color: rgb(255, 255, 255);
    padding: 3%;
    padding-bottom: 2%;
    font-size: 1.3vh;
    margin: 5%;
    width: 100%;

    & input,
    textarea {
      background-color: #30303015;
      height: 4vh;
      border: none;

      &:hover {
        background-color: #a6d0f42d;
        border: 1px solid #a6d0f42d;
      }
    }
    & .title {
      display: grid;
      justify-content: center;
      align-items: center;
      grid-template-columns: repeat(3, 1fr);
      color: #484877ff;
      text-align: center;
      height: 10px;
      font-size: 150%;

      & span {
        grid-column: 2/3;
      }
    }

    & #button {
      display: flex;
      justify-content: center;
      background-color: white;
      border: none !important;

      & button {
        border-radius: 100px;
        width: 50%;
        height: 5vh;
        border: none !important;
        background-color: rgb(48, 48, 78);
        color: white;
        cursor: pointer;

        &:hover {
          border: none !important;
          background-color: rgba(72, 72, 119, 0.822);
          transition: 0.5s;
        }
      }
    }
  }
}

.modal__close {
  position: absolute;
  top: 10px;
  right: 10px;
  color: #a6cff4;
  margin-right: 5%;
  font-size: 30px;
  text-decoration: none;

  &:hover {
    color: #a6cff4;
  }
}

#iconsModal {
  border: none;
  border-radius: 0;

  & a:hover {
    border-radius: 0;
    border: none;
    background: #f5f5f5 !important;
  }
}

#retour{
    background: rgba(255, 255, 255);
    color:grey;
    border-radius: 0px;
    height: 15px;
    justify-content: center;
    margin-left: 8.2%;

    &:hover{
        border: none;
        color: #484877ff;
    }
}
#imageDelete{
  height:40px;
  width:40px;
}
</style>
