const DELAY_UPDATE_ALERTS = 5     // Verifier et mettre a jour le nombre d'alerts chaque x secondes
const DELAY_UPDATE_GRAPH = 5      // Mettre a jour le graphique chaque x secondes
const DELAY_UPDATE_ETAT = 10      // Le temps entre chaque mise a jour de l'état des modules en secondes
const DELAY_INSERT_NEW_DATA = 10  // Le temps entre chaque nouvelle insertion de données

const LIMIT_DATA_IN_GRAPH = 20  // Limiter l'affichage du graphique aux 20 dernières données

document.addEventListener('DOMContentLoaded', function () {
  // navbar mobile 
  const button = document.querySelector(".mobile-nav-toggle");
  button.addEventListener("click", (event) => {
    let body = document.querySelector("body")
    body.classList.toggle('mobile-nav-active')
  });

  // initialisation de "simpleTable"
  const dataTable = document.getElementById('dataTable');
  if (dataTable) {
    new simpleDatatables.DataTable(dataTable);
  }

  // si l'option "Autre" est sélectionnée, un champ apparait pour ajouter un type de module
  const selectElement = document.getElementById('type_module');
  if (selectElement != null) {
    selectElement.addEventListener('change', function () {
      const selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);
      if (selectedOptions.includes('autre')) {
        const autreTypeModuleContainer = document.getElementById('autre_type_module_container');
        autreTypeModuleContainer.style.display = 'block';
      } else {
        const autreTypeModuleContainer = document.getElementById('autre_type_module_container');
        autreTypeModuleContainer.style.display = 'none';
      }
    });
  }

  // mettre a jour le nombre d'alertes en temps réel
  function update_alerts() {  
    $.ajax({
      url: 'http://localhost:8000/api/modules/panne',
      method: 'GET',
      success: function(response) {
          let notif_elmnt = document.querySelector(".alerts")
          if (response.nb_panne > 0) {
              notif_elmnt.innerHTML = '<span class="alerts-count">'+response.nb_panne+'</span>'
          } else {
              notif_elmnt.innerHTML = ""
          }
      },
      error: function(xhr, status, error) {
          console.error(error);
      }
    });
  }

  // mettre a jour l'état des module (Fonctionnel, En panne, En maintenance)
  function update_etat() {
    $.ajax({
      url: 'http://localhost:8000/api/updatePannes',
      method: 'POST',
      success: function(response) {
        console.log(response)
      },
      error: function(xhr, status, error) {
          console.error(error);
      }
    });
  }

  // Insertion d'une nouvelle données pour chaque module fonctionnel
  function generer_donnees() {
    $.ajax({
      url: 'http://localhost:8000/api/genererDonnees',
      method: 'POST',
      success: function(response) {
        console.log(response)
      },
      error: function(xhr, status, error) {
          console.error(error);
      }
    });
  }

  setInterval(update_alerts, DELAY_UPDATE_ALERTS * 1000)    // pour mettre a jour le nombre d'alerts chaque DELAY_UPDATE_ALERTS secondes
  setInterval(generer_donnees, DELAY_INSERT_NEW_DATA * 1000)
  setInterval(update_etat, DELAY_UPDATE_ETAT * 1000)
});